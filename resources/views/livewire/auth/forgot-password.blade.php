@extends('components.layouts.auth')
@section('content')
    <div class="forget">
        <div class="container">
            <div class="icon">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M12 17a2 2 0 0 0 2-2c0-1.11-.89-2-2-2a2 2 0 0 0-2 2c0 1.11.89 2 2 2m6-9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h1V6a5 5 0 0 1 5-5 5 5 0 0 1 5 5v2h1m-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3z" />
                </svg>
            </div>
            <h1>Quên Mật Khẩu?</h1>
            <p class="subtitle">
                Đừng lo lắng! Nhập địa chỉ email của bạn và chúng tôi sẽ gửi liên kết đặt lại mật khẩu.
            </p>
            @if (session('status') == 'passwords.sent')
                <div class="success-message">
                    Liên kết đặt lại mật khẩu đã được gửi đến email của bạn!
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Địa chỉ Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email của bạn" required>
                </div>
                <button type="submit" class="btn-forgot">
                    Gửi liên kết đặt lại mật khẩu
                </button>
            </form>
            <div class="back-link">
                <a href="/login">
                    ← Quay lại đăng nhập
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .prs_navigation_main_wrapper {
            margin-bottom: 50px;
        }

        #preloader {
            display: none;
        }

        .forget {
            .container {
                border-radius: 20px;
                box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
                padding: 40px;
                width: 100%;
                max-width: 450px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                position: relative;
                overflow: hidden;
                margin-bottom: 50px;
            }

            .icon {
                width: 60px;
                height: 60px;
                background: #FF4444;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
            }

            .icon svg {
                width: 30px;
                height: 30px;
                fill: white;
            }

            h1 {
                text-align: center;
                color: #333;
                margin-bottom: 10px;
                font-size: 28px;
                font-weight: 700;
            }

            .subtitle {
                text-align: center;
                color: #666;
                margin-bottom: 30px;
                font-size: 14px;
                line-height: 1.5;
            }

            .form-group {
                margin-bottom: 25px;
                position: relative;
            }

            label {
                display: block;
                margin-bottom: 8px;
                color: #333;
                font-weight: 500;
                font-size: 14px;
            }

            input[type="email"] {
                width: 100%;
                padding: 15px 20px;
                border: 2px solid #e1e5e9;
                border-radius: 12px;
                font-size: 16px;
                background: #f8f9fa;
                outline: none;
            }

            input[type="email"]:focus {
                border-color: #667eea;
                background: white;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                transform: translateY(-2px);
            }

            input[type="email"]:hover {
                border-color: #667eea;
                background: white;
            }

            .btn-forgot {
                width: 100%;
                padding: 16px;
                background: #FF4444;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                margin-top: 8px;
                cursor: pointer;
            }

            .btn-forgot:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(234, 102, 102, 0.3);
            }

            .btn-forgot:active {
                transform: translateY(0);
            }

            .back-link {
                text-align: center;
                margin-top: 25px;
            }

            .back-link a {
                color: #667eea;
                text-decoration: none;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .back-link a:hover {
                color: #764ba2;
                transform: translateX(-3px);
            }

            .success-message {
                background: linear-gradient(135deg, #4CAF50, #45a049);
                color: white;
                padding: 15px;
                border-radius: 12px;
                text-align: center;
                margin-bottom: 20px;
            }
        }
    </style>
@endpush