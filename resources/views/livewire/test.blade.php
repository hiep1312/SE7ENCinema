@assets
    <link rel="stylesheet" href="{{ asset('client/assets/css/style.css') }}">
@endassets
<div>
    <div>
        <ul id="__sc__sortable1" sc-group="temp" data-row="A" wire:sc-sortable="updateOrder1" wire:sc-model.live.debounce.1000ms="temp">
            <li sc-id="A1">1</li>
            <li sc-id="A2">2</li>
            <li sc-id="A3">3</li>
            <li sc-id="A4">4</li>
            <li sc-id="A5">5</li>
            <li sc-id="A6">6</li>
            <li sc-id="A7">7</li>
            <li sc-id="A8">8</li>
            <li sc-id="A9">9</li>
            <li sc-id="A10">10</li>
        </ul>
        <ul id="sortable2" sc-group="temp" data-row="B" wire:sc-sortable="updateOrder1" wire:sc-model.live.debounce.1000ms="temp">
            <li sc-id="A11">11</li>
            <li sc-id="A12">12</li>
            <li sc-id="A13">13</li>
            <li sc-id="A14">14</li>
            <li sc-id="A15">15</li>
            <li sc-id="A16">16</li>
            <li sc-id="A17">17</li>
            <li sc-id="A18">18</li>
            <li sc-id="A19">19</li>
            <li sc-id="A20">20</li>
        </ul>
    </div>
    <button wire:click="toggle">{{$to ? 'True' : 'False'}}</button>
    @php
        print_r($temp);
    @endphp
    <p x-text="$wire.temp"></p>
    <div class="d-flex justify-content-between mb-3" id="generate-seats">
        <button type="button" class="btn btn-success">
            <i class="fas fa-save"></i> Tạo sơ đồ phòng chiếu
        </button>
    </div>
</div>
@script
<script>
    document.querySelector('#generate-seats').after(window.generateDOMSeats({
    rows: 10,
    seatsPerRow: 17,
    vipRows: 'A',
    coupleRows: 'B'}));
</script>

@endscript
