@assets
    @vite('resources/css/movieList.css')
@endassets
<div class="scRender">
    <div class="prs_main_slider_wrapper">
        <div id="rev_slider_41_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container"
            data-alias="food-carousel26" data-source="gallery"
            style="margin:0px auto;padding:0px;margin-top:0px;margin-bottom:0px;">
            <div class="prs_slider_overlay"></div>
            <!-- START REVOLUTION SLIDER 5.4.1 fullwidth mode -->
            <div id="rev_slider_41_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.1">
                <ul>
                    @foreach($banners as $banner)
                    <!-- SLIDE  -->
                    <li data-index="rs-145" data-transition="fade" data-slotamount="7" data-hideafterloop="0"
                        data-hideslideonmobile="off" data-easein="default" data-easeout="default" data-masterspeed="300"
                        data-rotate="0" data-saveperformance="off" data-title="{{ $banner->title }}" data-param1=""
                        data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7=""
                        data-param8="" data-param9="" data-param10="" data-description="">

                        <!-- MAIN IMAGE -->
                        @if($banner->link)
                        <a href="{{ $banner->link }}" style="display: block; width: 100%; aspect-ratio: 16/9;padding-top: 50px;">
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" data-bgposition="center center" data-bgfit="cover"
                                data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina style="width: 100%; height: 100%; object-fit: cover; aspect-ratio: 16/9;">
                        </a>
                        @else
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" data-bgposition="center center" data-bgfit="cover"
                                data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina style="aspect-ratio: 16/9; width: 100%; object-fit: cover;">
                        @endif

                        <!-- LAYERS -->
                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption FoodCarousel-CloseButton rev-btn  tp-resizeme" id="slide-145-layer-5"
                            data-x="441" data-y="110" data-width="['auto']" data-height="['auto']" data-type="button"
                            data-actions='[{"event":"click","action":"stoplayer","layer":"slide-145-layer-3","delay":""},{"event":"click","action":"stoplayer","layer":"slide-145-layer-5","delay":""},{"event":"click","action":"startlayer","layer":"slide-145-layer-1","delay":""}]'
                            data-responsive_offset="on"
                            data-frames='[{"from":"z:0;rX:0;rY:0;rZ:0;sX:0.9;sY:0.9;skX:0;skY:0;opacity:0;","speed":800,"to":"o:1;","delay":"bytrigger","ease":"Power3.easeInOut"},{"delay":"bytrigger","speed":500,"to":"auto:auto;","ease":"nothing"},{"frame":"hover","speed":"300","ease":"Power1.easeInOut","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgba(255,255,255,1);bg:rgba(41,46,49,1);bw:1px 1px 1px 1px;"}]'
                            data-textAlign="['left','left','left','left']" data-paddingtop="[14,14,14,14]"
                            data-paddingright="[14,14,14,14]" data-paddingbottom="[14,14,14,14]"
                            data-paddingleft="[16,16,16,16]" data-lasttriggerstate="reset"
                            style="z-index: 7; white-space: nowrap;border-color:transparent;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">
                            <i class="fa-icon-remove"></i>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
            </div>
        </div>
    </div>
    <div class="scMovieList prz_main_wrapper">
    <div class="prs_mc_slider_main_wrapper " style="padding-bottom: 20px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_heading_section_wrapper">
                        <h2>Trải nghiệm xem phim cùng SE7VENCINEMA</h2>
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
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_mcc_right_side_wrapper">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="tab-content">
                                    <div id="coming_soon" class="tab-pane in active">
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
                                                                    @if ($movie->trailer_url)
                                                                        <li><a href="{{ $movie->trailer_url }}" target="_blank">Xem trailer</a></li>
                                                                    @endif
                                                                    <li><a href="{{ route('client.movie_detail', $movie->id) }}">Xem chi tiết</a></li>
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
                                                                    <a href="{{ route('client.movieBooking.movie', $movie->id) }}"
                                                                        class="btn btn-primary"
                                                                        style="background-color: #e50914; border: none; padding: 10px 20px; font-size: 14px; color: white; text-transform: uppercase; width: 100%; border-radius: 5px; font-weight: bold; margin-top: 10px;">
                                                                        Mua Vé Ngay
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('login') }}"
                                                                        wire:confirm.confirm="Vui lòng đăng nhập để mua vé"
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
                                        {{-- <div style="clear: both;"></div> --}}
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <ul class="sc-pagination ">
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

  </div>
</div>
