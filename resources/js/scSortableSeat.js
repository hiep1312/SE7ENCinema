import Sortable, {Swap} from 'sortablejs';
Sortable.mount(new Swap());


document.addEventListener('livewire:initialized', function () {
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
});
