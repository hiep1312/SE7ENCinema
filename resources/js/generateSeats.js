document.addEventListener('livewire:init', () => {
    window.getRowSeat = function (row){
        if(
            (typeof row === 'number' && (row < 1 || row > 26)) ||
            (typeof row === 'string' && row.length !== 1) ||
            (typeof row !== 'number' && typeof row !== 'string')
        ) return null;

        return typeof row === 'number' ? String.fromCharCode(row + 64) : (row.toUpperCase().charCodeAt(0) - 64);
    };

    // Tính toán phân bố ghế thành 3 cột
    function calculateSeatDistribution(totalSeats){
        let averageOver4 = (totalSeats / 4);
        averageOver4 = averageOver4 - Math.trunc(averageOver4) >= 0.5 ? Math.ceil(averageOver4) : Math.floor(averageOver4);
        const leftPosition = averageOver4;
        const centerPosition = averageOver4 * 2;
        const rightPosition = averageOver4 * 3;

        return rightPosition < totalSeats ? [leftPosition, centerPosition, rightPosition] : [];
    };

    function checkAsileForSeatCouple(positionCurrent, positionAsile){
        if(typeof positionCurrent !== 'number' && (typeof positionAsile !== 'number' || !Array.isArray(positionAsile))) return void 0;

        const positionAsileCurrent = typeof positionAsile === 'number' ? positionAsile : (positionAsile.find(asile => positionCurrent <= asile) ?? positionAsile[2]);
        if(positionCurrent > positionAsileCurrent) return false;

        return positionCurrent === positionAsileCurrent || (function(){
            return ++positionCurrent === positionAsileCurrent && "nexting";
        })();
    }

    window.generateDOMSeats = function ({rows, seatsPerRow, vipRows, coupleRows}, pathScreen) {
        // Xử lý input
        rows = parseInt(rows);
        seatsPerRow = parseInt(seatsPerRow);
        const vipArr = vipRows ? vipRows.split(',').map(r => r.trim().toUpperCase()) : [];
        const coupleArr = coupleRows ? coupleRows.split(',').map(r => r.trim().toUpperCase()) : [];

        // Tạo DOM wrapper
        const frameSeats = document.createElement('div');
        frameSeats.setAttribute('wire:ignore', '');
        frameSeats.classList = "st_seatlayout_main_wrapper w-100 mt-2";
        frameSeats.innerHTML = `
            <div class="container">
                <div class="st_seat_lay_heading float_left">
                    <h3>SE7ENCINEMA SCREEN</h3>
                </div>
                <div class="st_seat_full_container" style="float: none">
                    <div class="st_seat_lay_economy_wrapper float_left" style="width: 100% !important">
                        <div class="screen">
                            <img src="${pathScreen || location.origin + '/client/assets/images/content/screen.png'}">
                        </div>
                    </div>
                    <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left" style="width: auto !important" id="seats-layout"></div>
                </div>
            </div>
        `;

        const seatsLayout = frameSeats.querySelector('#seats-layout');

        /* Caculate Row & Column Empty */
        const caculateColumnAsile = calculateSeatDistribution(seatsPerRow);
        const caculateRowAsile = calculateSeatDistribution(rows);

        // Sinh từng hàng ghế
        for(let i = 1; i <= rows; i++) {
            const rowChar = window.getRowSeat(i); // A, B, C...
            if(rowChar === null) break;

            const isVip = vipArr.includes(rowChar);
            const isCouple = coupleArr.includes(rowChar);

            /* Tạo DOM Row */
            const frameRow = document.createElement('ul');
            frameRow.id = `row-${rowChar}`;
            frameRow.className = "seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-start align-items-center";
            frameRow.setAttribute('data-row', rowChar);
            frameRow.setAttribute('wire:sc-sortable', "seat-row");

            let j = 1;
            while (j <= seatsPerRow) {
                const ceil = document.createElement('li');

                let seatType = isCouple ? 'double' : (isVip ? 'vip' : 'standard');
                let seatId = `${rowChar}${j}`;
                let seatClass = `seat seat-${seatType}`;
                let seatLabel = `Chỗ ngồi ${seatId}`;

                let checkAsile, isAsile, isDoor;
                if(checkAsile = checkAsileForSeatCouple(j, caculateColumnAsile)){
                    seatType = seatClass = j === seatsPerRow ? 'door' : 'aisle';
                    seatLabel = j === seatsPerRow ? 'Cửa' : "Chỗ trống";
                    j === seatsPerRow ? (isDoor = true) : (isAsile = true);
                }

                ceil.innerHTML = `
                    <span class="seat-helper">${seatLabel}</span>
                    <input type="checkbox" class="${seatClass}" id="${seatId}" data-number="${j}" data-id="${seatId}">
                    <label for="${seatId}" class="visually-hidden">${seatLabel}</label>
                `;
                ceil.dataset.seat = seatType;

                frameRow.appendChild(ceil);
                (isCouple && (!isAsile && !isDoor)) ? j += 2 : j++;
            }
            seatsLayout.appendChild(frameRow);
        }
        return frameSeats.cloneNode(true);
    };
});
