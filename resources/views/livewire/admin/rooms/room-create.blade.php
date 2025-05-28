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
            <h2>Thêm phòng chiếu mới</h2>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin phòng chiếu -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Thông tin phòng chiếu</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="createRoom">
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

                            <hr>
                            <h6>Cấu hình sơ đồ ghế</h6>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Giới hạn:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Số hàng ghế: 5 - 26 hàng</li>
                                    <li>Ghế mỗi hàng: 10 - 30 ghế</li>
                                    <li>Tổng sức chứa: 50 - 500 ghế</li>
                                </ul>
                            </div>

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
                                               class="form-control @if($capacity > 500) is-invalid @endif"
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

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success" @if($capacity > 500) disabled @endif>
                                    <i class="fas fa-save"></i> Tạo phòng chiếu
                                </button>
                                <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Xem trước sơ đồ ghế mới -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6>Xem trước sơ đồ ghế mới</h6>
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
                                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                                        <span><div class="seat seat-standard d-inline-block me-1"></div> Thường (50,000đ)</span>
                                        <span><div class="seat seat-vip d-inline-block me-1"></div> VIP (80,000đ)</span>
                                        <span><div class="seat seat-couple d-inline-block me-1"></div> Couple (100,000đ)</span>
                                    </div>
                                </div>

                                <div class="mt-3 text-center">
                                    <div class="row">
                                        @php
                                            $vipRowsArray = $vipRows ? explode(',', str_replace(' ', '', $vipRows)) : [];
                                            $coupleRowsArray = $coupleRows ? explode(',', str_replace(' ', '', $coupleRows)) : [];

                                            $vipSeatsCount = count($vipRowsArray) * $seatsPerRow;
                                            $coupleSeatsCount = count($coupleRowsArray) * $seatsPerRow;
                                            $standardSeatsCount = ($rows * $seatsPerRow) - $vipSeatsCount - $coupleSeatsCount;
                                        @endphp

                                        <div class="col-4">
                                            <div class="card bg-light">
                                                <div class="card-body py-2">
                                                    <h6 class="mb-0">{{ $standardSeatsCount }}</h6>
                                                    <small>Thường</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card bg-warning">
                                                <div class="card-body py-2">
                                                    <h6 class="mb-0">{{ $vipSeatsCount }}</h6>
                                                    <small>VIP</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="card bg-danger text-white">
                                                <div class="card-body py-2">
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
        .seats-grid {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
</div>
