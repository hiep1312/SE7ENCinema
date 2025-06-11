import Sortable from 'sortablejs';
const $sc_configSortableSeat = {
    _manage: {},
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
        emptyInsertThreshold: 10,
    },
    _validators: {
        group_expression: function (value){
            const value = value.test(/\{(?:[^}]*)\}/u) ? JSON.parse(value.replace(/\s+,\s+/g, ', ').replace(/'/g, '"')) : value;
            return value;
        },
        sort_directive: function (directive){  /* default true */
            const {modifiers} = directive;
            return modifiers.includes('sort');
        },
        disabled_directive: function (directive){
            const {modifiers} = directive;
            return modifiers.includes('disabled');
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
        },
        checkFunction: function (model, $wire){
            return (model || false) && ($wire || typeof Livewire.first()[model] === 'function') && typeof $wire[model]==='function';
        }
    }
};

document.addEventListener('livewire:init', () => {
    Livewire.directive('sc-sortable', function ({ $wire, el, directive, component, cleanup }){

    });
});
//handle
//filter
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
