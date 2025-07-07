@extends('components.layouts.client')

@section('title', "Đặt lại mật khẩu - SE7ENCinema")

@push('styles')
    @vite('resources/css/auth.css')
@endpush

@section('content')
<div class="scRender">
    <div class="custom-forgot-wrapper">
        <div class="container-md">
            <div class="row justify-content-center">
                <div class="col-lg-6 my-4">
                    <div class="card custom-forgot-card">
                        <!-- Card Header -->
                        <div class="card-header custom-card-header text-center">
                            <div class="custom-forgot-icon mb-3">
                                <i class="fas fa-key"></i>
                            </div>
                            <h2 class="custom-forgot-title mb-2">
                                Quên Mật Khẩu?
                            </h2>
                            <p class="custom-forgot-subtitle mb-0">
                                Đừng lo lắng! Nhập địa chỉ email của bạn và chúng tôi sẽ gửi liên kết đặt lại mật khẩu cho bạn.
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
                                        Không thể gửi email khôi phục
                                    </div>
                                    <div class="error-content-light">
                                        Hệ thống không thể gửi email đặt lại mật khẩu. Vui lòng kiểm tra lại địa chỉ email hoặc thử lại sau ít phút.
                                    </div>
                                </div>
                            @elseif(session()->has('status'))
                                <div class="error-panel-light success mb-3" style="display: block; margin-top: -12px !important;">
                                    <button type="button" class="close-error-light" onclick="this.parentElement.style.display = 'none'">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <div class="error-title-light">
                                        <i class="fa-solid fa-badge-check error-icon-light"></i>
                                        Email đã được gửi!
                                    </div>
                                    <div class="error-content-light">
                                        Liên kết đặt lại mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.
                                    </div>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('password.email') }}" novalidate>
                                @csrf
                                <div class="mb-4">
                                    <label for="email" class="form-label custom-form-label">
                                        <i class="fas fa-envelope me-1"></i>
                                        Địa chỉ Email
                                    </label>
                                    <div class="input-group custom-input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control custom-form-input @error('email') is-invalid @enderror"
                                               id="email" name="email"
                                               value="{{ old('email') }}" placeholder="Nhập địa chỉ email của bạn"
                                               required autocomplete="email">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            @if(str_contains($message, 'required'))
                                                Vui lòng nhập địa chỉ email của bạn để tiếp tục.
                                            @elseif(str_contains($message, 'email'))
                                                Địa chỉ email không đúng định dạng. Vui lòng nhập email hợp lệ (ví dụ: example@gmail.com).
                                            @elseif(str_contains($message, 'user'))
                                                Email này chưa được đăng ký trong hệ thống. Vui lòng kiểm tra lại hoặc đăng ký tài khoản mới.
                                            @endif
                                        </div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn w-100 mb-4 custom-submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Gửi liên kết đặt lại mật khẩu
                                </button>
                            </form>

                            <!-- Back to Login -->
                            <div class="text-center custom-back-section">
                                <a href="{{ route('login') }}" class="custom-back-link">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Quay lại đăng nhập
                                </a>
                            </div>

                            <!-- Help Section -->
                            <div class="custom-help-section mt-3">
                                <div class="text-center">
                                    <p class="custom-help-text mb-2">Nếu bạn vẫn gặp khó khăn?</p>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
