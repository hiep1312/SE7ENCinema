@assets
    @vite('resources/css/movieList.css')
@endassets
<div class="scMovieList prz_main_wrapper scMovieList">
    <div class="prs_title_main_sec_wrapper" 
     style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
             url('https://media.canva.com/v2/files/uri:ifs%3A%2F%2FM%2Fc36b55c4-973f-4678-ba6b-0e00cac174e8?csig=AAAAAAAAAAAAAAAAAAAAACCDi1HIF3Jm9xVDvPb6UH_cs-DSur6gvhsvWewSeOaE&exp=1755948760&signer=media-rpc&token=AAIAAU0AJGMzNmI1NWM0LTk3M2YtNDY3OC1iYTZiLTBlMDBjYWMxNzRlOAAAAAABmNazu8BsfuW98ebf4Hxzx2jEWQuz-OGpVkvsoEP5cvfeC8gmSw'); 
            background-size: cover; 
            background-position: center; 
            padding: 50px 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="prs_title_heading_wrapper text-center text-white">
                    <h2>Danh Sách Phim</h2>
                    <ul>
                        <li><a href="{{ route('client.index') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></li>
                        <li><i class="fa fa-chevron-right"></i> Danh Sách Phim</li>
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
                        @if($banners && $banners->count() > 0)
                            <div class="owl-carousel owl-theme">
                                @foreach($banners as $banner)
                                    <div class="item">
                                        <img src="{{ asset('storage/' . $banner->image) }}" 
                                             alt="{{ $banner->title ?? 'Banner' }}"
                                             style="width: 85%; height: 400px; object-fit: confit; margin: 0 auto;"> 
                                    </div>
                                @endforeach
                            </div>
                        @else
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
                        @endif
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
        <div class="container" >
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 hidden-sm hidden-xs">
                    <div class="prs_mcc_left_side_wrapper">
                        <div class="prs_mcc_left_searchbar_wrapper search-movies">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Tìm kiếm phim"
                                class="border rounded-lg px-4 py-2 w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <button><i class="flaticon-tool"></i></button>
                        </div>
                        <div class="prs_mcc_bro_title_wrapper title-category">
                            <h2>Danh Mục Phim</h2>
                            <ul>
                                <li><i class="fa fa-caret-right"></i> <a href=""
                                        wire:click="$set('genreFilter', '')">Tất Cả
                                        <span>{{ $genres->count() }}</span></a></li>
                                @foreach ($genres as $genre)
                                    <li><i class="fa fa-caret-right"></i> <a href="javascript:void(0)"
                                            wire:click="$set('genreFilter', '{{ $genre->id }}')">{{ $genre->name }}
                                            <span>{{ $genre->movies->count() ?? 0 }}</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Top Events -->
                        <div class="prs_mcc_event_title_wrapper title-top-event">
                            <h2>Phim Nổi Bật</h2>
                            <div class="mb-4">
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $topEventMovie->poster) }}"
                                        alt="{{ $topEventMovie->title }}" class="w-full h-32 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-30 rounded-lg"></div>
                                </div>
                                <h3 class="text-lg font-semibold text-white mt-2"><a
                                        href="{{ route('client.movieBooking.movie', $topEventMovie->id) }}">{{ $topEventMovie->title }}</a>
                                </h3>
                                <p class="text-gray-300">Thời gian: {{ $topEventMovie->duration }} Phút</p>
                                <p class="text-gray-400">Giá: {{ number_format($topEventMovie->price, 0, ',', '.') }}
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
                                <div class="prs_mcc_right_side_heading_wrapper ICON">
                                    <h2>Danh Sách Phim</h2>
                                    <ul class="nav nav-pills">
                                        <li class="active"><a data-toggle="pill" href="javascrip:void(0)"><i
                                                    class="fa fa-th-large"></i></a></li>
                                        <li><a data-toggle="pill" href="javascrip:void(0)"><i class="fa fa-list"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="tab-content">
                                    <div id="coming_soon" class="cinema-tab__content cinema-tab__content--active">
                                        <div class="cinema-movie-list">
                                           
                                            <div class="movie-grid">
                                                @forelse ($movies as $movie)
                                                    <div class="movie-card">
                                                        <div class="movie-card__container">
                                                            <div class="movie-poster movie-img-wrapper">
                                                                <img src="{{ asset('storage/' . $movie->poster) }}"
                                                                     alt="{{ $movie->title }}"
                                                                     class="movie-poster__image">
                                                                @php
                                                                    $age = strtoupper($movie->age_restriction);
                                                                @endphp
                                                                <span class="badge-age badge-age-{{ $age }}">
                                                                    {{ $age }}
                                                                </span>
                                    
                                                                <div class="movie-poster__overlay">
                                                                    <div class="movie-actions">
                                                                        <div class="movie-actions__list">
                                                                            @if ($movie->trailer_url)
                                                                                <a href="{{ $movie->trailer_url }}" 
                                                                                   target="_blank" 
                                                                                   class="movie-actions__button movie-actions__button--trailer">
                                                                                    <i class="fas fa-play"></i>
                                                                                    Trailer
                                                                                </a>
                                                                            @endif
                                                                            <a href="{{ route('client.movie_detail', $movie->id) }}" 
                                                                               class="movie-actions__button movie-actions__button--details">
                                                                                <i class="fas fa-info-circle"></i>
                                                                                Chi tiết
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="movie-info">
                                                                <div class="movie-info__header">
                                                                    <h3 class="movie-info__title">
                                                                        <a href="{{ route('client.movie_detail', $movie->id) }}" 
                                                                           class="movie-info__title-link">
                                                                            {{ $movie->title }}
                                                                        </a>
                                                                    </h3>
                                                                </div>
                                                                
                                                                <div class="movie-info__details">
                                                                    <div class="movie-detail">
                                                                        <span class="movie-detail__label">Thể loại:</span>
                                                                        <span class="movie-detail__value">{{ $movie->genres->pluck('name')->implode(', ') }}</span>
                                                                    </div>
                                                                    
                                                                    <div class="movie-detail">
                                                                        <span class="movie-detail__label">Thời lượng:</span>
                                                                        <span class="movie-detail__value">{{ $movie->duration }} phút</span>
                                                                    </div>
                                                                    
                                                                    <div class="movie-detail">
                                                                        <span class="movie-detail__label">Giá vé:</span>
                                                                        <span class="movie-detail__value movie-detail__value--price">
                                                                            {{ number_format($movie->price, 0, ',', '.') }} VND
                                                                        </span>
                                                                    </div>
                                                                    
                                                                    <div class="movie-rating">
                                                                        <div class="movie-rating__stars">
                                                                            @for ($i = 1; $i <= 5; $i++)
                                                                                @if ($movie->rating >= $i)
                                                                                    <i class="fas fa-star movie-rating__star movie-rating__star--filled"></i>
                                                                                @elseif ($movie->rating >= $i - 0.5)
                                                                                    <i class="fas fa-star-half-alt movie-rating__star movie-rating__star--half"></i>
                                                                                @else
                                                                                    <i class="far fa-star movie-rating__star movie-rating__star--empty"></i>
                                                                                @endif
                                                                            @endfor
                                                                        </div>
                                                                        <span class="movie-rating__score">({{ number_format($movie->rating, 1) }}/5)</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="booking-section">
                                                                    @auth
                                                                        <a href="{{ route('client.movieBooking.movie', $movie->id) }}"
                                                                           class="booking-button booking-button--primary">
                                                                            <i class="fas fa-ticket-alt"></i>
                                                                            Mua Vé Ngay
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ route('login') }}"
                                                                           wire:confirm.confirm="Vui lòng đăng nhập để mua vé"
                                                                           class="booking-button booking-button--primary">
                                                                            <i class="fas fa-sign-in-alt"></i>
                                                                            Đăng Nhập Để Mua Vé
                                                                        </a>
                                                                    @endauth
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="empty-state">
                                                        <div class="empty-state__icon">
                                                            <i class="fas fa-film"></i>
                                                        </div>
                                                        <p class="empty-state__message">
                                                            Không có phim nào phù hợp với bộ lọc hiện tại.
                                                        </p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                        
                                        {{-- <div style="clear: both;"></div> --}}
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="prs_pagination_wrapper">
                                                {{ $movies->links() }}
                                            </div>
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
                <h2>TOP PHIM NỔI BẬT</h2>
            </div>
            <div class="wrap-album-slider">
                <ul class="album-slider" style="width: 915%; position: relative;">
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
                                            <div class="prs_upcom_movie_img_btn_wrapper butoon_view">
                                                <ul>
                                                    @if ($movie->trailer_url)
                                                        <li><a href="{{ $movie->trailer_url }}" target="_blank">Xem trailer</a></li>
                                                    @endif
                                                    <li><a href="{{ route('client.movie_detail', $movie->id) }}">Xem chi tiết</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="prs_upcom_movie_content_box">
                                            <div class="prs_upcom_movie_content_box_inner">
                                                <h2><a style="display: block; width: 100%; line-clamp: 10px;"
                                                        href="{{ route('client.movieBooking.movie', $movie->id) }}">{{ $movie->title }}</a>
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
                                                    <li><a href="{{ route('client.movieBooking.movie', $movie->id) }}"><i
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
                        <h3>Đăng ký nhận tin</h3>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <div class="prs_newsletter_field">
                        <input type="text" placeholder="Nhập email của bạn">
                        <button type="submit">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
