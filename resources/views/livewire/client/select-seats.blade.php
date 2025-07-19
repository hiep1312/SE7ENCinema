@assets
<link rel="stylesheet" href="{{ asset('client/assets/css/style.css') }}">
@endassets
<div class="scRender">
    <h2>Chọn ghế cho suất chiếu: {{ $showtime->movie->title ?? '' }}</h2>
    <p>Thời gian: {{ $showtime->start_time->format('d/m/Y H:i') }}</p>
    {{-- <div style="display: flex; flex-wrap: wrap; max-width: 400px;">
        @foreach ($seats as $seat)
        <button wire:click="toggleSeat({{ $seat->id }})" @if ($seat->is_booked)
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
    </div> --}}

    <div id="user-seat-wrapper"></div>


   @foreach ($selectedSeats as $seatCode)
    @php
        $s = $seats->first(fn($item) => $item->seat_row . $item->seat_number === $seatCode);
    @endphp
    @if ($s)
        <span style="margin-right:5px;">{{ $s->seat_row }}{{ $s->seat_number }}</span>
    @endif
@endforeach

    @if(session()->has('error'))
    <div style="color:red;">{{ session('error') }}</div>
    @endif


    <button wire:click="goToSelectFood" style="margin-top: 15px; padding: 10px 20px; background: #EF2B2B; color: white; border:none; border-radius: 4px;">
        Tiếp tục chọn đồ ăn
    </button>

    @script
<script>
    function attachSeatCheckboxListeners() {
        document.querySelectorAll('input[type="checkbox"][wire\\:model="selectedSeats"]').forEach(cb => {
            cb.addEventListener("change", () => {
                const checkboxes = document.querySelectorAll('input[type="checkbox"][wire\\:model="selectedSeats"]:checked');
                const selected = Array.from(checkboxes).map(cb => cb.value);
                @this.set('selectedSeats', selected);
            });
        });
    }

    $wire.on('seatuserdetail', function ([rows, seatsPerRow, vipRows, coupleRows, selectedSeats]) {
        const wrapper = document.getElementById('user-seat-wrapper');
        try {
            wrapper.innerHTML = '';
            const dom = window.generateClientDOMSeats({
                rows: rows,
                seatsPerRow: seatsPerRow,
                vipRows: vipRows,
                coupleRows: coupleRows,
                selectedSeats: selectedSeats
            });
            wrapper.appendChild(dom);
            console.log('Seats generated successfully');

            setTimeout(attachSeatCheckboxListeners, 50);

        } catch (error) {
            console.error('Error generating seats:', error);
        }
        console.log('generateSeats event received:', {rows, seatsPerRow, vipRows, coupleRows, selectedSeats});
    });
</script>

    @endscript
</div>
