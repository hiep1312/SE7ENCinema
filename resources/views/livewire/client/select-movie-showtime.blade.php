<div>
    <h2>Chọn phim và giờ chiếu</h2>

    <label>Phim:</label>
    <select wire:model="selectedMovieId">
        <option value="">-- Chọn phim --</option>
        @foreach($movies as $movie)
            <option value="{{ $movie->id }}">{{ $movie->title }}</option>
        @endforeach
    </select>

    @if($showtimes)
        <label>Giờ chiếu:</label>
        <select wire:model="selectedShowtimeId">
            <option value="">-- Chọn giờ chiếu --</option>
            @foreach($showtimes as $showtime)
                <option value="{{ $showtime->id }}">{{ $showtime->start_time->format('d/m/Y H:i') }}</option>
            @endforeach
        </select>
    @endif

    <button wire:click="goToSelectSeats">Tiếp tục chọn ghế</button>

    @if(session()->has('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif
</div>
