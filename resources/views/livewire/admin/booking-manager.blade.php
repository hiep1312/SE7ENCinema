<div>
    {{-- <h2>test</h2> --}}
    <h2>Quản lý Booking (Admin)</h2>

    <label for="statusFilter">Lọc trạng thái:</label>
    <select wire:model="statusFilter" id="statusFilter">
        <option value="pending">Chưa thanh toán</option>
        <option value="paid">Đã thanh toán</option>
        <option value="canceled">Đã hủy</option>
    </select>

    @if(session()->has('success'))
        <div style="color:green; margin-top:10px;">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:15px;">
        <thead>
            <tr>
                <th>Mã Booking</th>
                <th>Khách hàng</th>
                <th>Phim</th>
                <th>Suất chiếu</th>
                <th>Trạng thái</th>
                <th>Thời gian đặt</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_code }}</td>
                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                    <td>{{ $booking->showtime->movie->title ?? 'N/A' }}</td>
                    <td>{{ $booking->showtime->start_time }}</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                    <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($booking->status === 'pending')
                            <button wire:click="markPaid({{ $booking->id }})" style="background:green; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">
                                Đánh dấu đã thanh toán
                            </button>
                        @else
                            ---
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Không có booking nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
