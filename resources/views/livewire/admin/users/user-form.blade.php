<div class="modal show d-block" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                @if(is_null($userId))
                    <h5 class="modal-title">Thêm người dùng</h5>
                @else
                    <h5 class="modal-title">Chỉnh sửa người dùng</h5>
                @endif
                <button type="button" class="btn-close" wire:click="$dispatch('closeEditModal')"></button>
            </div>
            <div class="modal-body">
                <input type="text" wire:model="name" class="form-control mb-2" placeholder="Tên*">
                @error('name') <span>{{ $message }}</span> @enderror
                <input type="text" wire:model="email" class="form-control mb-2" placeholder="Email*">
                @error('email') <span>{{ $message }}</span> @enderror
                @if (is_null($userId))
                    <input type="text" wire:model="password" class="form-control mb-2" placeholder="Mật khẩu*">
                    @error('email') <span>{{ $message }}</span> @enderror
                @endif
                <input type="number" wire:model="phone" class="form-control mb-2" placeholder="Số điện thoại">
                @error('phone') <span>{{ $message }}</span> @enderror
                <input type="text" wire:model="address" class="form-control mb-2" placeholder="Địa chỉ">
                @error('address') <span>{{ $message }}</span> @enderror
                <input type="file" wire:model="avatar" class="form-control mb-2">
                @error('avatar') <span>{{ $message }}</span> @enderror
                <input type="date" wire:model="birthday" class="form-control mb-2">
                @error('birthday') <span>{{ $message }}</span> @enderror
                <select class="form-control mb-2" wire:model='gender'>
                    <option value="man">Nam</option>
                    <option value="woman">Nữ</option>
                    <option value="other">Khác</option>
                </select>
                @error('gender') <span>{{ $message }}</span> @enderror
                <select class="form-control mb-2" wire:model='role'>
                    <option value="user">Người dùng</option>
                    <option value="staff">Nhân viên</option>
                    <option value="admin">Quản trị viên</option>
                </select>
                @error('role') <span>{{ $message }}</span> @enderror
                <select class="form-control mb-2" wire:model='status'>
                    <option value="active">Đang hoạt động</option>
                    <option value="inactive">Không hoạt động</option>
                    <option value="banned">Đã bị chặn</option>
                </select>
                @error('status') <span>{{ $message }} </span> @enderror
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" wire:click="$dispatch('closeEditModal')">Cancel</button>
                <button class="btn btn-primary" wire:click="update">Save</button>
            </div>
        </div>
    </div>
</div>