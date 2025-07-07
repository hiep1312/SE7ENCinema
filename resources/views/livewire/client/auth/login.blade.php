@extends('components.layouts.client')

@section('title', "Đăng nhập - SE7ENCinema")

@push('styles')
    @vite('resources/css/auth.css')
@endpush

@section('content')
<div class="scRender">
    <div class="custom-login-wrapper">
        <div class="container-md">
            <div class="row justify-content-center">
                <div class="col-lg-8 my-4">
                    <div class="card custom-login-card">
                        <div class="card-header custom-card-header text-center">
                            <h2 class="custom-login-title mb-2">
                                <i class="fas fa-user-circle me-2"></i>
                                Đăng nhập
                            </h2>
                            <p class="custom-login-subtitle mb-0">Chào mừng bạn trở lại!</p>
                        </div>

                        <div class="card-body custom-card-body">
                            @if($errors->isNotEmpty())
                                <div class="error-panel-light mb-3" style="display: block; margin-top: -12px !important;">
                                    <button type="button" class="close-error-light" onclick="this.parentElement.style.display = 'none'">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <div class="error-title-light">
                                        <i class="fa-solid fa-triangle-exclamation error-icon-light"></i>
                                        Đăng nhập thất bại
                                    </div>
                                    <div class="error-content-light">
                                        Tên đăng nhập hoặc mật khẩu không chính xác. Vui lòng kiểm tra lại và thử lại.
                                    </div>
                                </div>
                            @elseif(session()->has('status'))
                                <div class="error-panel-light success mb-3" style="display: block; margin-top: -12px !important;">
                                    <button type="button" class="close-error-light" onclick="this.parentElement.style.display = 'none'">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <div class="error-title-light">
                                        <i class="fa-solid fa-badge-check error-icon-light"></i>
                                        Đặt lại mật khẩu thành công!
                                    </div>
                                    <div class="error-content-light">
                                        Mật khẩu của bạn đã được cập nhật thành công. Bạn có thể đăng nhập bằng mật khẩu mới ngay bây giờ.
                                    </div>
                                </div>
                            @endif

                            <!-- Social Login Buttons -->
                            <div class="custom-social-section mb-4">
                                <button class="btn w-100 mb-3 custom-google-btn">
                                    <i class="fab fa-google me-2"></i>
                                    Đăng nhập bằng Google
                                </button>
                                <button class="btn w-100 custom-twitter-btn">
                                    <i class="fab fa-twitter me-2"></i>
                                    Đăng nhập bằng Twitter
                                </button>
                            </div>

                            <!-- Divider -->
                            <div class="custom-divider mb-4">
                                <span class="custom-divider-text">hoặc</span>
                            </div>

                            <form method="POST" action="{{ route('login.store') }}" novalidate>
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label custom-form-label">
                                        <i class="fas fa-envelope me-1"></i>
                                        Email
                                    </label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control custom-form-input"
                                               id="email" name="email" placeholder="Nhập địa chỉ email của bạn"
                                               required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label custom-form-label">
                                        <i class="fas fa-lock me-1"></i>
                                        Mật khẩu
                                    </label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text custom-input-icon">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control custom-form-input"
                                               id="password" name="password" placeholder="Nhập mật khẩu của bạn"
                                               required>
                                        <button class="btn btn-outline-secondary custom-toggle-password"
                                                type="button"
                                                onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4 custom-form-options">
                                    <div class="form-check custom-form-check">
                                        <input class="form-check-input custom-checkbox"
                                               type="checkbox" id="remember"
                                               name="remember">
                                        <label class="form-check-label custom-checkbox-label" for="remember">
                                            <i class="fas fa-check-circle me-1"></i>Lưu đăng nhập
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}" class="custom-forgot-link">
                                        <i class="fas fa-question-circle me-1"></i>Quên mật khẩu?
                                    </a>
                                </div>

                                <button type="submit" class="btn w-100 mb-3 custom-submit-btn">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Đăng nhập
                                </button>
                            </form>

                            <!-- Sign Up Link -->
                            <div class="text-center custom-signup-section">
                                <p class="mb-0 custom-signup-text">
                                    Bạn chưa có tài khoản?
                                    <a href="{{ route('register') }}" class="custom-register-link">
                                        <i class="fas fa-user-plus me-1"></i>Đăng ký ngay
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
