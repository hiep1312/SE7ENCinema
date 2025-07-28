<?php

namespace App\Livewire\Payment;

use App\Models\Booking;
use App\Models\BookingSeat;
use Livewire\Component;

class VnpayPayment extends Component
{
    public function render()
    {
        return view('livewire.payment.vnpay-payment');
    }


    public $cart = [];
    public $seats = [];
    public $food_total = 0;
    public $seat_total = 0;
    public $total_amount = 0;
    public $booking_id;
    public $payment_deadline;

    public function mount($booking_id)
    {
        $this->booking_id = $booking_id;

        $this->cart = session()->get('cart', []);
        $this->food_total = session()->get('cart_food_total', 0);
        $this->seat_total = session()->get('cart_seat_total', 0);
        $this->total_amount = $this->food_total + $this->seat_total;

        $this->seats = BookingSeat::where('booking_id', $this->booking_id)
            ->with('seat')
            ->get()
            ->map(function ($bookingSeat) {
                return $bookingSeat->seat->seat_row . $bookingSeat->seat->seat_number;
            })
            ->toArray();

        $this->payment_deadline = session()->get('payment_deadline_' . $this->booking_id);
        if (session()->has('booking_locked_' . $this->booking_id)) {
            return redirect()->route('client.index');
        }
    }

    public function testdeletesession()
    {
        session()->forget('payment_deadline_' . $this->booking_id);
    }




    public function redirectToVnpay()
    {
        $deadlineKey = 'payment_deadline_' . $this->booking_id;
        $extendedKey = 'payment_extended_' . $this->booking_id;

        // Check hết hạn cũ trước
        $deadline = session()->get($deadlineKey);

        if (!$deadline || now()->timestamp * 1000 > $deadline) {
            session()->flash('error', 'Đã hết thời gian thanh toán. Vui lòng đặt lại vé.');
            return redirect()->route('client.index');
        }

        // 👉 THÊM: chỉ cộng thêm 1 lần
        if (!session()->has($extendedKey)) {
            session()->put($deadlineKey, now()->addMinutes(15)->timestamp * 1000);
            session()->put($extendedKey, true);
        }

        session()->put('booking_locked_' . $this->booking_id, true);

        // Check booking status
        $booking = Booking::find($this->booking_id);
        if (!$booking || $booking->status !== 'pending') {
            session()->flash('error', 'Đơn hàng không tồn tại hoặc không còn khả dụng.');
            return redirect()->route('client.index');
        }




        $vnp_TmnCode = 'P8QX0KGT'; // Mã website VNPay
        $vnp_HashSecret = 'ITBJ2BGWRYTN5J2Z2QMXMXVAEEK5WBVA'; // Secret key
        $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        $vnp_Returnurl = route('vnpay.return');

        $vnp_TxnRef = $this->booking_id; // Mã giao dịch
        $vnp_OrderInfo = 'Thanh toan demo';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = intval($this->total_amount) * 100; // VNPay yêu cầu x100
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';

        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return redirect()->away($vnp_Url);
    }
}
