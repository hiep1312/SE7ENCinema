import Sortable from 'sortablejs';
const $sc_configSortableSeat = {
    _config: {
        delay: 0,
        delayOnTouchOnly: false,
        store: {
            get: function (sortable) {
                const order = localStorage.getItem(sortable.options.group.name);
                return order ? order.split('<$sc|>') : [];
            },
            set: function (sortable) {
                const order = sortable.toArray();
                localStorage.setItem(sortable.options.group.name, order.join('<$sc|>'));
            }
        },
        animation: 150,
        easing: null,
        handle: null,
        preventOnFilter: true,
        dataIdAttr: 'data-id',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        ignore: 'a, img',
        swapThreshold: 1,
        invertSwap: false,
        invertedSwapThreshold: null,
        dropBubble: false,
        dragoverBubble: false,
        emptyInsertThreshold: 5,
    },
    _validators: {
        sort_directive: function (directive){  /* default true */
            const {modifiers} = directive;
            return modifiers.includes('sort');
        },
        disabled_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('disabled');
        },
        filter_directive: function (directive){
            const {raw} = directive;
            const filter = raw.match(/\.filter\.([^\.]+)/);
            return filter && filter[1];
        },
        onAdd_directive: function (directive){
            const {raw} = directive;
            const onAdd = raw.match(/\.onAdd\.([^\.]+)/);
            return onAdd && onAdd[1];
        },
        onUpdate_directive: function (directive){
            const {raw} = directive;
            const onUpdate = raw.match(/\.onUpdate\.([^\.]+)/);
            return onUpdate && onUpdate[1];
        },
        onSort_directive: function (directive){
            const {raw} = directive;
            const onSort = raw.match(/\.onSort\.([^\.]+)/);
            return onSort && onSort[1];
        },
        onRemove_directive: function (directive){
            const {raw} = directive;
            const onRemove = raw.match(/\.onRemove\.([^\.]+)/);
            return onRemove && onRemove[1];
        },
        onMove_directive: function (directive){
            const {raw} = directive;
            const onMove = raw.match(/\.onMove\.([^\.]+)/);
            return onMove && onMove[1];
        },
        onClone_directive: function (directive){
            const {raw} = directive;
            const onClone = raw.match(/\.onClone\.([^\.]+)/);
            return onClone && onClone[1];
        },
        onChange_directive: function (directive){
            const {raw} = directive;
            const onChange = raw.match(/\.onChange\.([^\.]+)/);
            return onChange && onChange[1];
        }
    }
};
//group
//handle
//filter ()
//onAdd (*)
//onUpdate (*)
//onSort (*)
//onRemove (*)
//onMove (*)
//onClone (*)
//onChange (*)
document.addEventListener('livewire:init', () => {
    window.getRowSeat = function (row){
        if(
            (typeof row === 'number' && (row < 1 || row > 26)) ||
            (typeof row === 'string' && row.length !== 1) ||
            (typeof row !== 'number' && typeof row !== 'string')
        ) return null;

        return typeof row === 'number' ? String.fromCharCode(row + 64) : (row.toUpperCase().charCodeAt(0) - 64);
    };

    window.generateDOMSeats = function ({rows, seatsPerRow, vipRows, coupleRows}, pathScreen) {
        // Xử lý input
        rows = parseInt(rows);
        seatsPerRow = parseInt(seatsPerRow);
        const vipArr = vipRows ? vipRows.split(',').map(r => r.trim().toUpperCase()) : [];
        const coupleArr = coupleRows ? coupleRows.split(',').map(r => r.trim().toUpperCase()) : [];

        // Tạo wrapper
        const frameSeats = document.createElement('div');
        frameSeats.classList = "st_seatlayout_main_wrapper w-100 mt-2";
        frameSeats.innerHTML = `
            <div class="container">
                <div class="st_seat_lay_heading float_left">
                    <h3>SE7ENCINEMA SCREEN</h3>
                </div>
                <div class="st_seat_full_container" style="float: none">
                    <div class="st_seat_lay_economy_wrapper float_left" style="width: 100% !important">
                        <div class="screen">
                            <img src="${pathScreen || '/client/assets/images/content/screen.png'}">
                        </div>
                    </div>
                    <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left" style="width: auto !important" id="seats-layout"></div>
                </div>
            </div>
        `;

        const seatsLayout = frameSeats.querySelector('#seats-layout');
        // Sinh từng hàng ghế
        for(let i = 1; i <= rows; i++) {
            const rowChar = window.getRowSeat(i); // A, B, C...
            if(rowChar === null) break;
            const isVip = vipArr.includes(rowChar);
            const isCouple = coupleArr.includes(rowChar);

            const frameRow = document.createElement('ul');
            frameRow.className = "seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-start align-items-center";
            frameRow.setAttribute('data-row', rowChar);

            let j = 1;
            while (j <= seatsPerRow) {
                const ceil = document.createElement('li');
                let seatClass = 'seat seat-standard';
                let seatType = 'standard';
                let seatLabel = `Chỗ ngồi ${rowChar}${j}`;
                let seatId = `${rowChar}${j}`;
                let dataSeat = 'standard';

                if (isCouple) {
                    seatClass = 'seat seat-double';
                    seatType = 'couple';
                    seatLabel = `Couple ${rowChar}${j}-${rowChar}${j+1}`;
                    seatId = `${rowChar}${j}_${rowChar}${j+1}`;
                    dataSeat = 'double';
                    li.innerHTML = `
                        <span class="seat-helper">${seatLabel}</span>
                        <input type="checkbox" class="${seatClass}" id="${seatId}" data-number="${rowChar}${j},${rowChar}${j+1}">
                        <label for="${seatId}" class="visually-hidden">${seatLabel}</label>
                    `;
                    li.dataset.seat = dataSeat;
                    ul.appendChild(li);
                    j += 2; // Couple chiếm 2 ghế
                    continue;
                } else if (isVip) {
                    seatClass = 'seat seat-vip';
                    seatType = 'vip';
                    dataSeat = 'vip';
                }
                li.innerHTML = `
                    <span class="seat-helper">${seatLabel}</span>
                    <input type="checkbox" class="${seatClass}" id="${seatId}" data-number="${rowChar}${j}">
                    <label for="${seatId}" class="visually-hidden">${seatLabel}</label>
                `;
                li.dataset.seat = dataSeat;
                ul.appendChild(li);
                j++;
            }
            seatsLayout.appendChild(ul);
        }
        return frameSeats.cloneNode(true);
    };

});



