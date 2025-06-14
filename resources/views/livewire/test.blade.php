@assets
    <link rel="stylesheet" href="{{ asset('client/assets/css/style.css') }}">
@endassets
<div>
    {{-- <div>
        <ul id="sortable1" class="disabled" data-row="A" wire:sc-sortable.onmove.aibit="{'name': 'updateOrder1', 'pull': ['updateOrder1'], 'put': true}" >
            <li data-id="1">1</li>
            <li data-id="2">2</li>
            <li data-id="3">3</li>
            <li data-id="4">4</li>
            <li data-id="5">5</li>
            <li data-id="6">6</li>
            <li data-id="7">7</li>
            <li data-id="8">8</li>
            <li data-id="9">9</li>
            <li data-id="10">10</li>
        </ul>
        <ul id="sortable2" data-row="B" wire:sc-sortable="updateOrder1" wire:sc-model.live.debounce.3000ms="temp">
            <li data-id="11">11</li>
            <li data-id="12">12</li>
            <li data-id="13">13</li>
            <li data-id="14">14</li>
            <li data-id="15" class="disabled before">15</li>
            <li data-id="16">16</li>
            <li data-id="17">17</li>
            <li data-id="18">18</li>
            <li data-id="19">19</li>
            <li data-id="20">20</li>
        </ul>
    </div>
    <button wire:click="toggle">{{$to ? 'True' : 'False'}}</button>
    @php
        print_r($temp);
    @endphp
    <p x-text="$wire.temp"></p> --}}
    <div class="d-flex justify-content-between mb-3" id="generate-seats">
        <button type="button" class="btn btn-success">
            <i class="fas fa-save"></i> Tạo sơ đồ phòng chiếu
        </button>
    </div>
</div>
@script
<script>
    /* window.met = function(evt){
        console.log(evt);
        return void 0;
    }
    window.met2 = function(evt){
        console.log("met2");
        return void 0;
    } */

   document.querySelector('#generate-seats').after(window.generateDOMSeats({
    rows: 10,
    seatsPerRow: 17,
    vipRows: 'A',
    coupleRows: 'B'}));
</script>
@endscript
