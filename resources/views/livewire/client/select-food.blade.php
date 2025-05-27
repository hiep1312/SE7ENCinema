<div>
    <h2>Chọn đồ ăn cho booking: {{ $booking->booking_code }}</h2>

    @foreach ($foodVariants as $variant)
        <div style="margin-bottom: 10px;">
            <strong>{{ $variant->name }}</strong> - Giá: {{ number_format($variant->price) }} VNĐ
            <input
                type="number" min="0" wire:model.defer="selectedFood.{{ $variant->id }}"
                style="width: 60px; margin-left: 10px;" />
        </div>
    @endforeach

    @if(session()->has('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif

    <button wire:click="goToConfirmBooking" style="margin-top: 15px; padding: 10px 20px; background: #EF2B2B; color: white; border:none; border-radius: 4px;">
        Tiếp tục thanh toán
    </button>
</div>
