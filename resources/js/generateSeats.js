document.addEventListener("livewire:init", () => {
    window.currentSeatsPerRow = 17;
    window.currentRows = 10;
    window.currentVipRows = "A";
    window.currentCoupleRows = "B";

    window.getRowSeat = function (row) {
        if (
            (typeof row === "number" && (row < 1 || row > 26)) ||
            (typeof row === "string" && row.length !== 1) ||
            (typeof row !== "number" && typeof row !== "string")
        )
            return null;

        return typeof row === "number"
            ? String.fromCharCode(row + 64)
            : row.toUpperCase().charCodeAt(0) - 64;
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

    function checkAsileForSeatCouple(positionCurrent, positionAsile) {
        if (typeof positionCurrent !== "number") return void 0;
        if (typeof positionAsile !== "number" && !Array.isArray(positionAsile))
            return void 0;

        const aisleArray = Array.isArray(positionAsile)
            ? positionAsile
            : [positionAsile];

        if (aisleArray.includes(positionCurrent)) {
            return true;
        }

        if (aisleArray.includes(positionCurrent + 1)) {
            return "nexting";
        }

        return false;
    }

    // Function to handle total seats input change
    function handleTotalSeatsChange() {
        const totalSeatsInput = document.getElementById("total-seats-input");
        const seatsPerRowInput = document.getElementById("seats-per-row-input");
        const rowsInput = document.getElementById("rows-input");
        const currentTotalDisplay = document.getElementById(
            "current-total-seats"
        );

        function updateTotalSeats() {
            const totalSeats = parseInt(totalSeatsInput.value) || 0;
            const seatsPerRow =
                parseInt(seatsPerRowInput.value) || window.currentSeatsPerRow;

            if (totalSeats > 0 && seatsPerRow > 0) {
                const calculatedRows = Math.ceil(totalSeats / seatsPerRow);
                rowsInput.value = Math.min(calculatedRows, 26); // Max 26 rows (A-Z)

                // Update display
                const actualTotal = seatsPerRow * parseInt(rowsInput.value);
                currentTotalDisplay.textContent = actualTotal;
            }
        }

        function updateByRowsAndSeats() {
            const rows = parseInt(rowsInput.value) || window.currentRows;
            const seatsPerRow =
                parseInt(seatsPerRowInput.value) || window.currentSeatsPerRow;

            const calculatedTotal = rows * seatsPerRow;
            totalSeatsInput.value = calculatedTotal;
            currentTotalDisplay.textContent = calculatedTotal;
        }

        // Event listeners
        totalSeatsInput.addEventListener("input", updateTotalSeats);
        seatsPerRowInput.addEventListener("input", () => {
            updateByRowsAndSeats();
            updateTotalSeats();
        });
        rowsInput.addEventListener("input", updateByRowsAndSeats);

        // Apply changes button
        const applyBtn = document.getElementById("apply-seat-changes");
        applyBtn.addEventListener("click", () => {
            const newRows = parseInt(rowsInput.value) || window.currentRows;
            const newSeatsPerRow =
                parseInt(seatsPerRowInput.value) || window.currentSeatsPerRow;

            // Validate inputs
            if (newRows < 1 || newRows > 26) {
                alert("Số hàng phải từ 1 đến 26");
                return;
            }

            if (newSeatsPerRow < 1 || newSeatsPerRow > 50) {
                alert("Số ghế mỗi hàng phải từ 1 đến 50");
                return;
            }

            // Update global variables
            window.currentRows = newRows;
            window.currentSeatsPerRow = newSeatsPerRow;

            // Regenerate seats
            regenerateSeats();

            // Update total display
            currentTotalDisplay.textContent = newRows * newSeatsPerRow;

            // Show success message
            showNotification("Đã cập nhật bố cục ghế thành công!", "success");
        });
    }

    // Function to show notifications
    function showNotification(message, type = "info") {
        const notification = document.createElement("div");
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 4px;
            color: white;
            font-weight: 600;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            ${type === "success"
                ? "background: #28a745;"
                : "background: #17a2b8;"
            }
        `;

        notification.textContent = message;
        document.body.appendChild(notification);

        // Add CSS animation
        if (!document.querySelector("#notification-styles")) {
            const style = document.createElement("style");
            style.id = "notification-styles";
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }

        setTimeout(() => {
            notification.style.animation = "slideIn 0.3s ease-out reverse";
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add column functionality
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

        // Get current number of seats in this row (excluding aisles and add button)
        const currentSeats = rowElement.querySelectorAll(
            'li[data-seat]:not([data-seat="aisle"]):not([data-seat="add-column"])'
        ).length;
        const newSeatCount = currentSeats + 1;

        // Calculate new aisle positions for this row specifically
        const caculateColumnAsile = calculateSeatDistribution(newSeatCount);

        // Remove the add button temporarily
        const addBtn = rowElement.querySelector('[data-seat="add-column"]');
        if (addBtn) {
            addBtn.remove();
        }

        // Add new seat
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

        // Check if we need to add aisle before the new seat
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

        // Re-add the add button at the end
        const newAddColumnBtn = document.createElement("li");
        newAddColumnBtn.innerHTML = `
            <button class="add-column-btn" title="Thêm ghế">+</button>
        `;
        newAddColumnBtn.dataset.seat = "add-column";
        rowElement.appendChild(newAddColumnBtn);

        // Update current seats per row if this is the maximum
        const maxSeatsInAnyRow = Math.max(
            ...Array.from(document.querySelectorAll(".seat-row-layout")).map(
                (row) =>
                    row.querySelectorAll(
                        'li[data-seat]:not([data-seat="aisle"]):not([data-seat="add-column"])'
                    ).length
            )
        );

        window.currentSeatsPerRow = maxSeatsInAnyRow;

        // Update control panel values
        updateControlPanelValues();

        // Re-attach event handlers for new elements
        attachEventHandlers();
    }

    // Function to update control panel values
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

    // Delete seat functionality
    let selectedForDelete = null;
    let deleteClickCount = 0;
    let deleteTimeout = null;

    function handleSeatClick(event) {
        const seatItem = event.target.closest("li");
        const rowElement = event.target.closest("ul");

        if (!seatItem || !rowElement) return;

        // Check if clicking on row (not on specific seat)
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
            // Double click detected
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

            // Auto hide after 3 seconds
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
            // Double click detected
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

            // Auto hide after 3 seconds
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

        // Remove seat from DOM
        seatItem.remove();

        // Update seat numbering in the row
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

        // Update control panel values
        updateControlPanelValues();

        selectedForDelete = null;
    }

    function deleteRow(rowElement) {
        const rowChar = rowElement.dataset.row;

        // Remove row from DOM
        rowElement.remove();

        // Check if there's a row aisle after this row and remove it
        const nextElement = rowElement.nextElementSibling;
        if (nextElement && nextElement.classList.contains("row-aisle")) {
            nextElement.remove();
        }

        // Update current rows count
        window.currentRows--;

        // Update control panel values
        updateControlPanelValues();

        selectedForDelete = null;
    }

    function regenerateSeats() {
        const seatsContainer = document.querySelector(
            ".st_seat_lay_economy_wrapperexicutive"
        );
        if (seatsContainer) {
            seatsContainer.innerHTML = "";

            const newSeatsLayout = window.generateDOMSeats({
                rows: window.currentRows,
                seatsPerRow: window.currentSeatsPerRow,
                vipRows: window.currentVipRows,
                coupleRows: window.currentCoupleRows,
            });

            const newSeatsLayoutContent =
                newSeatsLayout.querySelector("#seats-layout");
            seatsContainer.appendChild(...newSeatsLayoutContent.children);

            // Re-attach event handlers
            attachEventHandlers();
        }
    }

    function attachEventHandlers() {
        // Remove existing event handlers to prevent duplicates
        document.removeEventListener("click", handleDocumentClick);

        // Add event handlers for seats and rows
        const seatsLayout = document.querySelector("#seats-layout");
        if (seatsLayout) {
            seatsLayout.addEventListener("click", handleSeatClick);
        }

        // Add event handlers for add column buttons
        const addColumnBtns = document.querySelectorAll(".add-column-btn");
        addColumnBtns.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                addColumn(e.target);
            });
        });

        // Document click handler to clear selections
        document.addEventListener("click", handleDocumentClick);
    }

    function handleDocumentClick(event) {
        // Clear selection if clicking outside
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

    window.generateDOMSeats = function (
        { rows, seatsPerRow, vipRows, coupleRows },
        pathScreen
    ) {
        rows = parseInt(rows);
        seatsPerRow = parseInt(seatsPerRow);
        const vipArr = vipRows
            ? vipRows.split(",").map((r) => r.trim().toUpperCase())
            : [];
        const coupleArr = coupleRows
            ? coupleRows.split(",").map((r) => r.trim().toUpperCase())
            : [];

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
                        <img src="${pathScreen ||
            location.origin +
            "/client/assets/images/content/screen.png"
            }">
                    </div>
                </div>
                <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left" style="width: auto !important" id="seats-layout"></div>
            </div>
        </div>
    `;

        const seatsLayout = frameSeats.querySelector("#seats-layout");

        const caculateColumnAsile = calculateSeatDistribution(seatsPerRow);
        const caculateRowAsile = calculateSeatDistribution(rows);

        for (let i = 1; i <= rows; i++) {
            const rowChar = window.getRowSeat(i);
            if (rowChar === null) break;

            const isVip = vipArr.includes(rowChar);
            const isCouple = coupleArr.includes(rowChar);

            const frameRow = document.createElement("ul");
            frameRow.id = `row-${rowChar}`;
            frameRow.className =
                "seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-start align-items-center";
            frameRow.setAttribute("data-row", rowChar);
            frameRow.setAttribute(
                "wire:sc-sortable.onMove.updateseatid",
                "seat-row"
            );
            frameRow.setAttribute("wire:sc-model.live", "temp");
            frameRow.setAttribute('sc-group', "seat-row");
            frameRow.style.position = "relative";

            for (let j = 1; j <= seatsPerRow; j++) {
                const seatCeil = document.createElement("li");
                const seatType = isCouple
                    ? "double"
                    : isVip
                        ? "vip"
                        : "standard";
                const seatClass = `seat seat-${seatType}`;
                const seatId = `${rowChar}${j}`;
                const seatLabel = `Chỗ ngồi ${seatId}`;

                seatCeil.innerHTML = `
                <span class="seat-helper">${seatLabel}</span>
                <input type="checkbox" class="${seatClass}" id="${seatId}" data-number="${j}">
                <label for="${seatId}" class="visually-hidden">${seatLabel}</label>
            `;
                seatCeil.dataset.seat = seatType;
                seatCeil.className = "seat-item";
                seatCeil.setAttribute('sc-id', `[${seatId}, ]`)
                frameRow.appendChild(seatCeil);

                if (caculateColumnAsile.includes(j + 1) && j < seatsPerRow) {
                    const aisleCeil = document.createElement("li");
                    aisleCeil.innerHTML = `
                    <span class="seat-helper">Lối đi</span>
                    <div class="aisle"></div>
                `;
                    aisleCeil.dataset.seat = "aisle";
                    aisleCeil.setAttribute('sc-id', 'asile');
                    frameRow.appendChild(aisleCeil);
                }

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


    function setCookie(name, value, minutes = 10) {
        try {
            const expires = new Date();
            expires.setTime(expires.getTime() + (minutes * 60 * 1000));
            document.cookie = `${name}=${encodeURIComponent(value)};expires=${expires.toUTCString()};path=/;SameSite=Lax`;
            if (!getCookie(name)) {
                localStorage.setItem(name, value);
            }
        } catch (error) {
            console.warn('Cookie not available, using localStorage:', error);
            localStorage.setItem(name, value);
        }
    }

    function getCookie(name) {
        try {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) {
                    return decodeURIComponent(c.substring(nameEQ.length, c.length));
                }
            }

            return localStorage.getItem(name);
        } catch (error) {
            console.warn('Cookie not available, using localStorage:', error);
            return localStorage.getItem(name);
        }
    }

    function deleteCookie(name) {
        try {
            const pastDate = new Date();
            pastDate.setTime(pastDate.getTime() - 1000);
            document.cookie = `${name}=;expires=${pastDate.toUTCString()};path=/;`;
            localStorage.removeItem(name);
        } catch (error) {
            localStorage.removeItem(name);
        }
    }

    class SeatSynchronizer {
        constructor() {
            this.lastSelectedSeats = getCookie("selectedSeats") || "[]";
            this.isIncognito = this.detectIncognitoMode();
            this.setupSynchronization();
        }

        detectIncognitoMode() {
            try {
                const testName = '_test_cookie_' + Date.now();
                document.cookie = `${testName}=test;path=/`;
                const cookieWorks = document.cookie.includes(testName);

                if (cookieWorks) {
                    const pastDate = new Date();
                    pastDate.setTime(pastDate.getTime() - 1000);
                    document.cookie = `${testName}=;expires=${pastDate.toUTCString()};path=/;`;
                    return false;
                }
                return true;
            } catch (error) {
                return true;
            }
        }

        setupSynchronization() {
            window.addEventListener('storage', (e) => {
                if (e.key === 'selectedSeats' && e.newValue !== e.oldValue) {
                    this.handleSeatUpdate(e.newValue || "[]");
                }
            });

            if ('BroadcastChannel' in window) {
                this.broadcastChannel = new BroadcastChannel('seat_updates');
                this.broadcastChannel.addEventListener('message', (event) => {
                    if (event.data.type === 'seat_update') {
                        this.handleSeatUpdate(JSON.stringify(event.data.seats));
                    }
                });
            }

            this.setupPolling();

            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    this.checkForUpdates();
                }
            });
        }

        setupPolling() {
            setInterval(() => {
                this.checkForUpdates();
            }, 1000);
        }

        checkForUpdates() {
            const currentSelectedSeats = getCookie("selectedSeats") || "[]";

            if (currentSelectedSeats !== this.lastSelectedSeats) {
                this.handleSeatUpdate(currentSelectedSeats);
            }
        }

        handleSeatUpdate(newSelectedSeatsJson) {
            this.lastSelectedSeats = newSelectedSeatsJson;

            const newSelected = JSON.parse(newSelectedSeatsJson);
            const selectedOnThisTab = Array.from(document.querySelectorAll('input.seat:checked')).map(i => i.value);

            document.querySelectorAll('input.seat').forEach(input => {
                const seatCode = input.value;
                const isExternal = newSelected.includes(seatCode);
                const isMine = selectedOnThisTab.includes(seatCode);
                const isBooked = input.dataset.booked === 'true';
                const parentLi = input.closest('li.seat-item');

                parentLi?.classList.remove("seat-held", "seat-selected", "seat-available");

                if (isBooked) {
                    parentLi?.classList.add("seat-booked");
                } else if (isExternal && !isMine) {
                    input.disabled = true;
                    input.dataset.disabled = "true";
                    input.checked = true;
                    parentLi?.classList.add("seat-held");
                } else {
                    input.disabled = false;
                    input.dataset.disabled = "false";
                    input.checked = isMine;
                    parentLi?.classList.add(isMine ? "seat-selected" : "seat-available");
                }
            });

            if (typeof window.reloadSeatsFromBlade === 'function') {
                window.reloadSeatsFromBlade();
            }
        }

        broadcastUpdate(seats) {
            setCookie("selectedSeats", JSON.stringify(seats), 15);

            if (this.broadcastChannel) {
                this.broadcastChannel.postMessage({
                    type: 'seat_update',
                    seats: seats,
                    timestamp: Date.now()
                });
            }

            localStorage.setItem('selectedSeats', JSON.stringify(seats));
            localStorage.setItem('selectedSeats_timestamp', Date.now().toString());
        }
    }

    const seatSynchronizer = new SeatSynchronizer();

    window.reloadSeatsFromBlade = function () {
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('reloadSeats');
        }
    };

    window.generateClientDOMSeats = function ({ seats, selectedSeats = [] }, pathScreen, modelBinding = 'selectedSeats') {
        window.currentSelectedSeats = selectedSeats;
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

            const frameRow = document.createElement("ul");
            frameRow.id = `row-${rowChar}`;
            frameRow.className = "seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-start align-items-center";
            frameRow.setAttribute("data-row", rowChar);
            frameRow.style.position = "relative";

            for (let j = 1; j <= seatsPerRow; j++) {
                const seatId = `${rowChar}${j}`;
                const seat = seats.find(s => s.seat_row.toUpperCase() === rowChar && s.seat_number === j);
                if (!seat) continue;

                const seatType = isCouple ? "double" : isVip ? "vip" : "standard";
                const seatClass = `seat seat-${seatType}`;
                const seatLabel = `Chỗ ngồi ${seatId}`;
                const isChecked = selectedSeats.includes(seatId) ? 'checked' : '';
                const externalSelectedSeats = JSON.parse(getCookie("selectedSeats") || "[]");
                const isExternalSelected = externalSelectedSeats.includes(seatId) && !selectedSeats.includes(seatId);
                const isBooked = seat.is_booked === true || seat.is_booked === 1;
                const isDisabled = isExternalSelected || isBooked ? 'disabled' : '';
                const dataDisabled = isExternalSelected || isBooked ? 'true' : 'false';
                const toastalert = isExternalSelected
                    ? `wire:sc-alert.warning.icon.position.timer.5000 wire:sc-title="Ghế ${seatId} đang được giữ bởi người khác" wire:sc-model="noop"`
                    : 'wire:sc-model="noop"';

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
                    ${isChecked}
                    ${toastalert}
                    >
            `;

                seatCeil.dataset.seat = seatType;
                seatCeil.className = "seat-item";

                if (isBooked) {
                    seatCeil.classList.add("seat-booked");
                } else if (isExternalSelected) {
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

        function updateSeatVisualState(seatCode, isSelected, isHeld, isBooked) {
            const input = frameSeats.querySelector(`input[value="${seatCode}"]`);
            const parentLi = input?.closest('li.seat-item');
            if (!parentLi) return;
            parentLi.classList.remove("seat-held", "seat-selected", "seat-booked", "seat-available");
            if (isBooked) {
                parentLi.classList.add("seat-booked");
                input.disabled = true;
                input.dataset.disabled = "true";
                input.dataset.booked = "true";
                input.checked = false;
            } else if (isHeld) {
                parentLi.classList.add("seat-held");
                input.disabled = true;
                input.dataset.disabled = "true";
                input.checked = true;
            } else {
                input.disabled = false;
                input.dataset.disabled = "false";
                if (isSelected) {
                    parentLi.classList.add("seat-selected");
                    input.checked = true;
                } else {
                    parentLi.classList.add("seat-available");
                    input.checked = false;
                }
            }
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

                for (let col = minCol; col <= maxCol; col++) {
                    const currentSeatId = `${row}${col}`;
                    const input = document.querySelector(`input[value="${currentSeatId}"]`);

                    if (!input || input.disabled || input.dataset.booked === 'true') {
                        continue;
                    }

                    const isSelected = selectedSeats.includes(currentSeatId);

                    if (!isSelected) {
                        const leftSelected = selectedSeats.includes(`${row}${col - 1}`);
                        const rightSelected = selectedSeats.includes(`${row}${col + 1}`);

                        if (leftSelected && rightSelected) {
                            return true;
                        }
                    }
                }
            }

            return false;
        }


        function hasSole(selectedSeats) {
            const grouped = {};
            for (const code of selectedSeats) {
                const row = code.match(/[A-Z]/i)[0];
                const col = parseInt(code.replace(/[A-Z]/i, ''));
                if (!grouped[row]) grouped[row] = [];
                grouped[row].push(col);
            }

            for (const cols of Object.values(grouped)) {
                cols.sort((a, b) => a - b);
                for (let i = 0; i < cols.length - 1; i++) {
                    if (cols[i + 1] - cols[i] > 1) {
                        return true; // sole
                    }
                }
            }
            return false;
        }


        function hasInvalidDiagonal(selectedSeats) {
            if (selectedSeats.length < 2) return false;

            const positions = selectedSeats.map(code => {
                const rowCode = code.match(/[A-Z]/i)[0].charCodeAt(0);
                const col = parseInt(code.replace(/[A-Z]/i, ''));
                return { rowCode, col };
            });

            for (let i = 0; i < positions.length; i++) {
                for (let j = i + 1; j < positions.length; j++) {
                    const a = positions[i];
                    const b = positions[j];
                    if (Math.abs(a.rowCode - b.rowCode) === Math.abs(a.col - b.col)) {
                        return true;
                    }
                }
            }
            return false;
        }

        frameSeats.querySelectorAll('input.seat').forEach(input => {
            input.addEventListener('change', (e) => {
                const current = e.target;
                const seatCode = current.value;

                const externalHeld = JSON.parse(getCookie("selectedSeats") || "[]");
                const isBooked = current.dataset.booked === 'true';
                const isDisabled = current.dataset.disabled === 'true' || current.disabled === true;

                const checkedInputs = Array.from(frameSeats.querySelectorAll('input.seat:checked'));
                const selectedSeatCodes = checkedInputs.map(i => i.value);

                if (isBooked) {
                    e.preventDefault();
                    current.checked = false;

                    current.setAttribute('wire:sc-alert.error.icon.position.timer.2500', '');
                    current.setAttribute('wire:sc-title', `Ghế ${seatCode} đã được đặt!`);
                    current.setAttribute('wire:sc-html', 'Ghế này đã được đặt trước đó và không thể chọn.');
                    return;
                }

                if (externalHeld.includes(seatCode)) {
                    e.preventDefault();
                    current.checked = false;

                    current.setAttribute('wire:sc-alert.warning.icon.position.timer.5000', '');
                    current.setAttribute('wire:sc-title', `Ghế ${seatCode} đang được giữ bởi người khác`);
                    current.setAttribute('wire:sc-model', 'noop');
                    return;
                }

                if (hasLonelySeat(selectedSeatCodes)) {
                    e.preventDefault();
                    current.checked = false;

                    current.setAttribute('wire:sc-alert.error.icon.position.timer.5000', '');
                    current.setAttribute('wire:sc-title', 'Không được để ghế lẻ!');
                    current.setAttribute('wire:sc-html', 'Vui lòng chọn lại ghế, không để ghế lẻ.');
                    return;
                }

                if (hasInvalidDiagonal(selectedSeatCodes)) {
                    e.preventDefault();
                    current.checked = false;

                    current.setAttribute('wire:sc-alert.error.icon.position.timer.5000', '');
                    current.setAttribute('wire:sc-title', 'Không được chọn ghế chéo!');
                    current.setAttribute('wire:sc-html', 'Bạn đang chọn ghế theo đường chéo không hợp lệ.');
                    return;
                }

                if (hasSole(selectedSeatCodes)) {
                    e.preventDefault();
                    current.checked = false;

                    current.setAttribute('wire:sc-alert.error.icon.position.timer.5000', '');
                    current.setAttribute('wire:sc-title', 'Không được chọn sole!');
                    current.setAttribute('wire:sc-html', 'Vui lòng chọn ghế liền kề, không để khoảng trống.');
                    return;
                }

                // Update visual & cookie
                const finalSelectedInputs = Array.from(frameSeats.querySelectorAll('input.seat:checked'));
                const finalSelectedSeatCodes = finalSelectedInputs.map(i => i.value);

                window.currentSelectedSeats = finalSelectedSeatCodes;
                seatSynchronizer.broadcastUpdate(finalSelectedSeatCodes);

                frameSeats.querySelectorAll('input.seat').forEach(seatInput => {
                    const code = seatInput.value;
                    const isSelected = finalSelectedSeatCodes.includes(code);
                    const isHeld = externalHeld.includes(code) && !finalSelectedSeatCodes.includes(code);
                    const isBookedSeat = seatInput.dataset.booked === 'true';
                    updateSeatVisualState(code, isSelected, isHeld, isBookedSeat);
                });

                Livewire.dispatch('updateSelectedSeats', [finalSelectedSeatCodes]);
            });
        });



        return frameSeats;
    };

    window.addEventListener('beforeunload', () => {
        if (seatSynchronizer.broadcastChannel) {
            seatSynchronizer.broadcastChannel.close();
        }
    });

});
