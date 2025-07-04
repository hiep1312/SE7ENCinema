<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Xác minh email - SE7ENCinema</title>
    @vite(['resources/css/app.css', 'resources/css/auth.css'])
</head>
<body class="scRender">
    <div class="custom-verification-wrapper">
        <div class="container-md">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-6 my-4">
                    <div class="card custom-verification-card">
                        <!-- Card Header -->
                        <div class="card-header custom-card-header text-center">
                            <div class="custom-verification-icon mb-3">
                                <i class="fas fa-envelope-circle-check"></i>
                            </div>
                            <h2 class="custom-verification-title mb-2">
                                Xác minh Email
                            </h2>
                            <p class="custom-verification-subtitle mb-0">
                                Vui lòng xác minh địa chỉ email của bạn để tiếp tục sử dụng dịch vụ
                            </p>
                        </div>

                        <div class="card-body custom-card-body">
                            @if(session('status') === 'verification-link-sent')
                                <div class="error-panel-light success mb-3" style="display: block;margin-top: -18px !important;">
                                    <button type="button" class="close-error-light" onclick="this.parentElement.style.display = 'none'">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <div class="error-title-light">
                                        <i class="fa-solid fa-badge-check error-icon-light"></i>
                                        Gửi thành công!
                                    </div>
                                    <div class="error-content-light">
                                        Liên kết xác minh đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.
                                    </div>
                                </div>
                            @endif

                            <!-- Email Info -->
                            <div class="custom-email-info mb-4">
                                <div class="custom-email-card">
                                    <i class="fas fa-at custom-email-icon"></i>
                                    <div class="custom-email-content">
                                        <h6 class="custom-email-title">Email của bạn</h6>
                                        <p class="custom-email-address mb-0">{{ auth()->user()->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Verification Form -->
                            <form method="POST" action="{{ route('verification.send') }}" novalidate id="verificationForm">
                                @csrf

                                <button type="button" class="btn w-100 mb-4 custom-send-btn">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Đã gửi mã xác minh
                                </button>
                            </form>

                            <!-- Instructions -->
                            <div class="custom-instructions-section mb-4">
                                <div class="custom-instructions-card">
                                    <i class="fas fa-lightbulb custom-instructions-icon"></i>
                                    <div class="custom-instructions-content">
                                        <h6 class="custom-instructions-title">Hướng dẫn</h6>
                                        <ul class="custom-instructions-list">
                                            <li>Kiểm tra hộp thư đến trong email</li>
                                            <li>Tìm email từ hệ thống của chúng tôi</li>
                                            <li>Nhấn vào liên kết xác minh trong email</li>
                                            <li>Nếu không thấy, kiểm tra thư mục spam</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Resend Section -->
                            <div class="custom-resend-section">
                                <div class="text-center mb-3">
                                    <p class="custom-resend-text mb-2">Không nhận được email?</p>
                                    <button type="submit" form="verificationForm" formmethod="POST" class="btn custom-resend-btn" name="verify-email">
                                        <i class="fas fa-redo me-2"></i>
                                        Gửi lại liên kết xác minh
                                    </button>
                                    @if(session('status') === 'verification-link-sent')
                                        <div class="custom-countdown" id="countdown">
                                            Có thể gửi lại sau <span id="countdownTime"></span> giây
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Help Section -->
                            <div class="custom-help-section mt-4">
                                <div class="text-center">
                                    <p class="custom-help-text mb-2">Cần hỗ trợ?</p>
                                    <div class="custom-help-links">
                                        <a href="#" class="custom-help-link me-3">
                                            <i class="fas fa-headset me-1"></i>
                                            Liên hệ hỗ trợ
                                        </a>
                                        <a href="#" class="custom-help-link">
                                            <i class="fas fa-question-circle me-1"></i>
                                            FAQ
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Back to Dashboard -->
                            <div class="text-center custom-back-section mt-4">
                                <a href="{{ route('client.index') }}" class="custom-back-link">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Quay lại trang chủ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Elements -->
        <div class="custom-floating-elements">
            <div class="custom-float-element custom-float-1">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="custom-float-element custom-float-2">
                <i class="fas fa-check"></i>
            </div>
            <div class="custom-float-element custom-float-3">
                <i class="fas fa-shield-alt"></i>
            </div>
        </div>
    </div>
    @vite('resources/js/app.js')
    <script>
        let countdown = 60;
        const countdownView = document.getElementById('countdownTime');

        if(countdownView){
            const resendBtn = document.querySelector('button[name="verify-email"]');
            resendBtn.type = "button";
            resendBtn.form = null;
            resendBtn.disabled = true;
            const interval = setInterval(() => {
                countdownView.textContent = countdown--;
                if (countdown < 0) {
                    resendBtn.disabled = false;
                    resendBtn.addEventListener('click', () => document.getElementById('verificationForm').submit());
                    countdownView.parentElement.classList.add('d-none');
                    clearInterval(interval);
                }
            }, 1000);
        }
    </script>
</body>
</html>
