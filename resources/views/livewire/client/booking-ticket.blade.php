<div>
    <!-- Hiển thị thông tin suất chiếu và thời gian -->
    <h2>Đặt vé cho suất chiếu: {{ $showtime->movie->title ?? '' }}</h2>
    <p>Thời gian: {{ $showtime ? $showtime->start_time->format('d/m/Y H:i') : 'Không có dữ liệu suất chiếu' }}</p>

    <div class="seats">
        <h3>Chọn ghế:</h3>
        <div style="display: flex; flex-wrap: wrap; max-width: 400px;">
            @foreach($seats as $seat)
                <button
                    wire:click.prevent="toggleSeat({{ $seat->id }})"
                    @if($seat->is_booked) disabled style="background-color: grey; color: white;"
                    @elseif(in_array($seat->id, $selectedSeats)) style="background-color: green; color: white;"
                    @else style="background-color: #eee; margin: 2px;" @endif
                >
                    {{ $seat->seat_row }}{{ $seat->seat_number }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Hiển thị ghế đã chọn -->
    <div style="margin-top: 10px;">
        <p>Ghế đã chọn:
            @foreach($selectedSeats as $seatId)
                @php
                    $s = $seats->firstWhere('id', $seatId);
                @endphp
                <span>{{ $s->seat_row }}{{ $s->seat_number }}</span>
            @endforeach
        </p>
    </div>

    <!-- Hiển thị thông báo lỗi và thành công -->
    @if(session()->has('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    @if(session()->has('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <!-- Nút đặt vé -->
    <button wire:click="submit" style="margin-top: 10px; padding: 8px 16px; background: #EF2B2B; color: white; border: none; border-radius: 4px;">
        Đặt vé
    </button>
</div>
