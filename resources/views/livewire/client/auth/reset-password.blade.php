@extends('components.layouts.client')

@section('title', "Đặt lại mật khẩu - SE7ENCinema")

@push('styles')
    @vite('resources/css/auth.css')
@endpush

@section('content')
<div class="scRender">
    <div class="custom-reset-wrapper">
        <div class="container-md">
            <div class="row justify-content-center">
                <div class="col-lg-6 my-4">
                    <div class="card custom-reset-card">
                        <div class="card-header custom-card-header text-center">
                            <div class="custom-reset-icon mb-3">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h2 class="custom-reset-title mb-2">
                                Đặt Lại Mật Khẩu
                            </h2>
                            <p class="custom-reset-subtitle mb-0">
                                Tạo mật khẩu mới để bảo mật tài khoản của bạn. Hãy chọn mật khẩu mạnh và dễ nhớ.
                            </p>
                        </div>

                        <div class="card-body custom-card-body">
                            @if ($errors->isNotEmpty())
                                <div class="error-panel-light mb-3" style="display: block; margin-top: -12px !important;">
                                    <button type="button" class="close-error-light" onclick="this.parentElement.style.display = 'none'">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <div class="error-title-light">
                                        <i class="fa-solid fa-triangle-exclamation error-icon-light"></i>
                                        Đặt lại mật khẩu thất bại
                                    </div>
                                    <div class="error-content-light">
                                        Không thể cập nhật mật khẩu mới. Liên kết đặt lại có thể đã hết hạn hoặc không hợp lệ. Vui lòng yêu cầu liên kết mới từ trang quên mật khẩu!
                                    </div>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('password.update') }}" novalidate>
                                @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                <input type="hidden" name="email" value="{{ $request->email }}">

                                <div class="row g-md-3 mb-1">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label custom-form-label">
                                            <i class="fas fa-lock me-1"></i>
                                            Mật khẩu mới *
                                        </label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" class="form-control custom-form-input @error('password') is-invalid @enderror"
                                                id="password" name="password"
                                                placeholder="Nhập mật khẩu mới"
                                                required autocomplete="new-password">
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
                                        <label for="password_confirmation" class="form-label custom-form-label">
                                            <i class="fas fa-lock me-1"></i>
                                            Xác nhận mật khẩu *
                                        </label>
                                        <div class="input-group custom-input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-check-double"></i>
                                            </span>
                                            <input type="password" class="form-control custom-form-input @error('password') is-invalid @enderror"
                                                id="password_confirmation" name="password_confirmation"
                                                placeholder="Nhập lại mật khẩu mới"
                                                required autocomplete="new-password">
                                            <button class="btn btn-outline-secondary custom-toggle-password"
                                                    type="button" onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn w-100 mb-3 custom-submit-btn">
                                    <i class="fas fa-key me-2"></i>
                                    Cập nhật mật khẩu
                                </button>
                            </form>

                            <!-- Security Tips -->
                            <div class="custom-security-section mb-4">
                                <div class="custom-security-card">
                                    <i class="fas fa-shield-alt custom-security-icon"></i>
                                    <div class="custom-security-content">
                                        <h6 class="custom-security-title">Mẹo bảo mật</h6>
                                        <ul class="custom-security-list">
                                            <li>Sử dụng mật khẩu duy nhất cho mỗi tài khoản</li>
                                            <li>Không chia sẻ mật khẩu với bất kỳ ai</li>
                                            <li>Cập nhật mật khẩu định kỳ</li>
                                            <li>Sử dụng trình quản lý mật khẩu</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center custom-back-section">
                                <a href="{{ route('login') }}" class="custom-back-link">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Quay lại đăng nhập
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
