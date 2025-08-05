@assets
<link rel="stylesheet" href="{{ asset('client/assets/css/style.css') }}">
@endassets
<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2 mx-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa phòng chiếu: {{ $room->name }}</h2>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form chỉnh sửa -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light"
                         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1" style="color: inherit !important">Thông tin phòng chiếu</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="updateRoom">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-light">Tên phòng chiếu *</label>
                                        <input type="text"
                                               wire:model="name"
                                               class="form-control bg-dark text-light border-light @error('name') is-invalid @enderror"
                                               placeholder="VD: Phòng A1">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-light">Trạng thái *</label>
                                        <select wire:model="status"
                                                class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
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
                                <label for="last_maintenance_date" class="form-label text-light">Ngày bảo trì lần cuối</label>
                                <input type="date"
                                       wire:model="last_maintenance_date"
                                       class="form-control bg-dark text-light border-light @error('last_maintenance_date') is-invalid @enderror">
                                @error('last_maintenance_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="border-light">
                            <h6 class="text-light">Cấu hình sơ đồ ghế</h6>

                            <div class="alert alert-info bg-dark border-info">
                                <i class="fas fa-info-circle text-info"></i>
                                <strong class="text-info">Lưu ý:</strong>
                                <ul class="mb-0 mt-2 text-light">
                                    <li>Số hàng ghế: 5 - 26 hàng</li>
                                    <li>Ghế mỗi hàng: 10 - 30 ghế</li>
                                    <li>Tổng sức chứa: 50 - 780 ghế</li>
                                    <li>Giá ghế tối thiểu từ 20.000 VNĐ</li>
                                </ul>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="mb-3">
                                        <label for="rows" class="form-label text-light">Số hàng ghế *</label>
                                        <input type="number"
                                               wire:model="rows"
                                               class="form-control bg-dark text-light border-light @error('rows') is-invalid @enderror">
                                        @error('rows')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="mb-3">
                                        <label for="seatsPerRow" class="form-label text-light">Ghế mỗi hàng *</label>
                                        <input type="number"
                                               wire:model="seatsPerRow"
                                               class="form-control bg-dark text-light border-light @error('seatsPerRow') is-invalid @enderror">
                                        @error('seatsPerRow')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="capacity" class="form-label text-light">Tổng sức chứa</label>
                                        <input type="number"
                                               :value="$wire.rows * $wire.seatsPerRow"
                                               class="form-control bg-dark text-light border-light"
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="vipRows" class="form-label text-light">Hàng VIP</label>
                                        <input type="text"
                                               wire:model="vipRows"
                                               class="form-control bg-dark text-light border-light"
                                               placeholder="VD: A,B,C">
                                        <small class="text-muted">Nhập các hàng VIP, cách nhau bằng dấu phẩy</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-light">Giá ghế VIP</label>
                                        <input type="number" wire:model="priceVip"
                                               class="form-control bg-dark text-light border-light @error('priceVip') is-invalid @enderror">
                                        @error('priceVip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="coupleRows" class="form-label text-light">Hàng Couple</label>
                                        <input type="text"
                                               wire:model="coupleRows"
                                               class="form-control bg-dark text-light border-light"
                                               placeholder="VD: J,K">
                                        <small class="text-muted">Nhập các hàng Couple, cách nhau bằng dấu phẩy</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-light">Giá ghế đôi</label>
                                        <input type="number" wire:model="priceCouple"
                                               class="form-control bg-dark text-light border-light @error('priceCouple') is-invalid @enderror">
                                        @error('priceCouple')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-light">Giá ghế thường</label>
                                        <input type="number" wire:model="priceStandard"
                                               class="form-control bg-dark text-light border-light @error('priceStandard') is-invalid @enderror">
                                        @error('priceStandard')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                     <div class="mb-3">
                                        <label class="form-label text-light">Cấu Hình Luật Kiểm Tra chọn ghế</label>
                                        <div>
                                            <input type="checkbox" wire:model="checkLonely" id="rule-lonely"> Cấm chỗ lẻ ví Dụ A1 , A3
                                        </div>

                                        <div>
                                            <input type="checkbox" wire:model="checkSole" id="rule-sole"> Cấm chọn ghế lẻ góc tường , ví dụ A1 trống -> chọn A2
                                        </div>

                                        <div>
                                            <input type="checkbox" wire:model="checkDiagonal" id="rule-diagonal"> Cấm chọn chéo A1 , B2
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <button type="button" class="btn btn-success" wire:click="handleGenerateSeats()">
                                    <i class="fas fa-eye"></i> Cập nhật sơ đồ ghế
                                </button>
                            </div>

                            <div wire:ignore id="generate-seats"></div>
                            @script
                            <script>
                                $wire.on('generateSeats', function([rows, seatsPerRow, vipRows, coupleRows , checkLonely , checkSole , checkDiagonal]) {
                                    document.querySelector('#generate-seats').innerHTML = '';
                                    document.querySelector('#generate-seats').append(window.generateDOMSeats({
                                        rows: rows,
                                        seatsPerRow: seatsPerRow,
                                        vipRows: vipRows,
                                        coupleRows: coupleRows,
                                        checkLonely: checkLonely,
                                        checkSole: checkSole,
                                        checkDiagonal: checkDiagonal
                                    }));
                                });

                                window.updateseatid = function(evt, originalEvent) {
                                    const updatedpointseat = (element, index) => {
                                        const Pointseat = `${rowCurrent}${index + 1}`;
                                        element.id = element.nextElementSibling.for = Pointseat;
                                        element.setAttribute('sc-id', Pointseat);
                                        element.dataset.number = index + 1;
                                    }
                                    const rowCurrent = evt.to.dataset.row;
                                    evt.to.querySelectorAll('input[type=checkbox]').forEach(updatedpointseat);
                                    evt.from.querySelectorAll('input[type=checkbox]').forEach(updatedpointseat);
                                }
                            </script>
                            @endscript

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Cập nhật phòng chiếu
                                </button>
                                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-danger">
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
