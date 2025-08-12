@assets
@vite('resources/css/confirm-access.css')
@endassets
<div class="scConfirm-access">
    <div style="clear: both"></div>
    <div class="main-container">
        <div class="confirm-card">
            <h1 class="confirm-title">Xác nhận quyền truy cập</h1>

            <div class="user-info">
                <div class="user-avatar">
                    @if ($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle"
                        style="width: 40px; height: 40px;border-radius: 50%;">
                    @else
                    <i class="fas fa-user"></i>
                    @endif
                </div>
                <span class="user-text">Đăng nhập dưới tên {{$user->name}}</span>
            </div>

            <form wire:submit.prevent="confirm">
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" wire:model.live="password" required>
                </div>
                @if (session()->has('error'))
                <div class="invalid-feedback">
                    {{ session('error') }}
                </div>
                @endif

                <button type="submit" class="btn confirm-btn">Xác nhận</button>
            </form>
        </div>
    </div>
</div>