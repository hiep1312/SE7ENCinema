<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo" href="index.html"><img style="width: 360px;"
        src="{{ asset('admin/assets/images/logo1.svg') }}" alt="logo" /></a>
    <a class="sidebar-brand brand-logo-mini" href="index.html"><img
        src="{{ asset('admin/assets/images/logo-mini1.svg') }}" alt="logo" /></a>
  </div>
  <ul class="nav">
    <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle " src="{{ asset('admin/assets/images/faces/face15.jpg') }}" alt="">
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name">
            <h5 class="mb-0 font-weight-normal">Henry Klein</h5>
            <span>Gold Member</span>
          </div>
        </div>
        <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
        <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
          <a href="#" class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-settings text-primary"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-onepassword  text-info"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-calendar-today text-success"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
            </div>
          </a>
        </div>
      </div>
    </li>
    <li class="nav-item nav-category">
      <span class="nav-link">Điều hướng</span>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <span class="menu-icon">
          <i class="mdi mdi-speedometer"></i>
        </span>
        <span class="menu-title">Bảng điều khiển</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-banner">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Quản lý banner</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-banner">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.banners.create') }}">Tạo banner mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.banners.index') }}">Danh sách banner</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-booking">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Quản lý đơn hàng</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-booking">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.bookings.index') }}">Danh sách đơn hàng</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-genres">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Quản lý thể loại</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-genres">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.genres.create') }}">Tạo thể loại mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.genres.index') }}">Danh sách thể loại</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-movies">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Quản lý phim</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-movies">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.movies.create') }}">Thêm phim mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.movies.index') }}">Danh sách phim</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-notifications">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Quản lý thông báo</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-notifications">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.notifications.create') }}">Tạo thông báo mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.notifications.index') }}">Danh sách thông báo</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-ratings">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Quản lý đánh giá</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-ratings">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.ratings.index') }}">Danh sách đánh giá</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-rooms">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Quản lý phòng chiếu</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-rooms">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.rooms.create') }}">Tạo phòng chiếu mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.rooms.index') }}">Danh sách phòng chiếu</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-showtimes">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Quản lý suất chiếu</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-showtimes">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.showtimes.create') }}">Tạo suất chiếu mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.showtimes.index') }}">Danh sách suất chiếu</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-tickets">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Quản lý vé</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-tickets">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.tickets.index') }}">Danh sách vé</a></li>
        </ul>
      </div>
    </li>
    {{-- <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-icon">
          <i class="mdi mdi-laptop"></i>
        </span>
        <span class="menu-title">Basic UI Elements</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.buttons') }}">Buttons</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.dropdowns') }}">Dropdowns</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.typography') }}">Typography</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="{{ route('admin.basic_elements') }}">
        <span class="menu-icon">
          <i class="mdi mdi-playlist-play"></i>
        </span>
        <span class="menu-title">Form Elements</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="{{ route('admin.table') }}">
        <span class="menu-icon">
          <i class="mdi mdi-table"></i>
        </span>
        <span class="menu-title">Tables</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="{{ route('admin.chartjs') }}">
        <span class="menu-icon">
          <i class="mdi mdi-chart-bar"></i>
        </span>
        <span class="menu-title">Charts</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="{{ route('admin.mdi') }}">
        <span class="menu-icon">
          <i class="mdi mdi-contacts"></i>
        </span>
        <span class="menu-title">Icons</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <span class="menu-icon">
          <i class="mdi mdi-security"></i>
        </span>
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.blank-page') }}"> Blank Page </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.error-404') }}"> 404 </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.error-500') }}"> 500 </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.login') }}"> Login </a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('admin.register') }}"> Register </a></li>
        </ul>
      </div>
    </li> --}}
  </ul>
</nav>
