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
            <h5 class="mb-0 font-weight-normal">Admin Cinema</h5>
            <span>Quản trị viên</span>
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
      <a class="nav-link" href="{{ route('admin.dashboards.index') }}">
        <span class="menu-icon">
          <i class="mdi mdi-speedometer"></i>
        </span>
        <span class="menu-title">Bảng điều khiển</span>
      </a>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#admin-banners-create">
        <span class="menu-icon">
          <i class="fa-solid fa-display"></i>
        </span>
        <span class="menu-title">Quản lý banner</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="admin-banners-create">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.banners.create') }}">Tạo banner mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.banners.index') }}">Danh sách banner</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-booking">
        <span class="menu-icon">
          <i class="fas fa-shopping-cart"></i>
        </span>
        <span class="menu-title">Quản lý đơn hàng</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-booking">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.bookings.index') }}">Danh sách đơn hàng</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-foods">
        <span class="menu-icon">
          <i class="fas fa-folder-tree"></i>
        </span>
        <span class="menu-title">Quản lý món ăn</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-foods">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.foods.create') }}">Tạo món ăn mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.foods.index') }}">Danh sách món ăn</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-food_variants">
        <span class="menu-icon">
          <i class="fas fa-folder-tree"></i>
        </span>
        <span class="menu-title">Quản lý biến thể</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-food_variants">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.food_variants.create') }}">Tạo biến thể mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.food_variants.index') }}">Danh sách biến thể</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-food_attributes">
        <span class="menu-icon">
          <i class="fas fa-shopping-cart"></i>
        </span>
        <span class="menu-title">Quản lý thuộc tính</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-food_attributes">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.food_attributes.index') }}">Danh sách thuộc tính</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-genres">
        <span class="menu-icon">
          <i class="fas fa-folder-tree"></i>
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
          <i class="fas fa-film"></i>
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
          <i class="fa-solid fa-bell"></i>
        </span>
        <span class="menu-title">Quản lý thông báo</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-notifications">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.notifications.create') }}">Tạo thông báo
              mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.notifications.index') }}">Danh sách thông
              báo</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-comments">
        <span class="menu-icon">
          <i class="fa-solid fa-comment-captions"></i>
        </span>
        <span class="menu-title">Quản lý bình luận</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-comments">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.comments.index') }}">Danh sách bình luận</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.comments.create') }}">Tạo bình luận nới</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-ratings">
        <span class="menu-icon">
          <i class="fa-classic fa-solid fa-star-sharp fa-fw"></i>
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
          <i class="mdi mdi-theater"></i>
        </span>
        <span class="menu-title">Quản lý phòng chiếu</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-rooms">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.rooms.create') }}">Tạo phòng chiếu mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.rooms.index') }}">Danh sách phòng chiếu</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-showtimes">
        <span class="menu-icon">
          <i class="mdi mdi-timetable"></i>
        </span>
        <span class="menu-title">Quản lý suất chiếu</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-showtimes">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.showtimes.create') }}">Tạo suất chiếu mới</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.showtimes.index') }}">Danh sách suất chiếu</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-tickets">
        <span class="menu-icon">
          <i class="fa-solid fa-ticket"></i>
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
    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-users">
        <span class="menu-icon">
          <i class="mdi mdi-timetable"></i>
        </span>
        <span class="menu-title">Quản lý người dùng</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-users">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.create') }}">Thêm người dùng mới</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Danh sách người dùng</a></li>
        </ul>
      </div>
    </li>
     <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#manage-chat">
        <span class="menu-icon">
          <i class="mdi mdi-timetable"></i>
        </span>
        <span class="menu-title">Chat với khách hàng</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="manage-chat">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{ route('admin.chat')}}">Khung chat</a></li>
        </ul>
      </div>
    </li>
  </ul>
</nav>
