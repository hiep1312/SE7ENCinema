<div class="prs_title_wrapper">
    <!-- Header Section -->
    <div class="prs_title_main_sec_wrapper">
        <div class="prs_title_img_overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_title_heading_wrapper">
                        <h2>Danh Sách Phim</h2>
                        <ul>
                            <li><a href="{{ route('client.index')}}">Home</a></li>
                            <li>   >   Danh Sách Phim</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- prs title wrapper End -->
    <!-- prs mc slider wrapper Start -->
    <div class="prs_mc_slider_main_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_heading_section_wrapper">
                        <h2>Trang Danh Sách Phim</h2>
                    </div>
                </div>
                <!-- Tabs -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_upcome_tabs_wrapper">
                        <div class="flex justify-center space-x-4 mb-6">
                            <button wire:click="setTab('coming_soon')"
                                class="px-4 py-2 rounded-lg font-semibold transition-all duration-300
                                    {{ $activeTab === 'coming_soon' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}"
                                role="tab" aria-controls="coming_soon">
                                Phim Sắp Chiếu
                            </button>
                            <button wire:click="setTab('showing')"
                                class="px-4 py-2 rounded-lg font-semibold transition-all duration-300
                                    {{ $activeTab === 'showing' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}"
                                role="tab" aria-controls="showing">
                                Phim Đang Chiếu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- prs mc slider wrapper End -->
    <!-- prs mc category slidebar Start -->
    <div class="prs_mc_category_sidebar_main_wrapper">
        <div class="container">
            <div class="row">
                <!-- Sidebar with Filters -->
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 hidden-sm hidden-xs">
                    <div class="prs_mcc_left_side_wrapper">
                        <div class="prs_mcc_left_searchbar_wrapper">
							<input type="text" placeholder="Search Movie">
							<button><i class="flaticon-tool"></i>
							</button>
						</div>
                    </div>
                </div>
                <!-- Movie List -->
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <div class="prs_mcc_right_side_wrapper">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="prs_mcc_right_side_heading_wrapper">
                                    <h2>Danh Sách Phim</h2>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="tab-content">
                                    <div id="grid" class="tab-pane fade in active">
                                        <div class="row">
                                            @forelse ($movies as $movie)
                                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_upcom_slide_first">
                                                    <div class="prs_upcom_movie_box_wrapper prs_mcc_movie_box_wrapper">
                                                        <div class="prs_upcom_movie_img_box">
                                                            <img src="https://cdn.popsww.com/blog/sites/2/2023/04/phim-ma-thai-lan-hay-nhat-756x1080.jpg"
                                                                alt="{{ $movie->title }}"
                                                                class="w-full h-64 object-cover" />
                                                            <div class="prs_upcom_movie_img_overlay"></div>
                                                            <!-- <img src="{{ asset('storage/' . $movie->poster) }}"
                                                                alt="{{ $movie->title }}"
                                                                class="w-full h-64 object-cover" />
                                                            <div class="prs_upcom_movie_img_overlay"></div> -->
                                                            <div class="prs_upcom_movie_img_btn_wrapper">
                                                                <ul>
                                                                    <li><a href="#">View Trailer</a></li>
                                                                    <li><a href="#">View Details</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="prs_upcom_movie_content_box">
                                                            <div class="prs_upcom_movie_content_box_inner">
                                                                <h2><a href="#">{{ $movie->title }}</a></h2>
                                                                <p>Thể loại: </p>
                                                                <p>Thời lượng: {{ $movie->duration }} phút</p>
                                                                <p>Giới hạn độ tuổi: {{ $movie->age_restriction }} / {{ $movie->format }}</p>
                                                                <p>Giá vé: {{ number_format($movie->price, 0, ',', '.') }} VND</p>
                                                            </div>
                                                            <div class="prs_upcom_movie_content_box_inner_icon">
                                                                <ul>
                                                                    <li><a href="movie_booking.html"><i
                                                                                class="flaticon-cart-of-ecommerce"></i></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-gray-500">
                                                    Không có phim nào phù hợp với bộ lọc hiện tại.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>