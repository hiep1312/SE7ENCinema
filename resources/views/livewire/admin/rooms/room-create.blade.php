@assets
    <link rel="stylesheet" href="{{ asset('client/assets/css/style.css') }}">
@endassets
<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm phòng chiếu mới</h2>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin phòng chiếu -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1" style="color: inherit !important">Thông tin phòng chiếu</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="createRoom">
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
                                        <select wire:model="status" class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
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

                            <hr class="border-light">
                            <h6 class="text-light">Cấu hình sơ đồ ghế</h6>

                            <div class="alert alert-info bg-dark border-info">
                                <i class="fas fa-info-circle text-info"></i>
                                <strong class="text-info">Giới hạn:</strong>
                                <ul class="mb-0 mt-2 text-light">
                                    <li>Số hàng ghế: 5 - 26 hàng</li>
                                    <li>Ghế mỗi hàng: 10 - 30 ghế</li>
                                    <li>Tổng sức chứa: 50 - 780 ghế</li>
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
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-3" id="generate-seats">
                                <button type="button" class="btn btn-success" wire:click="$dispatch('handleGenerate', {selector: '#generate-seats'})">
                                    <i class="fas fa-save"></i> Tạo sơ đồ phòng chiếu
                                </button>
                            </div>

                            {{-- <div class="st_seatlayout_main_wrapper w-100 mt-2">
                                <div class="container">
                                    <div class="st_seat_lay_heading float_left">
                                        <h3>SE7ENCINEMA SCREEN</h3>
                                    </div>
                                    <div class="st_seat_full_container" style="float: none">
                                        <div class="st_seat_lay_economy_wrapper float_left" style="width: 100% !important">
                                            <div class="screen">
                                                <img src="{{ asset('client/assets/images/content/screen.png')}}">
                                            </div>
                                        </div>
                                        <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left" style="width: auto !important">
                                            <ul class="seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-start align-items-center" data-row="A">
                                                <li>
                                                    <span class="seat-helper">Pay Rs.790.00</span>
                                                    <input type="checkbox" class="seat seat-standard" id="seat-1" data-number="S">
                                                    <label for="seat-1" class="visually-hidden">Chỗ ngồi 1</label>
                                                </li>
                                                <li>
                                                    <span class="seat-helper">Pay Rs.790.00</span>
                                                    <input type="checkbox" class="aisle" data-number="V">
                                                </li>
                                                <li data-seat="double">
                                                    <span class="seat-helper">Pay Rs.790.00</span>
                                                    <input type="checkbox" class="seat seat-double" data-number="S">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Tạo phòng chiếu
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
@script
<script type="module">
    console.log(getRowSeat(1));
</script>


@endscript
