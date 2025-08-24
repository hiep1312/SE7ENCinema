<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use RuntimeException, InvalidArgumentException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class VNPaymentService {
    const BANKCODE_VNPAYQR = 'VNPAYQR';
    const BANKCODE_VNBANK = 'VNBANK';
    const BANKCODE_INTCARD = 'INTCARD';

    protected string $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    const API_URL = 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction';
    protected string $paymentUrl ='';
    protected array $config = [];
    protected function configDefault(){
        return [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => config('services.vnpay.vnp_TmnCode'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => request()->ip(),
            'vnp_Locale' => 'vn',
            'vnp_OrderType' => 250000,
            'vnp_ReturnUrl' => route('admin.bookings.index'),
        ];
    }

    public function config(){
        return $this->config;
    }

    public function createPaymentUrl(int|string|array $vnp_Amount, ?string $vnp_TxnRef = null, ?string $vnp_OrderInfo = null, ?string $vnp_BankCode = null, ?string $vnp_ReturnUrl = null, Carbon|string|int|null $vnp_CreateDate = null, Carbon|string|int|null $vnp_ExpireDate = null, ?string $vnp_IpAddr = null): self
    {
        is_numeric($vnp_Amount) && $vnp_Amount = (int)$vnp_Amount * 100;
        !is_null($vnp_CreateDate) && $vnp_CreateDate = is_int($vnp_CreateDate) ? date('YmdHis', $vnp_CreateDate) : Carbon::parse($vnp_CreateDate)->format('YmdHis');
        !is_null($vnp_ExpireDate) && $vnp_ExpireDate = is_int($vnp_ExpireDate) ? date('YmdHis', $vnp_ExpireDate) : Carbon::parse($vnp_ExpireDate)->format('YmdHis');

        $this->config = array_merge($this->configDefault(), $this->buildPaymentConfig((is_array($vnp_Amount) && func_num_args() === 1) ? $vnp_Amount : compact('vnp_Amount', 'vnp_TxnRef', 'vnp_OrderInfo', 'vnp_BankCode', 'vnp_ReturnUrl', 'vnp_CreateDate', 'vnp_ExpireDate', 'vnp_IpAddr')));

        ksort($this->config);
        $keyFirst = array_key_first($this->config);
        $query = "";

        foreach ($this->config as $key => $value) {
            if ($keyFirst !== $key) $query .= '&' . urlencode($key) . "=" . urlencode($value);
            else $query .= urlencode($key) . "=" . urlencode($value);
        }

        $this->paymentUrl = "{$this->vnp_Url}?{$query}";

        $vnpHashSecret = config('services.vnpay.vnp_HashSecret');
        if($vnpHashSecret){
            $vnpSecureHash = hash_hmac('sha512', $query, $vnpHashSecret, false);
            $this->paymentUrl .= "&vnp_SecureHash={$vnpSecureHash}";
        }

        return $this;
    }

    public function paymentUrl(): string
    {
        return $this->paymentUrl;
    }

    public function payment(callable|bool $tabNeworCallback = false)
    {
        if(empty($this->paymentUrl)) throw new RuntimeException('Payment URL has not been initialized. Please call createPaymentUrl() before invoking payment().');

        if(is_callable($tabNeworCallback)) $tabNeworCallback($this->paymentUrl);
        else return $tabNeworCallback ? response(<<<JS
            <script type="text/javascript">
                const paymentLink = document.createElement('a');
                paymentLink.href = "{$this->paymentUrl}";
                paymentLink.target = '_blank';
                paymentLink.click();
            </script>
        JS, 200, ['Content-Type' => 'text/html']) : redirect($this->paymentUrl);
    }

    public function queryResult(bool $returnArray = false) {
        if(empty($this->config)) throw new RuntimeException('Payment has not been initialized. Please call createPaymentUrl() before queryResult().');

        return self::queryResultPayment($this->config, $returnArray);
    }

    public static function queryResultPayment(Collection|array $configInput, bool $returnArray = false) {
        $configInput = is_array($configInput) ? $configInput : $configInput->toArray();

        $config = [
            'vnp_RequestId' => "SE7ENCinema" . Str::uuid(),
            'vnp_Version' => $configInput['vnp_Version'] ?? '2.1.0',
            'vnp_Command' => 'querydr',
            'vnp_TmnCode' => $configInput['vnp_TmnCode'] ?? config('services.vnpay.vnp_TmnCode'),
            'vnp_TxnRef' => $configInput['vnp_TxnRef'] ?? throw new InvalidArgumentException('The vnp_TxnRef attribute is required'),
            'vnp_TransactionDate' => $configInput['vnp_CreateDate'] ?? throw new InvalidArgumentException('The vnp_CreateDate attribute is required'),
            'vnp_CreateDate' => $configInput['vnp_CreateDate'],
            'vnp_IpAddr' => $configInput['vnp_IpAddr'] ?? request()->ip(),
            'vnp_OrderInfo' => "Truy van ket qua giao dich cho ma {$configInput['vnp_TxnRef']}",
        ];

        isset($configInput['vnp_TransactionNo']) && ($config['vnp_TransactionNo'] = $configInput['vnp_TransactionNo']);
        $vnpHashSecret = config('services.vnpay.vnp_HashSecret');
        if($vnpHashSecret){
            $config['vnp_SecureHash'] = hash_hmac('sha512', implode('|', $config), $vnpHashSecret, false);
        }

        $http = Http::withHeaders(['Content-Type' => 'application/json'])->post(self::API_URL, $config);

        return $returnArray ? $http->json() : collect($http->json());
    }

    protected function buildPaymentConfig(array $configInput): array
    {
        $allowed = [
            'vnp_Amount' => fn(): never => throw new InvalidArgumentException('vnp_Amount is required and must be a positive integer (in VND multiplied by 100).'),
            'vnp_TxnRef' => function (): string{
                do {
                    $txnRef = Str::upper(Str::random(30));
                }while(Booking::where('transaction_code', $txnRef)->exists());
                return $txnRef;
            },
            'vnp_OrderInfo' => fn(): string => "SE7ENCinema - Thanh toan ve xem phim",
            'vnp_BankCode' => fn(): null => null,
            'vnp_ReturnUrl' => fn(): null => null,
            'vnp_CreateDate' => fn(): string => now()->format('YmdHis'),
            'vnp_ExpireDate' => fn(): string => now()->addMinutes(10)->format('YmdHis'),
            'vnp_IpAddr' => fn(): null => null,
        ];
        $configNew = [];

        foreach (array_keys($allowed) as $value) {
            $configNew[$value] = empty($configInput[$value]) ? $allowed[$value]() : $configInput[$value];
        }

        return array_filter($configNew);
    }

    public static function parse(array|string $dataInput, bool $checkSum = false, bool $returnArray = false) {
        if(empty($dataInput)) return [];

        $data = [];
        if(is_string($dataInput)) parse_str($dataInput, $data);
        elseif(is_array($dataInput)) $data = array_is_list($dataInput) ? collect($dataInput)->mapWithKeys(fn($item) => [$item[0] => $item[1]])->toArray() : $dataInput;

        $result = [
            'vnp_TmnCode' => $data['vnp_TmnCode'] ?? config('services.vnpay.vnp_TmnCode'),
            'vnp_Amount' => $data['vnp_Amount'] ?? 0,
            'vnp_BankCode' => $data['vnp_BankCode'] ?? null,
            'vnp_BankTranNo' => $data['vnp_BankTranNo'] ?? '',
            'vnp_CardType' => $data['vnp_CardType'] ?? '',
            'vnp_PayDate' => $data['vnp_PayDate'] ?? '',
            'vnp_OrderInfo' => $data['vnp_OrderInfo'] ?? '',
            'vnp_TransactionNo' => $data['vnp_TransactionNo'] ?? throw new InvalidArgumentException('The vnp_TransactionNo attribute is required'),
            'vnp_ResponseCode' => $data['vnp_ResponseCode'] ?? throw new InvalidArgumentException('The vnp_ResponseCode attribute is required'),
            'vnp_TransactionStatus' => $data['vnp_TransactionStatus'] ?? throw new InvalidArgumentException('The vnp_TransactionStatus attribute is required'),
            'vnp_TxnRef' => $data['vnp_TxnRef'] ?? '',
        ];

        ksort($result);
        $keyFirst = array_key_first($result);
        $query = "";

        foreach ($result as $key => $value) {
            if ($keyFirst !== $key) $query .= '&' . urlencode($key) . "=" . urlencode($value);
            else $query .= urlencode($key) . "=" . urlencode($value);
        }

        (isset($data['vnp_SecureHash']) || throw new InvalidArgumentException('The vnp_SecureHash attribute is required')) && ($result['vnp_SecureHash'] = $data['vnp_SecureHash']);
        $result['vnp_Amount'] = (int)$result['vnp_Amount'] / 100;

        if($checkSum){
            $secureHash = hash_hmac('sha512', $query, config('services.vnpay.vnp_HashSecret'), false);
            if($secureHash !== $result['vnp_SecureHash']) throw new InvalidArgumentException('Checksum mismatch: the vnp_SecureHash is invalid or the data has been tampered.');
        }

        return $returnArray ? $result : collect($result);
    }

    public static function parseWithQueryString(?string $query = null, bool $checkSum = false, bool $returnArray = true){
        return self::parse($query ?? $_SERVER['QUERY_STRING'] ?? request()->query(), $checkSum, $returnArray);
    }

    public static function cleanupURL(?callable $callback = null){
        $dataParse = self::parseWithQueryString();
        if($callback) $callback($dataParse);

        echo <<<JS
            <script type="text/javascript">
                if(window.location.search) window.history.replaceState({}, '', window.location.pathname);
            </script>
        JS;
    }
}
