<?php

namespace App\Livewire\Client\Bookings;

use App\Models\Booking;
use App\Models\FoodVariant;
use App\Models\Promotion;
use App\Models\PromotionUsage;
use App\Models\SeatHold;
use App\Services\VNPaymentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use SE7ENCinema\scAlert;

class BookingPayment extends Component
{
    use WithPagination, scAlert;

    public $seachPromotion = '';
    public $voucherCodeSelected = null;
    public $discountAmount = null;
    public $paymentSelected = null;

    public $booking;
    public $seatHold;
    public $totalPrice = 0;

    #[Locked]
    public $config = null;
    #[Locked]
    public $urlPayment = null;
    public $isPaymentMode = false;
    public $statusWindow = null;

    public function mount(string $bookingCode){
        $this->booking = Booking::with('showtime.movie', 'showtime.room', 'user', 'seats', 'bookingSeats', 'foodOrderItems.variant.foodItem')->where('booking_code', $bookingCode)->first();
        $this->seatHold = SeatHold::where('showtime_id', $this->booking?->showtime_id)->where('user_id', Auth::id())->where('status', 'holding')->where('expires_at', '>', now())->first();

        if(!$this->booking || $this->booking->user_id !== Auth::id() || !$this->seatHold) abort(404);
        $this->totalPrice = $this->booking->bookingSeats->sum('ticket_price') + ($this->booking->foodOrderItems?->sum(fn($foodOrderItem) => $foodOrderItem->quantity * $foodOrderItem->price, 0) ?? 0);

        if(session()->has("__sc-voucher__") && session("__sc-voucher__")[0] === $bookingCode) $this->voucherCodeSelected = session("__sc-voucher__")[1];
        if(session()->has('__sc-payment__') && session('__sc-payment__')[0] === $bookingCode){
            $this->isPaymentMode = true;
            [$this->config, $this->urlPayment] = session('__sc-payment__')[2];
            $this->js('openPaymentPopup', $this->urlPayment, $this->seatHold->expires_at->toIso8601String(), $this->seatHold->expires_at->subMinutes(5)->isFuture() ?: (($this->statusWindow = "closed") && false));
        }
    }

    public function applyVoucher(){
        if($this->seachPromotion && Promotion::where('code', trim($this->seachPromotion))->exists()){
            if(mb_strtoupper($this->voucherCodeSelected, "UTF-8") !== mb_strtoupper($this->seachPromotion, "UTF-8")){
                $this->voucherCodeSelected = $this->seachPromotion;
                $this->scToast('Áp dụng mã giảm giá thành công!', 'success', 5000, true);
            }else{
                $this->scToast('Mã giảm giá đã được áp dụng!', 'info', 5000, true);
            }
        }else{
            $this->scAlert('Áp dụng mã giảm giá thất bại', 'Mã giảm giá không tồn tại hoặc đã hết hạn. Vui lòng thử lại!', 'warning');
        }
    }

    public function realtimeUpdatePromotion(){
        Promotion::where('end_date', '>=', now())->where('status', 'expired')->update(['status' => 'active']);
        Promotion::where('end_date', '<', now())->where('status', '!=', 'expired')->update(['status' => 'expired']);
        if($this->voucherCodeSelected){
            $promotion = Promotion::withCount(['usages' => fn($query) => $query->whereHas('booking', fn($query) => $query->whereIn('status', ['pending', 'paid']))])->where('code', $this->voucherCodeSelected)->first();
            $messageText = '';
            if($promotion){
                if($promotion->min_purchase > $this->totalPrice){
                    $messageText = "Giá trị đơn hàng tối thiểu để áp dụng mã '{$this->voucherCodeSelected}' đã thay đổi";
                }elseif(!is_null($promotion->usage_limit) && $promotion->usages_count >= $promotion->usage_limit){
                    $messageText = "Mã giảm giá '{$this->voucherCodeSelected}' đã được sử dụng hết lượt";
                }elseif($promotion->status === 'expired'){
                    $messageText = "Mã giảm giá '{$this->voucherCodeSelected}' đã hết hạn";
                }
            }else {
                $messageText = "Mã giảm giá '{$this->voucherCodeSelected}' không tồn tại hoặc đã bị xóa";
            }

            if($messageText){
                $this->voucherCodeSelected = null;
                $this->scToast($messageText, 'warning', 5000, true);
            }
        }
    }

