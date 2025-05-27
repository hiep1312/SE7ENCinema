<div>
    <h2>Chọn ghế cho suất chiếu: {{ $showtime->movie->title ?? '' }}</h2>
    <p>Thời gian: {{ $showtime->start_time->format('d/m/Y H:i') }}</p>

    <div style="display: flex; flex-wrap: wrap; max-width: 400px;">
        @foreach ($seats as $seat)
            <button
                wire:click.prevent="toggleSeat({{ $seat->id }})"
                @if ($seat->is_booked)
                    disabled style="background-color: grey; color: white; margin:2px; width:40px; height:40px;"
                @elseif(in_array($seat->id, $selectedSeats))
                    style="background-color: green; color: white; margin:2px; width:40px; height:40px;"
                @else
                    style="background-color: #eee; margin:2px; width:40px; height:40px;"
                @endif
            >
                {{ $seat->seat_row }}{{ $seat->seat_number }}
            </button>
        @endforeach
    </div>

    <div style="margin-top: 10px;">
        <p>Ghế đã chọn:
            @foreach ($selectedSeats as $seatId)
                @php
                    $s = $seats->firstWhere('id', $seatId);
                @endphp
                <span style="margin-right:5px;">{{ $s->seat_row }}{{ $s->seat_number }}</span>
            @endforeach
        </p>
    </div>

    @if(session()->has('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif

    <button wire:click="goToSelectFood" style="margin-top: 15px; padding: 10px 20px; background: #EF2B2B; color: white; border:none; border-radius: 4px;">
        Tiếp tục chọn đồ ăn
    </button>
</div>
