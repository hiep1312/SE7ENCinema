<div class="container mt-4">
    <h2 class="mb-4 text-center text-primary fw-bold">Danh Sách Bỏng Nước</h2>

    {{-- Bộ lọc --}}
    <div class="row mb-3 g-2 align-items-center">
        <div class="col-md-4">
            <input wire:model.live="search" type="text" class="form-control" placeholder="Tìm theo tên món...">
        </div>
        <div class="col-md-3">
            <select wire:model.live="statusFilter" class="form-select">
                <option value="">-- Lọc theo trạng thái --</option>
                <option value="activate">Kích hoạt</option>
                <option value="discontinued">Ngừng bán</option>
            </select>
        </div>
        <div class="col-md-3">
            <select wire:model.live="sortDate" class="form-select">
                <option value="desc">Mới nhất</option>
                <option value="asc">Cũ nhất</option>
            </select>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.food.create') }}" class="btn btn-success w-100">
                <i class="bi bi-plus-lg"></i> Thêm Món Ăn
            </a>
        </div>
    </div>

    {{-- Thông báo --}}
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Bảng dữ liệu --}}
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm rounded">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Ảnh</th>
                    <th>Tên món</th>
                    <th>Mô tả</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($foodItems as $index => $item)
                    <tr>
                        <td class="text-center">{{ $foodItems->firstItem() + $index }}</td>
                        <td><img width="100" src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded border" alt="{{ $item->name }}"></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="text-center">
                            @php
                                $status = $item->status;
                                $badgeClass = match($status) {
                                    'activate' => 'bg-success',
                                    'discontinued' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                        </td>
                        <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.food.edit', $item->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="{{ route('admin.food.detail', $item->id) }}" class="btn btn-info btn-sm">Xem</a>
                            <a href="{{ route('admin.food.delete', $item->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xoá món ăn này không?')">Xoá</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có món ăn nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $foodItems->links() }}
    </div>
</div>
