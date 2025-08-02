<div id="preloader">
    <div id="status">
        <img src="{{ asset('client/assets/images/header/horoscope.gif') }}" id="preloader_image" alt="loader">
    </div>
</div>
<!-- prs navigation Start -->
<div class="prs_navigation_main_wrapper">
    <div class="container-fluid">
        <div id="search_open" class="gc_search_box">
            <input type="text" placeholder="Search here">
            <button><i class="fa fa-search" aria-hidden="true"></i>
            </button>
        </div>
        <div class="prs_navi_left_main_wrapper">
            <div class="prs_logo_main_wrapper">
                <a href="{{ route('client.index') }}">
                    <img src="{{ asset('client/assets/images/header/logo5.png') }}" alt="logo" />
                </a>
            </div>
            <div class="prs_menu_main_wrapper">
                <nav class="navbar navbar-default">
                    <div id="dl-menu" class="xv-menuwrapper responsive-menu">
                        <button class="dl-trigger">
                            <img src="{{ asset('client/assets/images/header/bars.png') }}" alt="bar_png">
                        </button>
                        <div class="prs_mobail_searchbar_wrapper" id="search_button"> <i class="fa fa-search"></i>
                        </div>
                        <div class="clearfix"></div>
                        <ul class="dl-menu">
                            <li class="parent"><a href="{{ route('client.index') }}">Trang Chủ</a></li>
                            <li class="parent megamenu"><a href="{{ route('client.movies.index') }}">Danh Sách Phim</a>
                            </li>
                            <li class="parent"><a href="#">Lịch chiếu</a></li>
                            <li class="parent"><a href="#">Khuyến Mãi</a></li>
                            <li class="parent"><a href="route('client.userInfo')">Thành Viên</a></li>
                            <li class="parent"><a href="{{ route('client.faq') }}">Chính sách & Bảo mật</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /dl-menuwrapper -->
                </nav>
            </div>
        </div>
        <div class="prs_navi_right_main_wrapper">
            <div class="prs_slidebar_wrapper">
                <button class="second-nav-toggler" type="button">
                    <img src="{{ asset('client/assets/images/header/bars.png') }}" alt="bar_png">
                </button>
            </div>
            <div class="prs_top_login_btn_wrapper">
                <div class="prs_animate_btn1">
                    <ul>
                        @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="button button--tamaya" data-text="Đăng xuất">Đăng
                                xuất</button>
                        </form>
                        @else
                        <li>
                            <a href="{{ route('login') }}" class="button button--tamaya"
                                data-text="Đăng nhập"><span>Đăng
                                    nhập</span></a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
            <livewire:client.content-page />
        </div>
        <div id="mobile-nav" data-prevent-default="true" data-mouse-events="true">
            <div class="mobail_nav_overlay"></div>
            <div class="mobile-nav-box">
                <div class="mobile-logo">
                    <a href="index.html" class="mobile-main-logo">
                        <img src="{{ asset('client/assets/images/header/logo3.png') }}" style="width: 220px;" />
                    </a>
                    <a href="#" class="manu-close"><i class="fa fa-times"></i></a>
                </div>
                <ul class="mobile-list-nav">
                    <li><a href="{{ route('client.index') }}">TRANG CHỦ</a>
                    </li>
                    <li><a href="{{ route('client.userInfo') }}">THÔNG TIN TÀI KHOẢN</a>
                    </li>
                    <li><a href="{{ route('client.movies.index') }}">DANH SÁCH PHIM</a>
                    </li>
                    <li><a href="{{ route('client.event_single') }}">LỊCH CHIẾU</a>
                    </li>
                    <li><a href="{{ route('client.event_single') }}">KHUYẾN MÃI</a>
                    </li>
                    <li><a href="{{ route('client.userInfo') }}">THÀNH VIÊN</a>
                    </li>
                    <li><a href="{{ route('client.contact') }}">CHÍNH SÁCH VÀ BẢO MẬT</a>
                    </li>
                </ul>

                <div class="achivement-blog">
                    <ul class="flat-list">
                        <li>
                            <a href="#"> <i class="fa fa-facebook"></i>
                                <h6>Facebook</h6>
                                <span class="counter">12546</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path
                                        d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                                </svg>
                                <h6>Twitter</h6>
                                <span class="counter">12546</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"> <i class="fa fa-pinterest"></i>
                                <h6>Pinterest</h6>
                                <span class="counter">12546</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="prs_top_login_btn_wrapper prs_slidebar_searchbar_btn_wrapper">
                    <div class="prs_animate_btn1">
                        <ul>
                            @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="button button--tamaya" data-text="Đăng xuất">Đăng
                                    xuất</button>
                            </form>
                            @else
                            <li>
                                <a href="/login" class="button button--tamaya" data-text="Đăng nhập"><span>Đăng
                                        nhập</span></a>
                            </li>
                            <li>
                                <a href="/register" class="button button--tamaya" data-text="Đăng ký"><span>Đăng
                                        ký</span></a>
                            </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
