document.addEventListener("livewire:init", () => {
    // Global variables for seat management
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
            else if(i + groupSize <= totalSeats) {
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
            ${
                type === "success"
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
                        <img src="${
                            pathScreen ||
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

            // Add column button at the end of each row
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
});
