<?php

namespace App\Livewire\Payment;

use Livewire\Component;

class VnpayPayment extends Component
{
    public function render()
    {
        return view('livewire.payment.vnpay-payment');
    }

    public function redirectToVnpay()
    {
        $vnp_TmnCode = 'P8QX0KGT'; // Mã website VNPay
        $vnp_HashSecret = 'ITBJ2BGWRYTN5J2Z2QMXMXVAEEK5WBVA'; // Secret key
        $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        $vnp_Returnurl = route('vnpay.return');

        $vnp_TxnRef = time(); // Mã giao dịch
        $vnp_OrderInfo = 'Thanh toan demo';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = 400000 * 100; // VNPay yêu cầu x100
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

    public function vnpayReturn()
    {
        $inputData = request()->all();
        $vnp_HashSecret = 'ITBJ2BGWRYTN5J2Z2QMXMXVAEEK5WBVA'; // Secret key
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($inputData['vnp_ResponseCode'] == '00') {
                echo "Thanh toán thành công!";
            } else {
                echo "Thanh toán thất bại!";
            }
        } else {
            echo "Chữ ký không hợp lệ!";
        }
    }
}
