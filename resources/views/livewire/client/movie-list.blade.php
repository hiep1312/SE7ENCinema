<div class="prs_movie_list_wrapper">
    <!-- Header Section -->
    <div class="prs_title_main_sec_wrapper">
        <div class="prs_title_img_overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_title_heading_wrapper">
                        <h2>Danh Sách Phim</h2>
                        <ul>
                            <li><a href="{{ route('client.index') }}">Home</a></li>
                            <li> > Danh Sách Phim</li>
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
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_upcome_tabs_wrapper">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="{{ $tabCurrent === 'coming_soon' ? 'active' : '' }}">
                                <a href="#" aria-controls="coming_soon" role="tab" data-toggle="tab" wire:click="setTab('coming_soon')">Phim Sắp Chiếu</a>
                            </li>
                            <li role="presentation" class="{{ $tabCurrent === 'showing' ? 'active' : '' }}">
                                <a href="#" aria-controls="showing" role="tab" data-toggle="tab" wire:click="setTab('showing')">Phim Đang Chiếu</a>
                            </li>
                            <li role="presentation" class="{{ $tabCurrent === 'ended' ? 'active' : '' }}">
                                <a href="#" aria-controls="ended" role="tab" data-toggle="tab" wire:click="setTab('ended')">Phim Đã Kết Thúc</a>
                            </li>
                        </ul>
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
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="prs_mcc_left_side_wrapper">
                        <div class="prs_mcc_left_searchbar_wrapper">
                            <input wire:model.live.debounce.100ms="search" type="text" placeholder="Tìm kiếm phim..."
                                class="border rounded-lg px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <button><i class="flaticon-tool"></i></button>
                        </div>

                        <div class="prs_mcc_bro_title_wrapper">
                            <h2>Lọc theo thể loại</h2>
                            <ul>
                                <li><i class="fa fa-caret-right"></i> <a href="#" wire:click.prevent="setGenreFilter('')">Tất cả</a></li>
                                @foreach ($genres as $genre)
                                <li><i class="fa fa-caret-right"></i> <a href="#" wire:click.prevent="setGenreFilter('{{ $genre }}')">{{ $genre }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="prs_mcc_event_title_wrapper">
                            <h2 class="text-xl font-bold text-gray-100 mb-3">Top Events</h2>
                            @forelse ($movies->sortByDesc('created_at')->take(1) as $movie)
                            <div class="mb-4">
                                <div class="relative">
                                    <img src="http://giadinh.mediacdn.vn/2016/photo-0-1477471953478.jpg" alt="{{ $movie->title }}" class="w-full h-32 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-30 rounded-lg"></div>
                                </div>
                                <h3 class="text-lg font-semibold text-white mt-2"><a href="{{ route('client.movie_booking', $movie->id) }}">{{ $movie->title }}</a></h3>
                                <p class="text-gray-300">Thời lượng: {{ $movie->duration }} phút </p>
                                <p class="text-gray-400">Giá vé: {{ number_format($movie->price, 0, ',', '.') }} VND</p>
                                <span class="ml-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($movie->rating >= $i)
                                        <i class="fa fa-star text-yellow-400"></i>
                                        @elseif ($movie->rating >= $i - 0.5)
                                        <i class="fa fa-star-half-alt text-yellow-400"></i>
                                        @else
                                        <i class="fa fa-star-o text-gray-400"></i>
                                        @endif
                                        @endfor
                                        ({{ number_format($movie->rating, 1) }}/5)
                                </span>
                            </div>
                            @empty
                            <div class="text-center text-gray-500 py-2">Không có phim mới thêm.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <div class="prs_mcc_right_side_wrapper">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="prs_mcc_right_side_heading_wrapper">
                                    <h2>Our Top Movies</h2>
                                    <ul class="nav nav-pills">
                                        <li class="active"><a data-toggle="pill" href="#coming_soon"><i class="fa fa-th-large"></i></a></li>
                                        <li><a data-toggle="pill" href="#list"><i class="fa fa-list"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="tab-content">
                                    <!-- Phim Sắp Chiếu -->
                                    <div id="coming_soon" class="tab-pane fade {{ $tabCurrent === 'coming_soon' ? 'in active' : '' }}">
                                        <div class="row">
                                            @forelse ($movies as $movie)
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_upcom_slide_first">
                                                <div class="prs_upcom_movie_box_wrapper prs_mcc_movie_box_wrapper">
                                                    <div class="prs_upcom_movie_img_box">
                                                        <img src="http://giadinh.mediacdn.vn/2016/photo-0-1477471953478.jpg"
                                                            alt="{{ $movie->title }}"
                                                            class="w-full h-64 object-cover" />
                                                        @php
                                                        $ageClass = 'age-' . strtoupper($movie->age_restriction);
                                                        @endphp
                                                        <span class="age-restriction-badge {{ $ageClass }}">{{ strtoupper($movie->age_restriction) }}</span>
                                                        <div class="prs_upcom_movie_img_overlay"></div>
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
                                                            <p>Thể loại: {{ $movie->genres()->pluck('name')->implode(', ') }}</p>
                                                            <p>Thời lượng: {{ $movie->duration }} phút</p>
                                                            <p>Giá vé: {{ number_format($movie->price, 0, ',', '.') }} VND</p>
                                                            <p>
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($movie->rating >= $i)
                                                                    <i class="fa fa-star"></i>
                                                                    @elseif ($movie->rating >= $i - 0.5)
                                                                    <i class="fa fa-star-half-alt"></i>
                                                                    @else
                                                                    <i class="fa fa-star-o"></i>
                                                                    @endif
                                                                    @endfor
                                                                    ({{ number_format($movie->rating, 1) }}/5)
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-gray-500 py-8">
                                                Không có phim nào phù hợp với bộ lọc hiện tại.
                                            </div>
                                            @endforelse
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="pager_wrapper gc_blog_pagination">
                                                <ul class="pagination">
                                                    <li><a href="#"><i class="flaticon-left-arrow"></i></a></li>
                                                    <li><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li class="prs_third_page"><a href="#">3</a></li>
                                                    <li class="hidden-xs"><a href="#">4</a></li>
                                                    <li><a href="#"><i class="flaticon-right-arrow"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Phim Đang Chiếu -->
                                    <div id="showing" class="tab-pane fade {{ $tabCurrent === 'showing' ? 'in active' : '' }}">
                                        <div class="row">
                                            @forelse ($movies as $movie)
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_upcom_slide_first">
                                                <div class="prs_upcom_movie_box_wrapper prs_mcc_movie_box_wrapper">
                                                    <div class="prs_upcom_movie_img_box">
                                                        <img src="http://giadinh.mediacdn.vn/2016/photo-0-1477471953478.jpg"
                                                            alt="{{ $movie->title }}"
                                                            class="w-full h-64 object-cover" />
                                                        @php
                                                        $ageClass = 'age-' . strtoupper($movie->age_restriction);
                                                        @endphp
                                                        <span class="age-restriction-badge {{ $ageClass }}">{{ strtoupper($movie->age_restriction) }}</span>
                                                        <div class="prs_upcom_movie_img_overlay"></div>
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
                                                            <p>Thể loại: {{ $movie->genres()->pluck('name')->implode(', ') }}</p>
                                                            <p>Thời lượng: {{ $movie->duration }} phút</p>
                                                            <p>Giá vé: {{ number_format($movie->price, 0, ',', '.') }} VND</p>
                                                            <p>
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($movie->rating >= $i)
                                                                    <i class="fa fa-star"></i>
                                                                    @elseif ($movie->rating >= $i - 0.5)
                                                                    <i class="fa fa-star-half-alt"></i>
                                                                    @else
                                                                    <i class="fa fa-star-o"></i>
                                                                    @endif
                                                                    @endfor
                                                                    ({{ number_format($movie->rating, 1) }}/5)
                                                            </p>
                                                        </div>
                                                        <div class="booking-button-container">
                                                            <a href="{{ route('client.movie_booking', $movie->id) }}" class="booking-button">
                                                                Mua Vé Ngay
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-gray-500 py-8">
                                                Không có phim nào phù hợp với bộ lọc hiện tại.
                                            </div>
                                            @endforelse
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="pager_wrapper gc_blog_pagination">
                                                <ul class="pagination">
                                                    <li><a href="#"><i class="flaticon-left-arrow"></i></a></li>
                                                    <li><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li class="prs_third_page"><a href="#">3</a></li>
                                                    <li class="hidden-xs"><a href="#">4</a></li>
                                                    <li><a href="#"><i class="flaticon-right-arrow"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Phim Đã Kết Thúc -->
                                    <div id="ended" wire:key="ended" class="tab-pane fade {{ $tabCurrent === 'ended' ? 'in active' : '' }}">
                                        <div class="row">
                                            @forelse ($movies as $movie)
                                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_upcom_slide_first" wire:key="ended-{{ $movie->id }}">
                                                <div class="prs_upcom_movie_box_wrapper prs_mcc_movie_box_wrapper">
                                                    <div class="prs_upcom_movie_img_box">
                                                        <img src="http://giadinh.mediacdn.vn/2016/photo-0-1477471953478.jpg"
                                                            alt="{{ $movie->title }}"
                                                            class="w-full h-64 object-cover" />
                                                        @php
                                                        $ageClass = 'age-' . strtoupper($movie->age_restriction);
                                                        @endphp
                                                        <span class="age-restriction-badge {{ $ageClass }}">{{ strtoupper($movie->age_restriction) }}</span>
                                                        <div class="prs_upcom_movie_img_overlay"></div>
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
                                                            <p>Thể loại: {{ $movie->genres()->pluck('name')->implode(', ') }}</p>
                                                            <p>Thời lượng: {{ $movie->duration }} phút</p>
                                                            <p>Giá vé: {{ number_format($movie->price, 0, ',', '.') }} VND</p>
                                                            <p>
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($movie->rating >= $i)
                                                                    <i class="fa fa-star"></i>
                                                                    @elseif ($movie->rating >= $i - 0.5)
                                                                    <i class="fa fa-star-half-alt"></i>
                                                                    @else
                                                                    <i class="fa fa-star-o"></i>
                                                                    @endif
                                                                    @endfor
                                                                    ({{ number_format($movie->rating, 1) }}/5)
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-gray-500 py-8">
                                                Không có phim nào phù hợp với bộ lọc hiện tại.
                                            </div>
                                            @endforelse
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="pager_wrapper gc_blog_pagination">
                                                <ul class="pagination">
                                                    <li><a href="#"><i class="flaticon-left-arrow"></i></a></li>
                                                    <li><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li class="prs_third_page"><a href="#">3</a></li>
                                                    <li class="hidden-xs"><a href="#">4</a></li>
                                                    <li><a href="#"><i class="flaticon-right-arrow"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- List View (Placeholder) -->
                                    <div id="list" class="tab-pane fade">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-gray-500 py-8">
                                                Chế độ danh sách chưa được triển khai.
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
    <div class="prs_theater_main_slider_wrapper">
        <div class="prs_theater_img_overlay"></div>
        <div class="prs_theater_sec_heading_wrapper">
            <h2>TOP MOVIES IN THEATRES</h2>
        </div>
        <div class="wrap-album-slider">
            <ul class="album-slider">
                @forelse ($movies as $movie)
                <li class="album-slider__item">
                    <figure class="album">
                        <div class="prs_upcom_movie_box_wrapper">
                            <div class="prs_upcom_movie_img_box">
                                <img src=""
                                    alt="{{ $movie->title }}"
                                    class="w-full h-64 object-cover" />
                                <span class="age-restriction-badge">{{ $movie->age_restriction }}</span>
                                <div class="prs_upcom_movie_img_overlay"></div>
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
                                    <p>Thể loại: {{ $movie->genres()->pluck('name')->implode(', ') }}</p>
                                    <p>Thời lượng: {{ $movie->duration }} phút</p>
                                    <p>Giá vé: {{ number_format($movie->price, 0, ',', '.') }} VND</p>
                                    <p>
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($movie->rating >= $i)
                                            <i class="fa fa-star"></i>
                                            @elseif ($movie->rating >= $i - 0.5)
                                            <i class="fa fa-star-half-alt"></i>
                                            @else
                                            <i class="fa fa-star-o"></i>
                                            @endif
                                            @endfor
                                            ({{ number_format($movie->rating, 1) }}/5)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </figure>
                </li>
                @empty
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-gray-500 py-8">
                    Không có phim nào phù hợp với bộ lọc hiện tại.
                </div>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="prs_newsletter_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="prs_newsletter_text">
                        <h3>Get update sign up now !</h3>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <div class="prs_newsletter_field">
                        <input type="text" placeholder="Enter Your Email">
                        <button type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>