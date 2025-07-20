<div>

    <style>
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .confirm-card {
            background: white;
            border: 1px solid #d1d9e0;
            border-radius: 12px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 24px rgba(140, 149, 159, 0.2);
        }

        .confirm-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 400;
            color: #24292f;
            margin-bottom: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background-color: #f6f8fa;
            border: 1px solid #d1d9e0;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #6c757d;
            margin-right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
        }

        .user-text {
            color: #656d76;
            font-size: 0.875rem;
        }

        .form-label {
            font-weight: 600;
            color: #24292f;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            border: 1px solid #d1d9e0;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .form-control:focus {
            border-color: #0969da;
            box-shadow: 0 0 0 3px rgba(9, 105, 218, 0.3);
        }

        .confirm-btn {
            background-color: #FF4444;
            border: 1px solid rgba(31, 136, 61, 0.15);
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            width: 100%;
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .confirm-btn:hover {
            background-color: #bf3636;
            color: white;
        }

        .confirm-btn:focus {
            outline: none !important;
            color: white;
        }

        .invalid-feedback {
            color: #d73a49;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>


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