    public function payment(){
        if($this->paymentSelected && !$this->isPaymentMode && in_array($this->paymentSelected, ['vnpay', 'atm', 'bank'], true)){
            $mappedPaymentMethod = match($this->paymentSelected){
                'vnpay' => ['e_wallet', null],
                'atm' => ['credit_card', 'VNBANK'],
                'bank' => ['bank_transfer', 'VNPAYQR'],
            };

            if($this->voucherCodeSelected){
                PromotionUsage::create([
                    'promotion_id' => Promotion::where('code', $this->voucherCodeSelected)->first()->id,
                    'booking_id' => $this->booking->id,
                    'discount_amount' => $this->discountAmount,
                    'used_at' => now()
                ]);
            }

            $totalPriceOrder = $this->totalPrice - ($this->discountAmount ?? 0);
            $transitionCode = str_replace('.', '', uniqid('se7encinema-payment-', true));
            $startTransaction = now();
            $endTransaction = null;

            $vnpay = new VNPaymentService;
            $vnpay->createPaymentUrl($totalPriceOrder, $transitionCode, "SE7ENCinema - Thanh toan don hang dat ve xem phim {$this->booking->code}", $mappedPaymentMethod[1], route('client.booking.handle-payment', $this->booking->booking_code));

            $this->config = $vnpay->config();
            $this->urlPayment = $vnpay->paymentUrl();
            $this->seatHold->update(['expires_at' => ($endTransaction = Carbon::parse($vnpay->config()['vnp_ExpireDate']))]);

            $this->booking->update([
                'total_price' => $totalPriceOrder,
                'transaction_code' => $transitionCode,
                'start_transaction' => $startTransaction,
                'end_transaction' => $endTransaction,
                'payment_method' => $mappedPaymentMethod[0],
            ]);

            $this->js('openPaymentPopup', $this->urlPayment, $endTransaction->toIso8601String());
            session(['__sc-payment__' => [$this->booking->booking_code, ($this->isPaymentMode = true), [$this->config, $this->urlPayment]]]);
        }
    }

    public function retryPayment(){
        if($this->statusWindow === "closed" && $this->urlPayment && $this->config && $this->seatHold->expires_at->subMinutes(5)->isFuture()){
            $this->js('openPaymentPopup', $this->urlPayment, Carbon::parse($this->config['vnp_ExpireDate'])->toIso8601String());
            $this->statusWindow = null;
        }
    }

    public function cancelPayment(?array $status = null){
        if(is_array($status) && $status['isConfirmed']){
            if($this->booking->foodOrderItems) foreach($this->booking->foodOrderItems as $foodOrderItem){
                FoodVariant::where('id', $foodOrderItem->variant_id)->increment('quantity_available', $foodOrderItem->quantity);
                $foodOrderItem->delete();
            }

            if($this->booking->bookingSeats) foreach ($this->booking->bookingSeats as $bookingSeat) $bookingSeat->delete();

            $this->booking->promotionUsage?->delete();
            $this->seatHold->delete();
            $this->booking->delete();
            session()->forget(['__sc-cart__', '__sc-voucher__', '__sc-payment__']);

            return redirect()->route('client.movieBooking.movie', $this->booking->showtime->movie->id)->with('success', 'Hủy đặt vé thành công! Đơn đặt vé của bạn đã được xóa.');
        }elseif(is_null($status)){
            $this->scConfirm('Xác nhận hủy đặt vé', 'Bạn có chắc chắn muốn hủy đặt vé này không? Hành động này sẽ xóa ghế và sản phẩm đã chọn.', 'info', 'cancelPayment');
        }
    }

    #[Title('Thanh toán đơn hàng - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        $this->realtimeUpdatePromotion();

        $query = Promotion::select('id', 'title', 'code', 'end_date', 'discount_type', 'discount_value', 'usage_limit', 'min_purchase', 'status', DB::raw("CASE
                WHEN {$this->totalPrice} < min_purchase THEN 'not_eligible'
                ELSE 'eligible'
            END as apply_status"))->withCount(['usages' => fn($query) => $query->whereHas('booking', fn($query) => $query->whereIn('status', ['pending', 'paid']))]);

        $promotionSelected = null;
        if(!is_null($this->voucherCodeSelected)) $this->discountAmount = ($promotionSelected = (clone $query)->where('code', $this->voucherCodeSelected)->first()) ? ($promotionSelected->discount_type === "percentage" ? ($this->totalPrice * ($promotionSelected->discount_value / 100)) : $promotionSelected->discount_value) : null;
        else ($this->discountAmount = null);

        $promotions = (clone $query)->where('status', 'active')->where('end_date', '>=', now())->whereDoesntHave('usages', fn($query) => $query->whereHas('booking', fn($query) => $query->where('user_id', Auth::id())))
            ->when($this->seachPromotion, fn($query) => $query->where(fn($subQuery) => $subQuery->whereLike('code', '%' . trim($this->seachPromotion) . '%')->orWhereLike('title', '%' . trim($this->seachPromotion) . '%')))
            ->when($promotionSelected, fn($query) => $query->where('id', '!=', $promotionSelected->id))
            ->orderBy('apply_status')->orderBy('usages_count')->paginate($promotionSelected ? 3 : 4);
        if($promotionSelected) $promotions->prepend($promotionSelected);
        $this->js("setDataPromotions", $promotions->getCollection());

        if(!empty($this->voucherCodeSelected)) session()->put('__sc-voucher__', [$this->booking->booking_code, $this->voucherCodeSelected]);
        else session()->forget("__sc-voucher__");

        return view('livewire.client.bookings.booking-payment', compact('promotions', 'promotionSelected'));
    }
}
