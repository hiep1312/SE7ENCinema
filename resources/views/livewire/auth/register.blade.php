@extends('components.layouts.auth')
@push('styles')
    <style>
        .prs_navigation_main_wrapper {
            margin-bottom: 50px;
        }

        #preloader {
            display: none;
        }

        .res {
            .container {
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                max-width: 1000px;
                width: 100%;
                margin-bottom: 50px;
            }

            .right-panel {
                flex: 1;
                padding: 60px 50px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .form-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 12px;
            }

            .form-group {
                margin-bottom: 24px;
                width: 100%;
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

            .form-group label .required {
                color: #ef4444;
                margin-left: 4px;
            }

            .form-row {
                display: flex;
                gap: 16px;
                margin-bottom: 24px;
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

            .invalid-feedback {
                width: 100%;
                margin-top: 0.25rem;
                font-size: 0.875em;
                color: #dc3545;
            }

            .social-btn:hover {
                border-color: #d1d5db;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            svg {
                width: 24px;
                margin-right: 12px
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

            .remember-me {
                display: flex;
            }

            .remember-me input[type="checkbox"] {
                margin-right: 8px;
                width: 16px;
                height: 16px;
                margin-top: 3px;
                accent-color: #667eea;
            }

            .remember-me label {
                font-size: 14px;
                color: #6b7280;
                line-height: 1.5;
                cursor: pointer;
            }

            .remember-me a {
                color: #667eea;
                text-decoration: none;
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
                cursor: pointer;
                transition: transform 0.2s ease;
            }

            .signup-btn:hover {
                transform: translateY(-1px);
            }

            .signin-link {
                text-align: center;
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
    <div class="res">
        <div class="container">
            <div class="right-panel">
                <form method="POST" action="/register">
                    @csrf
                    <div class="form-group">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Tên tài khoản
                                    <span class="required">*</span></label>
                                <div class="input-container">
                                    <input type="text" id="name" name="name" placeholder="Nhập tên tài khoản" />
                                </div>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <div class="input-container">
                                    <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" />
                                </div>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password">Mật khẩu<span class="required">*</span></label>
                                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" />
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Xác nhận mật khẩu<span class="required">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Nhập lại mật khẩu" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" id="address" name="address" placeholder="Nhập địa chỉ" />
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="number" id="phone" name="phone" placeholder="Nhập số điện thoại" />
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-options">
                            <div class="remember-me">
                                <input type="checkbox" id="agreeService" name="agreeService" />
                                <label class="agreeService" for="agreeService">
                                    Tôi đồng ý với
                                    <a href="">Điều khoản dịch vụ</a>
                                    và
                                    <a href="">Chính sách bảo mật</a>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="signup-btn">Đăng ký</button>
                    </div>
                </form>
                <div class="signin-link">
                    Đã có tài khoản? <a href="/login">Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
@endsection