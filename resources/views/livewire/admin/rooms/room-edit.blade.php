<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>Chỉnh sửa phòng chiếu: {{ $room->name }}</h2>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form chỉnh sửa -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Thông tin phòng chiếu</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="updateRoom">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tên phòng chiếu *</label>
                                        <input type="text"
                                               wire:model="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="VD: Phòng A1">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái *</label>
                                        <select wire:model="status" class="form-select @error('status') is-invalid @enderror">
                                            <option value="active">Hoạt động</option>
                                            <option value="maintenance">Bảo trì</option>
                                            <option value="inactive">Ngừng hoạt động</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="last_maintenance_date" class="form-label">Ngày bảo trì lần cuối</label>
                                <input type="date"
                                       wire:model="last_maintenance_date"
                                       class="form-control @error('last_maintenance_date') is-invalid @enderror">
                                @error('last_maintenance_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>
                            <h6>Cấu hình sơ đồ ghế</h6>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="rows" class="form-label">Số hàng ghế *</label>
                                        <input type="number"
                                               wire:model.live="rows"
                                               class="form-control @error('rows') is-invalid @enderror"
                                               min="5" max="26">
                                        @error('rows')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="seatsPerRow" class="form-label">Ghế mỗi hàng *</label>
                                        <input type="number"
                                               wire:model.live="seatsPerRow"
                                               class="form-control @error('seatsPerRow') is-invalid @enderror"
                                               min="10" max="30">
                                        @error('seatsPerRow')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="capacity" class="form-label">Tổng sức chứa</label>
                                        <input type="number"
                                               wire:model="capacity"
                                               class="form-control bg-dark @if($capacity > 500) is-invalid @endif"
                                               readonly>
                                        @if($capacity > 500)
                                            <div class="invalid-feedback">Vượt quá giới hạn 500 ghế</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="vipRows" class="form-label">Hàng VIP</label>
                                        <input type="text"
                                               wire:model.live="vipRows"
                                               class="form-control"
                                               placeholder="VD: A,B,C">
                                        <small class="text-muted">Nhập các hàng VIP, cách nhau bằng dấu phẩy</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="coupleRows" class="form-label">Hàng Couple</label>
                                        <input type="text"
                                               wire:model.live="coupleRows"
                                               class="form-control"
                                               placeholder="VD: J,K">
                                        <small class="text-muted">Nhập các hàng Couple, cách nhau bằng dấu phẩy</small>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Lưu ý:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Số hàng ghế: 5 - 26 hàng, Ghế mỗi hàng: 10 - 30 ghế</li>
                                    <li>Tổng sức chứa: 50 - 500 ghế</li>
                                    <li>Cập nhật thông tin phòng sẽ không ảnh hưởng đến sơ đồ ghế hiện tại</li>
                                    <li>Để áp dụng cấu hình ghế mới, nhấn nút "Tái tạo sơ đồ ghế"</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                                <button type="button"
                                        wire:click="regenerateSeats"
                                        class="btn btn-danger"
                                        @if($capacity > 500) disabled @endif
                                        onclick="return confirm('Bạn có chắc chắn muốn tái tạo sơ đồ ghế?\n\nTất cả ghế hiện tại sẽ bị xóa và tạo lại theo cấu hình mới!\n\nHành động này không thể hoàn tác!')">
                                    <i class="fas fa-sync"></i> Tái tạo sơ đồ ghế
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview sơ đồ ghế mới và Sơ đồ ghế hiện tại -->
        <div class="row mt-4">
            <div class="col-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6>Xem trước sơ đồ ghế m���i</h6>
                    </div>
                    <div class="card-body">
                        @if($rows && $seatsPerRow)
                            <div class="seat-preview">
                                <div class="screen mb-3 text-center">
                                    <div class="bg-dark text-white py-2 rounded">MÀN HÌNH</div>
                                </div>

                                <div class="seats-grid">
                                    @for($row = 1; $row <= $rows; $row++)
                                        <div class="seat-row mb-1 d-flex justify-content-center">
                                            <span class="row-label me-2">{{ chr(64 + $row) }}</span>
                                            @for($seat = 1; $seat <= $seatsPerRow; $seat++)
                                                @php
                                                    $rowLetter = chr(64 + $row);
                                                    $vipRowsArray = $vipRows ? explode(',', str_replace(' ', '', $vipRows)) : [];
                                                    $coupleRowsArray = $coupleRows ? explode(',', str_replace(' ', '', $coupleRows)) : [];

                                                    $seatClass = 'seat-standard';
                                                    if (in_array($rowLetter, $vipRowsArray)) {
                                                        $seatClass = 'seat-vip';
                                                    } elseif (in_array($rowLetter, $coupleRowsArray)) {
                                                        $seatClass = 'seat-couple';
                                                    }
                                                @endphp
                                                <div class="seat {{ $seatClass }}" title="{{ $rowLetter }}{{ $seat }}"></div>
                                            @endfor
                                        </div>
                                    @endfor
                                </div>

                                <div class="legend mt-3">
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <span><div class="seat seat-standard d-inline-block me-1"></div> Thường</span>
                                        <span><div class="seat seat-vip d-inline-block me-1"></div> VIP</span>
                                        <span><div class="seat seat-couple d-inline-block me-1"></div> Couple</span>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="row text-center">
                                        @php
                                            // Tính toán số ghế theo loại cho preview
                                            $vipRowsArray = $vipRows ? explode(',', str_replace(' ', '', $vipRows)) : [];
                                            $coupleRowsArray = $coupleRows ? explode(',', str_replace(' ', '', $coupleRows)) : [];

                                            $vipSeatsCount = count($vipRowsArray) * $seatsPerRow;
                                            $coupleSeatsCount = count($coupleRowsArray) * $seatsPerRow;
                                            $standardSeatsCount = ($rows * $seatsPerRow) - $vipSeatsCount - $coupleSeatsCount;
                                        @endphp

                                        <div class="col-4">
                                            <div class="card bg-light">
                                                <div class="card-body py-1">
                                                    <h6 class="mb-0">{{ $standardSeatsCount }}</h6>
                                                    <small>Thường</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card bg-warning">
                                                <div class="card-body py-1">
                                                    <h6 class="mb-0">{{ $vipSeatsCount }}</h6>
                                                    <small>VIP</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card bg-danger text-white">
                                                <div class="card-body py-1">
                                                    <h6 class="mb-0">{{ $coupleSeatsCount }}</h6>
                                                    <small>Couple</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted text-center">Nhập số hàng và số ghế để xem trước sơ đồ</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6>Sơ đồ ghế hiện tại ({{ $seats->count() }} ghế)</h6>
                    </div>
                    <div class="card-body">
                        @if($seats->count() > 0)
                            <div class="seat-map">
                                <div class="screen mb-3 text-center">
                                    <div class="bg-dark text-white py-2 rounded">MÀN HÌNH</div>
                                </div>

                                @php
                                    $seatsByRow = $seats->groupBy('seat_row');
                                @endphp

                                <div class="seats-grid">
                                    @foreach($seatsByRow as $rowLetter => $rowSeats)
                                        <div class="seat-row mb-2 d-flex justify-content-center">
                                            <span class="row-label me-2 fw-bold">{{ $rowLetter }}</span>
                                            <div class="seats-container">
                                                @foreach($rowSeats->sortBy('seat_number') as $seat)
                                                    @php
                                                        $seatClass = match($seat->seat_type) {
                                                            'vip' => 'seat-vip',
                                                            'couple' => 'seat-couple',
                                                            default => 'seat-standard'
                                                        };

                                                        $statusClass = $seat->status === 'active' ? '' : 'seat-inactive';
                                                    @endphp
                                                    <div class="seat {{ $seatClass }} {{ $statusClass }}"
                                                         title="{{ $seat->seat_row }}{{ $seat->seat_number }} - {{ ucfirst($seat->seat_type) }} - {{ number_format($seat->price) }}đ">
                                                        {{ $seat->seat_number }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="legend mt-3">
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <span><div class="seat seat-standard d-inline-block me-1"></div> Thường</span>
                                        <span><div class="seat seat-vip d-inline-block me-1"></div> VIP</span>
                                        <span><div class="seat seat-couple d-inline-block me-1"></div> Couple</span>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="card bg-light">
                                                <div class="card-body py-1">
                                                    <h6 class="mb-0">{{ $seats->where('seat_type', 'standard')->count() }}</h6>
                                                    <small>Thường</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card bg-warning">
                                                <div class="card-body py-1">
                                                    <h6 class="mb-0">{{ $seats->where('seat_type', 'vip')->count() }}</h6>
                                                    <small>VIP</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card bg-danger text-white">
                                                <div class="card-body py-1">
                                                    <h6 class="mb-0">{{ $seats->where('seat_type', 'couple')->count() }}</h6>
                                                    <small>Couple</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-chair fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có ghế nào được tạo</p>
                                <p class="text-muted">Cấu hình sơ đồ ghế và nhấn "Tái tạo sơ đồ ghế"</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .seat {
            width: 18px;
            height: 18px;
            margin: 1px;
            border-radius: 3px;
            display: inline-block;
        }
        .seat-standard { background-color: #28a745; }
        .seat-vip { background-color: #ffc107; }
        .seat-couple { background-color: #e83e8c; }
        .row-label {
            width: 20px;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }

        /* Current seats styling */
        .seat-map .seat {
            width: 20px;
            height: 20px;
            margin: 1px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }
        .seat-map .seat-vip { color: #000; }
        .seat-inactive { opacity: 0.5; }
        .seat-map .row-label {
            width: 20px;
            text-align: center;
        }
        .seats-container {
            display: flex;
            flex-wrap: wrap;
        }
        .seats-grid {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
</div>
