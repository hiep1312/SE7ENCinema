import Swal from "sweetalert2";

document.addEventListener('livewire:init', () => {

    window.selectPaymentMethod = function(methodElement) {
        const $wire = Livewire.find(document.querySelector("[wire\\:id][sc-root]")?.getAttribute('wire:id'));
        const methodSelection = methodElement.dataset.method;
        const methodOther = document.querySelectorAll(`.booking-payment-method:not([data-method="${methodSelection}"])`);

        methodOther.forEach(otherMethod => otherMethod.classList.remove('selected'));
        methodElement.classList.add('selected');
        $wire.$set('paymentSelected', methodSelection, true);
    };

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
