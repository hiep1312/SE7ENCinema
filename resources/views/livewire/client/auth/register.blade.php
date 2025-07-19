@extends('components.layouts.client')

@section('title', "Đăng ký tài khoản - SE7ENCinema")

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
                                <i class="fas fa-user-plus me-2"></i>
                                Đăng ký tài khoản
                            </h2>
                            <p class="custom-login-subtitle mb-0">Tạo tài khoản mới để bắt đầu!</p>
                        </div>

                        <div class="card-body custom-card-body">
                            @if($errors->isNotEmpty())
                                <div class="error-panel-light mb-3" style="display: block; margin-top: -12px !important;">
                                    <button type="button" class="close-error-light" onclick="this.parentElement.style.display = 'none'">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <div class="error-title-light">
                                        <i class="fa-solid fa-triangle-exclamation error-icon-light"></i>
                                        Đăng ký không thành công
                                    </div>
                                    <div class="error-content-light">
                                        Đã xảy ra lỗi trong quá trình tạo tài khoản. Vui lòng kiểm tra lại các thông tin đã nhập và thử lại.
                                    </div>
                                </div>
                            @endif

                            <div class="custom-social-section mb-4 custom-btn-loading">
                                <button class="btn w-100 mb-3 custom-google-btn">
                                    <i class="fab fa-google me-2"></i>
                                    Đăng nhập bằng Google
                                </button>
                                <button class="btn w-100 custom-twitter-btn">
                                    <i class="fab fa-twitter me-2"></i>
                                    Đăng nhập bằng Twitter
                                </button>
                            </div>

                            <!-- Custom Divider -->
                            <div class="custom-divider mb-4">
                                <span class="custom-divider-text">hoặc</span>
                            </div>

                            <!-- Register Form -->
                            <form method="POST" action="{{ route('register.store') }}" novalidate>
                                @csrf
                                <div class="row g-md-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="fullname" class="form-label custom-form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Họ và tên * 
                                        </label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input type="text" class="form-control custom-form-input @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" id="fullname" name="name" placeholder="Nhập họ và tên của bạn"
                                                required>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label custom-form-label">
                                            <i class="fas fa-envelope me-1"></i>
                                            Email *
                                        </label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control custom-form-input @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" id="email" name="email" placeholder="Nhập địa chỉ email của bạn"
                                                required>
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-md-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label custom-form-label">
                                            <i class="fas fa-lock me-1"></i>
                                            Mật khẩu *
                                        </label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" class="form-control custom-form-input @error('password') is-invalid @enderror"
                                                value="{{ old('password') }}" id="password" name="password" placeholder="Tạo mật khẩu của bạn"
                                                required>
                                            <button class="btn btn-outline-secondary custom-toggle-password"
                                                    type="button" onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="confirmPassword" class="form-label custom-form-label">
                                            <i class="fas fa-lock me-1"></i>
                                            Xác nhận mật khẩu *
                                        </label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" class="form-control custom-form-input @error('password_confirmation') is-invalid @enderror"
                                                value="{{ old('password_confirmation') }}" id="confirmPassword" name="password_confirmation" placeholder="Nhập lại mật khẩu"
                                                required>
                                            <button class="btn btn-outline-secondary custom-toggle-password"
                                                    type="button" onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-md-3">
                                    <div class="mb-3 col-md-4">
                                        <label for="phone" class="form-label custom-form-label">
                                            <i class="fas fa-phone me-1"></i>
                                            Số điện thoại
                                        </label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            <input type="tel" class="form-control custom-form-input @error('phone') is-invalid @enderror"
                                                value="{{ old('phone') }}" id="phone" name="phone" placeholder="Nhập số điện thoại của bạn"
                                                required>
                                            </button>
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="birthday" class="form-label custom-form-label">
                                            <i class="fas fa-birthday-cake me-1"></i>
                                            Ngày sinh
                                        </label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                            <input type="date" class="form-control custom-form-input @error('birthday') is-invalid @enderror"
                                                value="{{ old('birthday') }}" id="birthday" name="birthday"
                                                required>
                                        </div>
                                        @error('birthday')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="gender" class="form-label custom-form-label">
                                            <i class="fas fa-venus-mars me-1"></i>
                                            Giới tính
                                        </label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user-friends"></i>
                                            </span>
                                            <select class="form-select raw custom-form-select @error('gender') is-invalid @enderror"
                                                    id="gender" name="gender" required>
                                                <option value="" disabled>Chọn giới tính</option>
                                                <option value="man" {{ old('gender') == 'man' ? 'selected' : '' }}>Nam</option>
                                                <option value="woman" {{ old('gender') == 'woman' ? 'selected' : '' }}>Nữ</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                                            </select>
                                        </div>
                                        @error('gender')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label custom-form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Địa chỉ
                                    </label>
                                    <div class="custom-textarea-group">
                                        <div class="custom-textarea-icon">
                                            <i class="fas fa-home"></i>
                                        </div>
                                        <textarea class="form-control custom-form-textarea @error('address') is-invalid @enderror"
                                                id="address" name="address" rows="3"
                                                placeholder="Nhập địa chỉ đầy đủ của bạn (số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố)">{{ old('address') }}</textarea>
                                    </div>
                                    @error('address')
                                        <div class="invalid-feedback  d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4 custom-form-group">
                                    <div class="form-check custom-form-check">
                                        <input class="form-check-input custom-checkbox"
                                            type="checkbox" id="terms" name="terms"
                                            required onchange="this.closest('form').querySelector('button[type=submit]').disabled = !this.checked">
                                        <label class="form-check-label custom-checkbox-label" for="terms">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            Tôi đồng ý với
                                            <a href="#" class="custom-terms-link" target="_blank">
                                                Điều khoản sử dụng
                                            </a>
                                            và
                                            <a href="#" class="custom-terms-link" target="_blank">
                                                Chính sách bảo mật
                                            </a>
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn w-100 mb-3 custom-submit-btn" disabled>
                                    <i class="fas fa-user-plus me-2"></i>
                                    Tạo tài khoản
                                </button>
                            </form>

                            <!-- Sign Up Link -->
                            <div class="text-center custom-signup-section">
                                <p class="mb-0 custom-signup-text">
                                    Đã có tài khoản?
                                    <a href="{{ route('login') }}" class="custom-register-link">
                                        <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập ngay
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
