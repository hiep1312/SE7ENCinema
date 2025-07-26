<div class="prz_main_wrapper">
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

    <div class="prs_mc_slider_main_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_heading_section_wrapper">
                        <h2>Trang Danh Sách Phim</h2>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_mc_slider_wrapper">
                        <div class="owl-carousel owl-theme">
                            <div class="item">
                                <img src="{{ asset('client/assets/images/content/movie_category/slider_img1.jpg') }}"
                                    alt="about_img">
                            </div>
                            <div class="item">
                                <img src="{{ asset('client/assets/images/content/movie_category/slider_img2.jpg') }}"
                                    alt="about_img">
                            </div>
                            <div class="item">
                                <img src="{{ asset('client/assets/images/content/movie_category/slider_img3.jpg') }}"
                                    alt="about_img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_upcome_tabs_wrapper" style="text-align: center; margin-bottom: 20px;">
                        <ul class="nav nav-tabs" role="tablist"
                            style="display: inline-flex; gap: 10px; list-style: none; padding: 0; margin: 0; background: none !important;">
                            <li role="presentation" class="{{ $tabCurrent === 'coming_soon' ? 'active' : '' }}">
                                <button type="button" role="tab" wire:click="$set('tabCurrent', 'coming_soon')"
                                    style="padding: 10px 20px; border: none; border-radius: 5px; background-color: {{ $tabCurrent === 'coming_soon' ? '#e50914' : '#ddd' }}; color: {{ $tabCurrent === 'coming_soon' ? '#fff' : '#333' }}; font-weight: bold; cursor: pointer;">
                                    Phim Sắp Chiếu
                                </button>
                            </li>
                            <li role="presentation" class="{{ $tabCurrent === 'showing' ? 'active' : '' }}">
                                <button type="button" role="tab" wire:click="$set('tabCurrent', 'showing')"
                                    style="padding: 10px 20px; border: none; border-radius: 5px; background-color: {{ $tabCurrent === 'showing' ? '#e50914' : '#ddd' }}; color: {{ $tabCurrent === 'showing' ? '#fff' : '#333' }}; font-weight: bold; cursor: pointer;">
                                    Phim Đang Chiếu
                                </button>
                            </li>
                            <li role="presentation" class="{{ $tabCurrent === 'ended' ? 'active' : '' }}">
                                <button type="button" role="tab" wire:click="$set('tabCurrent', 'ended')"
                                    style="padding: 10px 20px; border: none; border-radius: 5px; background-color: {{ $tabCurrent === 'ended' ? '#e50914' : '#ddd' }}; color: {{ $tabCurrent === 'ended' ? '#fff' : '#333' }}; font-weight: bold; cursor: pointer;">
                                    Phim Đã Kết Thúc
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="prs_mc_category_sidebar_main_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 hidden-sm hidden-xs">
                    <div class="prs_mcc_left_side_wrapper">
                        <div class="prs_mcc_left_searchbar_wrapper">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search Movie"
                                class="border rounded-lg px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <button><i class="flaticon-tool"></i></button>
                        </div>
                        <div class="prs_mcc_bro_title_wrapper">
                            <h2>Browse Title</h2>
                            <ul>
                                <li><i class="fa fa-caret-right"></i> <a href="#"
                                        wire:click="$set('genreFilter', '')">All
                                        <span>{{ $genres->count() }}</span></a></li>
                                @foreach ($genres as $genre)
                                    <li><i class="fa fa-caret-right"></i> <a href="#"
                                            wire:click="$set('genreFilter', '{{ $genre->id }}')">{{ Str::limit($genre->name, 15) }}
                                            <span>{{ $genre->movies->count() ?? 0 }}</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Top Events -->
                        <div class="prs_mcc_event_title_wrapper">
                            <h2>Top Events</h2>
                            <div class="mb-4">
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $topEventMovie->poster) }}"
                                        alt="{{ $topEventMovie->title }}" class="w-full h-32 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-30 rounded-lg"></div>
                                </div>
                                <h3 class="text-lg font-semibold text-white mt-2"><a
                                        href="{{ route('client.movie_booking', $topEventMovie->id) }}">{{ $topEventMovie->title }}</a>
                                </h3>
                                <p class="text-gray-300">Duration: {{ $topEventMovie->duration }} minutes</p>
                                <p class="text-gray-400">Price: {{ number_format($topEventMovie->price, 0, ',', '.') }}
                                    VND</p>
                                <span class="ml-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if (($topEventMovie->rating ?? 0) >= $i)
                                            <i class="fa-solid fa-star-sharp"></i>
                                        @elseif (($topEventMovie->rating ?? 0) >= $i - 0.5)
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        @else
                                            <i class="fa-regular fa-star-sharp"></i>
                                        @endif
                                    @endfor
                                    ({{ number_format($topEventMovie->rating ?? 0, 1) }}/5)
                                </span>
                            </div>
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
                                        <li class="active"><a data-toggle="pill" href="#coming_soon"><i
                                                    class="fa fa-th-large"></i></a></li>
                                        <li><a data-toggle="pill" href="#list"><i class="fa fa-list"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="tab-content">
                                    <div id="coming_soon" class="tab-pane fade in active">
                                        <div class="row">
                                            @forelse ($movies as $movie)
                                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 prs_upcom_slide_first">
                                                    <div class="prs_upcom_movie_box_wrapper prs_mcc_movie_box_wrapper">
                                                        <div class="prs_upcom_movie_img_box movie-img-wrapper">
                                                            <img src="{{ asset('storage/' . $movie->poster) }}"
                                                                alt="{{ $movie->title }}"
                                                                style="aspect-ratio: 4 / 5; object-fit: cover;">
                                                            @php
                                                                $age = strtoupper($movie->age_restriction);
                                                            @endphp
                                                            <span class="badge-age badge-age-{{ $age }}">
                                                                {{ $age }}
                                                            </span>

                                                            <div class="prs_upcom_movie_img_overlay"></div>
                                                            <div class="prs_upcom_movie_img_btn_wrapper">
                                                                <ul>
                                                                    <li><a
                                                                            href="{{ $movie->trailer_url ?? 'javascript:void(0)' }}">View
                                                                            Trailer</a></li>
                                                                    <li><a href="javascript:void(0)">View Details</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="prs_upcom_movie_content_box">
                                                            <div class="prs_upcom_movie_content_box_inner"
                                                                style="max-width: 100% !important;">
                                                                <h2><a style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap; display: block;"
                                                                        href="#">{{ $movie->title }}</a></h2>
                                                                <p>Thể loại:
                                                                    {{ Str::limit($movie->genres->pluck('name')->implode(', '), 20) }}
                                                                </p>
                                                                <p>Thời lượng: {{ $movie->duration }} phút</p>
                                                                <p>Giá vé:
                                                                    {{ number_format($movie->price, 0, ',', '.') }} VND
                                                                </p>
                                                                <p>
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @if ($movie->rating >= $i)
                                                                            <i class="fa-solid fa-star-sharp"></i>
                                                                        @elseif ($movie->rating >= $i - 0.5)
                                                                            <i
                                                                                class="fa-solid fa-star-half-stroke"></i>
                                                                        @else
                                                                            <i class="fa-regular fa-star-sharp"></i>
                                                                        @endif
                                                                    @endfor
                                                                    ({{ number_format($movie->rating, 1) }}/5)
                                                                </p>
                                                            </div>
                                                            <div class="booking-button-container"
                                                                style="text-align: center; margin-top: 20px;">
                                                                @auth
                                                                    <a href="{{ route('client.movie_booking', $movie->id) }}"
                                                                        class="btn btn-primary"
                                                                        style="background-color: #e50914; border: none; padding: 10px 20px; font-size: 14px; color: white; text-transform: uppercase; width: 100%; border-radius: 5px; font-weight: bold; margin-top: 10px;">
                                                                        Mua Vé Ngay
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('login') }}"
                                                                        onclick="alert('Vui lòng đăng nhập để mua vé')"
                                                                        class="btn btn-primary"
                                                                        style="background-color: #e50914; border: none; padding: 10px 20px; font-size: 16px; color: white; text-transform: uppercase;">
                                                                        Mua Vé Ngay
                                                                    </a>
                                                                @endauth
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div
                                                    class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-gray-500 py-8">
                                                    Không có phim nào phù hợp với bộ lọc hiện tại.
                                                </div>
                                            @endforelse
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="sc-pagination">
                                                {{-- Previous Page --}}
                                                <li>
                                                    <button class="sc-page sc-page--arrow"
                                                        wire:click="gotoPage({{ max(1, $movies->currentPage() - 1) }})">
                                                        <span style=" display: flex; align-items: center; justify-content: center;">&larr;</span>
                                                    </button>
                                                </li>
                                                @php
                                                    $start = max(1, $movies->currentPage() - 2);
                                                    $end = min($movies->lastPage(), $movies->currentPage() + 2);
                                                @endphp
                                                @if($start > 1)
                                                    <li><button class="sc-page" wire:click="gotoPage(1)">1</button></li>
                                                    @if($start > 2)
                                                        <li><span class="sc-page sc-page--disabled" style="cursor: default;">...</span></li>
                                                    @endif
                                                @endif
                                                @for ($page = $start; $page <= $end; $page++)
                                                    <li>
                                                        <button class="sc-page{{ $page == $movies->currentPage() ? ' sc-page--active' : '' }}"
                                                            wire:click="gotoPage({{ $page }})">
                                                            {{ $page }}
                                                        </button>
                                                    </li>
                                                @endfor
                                                @if($end < $movies->lastPage())
                                                    @if($end < $movies->lastPage() - 1)
                                                        <li><span class="sc-page sc-page--disabled" style="cursor: default;">...</span></li>
                                                    @endif
                                                    <li><button class="sc-page" wire:click="gotoPage({{ $movies->lastPage() }})">{{ $movies->lastPage() }}</button></li>
                                                @endif
                                                <li>
                                                    <button class="sc-page sc-page--arrow"
                                                        wire:click="gotoPage({{ min($movies->lastPage(), $movies->currentPage() + 1) }})">
                                                        <span style="font-size: 1.3em; display: flex; align-items: center; justify-content: center;">&rarr;</span>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- List View (Placeholder) -->
                                    <div id="list" class="tab-pane fade">
                                        <div class="row">
                                            <div
                                                class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center text-gray-500 py-8">
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

    <div>
        {{-- Top Movies Casual Slider Section --}}
        <div class="prs_theater_main_slider_wrapper">
            <div style="clear: both;"></div>
            <div class="prs_theater_img_overlay"></div>
            <div class="prs_theater_sec_heading_wrapper">
                <h2>TOP MOVIES IN THEATRES</h2>
            </div>
            <div class="wrap-album-slider">
                <ul class="album-slider raw scApp" style="width: 915%; position: relative;">
                    @for ($loopCount = 0; $loopCount < 3; $loopCount++)
                        @foreach ($topMovies as $movie)
                            <li class="album-slider__item"
                                style="float: left; list-style: none; position: relative; width: 257px; margin-right: 17px;">
                                <figure class="album">
                                    <div class="prs_upcom_movie_box_wrapper">
                                        <div class="prs_upcom_movie_img_box movie-img-wrapper">
                                            <img style="aspect-ratio: 4 / 5; object-fit: cover;"
                                                src="{{ asset('storage/' . $movie->poster) }}"
                                                alt="{{ $movie->title }}" />
                                            @php
                                                $age = strtoupper($movie->age_restriction);
                                            @endphp
                                            <span class="badge-age badge-age-{{ $age }}">
                                                {{ $age }}
                                            </span>
                                            <div class="prs_upcom_movie_img_overlay"></div>
                                            <div class="prs_upcom_movie_img_btn_wrapper">
                                                <ul>
                                                    @if ($movie->trailer_url)
                                                        <li><a href="{{ $movie->trailer_url }}" target="_blank">View
                                                                Trailer</a></li>
                                                    @endif
                                                    <li><a href="{{ route('client.movie_booking', $movie->id) }}">View
                                                            Details</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="prs_upcom_movie_content_box">
                                            <div class="prs_upcom_movie_content_box_inner">
                                                <h2><a style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap; display: block; width: 100%;"
                                                        href="{{ route('client.movie_booking', $movie->id) }}">{{ $movie->title }}</a>
                                                </h2>
                                                <p
                                                    style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap; display: block; width: 100%;">
                                                    {{ $movie->genres->pluck('name')->implode(', ') }}</p>
                                                <span>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if (($movie->ratings_avg_score ?? 0) >= $i)
                                                            <i class="fa fa-star"></i>
                                                        @elseif (($movie->ratings_avg_score ?? 0) >= $i - 0.5)
                                                            <i class="fa fa-star-half-o"></i>
                                                        @else
                                                            <i class="fa fa-star-o"></i>
                                                        @endif
                                                    @endfor
                                                </span>
                                            </div>
                                            <div class="prs_upcom_movie_content_box_inner_icon">
                                                <ul>
                                                    <li><a href="{{ route('client.movie_booking', $movie->id) }}"><i
                                                                class="flaticon-cart-of-ecommerce"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </figure>
                            </li>
                        @endforeach
                    @endfor
                </ul>
            </div>
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
    <style>
        /* Modern Age Restriction Badges - Professional Design */
        .badge-age {
            display: inline-block !important;
            min-width: 46px !important;
            max-width: 70px !important;
            height: 32px !important;
            padding: 0 !important;
            font-size: 0.95rem !important;
            font-weight: 900 !important;
            border-radius: 4px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2) !important;
            border: none !important;
            letter-spacing: 0.3px !important;
            text-align: center !important;
            vertical-align: middle !important;
            color: #fff !important;
            background: #bdbdbd !important;
            position: relative !important;
            z-index: 15 !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            text-transform: uppercase !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4) !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        /* Hover Effects */
        .badge-age:hover {
            transform: translateY(-1px) scale(1.03) !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3) !important;
        }

        /* P - All Ages - Deep Blue */
        .badge-age-P {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%) !important;
            box-shadow: 0 2px 6px rgba(25, 118, 210, 0.35) !important;
        }

        .badge-age-P:hover {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%) !important;
            box-shadow: 0 4px 10px rgba(25, 118, 210, 0.5) !important;
        }

        /* K - Kids with Adult - Light Blue */
        .badge-age-K {
            background: linear-gradient(135deg, #64b5f6 0%, #42a5f5 100%) !important;
            box-shadow: 0 2px 6px rgba(100, 181, 246, 0.35) !important;
        }

        .badge-age-K:hover {
            background: linear-gradient(135deg, #42a5f5 0%, #2196f3 100%) !important;
            box-shadow: 0 4px 10px rgba(100, 181, 246, 0.5) !important;
        }

        /* T13 - 13+ Years - Orange */
        .badge-age-T13 {
            background: linear-gradient(135deg, #e22ca6 0%, #e22ca6 100%) !important;
            box-shadow: 0 2px 6px rgba(255, 179, 0, 0.35) !important;
        }

        .badge-age-T13:hover {
            background: linear-gradient(135deg, #e22ca6 0%, #e22ca6 100%) !important;
            box-shadow: 0 4px 10px rgba(255, 179, 0, 0.5) !important;
        }

        /* T16 - 16+ Years - Golden Yellow like image */
        .badge-age-T16 {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%) !important;
            box-shadow: 0 2px 6px rgba(255, 193, 7, 0.35) !important;
            color: #fff !important;
        }

        .badge-age-T16:hover {
            background: linear-gradient(135deg, #ffb300 0%, #ffa000 100%) !important;
            box-shadow: 0 4px 10px rgba(255, 193, 7, 0.5) !important;
        }

        /* T18 - 18+ Years - Pink/Rose like image */
        .badge-age-T18 {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%) !important;
            box-shadow: 0 2px 6px rgba(233, 30, 99, 0.35) !important;
            color: #fff !important;
        }

        .badge-age-T18:hover {
            background: linear-gradient(135deg, #c2185b 0%, #ad1457 100%) !important;
            box-shadow: 0 4px 10px rgba(233, 30, 99, 0.5) !important;
        }

        /* C - Banned Content - Gray with Warning */
        .badge-age-C {
            background: linear-gradient(135deg, #9e9e9e 0%, #757575 100%) !important;
            box-shadow: 0 2px 6px rgba(158, 158, 158, 0.35) !important;
            color: #fff !important;
            text-decoration: line-through !important;
            position: relative !important;
        }

        .badge-age-C:hover {
            background: linear-gradient(135deg, #757575 0%, #616161 100%) !important;
            box-shadow: 0 4px 10px rgba(158, 158, 158, 0.5) !important;
        }

        /* Warning icon for C rating */
        .badge-age-C::after {
            content: "⚠" !important;
            position: absolute !important;
            top: -4px !important;
            right: -4px !important;
            font-size: 0.6rem !important;
            background: #ff5722 !important;
            border-radius: 50% !important;
            width: 12px !important;
            height: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 1px 3px rgba(255, 87, 34, 0.4) !important;
            text-decoration: none !important;
        }

        /* Enhanced positioning for movie cards */
        .movie-img-wrapper .badge-age {
            position: absolute !important;
            top: 8px !important;
            left: 8px !important;
            z-index: 20 !important;
            margin: 0 !important;
        }

        /* Premium shine effect */
        .badge-age::before {
            content: "" !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent) !important;
            transition: left 0.5s !important;
            border-radius: 4px !important;
        }

        .badge-age:hover::before {
            left: 100% !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .badge-age {
                min-width: 32px !important;
                max-width: 44px !important;
                height: 20px !important;
                font-size: 0.8rem !important;
            }

            .movie-img-wrapper .badge-age {
                top: 6px !important;
                left: 6px !important;
            }
        }

        @media (max-width: 480px) {
            .badge-age {
                min-width: 30px !important;
                max-width: 40px !important;
                height: 18px !important;
                font-size: 0.75rem !important;
            }

            .movie-img-wrapper .badge-age {
                top: 5px !important;
                left: 5px !important;
            }
        }

        /* Animation for badge appearance */
        @keyframes badgeSlideIn {
            from {
                opacity: 0;
                transform: translateX(-15px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        .badge-age {
            animation: badgeSlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Focus states for accessibility */
        .badge-age:focus {
            outline: 2px solid rgba(255, 255, 255, 0.6) !important;
            outline-offset: 1px !important;
        }

        /* Print styles */
        @media print {
            .badge-age {
                background: #333 !important;
                color: #fff !important;
                box-shadow: none !important;
                border: 1px solid #333 !important;
            }
        }

        .sc-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin: 32px 0 0 0;
            padding: 0;
            list-style: none;
        }
        .sc-page {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 500;
            border-radius: 12px;
            border: 1.5px solid #eee;
            background: #fff;
            color: #222;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.18s cubic-bezier(.4,0,.2,1);
            cursor: pointer;
            user-select: none;
        }
        .sc-page--arrow {
            background: #ff4747;
            color: #fff;
            border: none;
            font-size: 1.4rem;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(255,71,71,0.08);
        }
        .sc-page--active {
            background: Black;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }
        .sc-page:not(.sc-page--active):hover {
            color: white;
            background: Black;
            z-index: 2;
        }
        .sc-page--arrow:hover {
            background: #e50914;
            color: #fff;
        }
        @media (max-width: 600px) {
            .sc-page, .sc-page--arrow {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }
            .sc-pagination { gap: 6px; }
        }
    </style>
</div>