/* document.addEventListener('livewire:init', function () {
    window.$wire = Livewire.first();

    window.getRowSeat = function(row){
        if(
            (typeof row === 'number' && (row < 1 || row > 26)) ||
            (typeof row === 'string' && row.length !== 1) ||
            (typeof row !== 'number' && typeof row !== 'string')
        ) return null;

        return typeof row === 'number' ? String.fromCharCode(row + 64) : (row.toUpperCase().charCodeAt(0) - 64);
    }

    $wire.on('generateSeats', function([selector]){
        const manageSortable = [];
        const generateDOMSeats = function({rows, seatsPerRow, vipRows, coupleRows}){
            // Xử lý input
            const vipArr = vipRows ? vipRows.split(',').map(r => r.trim().toUpperCase()) : [];
            const coupleArr = coupleRows ? coupleRows.split(',').map(r => r.trim().toUpperCase()) : [];
            const frameSeats = document.createElement('div');
            frameSeats.classList = "st_seatlayout_main_wrapper w-100 mt-2";
            frameSeats.innerHTML = `
                <div class="container">
                    <div class="st_seat_lay_heading float_left">
                        <h3>SE7ENCINEMA SCREEN</h3>
                    </div>
                    <div class="st_seat_full_container" style="float: none">
                        <div class="st_seat_lay_economy_wrapper float_left" style="width: 100% !important">
                            <div class="screen">
                                <img src="{{ asset('client/assets/images/content/screen.png') }}">
                            </div>
                        </div>
                        <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left" style="width: auto !important" id="seats-layout"></div>
                    </div>
                </div>
            `;
            const seatsLayout = frameSeats.querySelector('#seats-layout');
            // Sinh từng hàng ghế
            for(let i = 1; i <= rows; i++) {
                const rowChar = getRowSeat(i); // A, B, C...
                if(rowChar === null) break;
                const isVip = vipArr.includes(rowChar);
                const isCouple = coupleArr.includes(rowChar);

                const ul = document.createElement('ul');
                ul.className = "seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-start align-items-center";
                ul.setAttribute('data-row', rowChar);

                for(let j = 1; j <= seatsPerRow; j++) {
                    const li = document.createElement('li');
                    li.dataset.seat = isCouple ? 'double' : 'standard|vip';
                    let seatClass = isVip ? 'seat seat-vip' : (isCouple ? 'seat seat-double' : 'seat seat-standard');

                    li.innerHTML = `
                        <span class="seat-helper">Chỗ ngồi ${rowChar}${j}</span>
                        <input type="checkbox" class="${seatClass}" id="${rowChar}${j}" data-number="${j}">
                        <label for="${rowChar}${j}" class="visually-hidden">Chỗ ngồi ${rowChar}${j}</label>
                    `;

                    ul.appendChild(li); isCouple && j++;
                }

                seatsLayout.appendChild(ul);
            }

            return frameSeats.cloneNode(true);
        };

        const handleSortable = function (seats) {
            if(!(seats instanceof NodeList || seats instanceof HTMLCollection)) return null;

            Array.from(seats).forEach(element => {
                manageSortable.push(new Sortable(element, {
                    group: 'seats',
                    swap: true
                }));
            });
        };

        const rootElement = document.querySelector(selector);

        rootElement.nextElementSibling.matches('.st_seatlayout_main_wrapper') ? rootElement.nextElementSibling.replaceWith(generateDOMSeats($wire)) : rootElement.after(generateDOMSeats($wire));
        handleSortable(document.querySelectorAll('.seat-row-layout'));
    });
}); */
