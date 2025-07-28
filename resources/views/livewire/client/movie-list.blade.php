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
								<img src="{{ asset('client/assets/images/content/movie_category/slider_img1.jpg')}}" alt="about_img">
							</div>
							<div class="item">
								<img src="{{ asset('client/assets/images/content/movie_category/slider_img2.jpg')}}" alt="about_img">
							</div>
							<div class="item">
								<img src="{{ asset('client/assets/images/content/movie_category/slider_img3.jpg')}}" alt="about_img">
							</div>
						</div>
					</div>
				</div>
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_upcome_tabs_wrapper" style="text-align: center; margin-bottom: 20px;">
                        <ul class="nav nav-tabs" role="tablist"
                            style="display: inline-flex; gap: 10px; list-style: none; padding: 0; margin: 0;">
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
                                            wire:click="$set('genreFilter', '{{ $genre->id }}')">{{ $genre->name }}
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
                                        href="{{ route('client.booking.select_showtime', $topEventMovie->id) }}">{{ $topEventMovie->title }}</a>
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
                                        <li class="active"><a data-toggle="pill" href="#coming_soon"><i class="fa fa-th-large"></i></a></li>
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
                                                        <div class="prs_upcom_movie_img_box">
															<img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" style="aspect-ratio: 4 / 5; object-fit: cover;">
															@php
															$age = strtoupper($movie->age_restriction);
															$color = match ($age) {'P' => '#28a745', 'K' => '#20c997', 'T13' => '#17a2b8', 'T16' => '#ffc107', 'T18' =>
															'#fd7e14', 'C' => '#6c757d',default => '#343a40', // Xám đậm – Không xác định
															};
															@endphp
															<span
																style="position: absolute; top: 8px; left: 8px; display: inline-block; background-color: {{ $color }}; color: #fff; font-size: 12px; font-weight: bold; padding: 4px 10px; border-radius: 4px; text-transform: uppercase; z-index: 10;">
																{{ $age }}
															</span>

															<div class="prs_upcom_movie_img_overlay"></div>
															<div class="prs_upcom_movie_img_btn_wrapper">
																<ul>
																	<li><a href="{{ $movie->trailer_url ?? '#' }}">View
																			Trailer</a></li>
																	<li><a href="#">View Details</a></li>
																</ul>
															</div>
														</div>
                                                        <div class="prs_upcom_movie_content_box">
                                                            <div class="prs_upcom_movie_content_box_inner" style="max-width: 100% !important;">
                                                                <h2><a href="#">{{ $movie->title }}</a></h2>
                                                               <p>Thể loại: {{ Str::limit($movie->genres->pluck('name')->implode(', '), 20) }}</p>
                                                                <p-*+>Thời lượng: {{ $movie->duration }} phút</p-*+>
                                                                <p>Giá vé:
                                                                    {{ number_format($movie->price, 0, ',', '.') }} VND
                                                                </p>
                                                                <p>
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @if ($movie->rating >= $i)
                                                                           <i class="fa-solid fa-star-sharp"></i>
                                                                        @elseif ($movie->rating >= $i - 0.5)
                                                                           <i class="fa-solid fa-star-half-stroke"></i>
                                                                        @else
                                                                           <i class="fa-regular fa-star-sharp"></i>
                                                                        @endif
                                                                    @endfor
                                                                    ({{ number_format($movie->rating, 1) }}/5)
                                                                </p>
                                                            </div>
                                                           <div class="booking-button-container" style="text-align: center; margin-top: 20px;">
                                                            @auth
                                                            <a href="{{ route('client.booking.select_showtime', $movie->id) }}" class="btn btn-primary"
                                                                style="background-color: #e50914; border: none; padding: 10px 20px; font-size: 16px; color: white; text-transform: uppercase;">
                                                                Mua Vé Ngay
                                                            </a>
                                                            @else
                                                            <a href="{{ route('login') }}" onclick="alert('Vui lòng đăng nhập để mua vé')" class="btn btn-primary"
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
                                            <div class="pager_wrapper gc_blog_pagination">
                                                <ul class="pagination">
                                                    <li><a href="#"><i class="flaticon-left-arrow"></i></a></li>
                                                    <li><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li class="prs_third_page"><a href="#">3</a></li>
                                                    <li class="hidden-xs"><a href="#">4</a></li>
                                                    <li><a href="#"><i class="flaticon-right-arrow"></i></a>
                                                    </li>
                                                </ul>
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

   <div class="prs_theater_main_slider_wrapper">
        <div class="prs_theater_img_overlay"></div>
        <div class="prs_theater_sec_heading_wrapper">
            <h2>TOP MOVIES IN THEATRES</h2>
        </div>
        <div class="wrap-album-slider" wire:ignore.self>
            <ul class="album-slider raw" wire:ignore.self>
                @forelse ($topMovies as $movie)
                    <li class="album-slider__item" wire:key="movie-{{ $movie->id }}" wire:ignore.self
                       >
                        <figure class="album">
                            <div class="prs_upcom_movie_box_wrapper">
                              <div class="prs_upcom_movie_img_box">
								<img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}">
								@php
								$age = strtoupper($movie->age_restriction);
								$color = match ($age) {'P' => '#28a745', 'K' => '#20c997', 'T13' => '#17a2b8', 'T16' => '#ffc107', 'T18' =>
								'#fd7e14', 'C' => '#6c757d',default => '#343a40', // Xám đậm – Không xác định
								};
								@endphp
								<span
									style="position: absolute; top: 8px; left: 8px; display: inline-block; background-color: {{ $color }}; color: #fff; font-size: 12px; font-weight: bold; padding: 4px 10px; border-radius: 4px; text-transform: uppercase; z-index: 10;">
									{{ $age }}
								</span>

								<div class="prs_upcom_movie_img_overlay"></div>
								<div class="prs_upcom_movie_img_btn_wrapper">
									<ul>
										<li><a href="{{ $movie->trailer_url ?? '#' }}">View
												Trailer</a></li>
										<li><a href="#">View Details</a></li>
									</ul>
								</div>
							</div>
                                <div class="prs_upcom_movie_content_box">
                                    <div class="prs_upcom_movie_content_box_inner">
                                        <h2><a
                                                href="{{ route('client.booking.select_showtime', $movie->id) }}">{{ $movie->title }}</a>
                                        </h2>
                                       <p>Thể loại: {{ Str::limit($movie->genres->pluck('name')->implode(', '), 10) }}</p>
                                        <p>Thời lượng: {{ $movie->duration }} phút</p>
                                        <p>Giá vé: {{ number_format($movie->price, 0, ',', '.') }} VND</p>
                                         <p>
                                             @for ($i = 1; $i <= 5; $i++)
                                                 @if ($movie->rating >= $i)
                                                    <i class="fa-solid fa-star-sharp"></i>
                                                 @elseif ($movie->rating >= $i - 0.5)
                                                    <i class="fa-solid fa-star-half-stroke"></i>
                                                 @else
                                                    <i class="fa-regular fa-star-sharp"></i>
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
                    <li class="album-slider__item">
                        <div class="text-center text-gray-500 py-8">
                            No movies currently in theatres.
                        </div>
                    </li>
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
