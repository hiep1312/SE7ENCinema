<div>
    <div class="@if($statusPayment) success-background @else failed-background @endif">
        <div class="success-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="success-container">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="success-card">
                        <!-- Success Icon -->
                        <div class="success-icon-container">
                            @if($statusPayment)
                                <div class="success-icon-wrapper">
                                    <i class="fas fa-check success-icon"></i>
                                </div>
                                <div class="success-ripple"></div>
                                <div class="success-ripple success-ripple-delay"></div>
                            @else
                                <div class="failed-icon-wrapper">
                                    <i class="fas fa-exclamation-triangle success-icon"></i>
                                </div>
                                <div class="failed-ripple"></div>
                                <div class="failed-ripple success-ripple-delay"></div>
                            @endif
                        </div>

                        <!-- Success Content -->
                        <div class="success-content">
                            @if($statusPayment)
                                <h1 class="success-title">Thanh Toán Thành Công!</h1>
                                <p class="success-subtitle">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi</p>
                            @else
                                <h1 class="failed-title">Thanh Toán Thất Bại!</h1>
                                <p class="success-subtitle">Rất tiếc, giao dịch của bạn không thành công. Vui lòng thử lại sau.</p>
                            @endif

                            <div class="success-timer">
                                <div class="success-timer-icon">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div class="success-timer-text">
                                    Trang sẽ tự động đóng sau <span id="countdown" wire:ignore>6</span> giây
                                </div>
                                <div class="success-timer-progress">
                                    <div class="success-timer-bar" id="progressBar" wire:ignore></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
    window.history.replaceState(null, null, `${window.location.origin}${window.location.pathname}`);

    const countdown = document.getElementById('countdown');
    const progressBar = document.getElementById('progressBar');
    let countdownTime = 6;

    if(window.opener){
        window.opener.location.assign(@json(route('client.userBooking', $booking->id)));
    }

    setInterval(() => {
        countdown.textContent = (countdownTime = --countdownTime);
        progressBar.style.width = `${(countdownTime / 6) * 100}%`;
        if(countdownTime <= 0){
            if(window.opener) window.close();
            else window.location.location.assign(@json(route('client.userBooking', $booking->id)));
        }
    }, 1000);
</script>
@endscript
