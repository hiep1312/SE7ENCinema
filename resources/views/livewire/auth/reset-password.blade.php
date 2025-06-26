@extends('components.layouts.auth')
@section('content')
    <div class="reset">
        <div class="container">
            <div class="icon">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9A3,3 0 0,0 9,12M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z" />
                </svg>
            </div>

            <h1>Đặt Lại Mật Khẩu</h1>
            <p class="subtitle">
                Tạo mật khẩu mới để bảo mật tài khoản của bạn. Hãy chọn mật khẩu mạnh và dễ nhớ.
            </p>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ $request->email }}">
                <div class="form-group">
                    <label for="newPassword">Mật khẩu mới</label>
                    <div class="input-container">
                        <input type="password" name="password" placeholder="Nhập mật khẩu mới" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Xác nhận mật khẩu</label>
                    <div class="input-container">
                        <input type="password" id="confirmPassword" name="password_confirmation"
                            placeholder="Nhập lại mật khẩu mới" required>
                    </div>
                </div>
                <button type="submit" class="btn">
                    Cập nhật mật khẩu
                </button>
            </form>

            <div class="back-link">
                <a href="#" onclick="goBack()">
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

        .reset {
            .container {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
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

            .input-container {
                position: relative;
            }

            input[type="password"] {
                width: 100%;
                padding: 15px 50px 15px 20px;
                border: 2px solid #e1e5e9;
                border-radius: 12px;
                font-size: 16px;
                background: #f8f9fa;
                outline: none;
            }

            input[type="password"]:focus {
                border-color: #667eea;
                background: white;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                transform: translateY(-2px);
            }

            input[type="password"]:hover {
                border-color: #667eea;
                background: white;
            }

            .toggle-password {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                padding: 5px;
                color: #666;
            }

            .toggle-password:hover {
                color: #667eea;
            }

            .toggle-password svg {
                width: 20px;
                height: 20px;
                fill: currentColor;
            }

            .btn {
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

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(234, 102, 102, 0.3);
            }

            .btn:active:not(:disabled) {
                transform: translateY(0);
            }

            .success-message {
                background: linear-gradient(135deg, #4CAF50, #45a049);
                color: white;
                padding: 15px;
                border-radius: 12px;
                text-align: center;
                margin-bottom: 20px;
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
        }
    </style>
@endpush