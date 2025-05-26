<div>
    <div class="container mt-4">
        <div class="row">
            <!-- Menu bên trái -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Chức năng</div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="#" wire:click.prevent>Tạo suất chiếu</a></li>
                        <li class="list-group-item"><a href="#" wire:click.prevent>Xem lịch chiếu</a></li>
                        <li class="list-group-item"><a href="#" wire:click.prevent>Tìm kiếm/Lọc</a></li>
                        @if($isAdmin)
                            <li class="list-group-item"><a href="#" wire:click.prevent>Quản lý phim</a></li>
                            <li class="list-group-item"><a href="#" wire:click.prevent>Quản lý phòng</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Nội dung bên phải -->
            <div class="col-md-9">
                <h2>Quản lý suất chiếu</h2>

                <!-- Lọc suất chiếu -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Lọc suất chiếu</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="dateFilter" class="form-label">Ngày chiếu</label>
                                <input type="date" wire:model="selectedDate" class="form-control" id="dateFilter">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="searchMovie" class="form-label">Tìm phim</label>
                                <input type="text" wire:model.debounce.500ms="searchMovie" class="form-control" id="searchMovie" placeholder="Nhập tên phim">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="searchFormat" class="form-label">Định dạng</label>
                                <select wire:model="searchFormat" class="form-select" id="searchFormat">
                                    <option value="">Tất cả</option>
                                    <option value="2D">2D</option>
                                    <option value="3D">3D</option>
                                    <option value="4DX">4DX</option>
                                    <option value="IMAX">IMAX</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách suất chiếu -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Danh sách suất chiếu</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Phim</th>
                                    <th>Phòng</th>
                                    <th>Giờ chiếu</th>
                                    <th>Thời lượng</th>
                                    <th>Bắt đầu</th>
                                    <th>Kết thúc</th>
                                    <th>Giá vé</th>
                                    <th>Ngày chiếu</th>
                                    @if($isAdmin)
                                        <th>Hành động</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($showtimes as $showtime)
                                    <tr>
                                        <td>{{ $showtime->movie->title ?? 'N/A' }} ({{ $showtime->movie->format ?? 'N/A' }})</td>
                                        <td>{{ $showtime->room->name ?? 'N/A' }}</td>
                                        <td>{{ $showtime->start_time->format('H:i') ?? 'N/A' }}</td>
                                        <td>{{ $showtime->movie->duration ?? 'N/A' }} phút</td>
                                        <td>{{ $showtime->start_time->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                        <td>{{ $showtime->end_time->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                        <td>{{ number_format($showtime->price) ?? 'N/A' }} VND</td>
                                        <td>{{ $showtime->start_time->format('Y-m-d') ?? 'N/A' }}</td>
                                        @if($isAdmin == 'admin' && $isAdmin !== 'staff')
                                            <td class="d-flex justify-content-between">
                                                <button class="btn btn-sm btn-warning" wire:click="editShowtime({{ $showtime->id }})">Sửa</button>

                                                <button class="btn btn-sm btn-danger" wire:click="deleteShowtime({{ $showtime->id }})">Hủy</button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 9 : 8 }}" class="text-center">Không có suất chiếu nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $showtimes->links() }}
                    </div>
                </div>

                <!-- Form tạo suất chiếu -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Tạo suất chiếu mới</h5>
                        <form wire:submit.prevent="createShowtime">
                            <div class="mb-3">
                                <label for="movie" class="form-label">Phim</label>
                                <select wire:model="selectedMovie" class="form-select" id="movie">
                                    <option value="">Chọn phim</option>
                                    @foreach($movies as $movie)
                                        <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->format }})</option>
                                    @endforeach
                                </select>
                                @error('selectedMovie')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="room" class="form-label">Phòng chiếu</label>
                                <select wire:model="selectedRoom" class="form-select" id="room">
                                    <option value="">Chọn phòng</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedRoom')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="startTime" class="form-label">Thời gian bắt đầu</label>
                                <input type="datetime-local" wire:model="startTime" class="form-control" id="startTime">
                                @error('startTime')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Giá vé (VND)</label>
                                <input type="number" wire:model="price" class="form-control" id="price" placeholder="Nhập giá hoặc để trống để dùng giá phim">
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Tạo suất chiếu</button>
                        </form>
                        @if(session()->has('message'))
                            <div class="alert alert-success mt-3">{{ session('message') }}</div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>

                <!-- Form chỉnh sửa suất chiếu (chỉ admin) -->
                @if($isAdmin && $editShowtimeId)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Chỉnh sửa suất chiếu</h5>
                            <form wire:submit.prevent="updateShowtime">
                                <div class="mb-3">
                                    <label for="editMovie" class="form-label">Phim</label>
                                    <select wire:model="editMovie" class="form-select" id="editMovie">
                                        <option value="">Chọn phim</option>
                                        @foreach($movies as $movie)
                                            <option value="{{ $movie->id }}" {{ old('editMovie', $editMovie) == $movie->id ? 'selected' : '' }}>
                                                {{ $movie->title }} ({{ $movie->format }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('editMovie')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="editRoom" class="form-label">Phòng chiếu</label>
                                    <select wire:model="editRoom" class="form-select" id="editRoom">
                                        <option value="">Chọn phòng</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ old('editRoom', $editRoom) == $room->id ? 'selected' : '' }}>
                                                {{ $room->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('editRoom')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="editStartTime" class="form-label">Thời gian bắt đầu</label>
                                    <input type="datetime-local" wire:model="editStartTime" class="form-control" id="editStartTime">
                                    @error('editStartTime')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="editPrice" class="form-label">Giá vé (VND)</label>
                                    <input type="number" wire:model="editPrice" class="form-control" id="editPrice">
                                    @error('editPrice')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                <button type="button" class="btn btn-secondary" wire:click="$set('editShowtimeId', null)">Hủy</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
