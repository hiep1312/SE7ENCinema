import Swal from "sweetalert2";

let dataPromotions = [];

function canApplyCoupon(promotionOrVoucherCode){
    const promotionCurrent = typeof promotionOrVoucherCode === "object" ? promotionOrVoucherCode : dataPromotions.find(promotion => promotion.code === promotionOrVoucherCode);
    return Boolean(promotionCurrent) && promotionCurrent.apply_status !== "not_eligible" && (promotionCurrent.usage_limit === null || promotionCurrent.usages_count < promotionCurrent.usage_limit);
}

function realtimeUpdateView(){
    const cardVouchers = document.querySelectorAll('.booking-payment-voucher-item');
    const $wire = Livewire.find(document.querySelector("[wire\\:id][sc-root]")?.getAttribute('wire:id'));
    cardVouchers.forEach(cardVoucher => {
        let promotion;
        if(cardVoucher.dataset.voucher && (promotion = dataPromotions.find(promotion => promotion.code === cardVoucher.dataset.voucher))){
            const voucherStatus = cardVoucher.querySelector('.booking-payment-voucher-status');
            cardVoucher.classList.toggle('disabled', !canApplyCoupon(promotion));
            voucherStatus.classList.remove('insufficient', 'expired', 'available');
            voucherStatus.classList.add(promotion.apply_status === "not_eligible" ? 'insufficient' : ((promotion.usage_limit !== null && promotion.usages_count >= promotion.usage_limit) ? 'expired' :'available'));

            if($wire?.voucherCodeSelected?.toUpperCase() === cardVoucher.dataset.voucher.toUpperCase()) cardVoucher.classList.add('selected') || (voucherStatus.innerHTML = `<i class="fas fa-check me-1"></i><span>Đã chọn</span>`);
        }else cardVoucher.remove();
    });
}

document.addEventListener('livewire:init', () => {
    window.setDataPromotions = function (promotions){
        dataPromotions = promotions;
        realtimeUpdateView();
    };

    window.selectVoucher = function(voucherElement) {
        realtimeUpdateView();
        const voucherCodeSelection = voucherElement.dataset.voucher;
        const promotion = dataPromotions.find(promotion => promotion.code === voucherCodeSelection);

        if(canApplyCoupon(promotion)){
            const $wire = Livewire.find(document.querySelector("[wire\\:id][sc-root]")?.getAttribute('wire:id'));
            const isReactivating = $wire.voucherCodeSelected === voucherCodeSelection;
            const otherVouchers = document.querySelectorAll(`.booking-payment-voucher-item:not([data-voucher="${voucherCodeSelection}"]):not(.disabled)`);
            otherVouchers.forEach(otherVoucher => otherVoucher.classList.remove('selected') || (otherVoucher.querySelector('.booking-payment-voucher-status').innerHTML = `<i class="fas fa-check-circle me-1"></i><span>Có thể sử dụng</span>`));
            voucherElement.classList.toggle('selected', !isReactivating);

            const voucherStatus = voucherElement.querySelector('.booking-payment-voucher-status');
            if(voucherStatus && !isReactivating) voucherStatus.innerHTML = `<i class="fas fa-check me-1"></i><span>Đã chọn</span>`;
            else voucherStatus.innerHTML = `<i class="fas fa-check-circle me-1"></i><span>Có thể sử dụng</span>`;

            !$wire.isPaymentMode && $wire.$set('voucherCodeSelected', isReactivating ? null : voucherCodeSelection, true);
        }
    }

    window.selectPaymentMethod = function(methodElement) {
        const $wire = Livewire.find(document.querySelector("[wire\\:id][sc-root]")?.getAttribute('wire:id'));
        const methodSelection = methodElement.dataset.method;
        const methodOther = document.querySelectorAll(`.booking-payment-method:not([data-method="${methodSelection}"])`);

        methodOther.forEach(otherMethod => otherMethod.classList.remove('selected'));
        methodElement.classList.add('selected');
        !$wire.isPaymentMode && $wire.$set('paymentSelected', methodSelection, true);
    };

    window.openPaymentPopup = function(urlPayment, paymentExpiredAt, openTab = true){
        const windowPayment = openTab ? window.open(urlPayment, '_blank', 'width=1200,height=700,left=160,top=20,popup') : null;
        let $wire = Livewire.find(document.querySelector("[wire\\:id][sc-root]")?.getAttribute('wire:id'));
        const expiredAt = new Date(paymentExpiredAt);
        let timer = null;
        clearInterval(timer);
        timer = setInterval(() => {
            if(!$wire) $wire = Livewire.find(document.querySelector("[wire\\:id][sc-root]")?.getAttribute('wire:id'));
            const diffSeconds = Math.floor((expiredAt - Date.now()) / 1000);
            const minutes = Math.floor(diffSeconds / 60);
            const seconds = diffSeconds % 60;
            if(windowPayment?.closed) $wire?.$set('statusWindow', 'closed', true);
            document.querySelector('#paymentTimer').textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);

        console.log($wire)

        $wire?.on('closePaymentPopup', () => windowPayment?.close());
    }

    Livewire.on('reservationExpired', function(redirectUrl){
        Swal.fire({
            title: "Hết thời gian giữ ghế",
            html: "Vui lòng chọn lại suất chiếu và ghế để tiếp tục đặt vé",
            icon: 'info',
            iconColor: '#ffbb33',
        }).then(() => {
            const redirectEl = document.createElement('a');
            redirectEl.href = redirectUrl;
            redirectEl.click();
        });
    });
});
