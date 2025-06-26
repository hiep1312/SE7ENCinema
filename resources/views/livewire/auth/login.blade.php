@extends('components.layouts.auth')
@push('styles')
    <style>
        .prs_navigation_main_wrapper {
            margin-bottom: 50px;
        }

        #preloader {
            display: none;
        }

        .log {
            .container {
                display: flex;
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                max-width: 800px;
                width: 100%;
                margin-bottom: 50px;

            }

            .forgot-password {
                color: #667eea;
                text-decoration: none;
                font-size: 14px;
                font-weight: 500;
                transition: color 0.3s ease;
                margin-top: 8px;
            }

            .form-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }

            .divider {
                display: flex;
                align-items: center;
                margin: 30px 0;
                color: #9ca3af;
                font-size: 14px;
            }

            .divider::before,
            .divider::after {
                content: "";
                flex: 1;
                height: 1px;
                background: #e5e7eb;
            }

            .divider span {
                padding: 0 16px;
            }

            .warning {
                color: red;
                text-align: center;
                justify-content: center;
                margin-bottom: 10px;
                font-size: 15px;
            }

            .right-panel {
                flex: 1;
                padding: 60px 50px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .social-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: 14px 20px;
                margin-bottom: 12px;
                border: 2px solid #e1e5e9;
                border-radius: 8px;
                background: white;
                color: #5f6368;
                text-decoration: none;
                font-size: 16px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .social-btn:hover {
                border-color: #d1d5db;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            svg {
                width: 24px;
                margin-right: 12px
            }

            .social-btn i {
                margin-right: 12px;
                font-size: 18px;
            }

            .google-btn i {
                color: #4285f4;
            }

            .twitter-btn i {
                color: #1da1f2;
            }

            .form-group {
                margin-bottom: 24px;
            }

            .form-group label {
                display: block;
                margin-bottom: 8px;
                color: #374151;
                font-size: 14px;
                font-weight: 500;
            }

            .form-group input {
                width: 100%;
                padding: 14px 16px;
                border: 2px solid #e1e5e9;
                border-radius: 8px;
                font-size: 16px;
                transition: border-color 0.3s ease;
            }

            .form-group input:focus {
                outline: none;
                border-color: #667eea;
            }

            .form-group input::placeholder {
                color: #9ca3af;
            }

            .checkbox-group {
                margin-bottom: 24px;
            }

            .checkbox-item {
                display: flex;
                align-items: flex-start;
                margin-bottom: 16px;
            }

            .checkbox-item input[type="checkbox"] {
                margin-right: 12px;
                margin-top: 2px;
                width: 18px;
                height: 18px;
                accent-color: #667eea;
            }

            .checkbox-item label {
                font-size: 14px;
                color: #6b7280;
                line-height: 1.5;
                cursor: pointer;
            }

            .checkbox-item label a {
                color: #667eea;
                text-decoration: none;
            }

            .checkbox-item label a:hover {
                text-decoration: underline;
            }

            .signup-btn {
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

            .remember-me {
                display: flex;
                align-items: center;
            }

            .remember-me input[type="checkbox"] {
                margin-right: 8px;
                width: 16px;
                height: 16px;
                accent-color: #667eea;
            }

            .remember-me label {
                font-size: 14px;
                color: #6b7280;
                cursor: pointer;
                margin-top: 9px;
            }

            .signup-btn:hover {
                transform: translateY(-1px);
            }

            .signin-link {
                text-align: center;
                margin-top: 30px;
                color: #6b7280;
                font-size: 14px;
            }

            .signin-link a {
                color: #667eea;
                text-decoration: none;
                font-weight: 500;
            }

            .signin-link a:hover {
                text-decoration: underline;
            }
        }
    </style>
@endpush
@section('content')
    <div class="log">
        <div class="container">
            <div class="right-panel">
                <div class="social-buttons">
                    <a href="#" class="social-btn google-btn">
                        <svg id="icon-google" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22 12.4364C22 11.743 21.9438 11.0457 21.8238 10.3635H12.2015V14.2919H17.7117C17.4831 15.5589 16.7484 16.6797 15.6726 17.3919V19.9409H18.96C20.8904 18.1641 22 15.5402 22 12.4364Z"
                                fill="#4285F4" />
                            <path
                                d="M12.2015 22.4036C14.9529 22.4036 17.2732 21.5002 18.9637 19.9409L15.6763 17.3919C14.7617 18.0142 13.5809 18.3665 12.2052 18.3665C9.54382 18.3665 7.28723 16.571 6.47756 14.157H3.08519V16.7847C4.81699 20.2295 8.3443 22.4036 12.2015 22.4036Z"
                                fill="#34A853" />
                            <path
                                d="M6.47381 14.157C6.04648 12.89 6.04648 11.5181 6.47381 10.2511V7.62341H3.08518C1.63827 10.506 1.63827 13.9021 3.08518 16.7847L6.47381 14.157Z"
                                fill="#FBBC04" />
                            <path
                                d="M12.2015 6.0378C13.6559 6.01531 15.0616 6.56258 16.1149 7.56718L19.0275 4.65461C17.1832 2.92281 14.7355 1.9707 12.2015 2.00069C8.3443 2.00069 4.81699 4.1748 3.08519 7.6234L6.47381 10.2511C7.27974 7.83332 9.54007 6.0378 12.2015 6.0378Z"
                                fill="#EA4335" />
                        </svg>

                        Đăng nhập bằng Google
                    </a>
                    <a href="#" class="social-btn twitter-btn">

                        <svg id="icon-twitter" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.176 4H19.936L13.9061 10.892L21 20.2703H15.4454L11.0951 14.5823L6.11727 20.2703H3.35539L9.80498 12.8986L3 4H8.69528L12.6277 9.19891L17.176 4ZM16.2073 18.6181H17.7367L7.86432 5.5654H6.22323L16.2073 18.6181Z" />
                        </svg>

                        Đăng nhập bằng Twitter
                    </a>
                </div>
                <div class="divider">
                    <span>or</span>
                </div>
                <form method="POST" action="/login">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Địa chỉ email" />
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" placeholder="Mật khẩu" />
                    </div>
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember" />
                            <label for="remember">Lưu đăng nhập</label>
                        </div>
                        <a href="forgot-password" class="forgot-password" id="forgotPassword">Quên mật khẩu?</a>
                    </div>
                    @if (!$errors->isEmpty())
                        <span class="warning">Sai tài khoản và mật khẩu hoặc để trống</span>
                    @endif
                    <button type="submit" class="signup-btn">Đăng nhập</button>
                </form>
                <div class="signin-link">
                    Chưa có tài khoản? <a href="/register">Đăng ký</a>
                </div>
            </div>
        </div>
    </div>
@endsection