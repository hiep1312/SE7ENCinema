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
                                    Trang sẽ tự động đóng sau <span id="countdown">6</span> giây
                                </div>
                                <div class="success-timer-progress">
                                    <div class="success-timer-bar" id="progressBar"></div>
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
    window.opener.location = @json(route('client.userBooking', $booking->id));
    setInterval(() => {
        document.getElementById('countdown').textContent = parseInt(document.getElementById('countdown').textContent) - 1;
        const progressPercent = (parseInt(document.getElementById('countdown').textContent) / 6) * 100;
        document.getElementById('progressBar').style.width = progressPercent + "%";
        if(parseInt(document.getElementById('countdown').textContent) <= 0) window.close();
    }, 1000);
</script>
@endscript
