<div>
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <button class='btn btn-success' wire:click='create'>Thêm mới</button>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="col-md-6 col-xl-4 mb-2 mb-md-0">
            <label class="label-control">Tìm kiếm</label>
            <div class="input-group input-group-search">
                <input type="text" class="form-control" wire:model.live='search'>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 mb-2 mb-md-0">
            <label class="label-control">Vai trò</label>
            <div class="input-group input-group-search">
                <select class="form-control" wire:model.live='role'>
                    <option value="">Tất cả</option>
                    <option value="user">Người dùng</option>
                    <option value="staff">Nhân viên</option>
                    <option value="admin">Quản trị viên</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 mb-2 mb-md-0">
            <label class="label-control">Trạng thái</label>
            <div class="input-group input-group-search">
                <select name="" id="" class="form-control" wire:model.live='status'>
                    <option value="">Tất cả</option>
                    <option value="inactive">Không hoạt động</option>
                    <option value="active">Đang hoạt động</option>
                    <option value="banned">Đã bị cấm</option>
                </select>
            </div>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td>STT</td>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $key => $user)
                <tr>
                    <td wire:key='{{$user->id}}'>{{$key + 1}}</td>
                    <td class="table-user text-nowrap">
                        {{-- <img src="{{asset('storage/' . $user['avatar']) }}" class="me-2 rounded-circle"> --}}
                        <a class="text-decoration-none name" href="#">{{ $user['name'] }}</a>
                    </td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ $user['role'] }}</td>
                    <td>{{ $user['status'] }}</td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button class='button' wire:click='edit({{$user->id}})'><i
                                class="fa-solid fa-pen-to-square"></i></button>
                        <button class='button' wire:click='delete({{$user->id}})'
                            onclick="return confirm('ấn vào là xóa')"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Không có người dùng nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
    @if ($showModal)
        <livewire:admin.users.user-form :userId="$editUser" wire:key="user-form-{{ $editUser ?? 'new' }}" />
    @endif
</div>