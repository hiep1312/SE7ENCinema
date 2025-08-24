document.addEventListener("livewire:init", () => {
    const qs = (s, r = document) => r.querySelector(s);
    const qsa = (s, r = document) => Array.from(r.querySelectorAll(s));
    const SEAT_LI_SEL = 'li[data-seat]:not([data-seat="aisle"]):not([data-seat="add-column"]):not([data-seat="delete-row"])';
    const BTN = {
        addCol: ".add-column-btn",
        delRow: ".delete-row-btn",
        delSeat: ".delete-seat-btn",
        addRow: ".add-row-btn",
        maint: ".maintenance-seat-btn",
    };
    const setHtml = (el, html) => (el.innerHTML = html, el);
    const make = (tag, cls = "", html = "") => {
        const el = document.createElement(tag);
        if (cls) el.className = cls;
        if (html) el.innerHTML = html;
        return el;
    };
    function pushAisleIf(rowEl, cond, isAdmin = true) {
        if (!cond) return;
        const li = make("li");
        li.dataset.seat = "aisle";
        li.title = "Lối đi";
        setHtml(li, `
         ${isAdmin ? `<span class="seat-helper">Lối đi</span> ` : ""}
        <div class="aisle"></div>
        ${isAdmin ? `<button type="button" class="btn-delete-aisle" aria-label="Xoá lối đi"><i class="fa-solid fa-xmark"></i></button>` : ""}
    `);
        if (isAdmin) {
            li.querySelector(".btn-delete-aisle").addEventListener("click", (e) => {
                e.stopPropagation();
                deleteAisle(li, isAdmin);
            });
        }
        rowEl.appendChild(li);
    }
    const seatTypeOfRow = (rowChar) => {
        const vipArr = (window.currentVipRows || "").split(",").map(s => s.trim().toUpperCase()).filter(Boolean);
        const coupleArr = (window.currentCoupleRows || "").split(",").map(s => s.trim().toUpperCase()).filter(Boolean);
        return coupleArr.includes(rowChar) ? "double" : (vipArr.includes(rowChar) ? "vip" : "standard");
    };
    const seatLiCount = (rowEl) => rowEl.querySelectorAll(SEAT_LI_SEL).length;
    const maxSeatsPerAnyRow = () => Math.max(0, ...qsa(".seat-row-layout").map(r => r.querySelectorAll(SEAT_LI_SEL).length));
    const seatIdOf = (rowChar, num) => `${rowChar}${num}`;
    const calcAisles = (n) => {
        const a = []; let i = 2;
        while (i <= n) {
            let g = 4, remain = n - i + 1;
            if (remain < 6) g = remain; else if (i + g <= n) a.push(i + g);
            i += g;
        }
        return a;
    };

    window.getRowSeat = function (row) {
        if ((typeof row === "number" && (row < 1 || row > 26)) || (typeof row === "string" && row.length !== 1) || (typeof row !== "number" && typeof row !== "string")) return null;
        return typeof row === "number" ? String.fromCharCode(row + 64) : row.toUpperCase().charCodeAt(0) - 64;
    };

    function createAddColumnButton(isAdmin = true) {
        const li = make("li");
        li.dataset.seat = "add-column";
        if (isAdmin) li.setAttribute("sc-id", "add-column-btn");
        setHtml(li, `<button type="button" class="add-column-btn" title="Thêm cột"><i class="fas fa-plus"></i></button>`);
        return li;
    }

    function createDeleteRowButton(rowChar, isAdmin = true) {
        const li = make("li");
        li.dataset.seat = "delete-row";
        if (isAdmin) setHtml(li, `<button type="button" class="delete-row-btn" title="Xóa hàng ${rowChar}" data-row="${rowChar}"><i class="fas fa-trash-alt"></i></button>`);
        return li;
    }

    function createAddRowButton(isAdmin = true) {
        const div = make("div", "add-row-container text-center mt-3");
        if (isAdmin) setHtml(div, `<button type="button" class="add-row-btn btn btn-primary" title="Thêm hàng mới"><i class="fas fa-plus"></i> Thêm hàng</button>`);
        return div;
    }

    function generateDOMFrameSeats(pathScreen) {
        const wrap = make("div");
        wrap.setAttribute("wire:ignore", "");
        wrap.className = "st_seatlayout_main_wrapper w-100 mt-2";
        setHtml(wrap, `
      <div class="container position-relative">
        <div class="st_seat_lay_heading float_left"><h3>SE7ENCINEMA SCREEN</h3></div>
        <div class="st_seat_full_container" style="float:none">
          <div class="st_seat_lay_economy_wrapper float_left" style="width:100%!important">
            <div class="screen"><img src="${pathScreen || location.origin + "/client/assets/images/content/screen.png"}"></div>
          </div>
          <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left" style="width:auto!important" id="seats-layout"></div>
        </div>
      </div>`);
        return wrap.cloneNode(true);
    }

    function generateDOMFrameRow(rowChar, model, isAdmin = true) {
        const ul = make("ul", "seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-center align-items-center");
        ul.id = `row-${rowChar}`;
        ul.dataset.row = rowChar;
        if (isAdmin) {
            ul.setAttribute("wire:sc-sortable.onMove.updateseatid", "sc-row");
            ul.setAttribute("wire:sc-model.live", model);
            ul.setAttribute("sc-group", "seat-row");
        }
        ul.style.position = "relative";
        return ul.cloneNode(true);
    }

    function generateDOMSeatCeil(seatType, rowChar, num, isAdmin = true) {
        const li = make("li");
        li.dataset.seat = seatType;
        const id = seatIdOf(rowChar, num);
        const sCls = `seat seat-${seatType}`;
        setHtml(li, `
        <input type="checkbox" class="${sCls}" id="${id}" data-number="${num}">
        <label for="${id}" class="visually-hidden">Chỗ ngồi ${id}</label>
        ${isAdmin ? `
        <div class="seat-tooltip mx-auto">
            <div class="seat-info"><strong>${id}</strong> - ${seatTypeLabel(seatType)}</div>
            <button type="button" class="maintenance-seat-btn" data-seat="${id}"><i class="fas fa-tools"></i> Bảo trì</button>
            <button type="button" class="delete-seat-btn" data-seat="${id}"><i class="fas fa-trash-alt"></i> Xóa</button>
        </div>` : ""}
    `);
        li.className = "seat-item";
        return li;
    }


    function remountRowButtons(rowEl, rowChar, isAdmin = true) {
        qs('[data-seat="add-column"]', rowEl)?.remove();
        qs('[data-seat="delete-row"]', rowEl)?.remove();
        if (isAdmin) {
            rowEl.appendChild(createAddColumnButton(true));
            rowEl.appendChild(createDeleteRowButton(rowChar, true));
        }
    }

    function updateControlPanelValues() {
        const totalSeatsInput = qs("#total-seats-input");
        const seatsPerRowInput = qs("#seats-per-row-input");
        const rowsInput = qs("#rows-input");
        const currentTotalDisplay = qs("#current-total-seats");
        if (!(totalSeatsInput && seatsPerRowInput && rowsInput && currentTotalDisplay)) return;
        seatsPerRowInput.value = window.currentSeatsPerRow;
        rowsInput.value = window.currentRows;
        const total = (window.currentSeatsPerRow || 0) * (window.currentRows || 0);
        totalSeatsInput.value = total;
        currentTotalDisplay.textContent = total;
    }

    function attachEventHandlers(isAdmin = true) {
        if (!isAdmin) return;
        [BTN.addCol, BTN.delRow, BTN.delSeat, BTN.addRow, BTN.maint].forEach(sel => {
           qsa(sel).forEach(b => b.replaceWith(b.cloneNode(true)));
        });

        qsa(BTN.addCol).forEach(btn =>
            btn.addEventListener("click", e => { e.stopPropagation(); addColumn(e.currentTarget, true); })
        );
        qsa(BTN.delRow).forEach(btn =>
            btn.addEventListener("click", e => { e.stopPropagation(); deleteRow(e.currentTarget.dataset.row, true); })
        );
        qsa(BTN.delSeat).forEach(btn =>
            btn.addEventListener("click", e => { e.stopPropagation(); deleteSeat(e.currentTarget.dataset.seat, true); })
        );
        qsa(BTN.addRow).forEach(btn =>
            btn.addEventListener("click", e => { e.stopPropagation(); addRow(true); })
        );
        qsa(BTN.maint).forEach(btn =>
            btn.addEventListener("click", e => { e.stopPropagation(); maintenanceSeat(e.currentTarget.dataset.seat, true); })
        );
        qsa('li[data-seat="aisle"]').forEach(li => li.replaceWith(li.cloneNode(true)));
        qsa('li[data-seat="aisle"]').forEach(li => {
            const btn = li.querySelector('.btn-delete-aisle');
            if (btn) btn.addEventListener('click', (e) => { e.stopPropagation(); deleteAisle(li, true); });
        });
    }

    function addColumn(clickedEl, isAdmin = true) {
        if (!isAdmin) return;
        const rowEl = clickedEl.closest(".seat-row-layout");
        if (!rowEl) return;

        const rowChar = rowEl.dataset.row;
        const firstSeat = rowEl.querySelector(`${SEAT_LI_SEL}`);
        const type = firstSeat?.dataset.seat || seatTypeOfRow(rowChar);
        const current = seatLiCount(rowEl) + 1;
        const aisles = calcAisles(current);

        qs('[data-seat="add-column"]', rowEl)?.remove();
        qs('[data-seat="delete-row"]', rowEl)?.remove();

        const id = seatIdOf(rowChar, current);
        const li = make("li", "seat-item");
        li.dataset.seat = type;

        setHtml(li, `
      <input type="checkbox" class="seat seat-${type}" id="${id}" data-number="${current}">
      ${isAdmin ? `
      <div class="seat-tooltip mx-auto">
        <div class="seat-info"><strong>${id}</strong> - ${seatTypeLabel(type)}</div>
        <button type="button" class="maintenance-seat-btn" data-seat="${id}"><i class="fas fa-tools"></i> Bảo trì</button>
        <button type="button" class="delete-seat-btn" data-seat="${id}"><i class="fas fa-trash-alt"></i> Xóa</button>
      </div>` : ``}
    `);

        pushAisleIf(rowEl, aisles.includes(current), isAdmin);
        rowEl.appendChild(li);
        remountRowButtons(rowEl, rowChar, isAdmin);
        window.currentSeatsPerRow = maxSeatsPerAnyRow();
        updateControlPanelValues();
        attachEventHandlers(isAdmin);
        if (isAdmin) syncToLivewire();
    }

    function deleteAisle(aisleLi, isAdmin = true) {
        if (!isAdmin) return;
        const rowEl = aisleLi.closest(".seat-row-layout");
        aisleLi.remove();
        if (rowEl) {
            const rowChar = rowEl.dataset.row;
            remountRowButtons(rowEl, rowChar, isAdmin);
        }
        attachEventHandlers(isAdmin);
        if (isAdmin) syncToLivewire();
    }


    function addRow(isAdmin = true) {
        if (!isAdmin) return;
        const layout = qs("#seats-layout");
        if (!layout) return;
        const rows = layout.querySelectorAll(".seat-row-layout");
        const lastChar = rows.length ? rows[rows.length - 1].dataset.row : "@";
        const newRowNum = lastChar.charCodeAt(0) - 64 + 1;
        const rowChar = window.getRowSeat(newRowNum);
        if (!rowChar) return;

        const seatsPerRow = window.currentSeatsPerRow || 10;
        const aislesCol = calcAisles(seatsPerRow);

        const ul = generateDOMFrameRow(rowChar, "temp", isAdmin);
        for (let j = 1; j <= seatsPerRow; j++) {
            ul.appendChild(generateDOMSeatCeil("standard", rowChar, j, isAdmin));
            pushAisleIf(ul, aislesCol.includes(j + 1) && j < seatsPerRow, isAdmin);
        }

        ul.append(createAddColumnButton(isAdmin), createDeleteRowButton(rowChar, isAdmin));

        qs(".add-row-container", layout)?.remove();
        layout.append(ul, createAddRowButton(isAdmin));
        window.currentRows = newRowNum;

        updateControlPanelValues();
        attachEventHandlers(isAdmin);
        if (isAdmin) syncToLivewire();
    }

    function deleteSeat(seatId, isAdmin = true) {
        if (!isAdmin) return;
        const input = document.getElementById(seatId);
        if (!input) return;

        const li = input.closest("li.seat-item");
        const rowEl = li.closest("ul");
        const rowChar = rowEl.dataset.row;
        li.remove();
        qsa(SEAT_LI_SEL, rowEl).forEach((seat, i) => {
            const n = i + 1;
            const newId = seatIdOf(rowChar, n);
            const inp = qs("input", seat);
            const label = qs("label", seat);
            const helper = qs(".seat-helper", seat);
            const tip = qs(".seat-tooltip", seat);

            if (inp) { inp.id = newId; inp.dataset.number = n; if ('value' in inp) inp.value = newId; }
            if (label) { label.setAttribute("for", newId); label.textContent = `Chỗ ngồi ${newId}`; }
            if (helper) helper.textContent = `Chỗ ngồi ${newId}`;
            if (tip) {
                const strong = qs(".seat-info strong", tip);
                const delBtn = qs(".delete-seat-btn", tip);
                const maint = qs(".maintenance-seat-btn", tip);
                if (strong) strong.textContent = newId;
                if (delBtn) delBtn.dataset.seat = newId;
                if (maint) maint.dataset.seat = newId;
            }
        });

        updateControlPanelValues();
        remountRowButtons(rowEl, rowChar, isAdmin);
        attachEventHandlers(isAdmin);
        if (isAdmin) syncToLivewire();
    }

    function deleteRow(rowChar, isAdmin = true) {
        if (!isAdmin) return;
        const rowEl = qs(`[data-row="${rowChar}"]`);
        if (!rowEl) return;
        const next = rowEl.nextElementSibling;
        rowEl.remove();
        if (next && next.classList.contains("row-aisle")) next.remove();
        window.currentRows = Math.max(0, (window.currentRows || 1) - 1);
        updateControlPanelValues();
        attachEventHandlers && isAdmin && attachEventHandlers();
        syncToLivewire && isAdmin && syncToLivewire();
    }

    function maintenanceSeat(seatId, isAdmin = true) {
        if (!isAdmin) return;

        const input = document.getElementById(seatId);
        if (!input) return;

        const li = input.closest("li.seat-item");
        const cur = input.dataset.maintenance === "true";

        const setMaintState = (isMaint) => {
            li.classList.toggle("seat-maintenance", isMaint);

            input.toggleAttribute("disabled", isMaint);
            input.checked = isMaint ? false : input.checked;

            input.dataset.maintenance = isMaint ? "true" : "false";
            input.setAttribute("data-maintenance", isMaint ? "true" : "false");

            input.setAttribute("aria-disabled", isMaint ? "true" : "false");
            input.dataset.disabled = isMaint ? "true" : "false";
        };

        if (cur) {
            setMaintState(false);
            const b = qs(".maintenance-seat-btn", li);
            if (b) {
                b.innerHTML = '<i class="fas fa-tools"></i> Bảo trì';
                b.title = "Đặt ghế vào trạng thái bảo trì";
            }
        } else {
            setMaintState(true);
            const b = qs(".maintenance-seat-btn", li);
            if (b) {
                b.innerHTML = '<i class="fas fa-unlock"></i> Bỏ bảo trì';
                b.title = "Bỏ ghế khỏi trạng thái bảo trì";
            }

            input.setAttribute("wire:sc-alert.warning.icon.position.timer.3000", "");
            input.setAttribute("wire:sc-title", `Ghế ${seatId} đã chuyển sang bảo trì`);
            input.setAttribute("wire:sc-html", "Ghế này hiện đang trong trạng thái bảo trì và không thể sử dụng.");
            input.setAttribute("wire:sc-model", "noop");
        }

        syncToLivewire();
    }

    window.generateDOMSeats = function ({ rows, seatsPerRow, vipRows, coupleRows, seat_algorithms }, pathScreen) {
        const frame = generateDOMFrameSeats(pathScreen);
        const layout = qs("#seats-layout", frame);
        const alg = seat_algorithms || {};
        window.seatRuleConfig = {
            lonely: alg.check_lonely,
            sole: alg.check_sole,
            diagonal: alg.check_diagonal
        };

        const aislesCol = calcAisles(seatsPerRow);
        const aislesRow = calcAisles(rows);
        for (let i = 1; i <= rows; i++) {
            const rowChar = window.getRowSeat(i); if (rowChar === null) break;
            const isVip = vipRows.includes(rowChar), isCouple = coupleRows.includes(rowChar);
            const ul = generateDOMFrameRow(rowChar, "temp");
            for (let j = 1; j <= seatsPerRow; j++) {
                ul.appendChild(generateDOMSeatCeil(isCouple ? "double" : (isVip ? "vip" : "standard"), rowChar, j));
                pushAisleIf(ul, aislesCol.includes(j + 1) && j < seatsPerRow);
                if (isCouple && j < seatsPerRow) j++;
            }
            ul.appendChild(createAddColumnButton(true));
            ul.appendChild(createDeleteRowButton(rowChar));
            layout.appendChild(ul);
            if (aislesRow.includes(i + 1) && i < rows) {
                const r = make("div", "row-aisle");
                r.style.height = "20px"; r.style.width = "100%";
                layout.appendChild(r);
            }
        }
        layout.appendChild(createAddRowButton());
        setTimeout(attachEventHandlers, 0);
        return frame.cloneNode(true);
    };


    window.generateClientDOMSeats = function ({ seats, selectedSeats = [], sessionId, holdExpiresAt = null }, pathScreen, modelBinding = "selectedSeats") {
        window.currentSelectedSeats = selectedSeats;
        window.currentSessionId = sessionId;
        if (holdExpiresAt && selectedSeats.length > 0) {
            const exp = new Date(holdExpiresAt), now = Date.now();
            exp > now ? seatSynchronizer.startCountdown(exp) : seatSynchronizer.stopCountdown();
        } else seatSynchronizer.stopCountdown();
        if (!seats || !seats.length) return;

        const rowSet = new Set(), rowInfo = {};
        for (const s of seats) {
            const r = s.seat_row.toUpperCase();
            rowSet.add(r);
            rowInfo[r] ||= { maxNumber: 0, type: s.seat_type };
            if (s.seat_number > rowInfo[r].maxNumber) rowInfo[r].maxNumber = s.seat_number;
        }

        const rowsSorted = Array.from(rowSet).sort();
        const rows = rowsSorted.length;
        const seatsPerRow = Math.max(...Object.values(rowInfo).map(r => r.maxNumber));
        const vipArr = rowsSorted.filter(r => rowInfo[r].type === "vip");
        const coupleArr = rowsSorted.filter(r => rowInfo[r].type === "couple");

        const frame = generateDOMFrameSeats(pathScreen);
        const layout = qs("#seats-layout", frame);
        const aislesCol = calcAisles(seatsPerRow);
        const aislesRow = calcAisles(rows);

        for (let i = 0; i < rowsSorted.length; i++) {
            const rowChar = rowsSorted[i];
            const isVip = vipArr.includes(rowChar), isCouple = coupleArr.includes(rowChar);
            const ul = generateDOMFrameRow(rowChar, undefined, false);
            const uiPositionMap = new Map();
            let uiPosition = 1;
            for (let j = 1; j <= seatsPerRow; j++) {
                const seat = seats.find(s => s.seat_row.toUpperCase() === rowChar && s.seat_number === j);
                if (!seat) continue;
                uiPositionMap.set(j, uiPosition);
                const sid = seatIdOf(rowChar, j);
                const type = isCouple ? "double" : isVip ? "vip" : "standard";
                const sCls = `seat seat-${type}`;
                const isChecked = selectedSeats.includes(sid) ? "checked" : "";
                const isBooked = seat.is_booked === true || seat.is_booked === 1;
                const isHeld = seat.is_held === true || seat.is_held === 1;
                const isTrueish = (v) => {
                    const s = (v ?? "").toString().trim().toLowerCase();
                    return v === 1 || v === true || s === "1" || s === "true";
                };
                const isMaintenance = (v) => {
                    const s = (v ?? "").toString().trim().toLowerCase();
                    return s === "maintenance" || isTrueish(v);
                };
                const isMaint = isMaintenance(seat.status) || isMaintenance(seat.is_maintenance) || isMaintenance(seat.seat_status) || isMaintenance(seat.maintenance);
                const disabled = (isBooked || isHeld) ? "disabled" : "";
                const dataDis = isBooked || isHeld || isMaint ? "true" : "false";
                const li = make("li", "seat-item");
                setHtml(li, `
                <input type="checkbox" class="${sCls}" id="${sid}" value="${sid}" wire:model.live="${modelBinding}"
                data-number="${j}"
                data-disabled="${dataDis}"
                data-booked="${isBooked ? "true" : "false"}"
                data-held="${isHeld ? "true" : "false"}"
                data-maintenance="${isMaint ? "true" : "false"}"
                data-type="${isCouple ? "couple" : (isVip ? "vip" : "standard")}"
                ${isChecked} ${disabled} wire:sc-model="noop">`);
                li.dataset.seat = type;
                if (isMaint) li.classList.add("seat-maintenance");
                else if (isBooked) li.classList.add("seat-booked");
                else if (isHeld) li.classList.add("seat-held");
                else if (selectedSeats.includes(sid)) li.classList.add("seat-selected");
                else li.classList.add("seat-available");
                if (isBooked || isHeld) addSeatOverlay(li, sid, isBooked ? "booked" : "held");
                ul.appendChild(li);
                pushAisleIf(ul, aislesCol.includes(uiPosition + 1) && uiPosition < seatsPerRow, false);
                if (isCouple) {
                    uiPosition += 2;
                } else {
                    uiPosition += 1;
                }
            }

            layout.appendChild(ul);
            if (aislesRow.includes(i + 2) && i < rows) {
                const r = make("div", "row-aisle");
                r.style.height = "20px";
                r.style.width = "100%";
                layout.appendChild(r);
            }
        }

          const legendHtml = `
        <div class="seat-legend" style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; padding: 15px; background: rgba(0, 0, 0, 0.1); border-radius: 8px; magin-top:100px">
           <div class="seat-legend-item d-flex align-items-center">
             <div class="seat seat-standard me-2"></div>
                <span class="text-light fw-bold">Ghế thường
            </div>
            <div class="seat-legend-item d-flex align-items-center">
                <div class="seat seat-vip me-2"></div>
                <span class="text-light fw-bold">Ghế VIP
            </div>
             <div class="seat-legend-item d-flex align-items-center">
                <div class="seat seat-double me-2"></div>
                <span class="text-light fw-bold">Ghế đôi
            </div>
            <div class="seat-item seat-held d-flex align-items-center">
            <div class="seat-wrapper d-flex align-items-center">
             <div class="seat seat-standard me-2"></div>
                <span class="text-light fw-bold">Ghế đang giữ
            </div>
            </div>
             <div class="seat-legend-item seat-booked d-flex align-items-center">
             <div class="seat-wrapper d-flex align-items-center">
             <div class="seat seat-standard me-2"></div>
                <span class="text-light fw-bold">Ghế đã thanh toán
            </div>
            </div>
             <div class="seat-legend-item d-flex align-items-center">
             <div class="seat seat-standard seat-maintenance me-2"></div>
                <span class="text-light fw-bold">Ghế bảo trì
            </div>
        </div>
    `;

    layout.insertAdjacentHTML('beforeend', legendHtml);

        function addSeatOverlay(parentLi, seatCode, type) {
            const input = qs("input", parentLi);
            const wrap = make("div", "seat-wrapper");
            wrap.style.position = "relative";
            const ov = make("div", "seat-overlay");
            Object.assign(ov.style, { position: "absolute", top: "0", left: "0", width: "100%", height: "100%", zIndex: "10" });
            if (type === "booked") {
                ov.setAttribute("wire:sc-alert.error.icon.position.timer.5000", "");
                ov.setAttribute("wire:sc-title", `Ghế ${seatCode} đã được đặt!`);
                ov.setAttribute("wire:sc-html", "Ghế này đã được đặt trước đó và không thể chọn.");
            } else if (type === "held") {
                ov.setAttribute("wire:sc-alert.warning.icon.position.timer.5000", "");
                ov.setAttribute("wire:sc-title", `Ghế ${seatCode} đang được giữ`);
                ov.setAttribute("wire:sc-html", "Ghế này đang được giữ bởi người khác.");
                } else if (type === "maintenance") {
                     ov.setAttribute("wire:sc-alert.info.icon.position.timer.5000", "");
                     ov.setAttribute("wire:sc-title", `Ghế ${seatCode} đang bảo trì`);
                     ov.setAttribute("wire:sc-html", "Ghế này đang bảo trì và không thể chọn.");
                }
            ov.setAttribute("wire:sc-model", "noop");
            input.remove();
            wrap.appendChild(input);
            wrap.appendChild(ov);
            parentLi.appendChild(wrap);
        }
        function groupSelectedNonCouple(selected) {
            const grouped = {};
            const nonCouple = selected.filter(code => {
                const i = qs(`input[value="${code}"]`);
                return i && i.dataset.type !== "couple";
            });
            for (const code of nonCouple) {
                const row = code.match(/[A-Z]/i)[0];
                const col = parseInt(code.replace(/[A-Z]/i, ""));
                (grouped[row] ||= []).push(col);
            }
            for (const r in grouped) grouped[r].sort((a, b) => a - b);
            return { grouped, nonCouple };
        }

        function hasLonelySeat(selected) {
            const { grouped, nonCouple } = groupSelectedNonCouple(selected);
            for (const row in grouped) {
                const cols = grouped[row];
                for (let c = Math.min(...cols) + 1; c < Math.max(...cols); c++) {
                    const id = `${row}${c}`, i = qs(`input[value="${id}"]`);
                    if (i && !i.disabled && i.dataset.booked !== "true" && i.dataset.held !== "true" && i.dataset.type !== "couple" && !nonCouple.includes(id)) {
                        return true;
                    }
                }
            }
            return false;
        }
        function hasSole(selected) {
            const { grouped, nonCouple } = groupSelectedNonCouple(selected);
            for (const row in grouped) {
                const inputs = qsa(`input[value^="${row}"]`);
                let maxN = 0; inputs.forEach(i => {
                    const n = parseInt(i.value.replace(/[A-Z]/i, ""));
                    if (!isNaN(n)) maxN = Math.max(maxN, n);
                });
                if (grouped[row].includes(2) && !nonCouple.includes(`${row}1`)) {
                    const i = qs(`input[value="${row}1"]`);
                    if (i && !i.disabled && i.dataset.booked !== "true" && i.dataset.held !== "true" && i.dataset.type !== "couple") return true;
                }
                if (grouped[row].includes(maxN - 1) && !nonCouple.includes(`${row}${maxN}`)) {
                    const i = qs(`input[value="${row}${maxN}"]`);
                    if (i && !i.disabled && i.dataset.booked !== "true" && i.dataset.held !== "true" && i.dataset.type !== "couple") return true;
                }
            }
            return false;
        }
        function hasInvalidDiagonal(selected) {
            const { grouped } = groupSelectedNonCouple(selected);
            const rows = Object.keys(grouped).sort();
            if (rows.length < 2) return false;
            for (let i = 0; i < rows.length - 1; i++) {
                const r1 = rows[i], r2 = rows[i + 1];
                if (r2.charCodeAt(0) - r1.charCodeAt(0) !== 1) return true;
                const c1 = grouped[r1], c2 = grouped[r2];
                let near = false;
                for (const a of c1) { for (const b of c2) { if (Math.abs(a - b) <= 1) { near = true; break; } } if (near) break; }
                if (!near) return true;
            }
            return false;
        }
        function validateDomSeatSelected(selected) {
            const r = window.seatRuleConfig || { lonely: true, sole: true, diagonal: true };
            if (r.lonely && hasLonelySeat(selected)) return { valid: false, reason: 'lonely' };
            if (r.sole && hasSole(selected)) return { valid: false, reason: 'sole' };
            if (r.diagonal && hasInvalidDiagonal(selected)) return { valid: false, reason: 'diagonal' };
            return { valid: true };
        }
        frame.querySelectorAll("input.seat").forEach(inp => {
            inp.addEventListener("change", e => {
                const cur = e.target, code = cur.value;
                const isBooked = cur.dataset.booked === "true", isHeld = cur.dataset.held === "true", isMaint = cur.dataset.maintenance === "true";
                if (isBooked || isHeld || isMaint) {
                    e.preventDefault(); cur.checked = false;
                    if (isMaint) {
                        cur.setAttribute("wire:sc-alert.info.icon.position.timer.3000", "");
                        cur.setAttribute("wire:sc-title", `Ghế ${code} đang bảo trì`);
                        cur.setAttribute("wire:sc-html", "Ghế này hiện đang trong trạng thái bảo trì và không thể chọn.");
                        cur.setAttribute("wire:sc-model", "noop");
                        return;
                    }
                    const t = isBooked ? "error" : "warning";
                    const title = isBooked ? `Ghế ${code} đã được đặt!` : `Ghế ${code} đang được giữ`;
                    const msg = isBooked ? "Ghế này đã được đặt trước đó. Vui lòng chọn ghế khác." : "Ghế này đang được giữ bởi người khác.";
                    cur.setAttribute(`wire:sc-alert.${t}.icon.position.timer.3000`, "");
                    cur.setAttribute("wire:sc-title", title);
                    cur.setAttribute("wire:sc-html", msg);
                    cur.setAttribute("wire:sc-model", "noop");
                    return;
                }
                const selectedCodes = Array.from(frame.querySelectorAll("input.seat:checked")).map(i => i.value);
                const v = validateDomSeatSelected(selectedCodes);
                if (!v.valid) {
                    e.preventDefault(); cur.checked = false;
                    const map = {
                        lonely: ["error", "Không được để ghế lẻ!", "Vui lòng chọn lại ghế, không để ghế lẻ giữa các ghế đã chọn (áp dụng cho ghế thường và VIP)"],
                        sole: ["error", "Không được để khoảng trống!", "Vui lòng không bỏ ghế trong góc tường hoặc cạnh lối ra (áp dụng cho ghế thường và VIP)"],
                        diagonal: ["error", "Cách chọn ghế không hợp lệ!", "Vui lòng chọn ghế ở các hàng liền kề và gần nhau không chọn chéo (áp dụng cho ghế thường và VIP)"],
                    }[v.reason];
                    cur.setAttribute(`wire:sc-alert.${map[0]}.icon.position.timer.5000`, "");
                    cur.setAttribute("wire:sc-title", map[1]);
                    cur.setAttribute("wire:sc-html", map[2]);
                    cur.setAttribute("wire:sc-model", "noop");
                    return;
                }
                const finalCodes = Array.from(frame.querySelectorAll("input.seat:checked")).map(i => i.value);
                window.currentSelectedSeats = finalCodes;
                frame.querySelectorAll("input.seat").forEach(si => {
                    const code2 = si.value, li = si.closest("li.seat-item"); if (!li) return;
                    li.classList.remove("seat-held", "seat-selected", "seat-booked", "seat-available");
                    if (si.dataset.booked === "true") li.classList.add("seat-booked");
                    else if (si.dataset.held === "true") li.classList.add("seat-held");
                    else if (finalCodes.includes(code2)) li.classList.add("seat-selected");
                    else li.classList.add("seat-available");
                });
                if (typeof Livewire !== "undefined") Livewire.dispatch("updateSelectedSeats", [finalCodes]);
            });
        });
        return frame;
    };

    class SeatCountdownTimer {
        constructor(expiresAt, onExpired, onUpdate) { this.expiresAt = expiresAt ? new Date(expiresAt) : null; this.onExpired = onExpired; this.onUpdate = onUpdate; this.interval = null; this.isRunning = false; if (this.expiresAt) this.start(); }
        start() { if (this.isRunning || !this.expiresAt) return; this.isRunning = true; this.updateTime(); this.interval = setInterval(() => this.updateTime(), 1000); }
        updateTime() {
            const now = Date.now(); const remain = Math.max(0, Math.floor((this.expiresAt - now) / 1000));
            if (remain <= 0) { this.stop(); this.onExpired && this.onExpired(); } else this.onUpdate && this.onUpdate(remain);
        }
        stop() { if (this.interval) clearInterval(this.interval); this.interval = null; this.isRunning = false; }
        formatTime(sec) { const m = Math.floor(sec / 60), s = sec % 60; return `${String(m).padStart(2, "0")}:${String(s).padStart(2, "0")}`; }
    }

    class SeatSynchronizer {
        constructor() { this.sessionId = Math.random().toString(36).substr(2, 9); this.countdownTimer = null; this.setupSynchronization(); }
        setupSynchronization() {
            setInterval(() => { if (typeof Livewire !== "undefined") Livewire.dispatch("checkHoldStatus"); }, 5000);
            document.addEventListener("visibilitychange", () => { if (!document.hidden && typeof Livewire !== "undefined") Livewire.dispatch("checkHoldStatus"); });
        }
        startCountdown(expiresAt, preventReset = false) {
            if (this.countdownTimer && preventReset) return;
            this.countdownTimer?.stop();
            const exp = new Date(expiresAt), now = new Date();
            if (exp <= now) { if (typeof Livewire !== "undefined") Livewire.dispatch("checkHoldStatus"); return; }
            this.countdownTimer = new SeatCountdownTimer(exp, () => { if (typeof Livewire !== "undefined") Livewire.dispatch("checkHoldStatus"); }, (remain) => {
                this.updateCountdownDisplay(remain);
                if (remain === 120) alert("Còn 2 phút để hoàn tất việc chọn ghế!");
                remain === 30 && alert("Còn 30 giây! Vui lòng nhanh chóng hoàn tất!");
            });
        }
        updateCountdownDisplay(remain) {
            const el = qs("#seat-countdown"); if (!el || !(this.countdownTimer && typeof this.countdownTimer.formatTime === "function")) return;
            const t = this.countdownTimer.formatTime(remain);
            let icon = "fas fa-clock"; if (remain <= 30) icon = "fas fa-exclamation-triangle"; else if (remain <= 120) icon = "fas fa-exclamation-circle";
            setHtml(el, `<div class="d-flex align-items-center justify-content-center fs-3 text-light alert-dark p-2 rounded"><p>Thời gian giữ ghế: ${t}</p></div>`);
        }
        stopCountdown() { this.countdownTimer?.stop(); this.countdownTimer = null; const el = qs("#seat-countdown"); if (el) el.innerHTML = ""; }
    }
    const seatSynchronizer = new SeatSynchronizer();


    function childForSeatIndex(rowEl, index) {
        const seats = qsa(SEAT_LI_SEL, rowEl);
        if (index < seats.length) return seats[index];
        return qs('[data-seat="add-column"]', rowEl) || qs('[data-seat="delete-row"]', rowEl) || null;
    }

    function getInsertIndex(rowEl, clientX) {
        const seats = qsa(SEAT_LI_SEL, rowEl);
        if (!seats.length) return 0;
        const mids = seats.map(li => { const r = li.getBoundingClientRect(); return r.left + r.width / 2; });
        let idx = seats.length;
        for (let i = 0; i < mids.length; i++) { if (clientX < mids[i]) { idx = i; break; } }
        return idx;
    }

    function showDropMarker(rowEl, index) {
        let marker = qs('li[data-seat="drop-marker"]', rowEl);
        if (!marker) {
            marker = make('li'); marker.dataset.seat = 'drop-marker';
            Object.assign(marker.style, { width: '0', borderLeft: '2px dashed #2ecc71', height: '24px', margin: '0 4px' });
        }
        rowEl.insertBefore(marker, childForSeatIndex(rowEl, index));
    }

    function clearDropMarker(rowEl) { qs('li[data-seat="drop-marker"]', rowEl)?.remove(); }

    function reindexRowSeats(rowEl, rowChar) {
        qsa(SEAT_LI_SEL, rowEl).forEach((seat, i) => {
            const n = i + 1, newId = seatIdOf(rowChar, n);
            const inp = qs('input', seat), label = qs('label', seat), tip = qs('.seat-tooltip', seat);
            if (inp) { inp.id = newId; inp.dataset.number = n; if ('value' in inp) inp.value = newId; }
            if (label) { label.setAttribute('for', newId); label.textContent = `Chỗ ngồi ${newId}`; }
            if (tip) {
                const strong = qs('.seat-info strong', tip),
                    del = qs('.delete-seat-btn', tip),
                    maint = qs('.maintenance-seat-btn', tip);
                if (strong) strong.textContent = newId;
                if (del) del.dataset.seat = newId;
                if (maint) maint.dataset.seat = newId;
            }
        });
    }

    function insertSeatAt(rowEl, rowChar, index, isAdmin = true) {
        if (!isAdmin) return;

        const haveSeats = qsa(SEAT_LI_SEL, rowEl).length > 0;
        const rowType = haveSeats ? (qs(SEAT_LI_SEL, rowEl)?.dataset.seat || 'standard') : seatTypeOfRow(rowChar);
        const tmpId = seatIdOf(rowChar, index + 1);
        const li = make('li', 'seat-item'); li.dataset.seat = rowType;
        setHtml(li, `
        <input type="checkbox" class="seat seat-${rowType}" id="${tmpId}" data-number="${index + 1}">
        <label for="${tmpId}" class="visually-hidden">Chỗ ngồi ${tmpId}</label>
        ${isAdmin ? `
        <div class="seat-tooltip mx-auto">
            <div class="seat-info"><strong>${tmpId}</strong> - ${seatTypeLabel(rowType)}</div>
            <button type="button" class="maintenance-seat-btn" data-seat="${tmpId}"><i class="fas fa-tools"></i> Bảo trì</button>
            <button type="button" class="delete-seat-btn" data-seat="${tmpId}"><i class="fas fa-trash-alt"></i> Xóa</button>
        </div>` : ``}
    `);
        rowEl.insertBefore(li, childForSeatIndex(rowEl, index));

        const rowChar2 = rowEl.dataset.row || rowChar;
        reindexRowSeats(rowEl, rowChar2);
        remountRowButtons(rowEl, rowChar2, isAdmin);
        window.currentSeatsPerRow = maxSeatsPerAnyRow();
        updateControlPanelValues();
        attachEventHandlers(isAdmin);
        if (isAdmin) syncToLivewire();
    }

    function insertAisleAt(rowEl, index, isAdmin = true) {
        if (!isAdmin) return;

        const li = make('li'); li.dataset.seat = 'aisle';
        setHtml(li, `${isAdmin ? ` <span class="seat-helper">Lối đi</span>
        <div class="aisle"></div><button type="button" class="btn-delete-aisle" aria-label="Xoá lối đi">✖</button>` : ``}
    `);
        if (isAdmin) {
            li.querySelector(".btn-delete-aisle")?.addEventListener('click', (e) => { e.stopPropagation(); deleteAisle(li, true); });
        }
        rowEl.insertBefore(li, childForSeatIndex(rowEl, index));

        const rowChar = rowEl.dataset.row;
        remountRowButtons(rowEl, rowChar, isAdmin);
        attachEventHandlers(isAdmin);
        if (isAdmin) syncToLivewire();
    }

    window.initSeatPalette = function () {
        function createInlinePalette() {
            const layout = qs("#seats-layout"); if (!layout || qs("#inline-seat-palette")) return;
            const html = `
        <div id="floating-seat-palette" class="floating-palette">
          <div class="floating-main-btn" id="palette-toggle-btn"><i class="fas fa-plus"></i></div>
          <div class="floating-menu" id="palette-menu">
            <div class="palette-header"><i class="fas fa-palette me-2"></i> Công cụ chỉnh sửa</div>
            <div class="palette-item" draggable="true" data-seat-type="add-seat">
              <div class="item-icon"><input type="checkbox" class="seat seat-standard" style="pointer-events:none;transform:scale(0.7);"></div>
              <span class="item-text">Thêm ghế</span>
            </div>
            <div class="palette-item" draggable="true" data-seat-type="aisle">
              <div class="item-icon"><div class="aisle" style="pointer-events:none;transform:scale(0.7);width:20px;height:20px;"></div></div>
              <span class="item-text">Thêm lối đi</span>
            </div>
          </div>
        </div>`;
            layout.insertAdjacentHTML("afterend", html);
            setupFloatingButtonEvents();
            setupDragAndDrop();
        }
        function setupFloatingButtonEvents() {
            const toggle = qs("#palette-toggle-btn"), menu = qs("#palette-menu"); if (!(toggle && menu)) return;
            toggle.addEventListener("click", e => { e.stopPropagation(); menu.classList.toggle("show"); toggle.classList.toggle("active"); });
            document.addEventListener("click", e => { if (!e.target.closest("#floating-seat-palette")) { menu.classList.remove("show"); toggle.classList.remove("active"); } });
            document.addEventListener("keydown", e => { if (e.key === "Escape") { menu.classList.remove("show"); toggle.classList.remove("active"); } });
        }
        function setupDragAndDrop() {
            let dragged = null;
            qsa(".palette-item").forEach(item => {
                item.addEventListener("dragstart", e => {
                    dragged = item; item.style.opacity = "0.7"; item.style.transform = "rotate(5deg)";
                    e.dataTransfer.setData("text/plain", item.dataset.seatType); e.dataTransfer.effectAllowed = "copy";
                    qsa(".seat-row-layout").forEach(r => {
                        r.addEventListener("dragover", over);
                        r.addEventListener("dragenter", enter);
                        r.addEventListener("dragleave", leave);
                        r.addEventListener("drop", drop);
                    });
                });
                item.addEventListener("dragend", () => {
                    item.style.opacity = "1"; item.style.transform = "none";
                    qsa(".seat-row-layout").forEach(r => {
                        r.style.background = ""; r.style.border = "";
                        clearDropMarker(r);
                        r.removeEventListener("dragover", over);
                        r.removeEventListener("dragenter", enter);
                        r.removeEventListener("dragleave", leave);
                        r.removeEventListener("drop", drop);
                    });
                    dragged = null;
                });
            });

            function over(e) {
                e.preventDefault(); e.dataTransfer.dropEffect = "copy";
                const rowEl = this;
                const idx = getInsertIndex(rowEl, e.clientX);
                rowEl.dataset.dropIndex = idx;
                showDropMarker(rowEl, idx);
            }
            function enter(e) { e.preventDefault(); this.style.background = "rgba(46,204,113,0.12)"; this.style.border = "2px dashed #2ecc71"; this.style.borderRadius = "8px"; }
            function leave(e) { if (!this.contains(e.relatedTarget)) { this.style.background = ""; this.style.border = ""; clearDropMarker(this); } }
            function drop(e) {
                e.preventDefault(); this.style.background = ""; this.style.border = "";
                const type = e.dataTransfer.getData("text/plain");
                const rowEl = this; const rowChar = rowEl.dataset.row; if (!rowChar) return;
                const idx = parseInt(rowEl.dataset.dropIndex ?? seatLiCount(rowEl), 10);
                clearDropMarker(rowEl);
                if (type === "aisle") insertAisleAt(rowEl, idx);
                else if (type === "add-seat") insertSeatAt(rowEl, rowChar, idx);
            }

            enhanceDragAndDropVisual();
        }

        function enhanceDragAndDropVisual() {
            qsa(".palette-item").forEach(item => {
                item.addEventListener("mouseenter", function () {
                    const p = make("div"); p.id = "drag-preview";
                    p.style.cssText = "position:fixed;pointer-events:none;z-index:1000;background:rgba(52,73,94,.9);border:1px solid #3498db;border-radius:4px;padding:4px 8px;color:#ecf0f1;font-size:11px;display:none;";
                    p.textContent = `Kéo để thêm ${this.querySelector("span").textContent}`;
                    document.body.appendChild(p);
                });
                item.addEventListener("mouseleave", function () { qs("#drag-preview")?.remove(); });
                item.addEventListener("dragstart", function () {
                    const p = qs("#drag-preview"); if (!p) return;
                    p.style.display = "block";
                    const follow = (e) => { p.style.left = e.clientX + 10 + "px"; p.style.top = e.clientY - 30 + "px"; };
                    document.addEventListener("dragover", follow);
                    this.addEventListener("dragend", () => { document.removeEventListener("dragover", follow); qs("#drag-preview")?.remove(); }, { once: true });
                });
            });
        }
        createInlinePalette();
    };

    function seatTypeLabel(type) {
        const map = {
            standard: "GHẾ THƯỜNG",
            vip: "GHẾ VIP",
            double: "GHẾ ĐÔI",
            aisle: "LỐI ĐI"
        };
        return map[(type || "").toLowerCase()] || String(type).toUpperCase();
    }

    const originalGenerateDOMSeats = window.generateDOMSeats;
    if (originalGenerateDOMSeats) {
        window.generateDOMSeats = function (config, pathScreen) {
            const res = originalGenerateDOMSeats.call(this, config, pathScreen);
            setTimeout(() => window.initSeatPalette(), 100);
            return res;
        };
    }

    function readLayoutSchema() {
        const layout = document.querySelector('#seats-layout');
        if (!layout) return null;

        const rows = [];
        let capacity = 0;
        let maxPerRow = 0;

        layout.querySelectorAll('.seat-row-layout').forEach(ul => {
            const rowChar = ul.dataset.row;
            const seats = [];
            ul.querySelectorAll('li').forEach(li => {
                const t = (li.dataset.seat || '').toLowerCase();
                if (!t) return;

                if (t === 'aisle') {
                    seats.push({ type: 'aisle' });
                    return;
                }
                if (!li.classList.contains('seat-item')) return;
                const inp = li.querySelector('input.seat');
                const n = inp ? parseInt(inp.dataset.number) : null;
                const isMaint = li.classList.contains('seat-maintenance') || (inp && inp.dataset.maintenance === 'true') || (inp && inp.disabled && inp.dataset.maintenance === 'true');
                const seatTypeUi = t;
                const seatTypeDb = seatTypeUi === 'double' ? 'couple' : seatTypeUi;
                seats.push({
                    num: n,
                    code: inp ? inp.id : null,
                    type: seatTypeDb,
                    uiType: seatTypeUi,
                    status: isMaint ? 'maintenance' : 'active'
                });

                if (seatTypeUi !== 'aisle') capacity++;
            });

            const countNoAisle = seats.filter(s => s.type !== 'aisle').length;
            maxPerRow = Math.max(maxPerRow, countNoAisle);

            rows.push({ row: rowChar, seats });
        });

        return {
            rows,
            rowsCount: rows.length,
            seatsPerRow: maxPerRow,
            capacity
        };
    }

    function syncToLivewire() {
        const data = readLayoutSchema();
        if (!data) return;
        const schemaInput = document.querySelector('#schema-json');
        if (schemaInput) {
            schemaInput.value = JSON.stringify(data);
            schemaInput.dispatchEvent(new Event('input', { bubbles: true }));
        }

        const rowsEl = document.querySelector('#rows-input');
        if (rowsEl) {
            rowsEl.value = data.rowsCount;
            rowsEl.dispatchEvent(new Event('input', { bubbles: true }));
        }

        const sprEl = document.querySelector('#seats-per-row-input');
        if (sprEl) {
            sprEl.value = data.seatsPerRow;
            sprEl.dispatchEvent(new Event('input', { bubbles: true }));
        }

        const capEl = document.querySelector('#capacity-input');
        if (capEl) {
            capEl.value = data.capacity;
            capEl.dispatchEvent(new Event('input', { bubbles: true }));
        }

        const cur = document.querySelector('#current-total-seats');
        if (cur) {
            cur.textContent = String(data.capacity);
        }

        if (typeof Livewire !== "undefined") {
            Livewire.dispatch('schemaUpdated', [data]);
        }
    }


});
