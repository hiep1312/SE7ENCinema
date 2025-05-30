<!-- Sidebar Menu -->
<div class="d-flex flex-column flex-shrink-0 p-3 bg-dark border-end col-md-3 top-0" style="width: 300px; min-height: 100vh;">
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="fas fa-film mt-3 me-2"></i>
        <span class="fs-5 mt-3 fw-bold">SE7ENCinema</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#" class="nav-link active" aria-current="page">
                <i class="fas fa-home me-2"></i>Trang chủ
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <i class="fas fa-plus-circle me-2"></i>Tạo suất chiếu
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <i class="fas fa-calendar-alt me-2"></i>Xem lịch chiếu
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <i class="fas fa-search me-2"></i>Tìm kiếm/Lọc
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <i class="fas fa-film me-2"></i>Quản lý phim
            </a>
        </li>
        <li>
            <a href="{{ route('manage.showtimes') }}" class="nav-link text-white">
                <i class="fas fa-door-open me-2"></i>Quản lý suất chiếu
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                <i class="fas fa-users me-2"></i>Quản lý người dùng
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://jbagy.me/wp-content/uploads/2025/03/hinh-anh-cute-avatar-vo-tri-3.jpg" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>Admin</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small shadow">
            <li><a class="dropdown-item" href="#">Cài đặt</a></li>
            <li><a class="dropdown-item" href="#">Hồ sơ</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Đăng xuất</a></li>
        </ul>
    </div>
</div>
