<div class="container mt-4">
    <h2 class="mb-4 text-center text-primary fw-bold">Danh Sách Bỏng Nước</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.food.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Thêm Món Ăn
        </a>
        <!-- Có thể thêm search hoặc filter ở đây nếu muốn -->
    </div>

                {{-- Hiển thị thông báo thành công --}}
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

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
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td> <img width="100px" src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                class="img-fluid rounded border"></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="text-center">
                            @php
                                $status = $item->status;
                                $badgeClass = 'badge bg-secondary'; // mặc định xám

                                if ($status === 'activate') {
                                    $badgeClass = 'badge bg-success'; // xanh
                                } elseif ($status === 'discontinued') {
                                    $badgeClass = 'badge bg-danger'; // đỏ
                                }
                            @endphp
                            <span class="{{ $badgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.food.edit', $item->id) }}" class="btn btn-warning">Sửa</a>
                            <a href="{{ route('admin.food.detail', $item->id) }}" class="btn btn-info">Xem</a>
                            <a href="{{ route('admin.food.delete', $item->id) }}" class="btn btn-danger"
                                onclick="return confirm('Bạn có chắc chắn muốn xoá món ăn này không?')">
                                Xoá
                            </a>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Không có món ăn nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
