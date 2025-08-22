<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FoodOrderItem;
use App\Models\SeatHold;

class VnpayController extends Controller
{
    public function vnpayReturn(Request $request)
    {
        $inputData = $request->all();
        $vnp_HashSecret = 'ITBJ2BGWRYTN5J2Z2QMXMXVAEEK5WBVA';
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);
        ksort($inputData);

        $hashData = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashData .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] === '00') {
                $booking_id = $inputData['vnp_TxnRef'];
                $food_total = session()->get('cart_food_total', 0);
                $seat_total = session()->get('cart_seat_total', 0);
                $total_amount = $food_total + $seat_total;

                $booking = Booking::find($booking_id);

                if ($booking) {
                    $ticketsAdd = BookingSeat::where('booking_id', $booking->id)->get('id')->map(function ($bookingSeat) {
                        return [
                            'booking_seat_id' => $bookingSeat->id,
                            'note' => null,
                            'qr_code' => Str::uuid(),
                            'taken' => false,
                            'status' => 'active',
                        ];
                    });

                    Ticket::insert($ticketsAdd->toArray());

                    SeatHold::releaseHoldsByUser($booking->user_id);
                    SeatHold::cleanupExpired();

                    $booking->status = 'paid';
                    $booking->end_transaction = now();
                    $booking->payment_method = 'bank_transfer';
                    $booking->save();//lưu booking

                    //  Nếu có món ăn thì mới lưu FoodOrderItem
                    $cart = session()->get('cart', []);
                    if (!empty($cart)) {
                        foreach ($cart as $foodId => $item) {
                            FoodOrderItem::create([
                                'booking_id' => $booking->id,
                                'food_variant_id' => $item['variant_id'],
                                'quantity' => $item['quantity'],
                                'price' => $item['price'],
                            ]);
                        }
                    }

                    session()->forget(['booking_id', 'cart', 'cart_food_total', 'cart_seat_total']);

                    return redirect()->route('client.userBooking', ['booking' => $booking->id])->with('success', 'Thanh toán thành công!');
                } else {
                    echo "Không tìm thấy booking!";
                }
            } else {
                echo "Thanh toán thất bại!";
            }
        } else {
            echo "Chữ ký không hợp lệ!";
        }
    }
}
