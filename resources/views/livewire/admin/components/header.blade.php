<!-- Font Awesome -->
<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-solid.css">
<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">

<!-- Header full width + Offcanvas -->
    <nav class="navbar navbar-expand-lg my-3 bg-light w-100">
        <div class="container-fluid d-flex align-items-center">
            <button class="btn btn-outline-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
                <i class="fas fa-bars"></i>
            </button>
            <span class="navbar-brand ms-3 text-dark">Chức năng</span>
        </div>
    </nav>
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel">Chức năng</h5>
            <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item bg-dark text-white">
                    <a href="#" class="text-decoration-none text-white" wire:click.prevent>
                        <i class="fas fa-plus-circle me-2"></i>Tạo suất chiếu
                    </a>
                </li>
                <li class="list-group-item bg-dark text-white">
                    <a href="#" class="text-decoration-none text-white" wire:click.prevent>
                        <i class="fas fa-calendar-alt me-2"></i>Xem lịch chiếu
                    </a>
                </li>
                <li class="list-group-item bg-dark text-white">
                    <a href="#" class="text-decoration-none text-white" wire:click.prevent>
                        <i class="fas fa-search me-2"></i>Tìm kiếm/Lọc
                    </a>
                </li>
                    <li class="list-group-item bg-dark text-white">
                        <a href="#" class="text-decoration-none text-white" wire:click.prevent>
                            <i class="fas fa-film me-2"></i>Quản lý phim
                        </a>
                    </li>
                    <li class="list-group-item bg-white text-white">
                        <a href="#" class="text-decoration-none text-white" wire:click.prevent>
                            <i class="fas fa-door-open me-2"></i>Quản lý phòng
                        </a>
                    </li>
            </ul>
        </div>
    </div>
