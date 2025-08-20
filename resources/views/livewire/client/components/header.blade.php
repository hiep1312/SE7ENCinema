{{-- <div id="preloader">
    <div id="status">
        <img src="{{ asset('client/assets/images/header/horoscope.gif') }}" id="preloader_image" alt="loader">
    </div>
</div> --}}
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
                            <li class="parent "><a href="{{ route('client.index') }}">Trang Chủ</a></li>
                            <li class="parent megamenu"><a href="{{ route('client.movies.index') }}">Danh Sách Phim</a>
                            </li>
                            <li class="parent"><a href="{{ route('client.showtimes.index') }}">Lịch chiếu</a></li>
                            <li class="parent"><a href="{{ route('client.promotions.index') }}">Khuyến Mãi</a></li>
                            <li class="parent"><a href="{{ route('client.faq') }}">Chính sách bảo mật</a>
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
            @guest
            <div class="prs_top_login_btn_wrapper">
                <div class="prs_animate_btn1">
                            <a href="{{ route('login') }}" class="button button--tamaya"
                                data-text="Đăng nhập"><span>Đăng
                                    nhập</span></a>
                </div>
            </div>
            @endguest
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
                    <li><a href="{{ route('client.showtimes.index') }}">LỊCH CHIẾU</a>
                    </li>
                    <li><a href="{{ route('client.promotions.index') }}">KHUYẾN MÃI</a>
                    </li>
                    <li><a href="{{ route('client.faq') }}">CHÍNH SÁCH VÀ BẢO MẬT</a>
                    </li>
                </ul>
                <div class="prs_top_login_btn_wrapper prs_slidebar_searchbar_btn_wrapper">
                    <div class="prs_animate_btn1">

                        <ul class="list-unstyled">
                            <div style="display:flex">
                                @auth
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="button button--tamaya" data-text="Đăng xuất">Đăng
                                        xuất</button>
                                </form>
                                @else
                                <li style="margin-right: 10px">
                                    <a href="/login" class="button button--tamaya" data-text="Đăng nhập"><span>Đăng
                                            nhập</span></a>
                                </li>
                                <li>
                                    <a href="/register" class="button button--tamaya" data-text="Đăng ký"><span>Đăng
                                            nhập</span></a>
                                </li>
                                @endauth
                                <li>
                                    {{-- Notification Bell --}}
                                    <div
                                        style="display: inline-block; position: relative; margin-left: 18px; vertical-align: middle; background: rgb(255, 109, 109); border-radius: 50%;">
                                        @livewire('client.notifications.notification-index')
                                    </div>
                                </li>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
