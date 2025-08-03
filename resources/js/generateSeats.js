document.addEventListener("livewire:init", () => {
    window.getRowSeat = function (row) {
        if ((typeof row === "number" && (row < 1 || row > 26)) ||
            (typeof row === "string" && row.length !== 1) ||
            (typeof row !== "number" && typeof row !== "string")) return null;

        return typeof row === "number" ? String.fromCharCode(row + 64) : row.toUpperCase().charCodeAt(0) - 64;
    };

    function calculateSeatDistribution(totalSeats) {
        const aislePositions = [];
        let i = 2;

        while (i <= totalSeats) {
            let groupSize = 4;
            let remaining = totalSeats - i + 1;

            if (remaining < 6) {
                groupSize = remaining;
            }
            else if (i + groupSize <= totalSeats) {
                aislePositions.push(i + groupSize);
            }

            i += groupSize;
        }

        return aislePositions;
    }

    function addColumn(clickedRow) {
        const rowElement = clickedRow.closest(".seat-row-layout");
        if (!rowElement) return;

        const rowChar = rowElement.dataset.row;
        const vipArr = window.currentVipRows
            ? window.currentVipRows
                .split(",")
                .map((r) => r.trim().toUpperCase())
            : [];
        const coupleArr = window.currentCoupleRows
            ? window.currentCoupleRows
                .split(",")
                .map((r) => r.trim().toUpperCase())
            : [];

        const isVip = vipArr.includes(rowChar);
        const isCouple = coupleArr.includes(rowChar);

        const currentSeats = rowElement.querySelectorAll(
            'li[data-seat]:not([data-seat="aisle"]):not([data-seat="add-column"])'
        ).length;
        const newSeatCount = currentSeats + 1;

        const caculateColumnAsile = calculateSeatDistribution(newSeatCount);

        const addBtn = rowElement.querySelector('[data-seat="add-column"]');
        if (addBtn) {
            addBtn.remove();
        }

        const newSeatNumber = newSeatCount;
        const seatCeil = document.createElement("li");
        const seatType = isCouple ? "double" : isVip ? "vip" : "standard";
        const seatClass = `seat seat-${seatType}`;
        const seatId = `${rowChar}${newSeatNumber}`;
        const seatLabel = `Chỗ ngồi ${seatId}`;

        seatCeil.innerHTML = `
            <span class="seat-helper">${seatLabel}</span>
            <input type="checkbox" class="${seatClass}" id="${seatId}" data-number="${newSeatNumber}">
            <label for="${seatId}" class="visually-hidden">${seatLabel}</label>
        `;
        seatCeil.dataset.seat = seatType;
        //seatCeil.setAttribute('sc-id', `${seatId}`);
        seatCeil.className = "seat-item";

        if (caculateColumnAsile.includes(newSeatNumber)) {
            const aisleCeil = document.createElement("li");
            aisleCeil.innerHTML = `
                <span class="seat-helper">Lối đi</span>
                <div class="aisle"></div>
            `;
            aisleCeil.dataset.seat = "aisle";
            rowElement.appendChild(aisleCeil);
        }

        rowElement.appendChild(seatCeil);

        const newAddColumnBtn = document.createElement("li");
        newAddColumnBtn.innerHTML = `
            <button class="add-column-btn" title="Thêm ghế">+</button>
        `;
        newAddColumnBtn.dataset.seat = "add-column";
        rowElement.appendChild(newAddColumnBtn);

        const maxSeatsInAnyRow = Math.max(
            ...Array.from(document.querySelectorAll(".seat-row-layout")).map(
                (row) =>
                    row.querySelectorAll(
                        'li[data-seat]:not([data-seat="aisle"]):not([data-seat="add-column"])'
                    ).length
            )
        );

        window.currentSeatsPerRow = maxSeatsInAnyRow;

        updateControlPanelValues();

        attachEventHandlers();
    }

    function updateControlPanelValues() {
        const totalSeatsInput = document.getElementById("total-seats-input");
        const seatsPerRowInput = document.getElementById("seats-per-row-input");
        const rowsInput = document.getElementById("rows-input");
        const currentTotalDisplay = document.getElementById(
            "current-total-seats"
        );

        if (
            totalSeatsInput &&
            seatsPerRowInput &&
            rowsInput &&
            currentTotalDisplay
        ) {
            seatsPerRowInput.value = window.currentSeatsPerRow;
            rowsInput.value = window.currentRows;
            const totalSeats = window.currentSeatsPerRow * window.currentRows;
            totalSeatsInput.value = totalSeats;
            currentTotalDisplay.textContent = totalSeats;
        }
    }

    let selectedForDelete = null;
    let deleteClickCount = 0;
    let deleteTimeout = null;

    function handleSeatClick(event) {
        const seatItem = event.target.closest("li");
        const rowElement = event.target.closest("ul");

        if (!seatItem || !rowElement) return;

        const isRowClick =
            event.target === rowElement || seatItem.dataset.seat === "aisle";

        if (isRowClick) {
            handleRowDelete(rowElement);
        } else {
            handleSeatDelete(seatItem);
        }
    }

    function handleSeatDelete(seatItem) {
        deleteClickCount++;

        if (deleteTimeout) {
            clearTimeout(deleteTimeout);
        }

        if (deleteClickCount === 1) {
            deleteTimeout = setTimeout(() => {
                deleteClickCount = 0;
            }, 500);
        } else if (deleteClickCount === 2) {
            if (selectedForDelete) {
                selectedForDelete.classList.remove("selected-for-delete");
                const existingConfirm =
                    selectedForDelete.querySelector(".delete-confirm");
                if (existingConfirm) {
                    existingConfirm.remove();
                }
            }

            selectedForDelete = seatItem;
            seatItem.classList.add("selected-for-delete");

            const deleteConfirm = document.createElement("div");
            deleteConfirm.className = "delete-confirm";
            deleteConfirm.innerHTML = "🗑️ Xóa ghế";
            deleteConfirm.onclick = (e) => {
                e.stopPropagation();
                deleteSeat(seatItem);
            };

            seatItem.appendChild(deleteConfirm);
            deleteClickCount = 0;

            setTimeout(() => {
                if (selectedForDelete === seatItem) {
                    seatItem.classList.remove("selected-for-delete");
                    deleteConfirm.remove();
                    selectedForDelete = null;
                }
            }, 3000);
        }
    }

    function handleRowDelete(rowElement) {
        deleteClickCount++;

        if (deleteTimeout) {
            clearTimeout(deleteTimeout);
        }

        if (deleteClickCount === 1) {
            deleteTimeout = setTimeout(() => {
                deleteClickCount = 0;
            }, 500);
        } else if (deleteClickCount === 2) {
            if (selectedForDelete && selectedForDelete !== rowElement) {
                selectedForDelete.classList.remove("selected-for-delete");
                const existingConfirm = selectedForDelete.querySelector(
                    ".row-delete-confirm"
                );
                if (existingConfirm) {
                    existingConfirm.remove();
                }
            }

            selectedForDelete = rowElement;
            rowElement.classList.add("selected-for-delete");

            const deleteConfirm = document.createElement("div");
            deleteConfirm.className = "row-delete-confirm";
            deleteConfirm.innerHTML = "🗑️ Xóa hàng";
            deleteConfirm.onclick = (e) => {
                e.stopPropagation();
                deleteRow(rowElement);
            };

            rowElement.style.position = "relative";
            rowElement.appendChild(deleteConfirm);
            deleteClickCount = 0;

            setTimeout(() => {
                if (selectedForDelete === rowElement) {
                    rowElement.classList.remove("selected-for-delete");
                    deleteConfirm.remove();
                    selectedForDelete = null;
                }
            }, 3000);
        }
    }

    function deleteSeat(seatItem) {
        const rowElement = seatItem.closest("ul");
        const rowChar = rowElement.dataset.row;

        seatItem.remove();

        const seats = rowElement.querySelectorAll(
            'li[data-seat]:not([data-seat="aisle"])'
        );
        seats.forEach((seat, index) => {
            const input = seat.querySelector("input");
            const label = seat.querySelector("label");
            const helper = seat.querySelector(".seat-helper");

            if (input && label && helper) {
                const newNumber = index + 1;
                const newId = `${rowChar}${newNumber}`;

                input.id = newId;
                input.dataset.number = newNumber;
                //input.setAttribute('sc-id', newId);
                label.setAttribute("for", newId);
                label.textContent = `Chỗ ngồi ${newId}`;
                helper.textContent = `Chỗ ngồi ${newId}`;
            }
        });

        updateControlPanelValues();

        selectedForDelete = null;
    }

    function deleteRow(rowElement) {
        const rowChar = rowElement.dataset.row;

        rowElement.remove();

        const nextElement = rowElement.nextElementSibling;
        if (nextElement && nextElement.classList.contains("row-aisle")) {
            nextElement.remove();
        }

        window.currentRows--;

        updateControlPanelValues();

        selectedForDelete = null;
    }

    function attachEventHandlers() {
        document.removeEventListener("click", handleDocumentClick);
        const seatsLayout = document.querySelector("#seats-layout");
        if (seatsLayout) {
            seatsLayout.addEventListener("click", handleSeatClick);
        }

        const addColumnBtns = document.querySelectorAll(".add-column-btn");
        addColumnBtns.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                addColumn(e.target);
            });
        });

        document.addEventListener("click", handleDocumentClick);
    }

    function handleDocumentClick(event) {
        if (
            !event.target.closest(".seat-item") &&
            !event.target.closest(".seat-row-layout") &&
            !event.target.closest(".delete-confirm") &&
            !event.target.closest(".row-delete-confirm")
        ) {
            if (selectedForDelete) {
                selectedForDelete.classList.remove("selected-for-delete");
                const deleteConfirm = selectedForDelete.querySelector(
                    ".delete-confirm, .row-delete-confirm"
                );
                if (deleteConfirm) {
                    deleteConfirm.remove();
                }
                selectedForDelete = null;
            }
        }
    }

    function generateDOMFrameSeats(pathScreen) {
        const frameSeats = document.createElement("div");
        frameSeats.setAttribute("wire:ignore", "");
        frameSeats.classList = "st_seatlayout_main_wrapper w-100 mt-2";
        frameSeats.innerHTML = `
            <div class="container">
                <div class="st_seat_lay_heading float_left">
                    <h3>SE7ENCINEMA SCREEN</h3>
                </div>
                <div class="st_seat_full_container" style="float: none">
                    <div class="st_seat_lay_economy_wrapper float_left" style="width: 100% !important">
                        <div class="screen">
                            <img src="${pathScreen || location.origin + "/client/assets/images/content/screen.png"}">
                        </div>
                    </div>
                    <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left" style="width: auto !important" id="seats-layout"></div>
                </div>
            </div>
        `;
        return frameSeats.cloneNode(true);
    }

    function generateDOMFrameRow(rowChar, model, isAdmin = true){
        const frameRow = document.createElement("ul");
        frameRow.id = `row-${rowChar}`;
        frameRow.className = "seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-start align-items-center";
        frameRow.setAttribute("data-row", rowChar);
        isAdmin && frameRow.setAttribute("wire:sc-sortable.onMove.updateseatid", "sc-row");
        isAdmin && frameRow.setAttribute("wire:sc-model.live", model);
        isAdmin && frameRow.setAttribute('sc-group', "seat-row");
        frameRow.style.position = "relative";

        return frameRow.cloneNode(true);
    }

    function generateDOMSeatCeil(seatType, rowChar, currentCeil) {
        const seatCeil = document.createElement("li");
        seatCeil.dataset.seat = seatType;
        if(seatType !== "aisle"){
            const seatClass = `seat seat-${seatType}`;
            const seatId = `${rowChar}${currentCeil}`;
            const seatLabel = `Chỗ ngồi ${seatId}`;
            seatCeil.innerHTML = `
                <span class="seat-helper">${seatLabel}</span>
                <input type="checkbox" class="${seatClass}" id="${seatId}" data-number="${currentCeil}">
                <label for="${seatId}" class="visually-hidden">${seatLabel}</label>
            `;
            seatCeil.className = "seat-item";
            seatCeil.setAttribute('sc-id', `[${seatId}, ${seatType}]`);
        }else {
            seatCeil.innerHTML = `
                <span class="seat-helper">Lối đi</span>
                <div class="aisle"></div>
            `;
            seatCeil.setAttribute('sc-id', 'asile');
        }

        return seatCeil.cloneNode(true);
    }

    window.generateDOMSeats = function ({ rows, seatsPerRow, vipRows, coupleRows }, pathScreen) {
        const frameSeats = generateDOMFrameSeats(pathScreen);
        const seatsLayout = frameSeats.querySelector("#seats-layout");

        const caculateColumnAsile = calculateSeatDistribution(seatsPerRow);
        const caculateRowAsile = calculateSeatDistribution(rows);

        for (let i = 1; i <= rows; i++) {
            const rowChar = window.getRowSeat(i);
            if (rowChar === null) break;

            const isVip = vipRows.includes(rowChar);
            const isCouple = coupleRows.includes(rowChar);
            const frameRow = generateDOMFrameRow(rowChar, "temp");

            for (let j = 1; j <= seatsPerRow; j++) {
                frameRow.appendChild(generateDOMSeatCeil((isCouple ? "double" : (isVip ? "vip" : "standard")), rowChar, j));

                if (caculateColumnAsile.includes(j + 1) && j < seatsPerRow) frameRow.appendChild(generateDOMSeatCeil("aisle"));

                /* Support */
                if (isCouple && j < seatsPerRow) {
                    j++;
                }
            }

            const addColumnBtn = document.createElement("li");
            addColumnBtn.innerHTML = `
                <button class="add-column-btn" title="Thêm cột">+</button>
            `;
            addColumnBtn.dataset.seat = "add-column";
            addColumnBtn.setAttribute('sc-id', 'add-column-btn');
            frameRow.appendChild(addColumnBtn);

            seatsLayout.appendChild(frameRow);

            if (caculateRowAsile.includes(i + 1) && i < rows) {
                const aisleRow = document.createElement("div");
                aisleRow.className = "row-aisle";
                aisleRow.style.height = "20px";
                aisleRow.style.width = "100%";
                seatsLayout.appendChild(aisleRow);
            }
        }

        setTimeout(() => {
            attachEventHandlers();
        }, 0);

        return frameSeats.cloneNode(true);
    };

    class SeatCountdownTimer {
        constructor(expiresAt, onExpired, onUpdate) {
            this.expiresAt = expiresAt ? new Date(expiresAt) : null;
            this.onExpired = onExpired;
            this.onUpdate = onUpdate;
            this.interval = null;
            this.isRunning = false;

            if (this.expiresAt) {
                this.start();
            }
        }

        start() {
            if (this.isRunning || !this.expiresAt) return;
            this.isRunning = true;
            this.updateTime();
            this.interval = setInterval(() => {
                this.updateTime();
            }, 1000);
        }

        updateTime() {
            const now = new Date();
            const remaining = Math.max(0, Math.floor((this.expiresAt - now) / 1000));

            if (remaining <= 0) {
                this.stop();
                if (this.onExpired) {
                    this.onExpired();
                }
            } else if (this.onUpdate) {
                this.onUpdate(remaining);
            }
        }

        stop() {
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
            this.isRunning = false;
        }

        formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }
    }

    class SeatSynchronizer {
        constructor() {
            this.sessionId = Math.random().toString(36).substr(2, 9);
            this.countdownTimer = null;
            this.setupSynchronization();
        }

        setupSynchronization() {
            setInterval(() => {
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('checkHoldStatus');
                }
            }, 5000);

            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    if (typeof Livewire !== 'undefined') {
                        Livewire.dispatch('checkHoldStatus');
                    }
                }
            });
        }

        startCountdown(expiresAt, preventReset = false) {
            if (this.countdownTimer && preventReset) {
                return;
            }

            if (this.countdownTimer) {
                this.countdownTimer.stop();
            }

            const expireDate = new Date(expiresAt);
            const now = new Date();

            if (expireDate <= now) {
                this.showTimeoutAlert();
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('checkHoldStatus');
                }
                return;
            }

            this.countdownTimer = new SeatCountdownTimer(
                expireDate,
                () => {
                    this.showTimeoutAlert();
                    if (typeof Livewire !== 'undefined') {
                        Livewire.dispatch('checkHoldStatus');
                    }
                },
                (remainingSeconds) => {
                    this.updateCountdownDisplay(remainingSeconds);
                    if (remainingSeconds === 120) {
                        this.showWarningAlert('Còn 2 phút để hoàn tất việc chọn ghế!');
                    } else if (remainingSeconds === 30) {
                        this.showWarningAlert('Còn 30 giây! Vui lòng nhanh chóng hoàn tất!');
                    }
                }
            );
        }

        updateCountdownDisplay(remainingSeconds) {
            const countdownElement = document.getElementById('seat-countdown');
            if (countdownElement) {
                const timeString = this.countdownTimer.formatTime(remainingSeconds);
                let alertClass = 'alert-dark';
                let icon = 'fas fa-clock';

                if (remainingSeconds <= 30) {
                    icon = 'fas fa-exclamation-triangle';
                } else if (remainingSeconds <= 120) {
                    icon = 'fas fa-exclamation-circle';
                }

                countdownElement.innerHTML = `
                <div class="d-flex align-items-center justify-content-center fs-3 text-light  ${alertClass} p-2 rounded">
                        <i class="${icon} me-2"></i>
                        <strong>Thời gian giữ ghế: ${timeString}</strong>
                </div>
            `;
            }
        }

        showTimeoutAlert() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Hết thời gian giữ ghế!',
                    text: 'Thời gian giữ ghế đã hết. Vui lòng chọn lại ghế.',
                    confirmButtonText: 'Đồng ý'
                });
            } else {
                alert('Hết thời gian giữ ghế! Vui lòng chọn lại ghế.');
            }
        }

        showWarningAlert(message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cảnh báo!',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        }

        stopCountdown() {
            if (this.countdownTimer) {
                this.countdownTimer.stop();
                this.countdownTimer = null;
            }

            const countdownElement = document.getElementById('seat-countdown');
            if (countdownElement) {
                countdownElement.innerHTML = '';
            }
        }
    }

    const seatSynchronizer = new SeatSynchronizer();

    window.generateClientDOMSeats = function ({ seats, selectedSeats = [], sessionId, holdExpiresAt = null, remainingSeconds = 0 }, pathScreen, modelBinding = 'selectedSeats') {
        window.currentSelectedSeats = selectedSeats;
        window.currentSessionId = sessionId;

        if (holdExpiresAt && selectedSeats.length > 0) {
            const expiresDate = new Date(holdExpiresAt);
            const now = new Date();

            if (expiresDate > now) {
                seatSynchronizer.startCountdown(expiresDate);
            } else {
                seatSynchronizer.stopCountdown();
            }
        } else {
            seatSynchronizer.stopCountdown();
        }

        if (!seats || seats.length === 0) return;

        const rowSet = new Set();
        const rowInfo = {};

        for (const seat of seats) {
            const row = seat.seat_row.toUpperCase();
            rowSet.add(row);

            if (!rowInfo[row]) {
                rowInfo[row] = {
                    maxNumber: 0,
                    type: seat.seat_type
                };
            }

            if (seat.seat_number > rowInfo[row].maxNumber) {
                rowInfo[row].maxNumber = seat.seat_number;
            }
        }

        const sortedRows = Array.from(rowSet).sort();
        const rows = sortedRows.length;
        const seatsPerRow = Math.max(...Object.values(rowInfo).map(r => r.maxNumber));
        const vipRows = sortedRows.filter(r => rowInfo[r].type === 'vip').join(',');
        const coupleRows = sortedRows.filter(r => rowInfo[r].type === 'couple').join(',');

        const vipArr = vipRows.split(',').map(r => r.trim().toUpperCase());
        const coupleArr = coupleRows.split(',').map(r => r.trim().toUpperCase());

        const frameSeats = document.createElement("div");
        frameSeats.setAttribute("wire:ignore", "");
        frameSeats.classList = "st_seatlayout_main_wrapper w-100 mt-2";
        frameSeats.innerHTML = `
        <div class="container">
            <div class="st_seat_lay_heading float_left">
                <h3>SE7ENCINEMA SCREEN</h3>
            </div>

            <!-- Countdown Timer Display -->
            <div id="seat-countdown" class="seat-countdown-container text-center mb-3"></div>

            <div class="st_seat_full_container" style="float: none">
                <div class="st_seat_lay_economy_wrapper float_left" style="width: 100% !important">
                    <div class="screen">
                        <img src="${pathScreen || (location.origin + "/client/assets/images/content/screen.png")}" alt="Screen">
                    </div>
                </div>
                <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left" style="width: auto !important" id="seats-layout"></div>
            </div>
        </div>
    `;

        const seatsLayout = frameSeats.querySelector("#seats-layout");
        const caculateColumnAsile = calculateSeatDistribution(seatsPerRow);
        const caculateRowAsile = calculateSeatDistribution(rows);

        for (let i = 0; i < sortedRows.length; i++) {
            const rowChar = sortedRows[i];
            const isVip = vipArr.includes(rowChar);
            const isCouple = coupleArr.includes(rowChar);

            const frameRow = generateDOMFrameRow(rowChar, undefined, false);

            for (let j = 1; j <= seatsPerRow; j++) {
                const seatId = `${rowChar}${j}`;
                const seat = seats.find(s => s.seat_row.toUpperCase() === rowChar && s.seat_number === j);
                if (!seat) continue;

                const seatType = isCouple ? "double" : isVip ? "vip" : "standard";
                const seatClass = `seat seat-${seatType}`;
                const isChecked = selectedSeats.includes(seatId) ? 'checked' : '';
                const isBooked = seat.is_booked === true || seat.is_booked === 1;
                const isHeld = seat.is_held === true || seat.is_held === 1;
                const isDisabled = isBooked || isHeld ? 'disabled' : '';
                const dataDisabled = isBooked || isHeld ? 'true' : 'false';

                const seatCeil = document.createElement("li");
                seatCeil.innerHTML = `
                <input
                    type="checkbox"
                    class="${seatClass}"
                    id="${seatId}"
                    value="${seatId}"
                    wire:model.live="${modelBinding}"
                    data-number="${j}"
                    data-disabled="${dataDisabled}"
                    data-booked="${isBooked ? 'true' : 'false'}"
                    data-held="${isHeld ? 'true' : 'false'}"
                    ${isChecked}
                    ${isDisabled}
                    wire:sc-model="noop"
                    >
            `;

                if (isBooked || isHeld) {
                    addSeatOverlay(seatCeil, seatId, isBooked ? 'booked' : 'held');
                }

                seatCeil.dataset.seat = seatType;
                seatCeil.className = "seat-item";

                if (isBooked) {
                    seatCeil.classList.add("seat-booked");
                } else if (isHeld) {
                    seatCeil.classList.add("seat-held");
                } else if (selectedSeats.includes(seatId)) {
                    seatCeil.classList.add("seat-selected");
                } else {
                    seatCeil.classList.add("seat-available");
                }

                frameRow.appendChild(seatCeil);

                if (caculateColumnAsile.includes(j + 1) && j < seatsPerRow) {
                    const aisleCeil = document.createElement("li");
                    aisleCeil.innerHTML = `
                    <span class="seat-helper">Lối đi</span>
                    <div class="aisle"></div>
                `;
                    aisleCeil.dataset.seat = "aisle";
                    aisleCeil.className = "aisle-item";
                    frameRow.appendChild(aisleCeil);
                }

                if (isCouple) j++;
            }

            seatsLayout.appendChild(frameRow);

            if (caculateRowAsile.includes(i + 2) && i < rows) {
                const aisleRow = document.createElement("div");
                aisleRow.className = "row-aisle";
                aisleRow.style.height = "20px";
                aisleRow.style.width = "100%";
                seatsLayout.appendChild(aisleRow);
            }
        }

        function addSeatOverlay(parentLi, seatCode, type) {
            const input = parentLi.querySelector('input');
            const wrapper = document.createElement("div");
            wrapper.className = "seat-wrapper";
            wrapper.style.position = "relative";
            wrapper.style.cursor = "not-allowed";

            const overlay = document.createElement("div");
            overlay.className = "seat-overlay";
            overlay.style.position = "absolute";
            overlay.style.top = "0";
            overlay.style.left = "0";
            overlay.style.width = "100%";
            overlay.style.height = "100%";
            overlay.style.zIndex = "10";
            overlay.style.cursor = "not-allowed";

            if (type === 'booked') {
                overlay.setAttribute('wire:sc-alert.error.icon.position.timer.3000', '');
                overlay.setAttribute('wire:sc-title', `Ghế ${seatCode} đã được đặt!`);
                overlay.setAttribute('wire:sc-html', 'Ghế này đã được đặt trước đó và không thể chọn.');
            } else if (type === 'held') {
                overlay.setAttribute('wire:sc-alert.warning.icon.position.timer.5000', '');
                overlay.setAttribute('wire:sc-title', `Ghế ${seatCode} đang được giữ`);
                overlay.setAttribute('wire:sc-html', 'Ghế này đang được giữ bởi người khác.');
            }
            overlay.setAttribute('wire:sc-model', 'noop');

            input.remove();
            wrapper.appendChild(input);
            wrapper.appendChild(overlay);
            parentLi.appendChild(wrapper);
        }

        function hasLonelySeat(selectedSeats) {
            const grouped = {};

            for (const code of selectedSeats) {
                const row = code.match(/[A-Z]/i)[0];
                const col = parseInt(code.replace(/[A-Z]/i, ''));
                if (!grouped[row]) grouped[row] = [];
                grouped[row].push(col);
            }

            for (const row in grouped) {
                const cols = grouped[row].sort((a, b) => a - b);
                const minCol = Math.min(...cols);
                const maxCol = Math.max(...cols);

                for (let col = minCol + 1; col < maxCol; col++) {
                    const currentSeatId = `${row}${col}`;
                    const input = document.querySelector(`input[value="${currentSeatId}"]`);

                    if (input && !input.disabled && input.dataset.booked !== 'true' && input.dataset.held !== 'true') {
                        const isSelected = selectedSeats.includes(currentSeatId);
                        if (!isSelected) {
                            return true;
                        }
                    }
                }
            }

            return false;
        }

        function hasSole(selectedSeats) {
            if (selectedSeats.length < 2) return false;

            const grouped = {};
            for (const code of selectedSeats) {
                const row = code.match(/[A-Z]/i)[0];
                const col = parseInt(code.replace(/[A-Z]/i, ''));
                if (!grouped[row]) grouped[row] = [];
                grouped[row].push(col);
            }

            for (const row in grouped) {
                const cols = grouped[row].sort((a, b) => a - b);
                const groups = [];
                let currentGroup = [cols[0]];

                for (let i = 1; i < cols.length; i++) {
                    if (cols[i] - cols[i - 1] === 1) {
                        currentGroup.push(cols[i]);
                    } else {
                        groups.push(currentGroup);
                        currentGroup = [cols[i]];
                    }
                }
                groups.push(currentGroup);

                if (groups.length > 1) {
                    for (let i = 0; i < groups.length - 1; i++) {
                        const gap = groups[i + 1][0] - groups[i][groups[i].length - 1];

                        if (gap === 2) {
                            const middleSeat = groups[i][groups[i].length - 1] + 1;
                            const middleSeatId = `${row}${middleSeat}`;
                            const middleInput = document.querySelector(`input[value="${middleSeatId}"]`);

                            if (middleInput && !middleInput.disabled &&
                                middleInput.dataset.booked !== 'true' &&
                                middleInput.dataset.held !== 'true') {
                                return true;
                            }
                        }
                    }
                }
            }
            return false;
        }

        function hasInvalidDiagonal(selectedSeats) {
            if (selectedSeats.length < 2) return false;

            const positions = selectedSeats.map(code => {
                const row = code.match(/[A-Z]/i)[0];
                const col = parseInt(code.replace(/[A-Z]/i, ''));
                return { row, col, rowCode: row.charCodeAt(0) };
            });

            const rowGroups = {};
            positions.forEach(pos => {
                if (!rowGroups[pos.row]) rowGroups[pos.row] = [];
                rowGroups[pos.row].push(pos.col);
            });

            if (Object.keys(rowGroups).length === 1) return false;

            const rows = Object.keys(rowGroups).sort();

            for (let i = 0; i < rows.length - 1; i++) {
                const currentRow = rows[i];
                const nextRow = rows[i + 1];

                if (nextRow.charCodeAt(0) - currentRow.charCodeAt(0) === 1) {
                    const currentCols = rowGroups[currentRow];
                    const nextCols = rowGroups[nextRow];

                    let hasNearbySeats = false;
                    for (const col1 of currentCols) {
                        for (const col2 of nextCols) {
                            if (Math.abs(col1 - col2) <= 1) {
                                hasNearbySeats = true;
                                break;
                            }
                        }
                        if (hasNearbySeats) break;
                    }

                    if (!hasNearbySeats) {
                        return true;
                    }
                } else {
                    return true;
                }
            }

            return false;
        }

        frameSeats.querySelectorAll('input.seat').forEach(input => {
            input.addEventListener('change', (e) => {
                const current = e.target;
                const seatCode = current.value;
                const isBooked = current.dataset.booked === 'true';
                const isHeld = current.dataset.held === 'true';

                if (isBooked || isHeld) {
                    e.preventDefault();
                    current.checked = false;

                    const alertType = isBooked ? 'error' : 'warning';
                    const title = isBooked ? `Ghế ${seatCode} đã được đặt!` : `Ghế ${seatCode} đang được giữ`;
                    const message = isBooked ? 'Ghế này đã được đặt trước đó. Vui lòng chọn ghế khác.' : 'Ghế này đang được giữ bởi người khác.';

                    current.setAttribute(`wire:sc-alert.${alertType}.icon.position.timer.3000`, '');
                    current.setAttribute('wire:sc-title', title);
                    current.setAttribute('wire:sc-html', message);
                    current.setAttribute('wire:sc-model', 'noop');
                    return;
                }

                const checkedInputs = Array.from(frameSeats.querySelectorAll('input.seat:checked'));
                const selectedSeatCodes = checkedInputs.map(i => i.value);

                if (hasLonelySeat(selectedSeatCodes)) {
                    e.preventDefault();
                    current.checked = false;
                    current.setAttribute('wire:sc-alert.error.icon.position.timer.5000', '');
                    current.setAttribute('wire:sc-title', 'Không được để ghế lẻ!');
                    current.setAttribute('wire:sc-html', 'Vui lòng chọn lại ghế, không để ghế lẻ giữa các ghế đã chọn.');
                    return;
                }

                if (hasSole(selectedSeatCodes)) {
                    e.preventDefault();
                    current.checked = false;
                    current.setAttribute('wire:sc-alert.error.icon.position.timer.5000', '');
                    current.setAttribute('wire:sc-title', 'Không được để khoảng trống!');
                    current.setAttribute('wire:sc-html', 'Vui lòng chọn ghế liền kề, không để khoảng trống có thể chọn được.');
                    return;
                }

                if (hasInvalidDiagonal(selectedSeatCodes)) {
                    e.preventDefault();
                    current.checked = false;
                    current.setAttribute('wire:sc-alert.error.icon.position.timer.5000', '');
                    current.setAttribute('wire:sc-title', 'Cách chọn ghế không hợp lệ!');
                    current.setAttribute('wire:sc-html', 'Vui lòng chọn ghế ở các hàng liền kề và gần nhau.');
                    return;
                }

                const finalSelectedInputs = Array.from(frameSeats.querySelectorAll('input.seat:checked'));
                const finalSelectedSeatCodes = finalSelectedInputs.map(i => i.value);

                window.currentSelectedSeats = finalSelectedSeatCodes;

                frameSeats.querySelectorAll('input.seat').forEach(seatInput => {
                    const code = seatInput.value;
                    const isSelected = finalSelectedSeatCodes.includes(code);
                    const parentLi = seatInput.closest('li.seat-item');
                    if (!parentLi) return;

                    parentLi.classList.remove("seat-held", "seat-selected", "seat-booked", "seat-available");

                    if (seatInput.dataset.booked === 'true') {
                        parentLi.classList.add("seat-booked");
                    } else if (seatInput.dataset.held === 'true') {
                        parentLi.classList.add("seat-held");
                    } else if (isSelected) {
                        parentLi.classList.add("seat-selected");
                    } else {
                        parentLi.classList.add("seat-available");
                    }
                });

                Livewire.dispatch('updateSelectedSeats', [finalSelectedSeatCodes]);
            });
        });

        return frameSeats;
    };

    window.addEventListener('beforeunload', () => {
        if (seatSynchronizer.countdownTimer) {
            seatSynchronizer.countdownTimer.stop();
        }

        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('checkHoldStatus');
        }
    });
});
