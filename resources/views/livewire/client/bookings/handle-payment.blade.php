<div>
    <div class="success-background">
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
                            <div class="success-icon-wrapper">
                                <i class="fas fa-check success-icon"></i>
                            </div>
                            <div class="success-ripple"></div>
                            <div class="success-ripple success-ripple-delay"></div>
                        </div>

                        <!-- Success Content -->
                        <div class="success-content">
                            <h1 class="success-title">Thanh Toán Thành Công!</h1>
                            <p class="success-subtitle">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi</p>

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
    const countdown = document.getElementById('countdown');
    const progressBar = document.getElementById('progressBar');
    let countdownTime = 6;

    if(window.opener){
        window.opener.redirectAfterPayment();
    }

    setInterval(() => {
        countdown.textContent = (countdownTime = --countdownTime);
        progressBar.style.width = `${(countdownTime / 6) * 100}%`;
        if(countdownTime <= 0){
            if(window.opener) window.close();
            else window.location.href = @json(route('client.userBooking', $booking->id));
        }
    }, 1000);
</script>
@endscript
