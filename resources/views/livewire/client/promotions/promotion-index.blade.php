@assets
    @vite('resources/css/promotion.css')
@endassets
<div class="scRender scPromotion" wire:poll.10s>
    <div class="container-fluid d-flex justify-content-center align-items-start scPromotion">
        <div class="container">
            <!-- Tips/Intro Section -->
            <div class="promotion-tips-box mt-4 mb-4">
                <div class="promotion-tips-title"><i class="fa fa-lightbulb-o me-2"></i> Mẹo & Thông tin khuyến mãi
                </div>
                <div class="promotion-tip"><i class="fa fa-check-circle"></i> <strong>TIP 1: </strong> Theo dõi trang
                    này để cập nhật các mã giảm giá, voucher và chương trình giảm giá mới nhất từ SE7ENCinema.</div>
                <div class="promotion-tip"><i class="fa fa-info-circle"></i> <strong>TIP 2: </strong> Mỗi voucher đều có
                    điều kiện áp dụng riêng, hãy đọc kỹ thông tin trước khi sử dụng để nhận ưu đãi tốt nhất!</div>
                <div class="promotion-tip"><i class="fa fa-gift"></i> <strong>TIP 3: </strong> Số lượng voucher có hạn,
                    nhanh tay lưu và sử dụng trước khi hết hạn nhé!</div>
                <div class="alert alert-warning mt-3 mb-0 py-2 px-3"><i class="fa fa-exclamation-triangle me-2"></i> Nếu
                    gặp vấn đề khi sử dụng mã, vui lòng liên hệ bộ phận <a href="#">CSKH</a> để được hỗ trợ nhanh nhất.
                </div>
            </div>
            <div class="row promotion-main-row" style="--bs-gutter-x: 1.2rem !important;">
                <!-- Left: Promotions List -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <h2 class="fw-bold mb-4"
                        style="font-family: 'Oswald', sans-serif; letter-spacing: 1px; display: inline-block; margin-bottom: -30px !important;">
                        MÃ GIẢM GIÁ</h2>

                    <div class="respon">
                        @forelse($promotions as $promotion)
                        <div class="voucher-card-modern my-4 position-relative animate__animated animate__fadeInUp">
                            <!-- Ribbon nhỏ hơn -->
                            <div class="voucher-ribbon position-absolute top-0 start-0 px-3 py-1 fw-bold text-white"
                                style="font-size: 1rem; border-bottom-right-radius: 1rem;">
                                <i class="fas fa-ticket-alt me-2 fa-lg animate__animated animate__tada"></i>VOUCHER
                            </div>
                            <!-- Lỗ vé nhỏ hơn -->
                            <div class="voucher-hole position-absolute bg-white border rounded-circle"
                                style="left: -8px; top: 18%; width: 16px; height: 16px;"></div>
                            <div class="voucher-hole position-absolute bg-white border rounded-circle"
                                style="left: -8px; bottom: 18%; width: 16px; height: 16px;"></div>
                            <div class="voucher-hole position-absolute bg-white border rounded-circle"
                                style="right: -8px; top: 18%; width: 16px; height: 16px;"></div>
                            <div class="voucher-hole position-absolute bg-white border rounded-circle"
                                style="right: -8px; bottom: 18%; width: 16px; height: 16px;"></div>
                            <!-- Main content -->
                            <div class="voucher-main-modern d-flex align-items-center mb-2 mt-3">
                                <div class="voucher-icon rounded-circle d-flex align-items-center justify-content-center me-2"
                                    style="width: 40px; height: 40px; font-size: 1.2rem;">
                                    <i class="fas fa-gift text-white"></i>
                                </div>
                                <div style="min-width:0; margin: 10px 0px !important">
                                    <span class="fw-bold text-gradient-orange voucher-discount"
                                        style="font-size: 1.7rem;">{{ $promotion->discount_type === 'percentage' ?
                                        $promotion->discount_value . '%' : number_format($promotion->discount_value, 0,
                                        '.', '.') . 'đ' }}</span>
                                    <span class="text-secondary ms-1 fw-medium" style="font-size: 1rem;">giảm</span>
                                </div>
                            </div>
                            <!-- Code & Info -->
                            <div class="mb-1" style="gap: 0.5rem; ">
                                <span
                                    class="badge bg-gradient-orange text-white px-3 py-1 fs-6 fw-bold shadow-sm voucher-code-modern">{{
                                    $promotion->code }}</span>
                            </div>
                            <span class="text-secondary small" style="font-size: 0.95rem;">Đơn tối thiểu: <b
                                    class="text-dark">{{ number_format($promotion->min_purchase, 0, '.', '.')
                                    }}đ</b></span>
                            <div class="text-secondary small" style="margin:10px 0px !important ">HSD: <b
                                    class="text-dark">{{ $promotion->end_date->format('d/m/Y') }}</b></div>
                            <div class="my-1 text-secondary small">Mô tả: <span class="fw-medium text-dark">{{
                                    Str::limit($promotion->description, 50) }}</span></div>
                            <div class="d-flex justify-content-between align-items-center mt-2" style="gap: 0.5rem;">
                                <div>
                                    <button
                                        class="badge rounded-pill text-bg-primary fw-semibold shadow-sm voucher-detail-toggle"
                                        style="font-size: 0.75rem; border: 1px solid blue !important; "
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapsePromoModern{{ $promotion->id }}" aria-expanded="false"
                                        aria-controls="collapsePromoModern{{ $promotion->id }}">
                                        Xem chi tiết
                                    </button>
                                </div>
                                <div>
                                    <button
                                        class="btn btn-warning color1 fw-bold px-3 py-1 rounded-pill shadow-sm border-0">
                                        Lưu voucher
                                    </button>
                                </div>
                            </div>
                            <!-- Chi tiết voucher -->
                            <div class="collapse animate__animated animate__fadeIn"
                                id="collapsePromoModern{{ $promotion->id }}">
                                <div class="voucher-detail-modern card-body mt-2 p-2"
                                    style="background: transparent; box-shadow: none; border: none;">
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Tên khuyến
                                            mãi:</div>
                                        <div class="col-7 text-dark" style="font-size: 0.95rem;">{{ $promotion->title }}
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Mã khuyến
                                            mãi:</div>
                                        <div class="col-7"><span class="fw-bold badge bg-primary"
                                                style="font-size: 0.95rem;">{{ $promotion->code }}</span></div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Mô tả:
                                        </div>
                                        <div class="col-7 text-dark" style="font-size: 0.95rem;">{{
                                            $promotion->description }}</div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Trạng
                                            thái:</div>
                                        <div class="col-7">
                                            <span
                                                class="badge {{ $promotion->status === 'active' ? 'bg-success' : ($promotion->status === 'expired' ? 'bg-secondary' : 'bg-danger') }}"
                                                style="font-size: 0.75rem;">
                                                {{ $promotion->status === 'active' ? 'Hoạt động' : ($promotion->status
                                                === 'expired' ? 'Hết hạn' : 'Ngừng hoạt động') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Ngày bắt
                                            đầu:</div>
                                        <div class="col-7 text-dark" style="font-size: 0.95rem;">{{
                                            $promotion->start_date->format('d/m/Y H:i') }}</div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Ngày kết
                                            thúc:</div>
                                        <div class="col-7 text-dark" style="font-size: 0.95rem;">{{
                                            $promotion->end_date->format('d/m/Y H:i') }}</div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Loại giảm
                                            giá:</div>
                                        <div class="col-7 text-dark" style="font-size: 0.95rem;">{{
                                            $promotion->discount_type === 'percentage' ? 'Phần trăm' : 'Cố định' }}
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Giá trị
                                            khuyến mãi:</div>
                                        <div class="col-7"><span class="fw-bold badge bg-danger"
                                                style="font-size: 0.75rem;">{{ $promotion->discount_type ===
                                                'percentage' ? $promotion->discount_value . '%' :
                                                number_format($promotion->discount_value, 0, '.', '.') . 'đ' }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Giới hạn
                                            sử dụng:</div>
                                        <div class="col-7 text-dark" style="font-size: 0.95rem;">{{
                                            $promotion->usage_limit }}</div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-5 fw-bold text-secondary" style="font-size: 0.95rem;">Giá trị
                                            tối thiểu đơn hàng:</div>
                                        <div class="col-7 text-dark" style="font-size: 0.95rem;">{{
                                            number_format($promotion->min_purchase, 0, '.', '.') }}đ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="alert alert-info">Hiện chưa có khuyến mãi nào.</div>
                        @endforelse
                    </div>
                </div>
                <!-- Right: Hot Movies -->
                <div class="col-lg-6 col-md-12" style="text-align: center !important">
                    <h2 class="fw-bold mb-4 "
                        style="font-family: 'Oswald', sans-serif; letter-spacing: 1px; display: inline-block; ">PHIM
                        ĐANG HOT</h2>
                    <div class="row g-4">
                        @forelse($hotMovies as $movie)
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card movie-hot p-0 border-0" style="height: 440px; min-height: 440px;">
                                <div class="position-relative movie-img-wrapper"
                                    style="height: 400px; border-radius: 1.2rem; overflow: hidden;">
                                    <img src="{{ $movie->poster ? (Str::startsWith($movie->poster, ['http://', 'https://']) ? $movie->poster : asset('storage/' . $movie->poster)) : asset('404.webp') }}"
                                        class="card-img-top movie-img-large" alt="{{ $movie->title }}"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 1.2rem;">
                                    @if($movie->age_restriction)
                                    <span class="badge badge-age badge-age-{{ strtoupper($movie->age_restriction) }}">
                                        {{ $movie->age_restriction }}
                                    </span>
                                    @endif
                                    <div class="movie-overlay"
                                        style="position:absolute;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                        <div class="play-btn" data-bs-toggle="modal"
                                            data-bs-target="#movieTrailerModal{{ $movie->id }}"
                                            style="width: 60px; height: 60px; background: rgba(0,0,0,0.45); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                            <i class="fa fa-play" style="color: #fff; font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body text-center p-2">
                                    <a href="{{route('client.movie_detail',$movie->id)}}"
                                        class="card-title mb-2 movie-title-main text-decoration-none"
                                        style="max-width: 100%; font-size: 25px; font-weight: 700; color: #1976d2;">{{
                                        Str::limit($movie->title, 40) }}</a>
                                </div>
                            </div>
                            <!-- Modal trailer riêng cho từng phim, style giống admin, có background poster mờ, nếu không có trailer thì hiện 404.webp -->
                            <div class="modal modal-backdrop fade" id="movieTrailerModal{{ $movie->id }}" wire:ignore
                                tabindex="-1" aria-labelledby="movieTrailerModalLabel{{ $movie->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content"
                                        style="background: none; box-shadow: none; border: none;">
                                        <div class="modal-body p-0" style="position:relative;">
                                            <div class="trailer-bg-blur"
                                                style="position:absolute;top:0;left:0;width:100%;height:100%;background:url('{{ Str::startsWith($movie->poster ?? '', ['http://', 'https://']) ? $movie->poster : asset('storage/' . ($movie->poster ?? '404.webp')) }}') center center / cover no-repeat;filter: blur(16px) brightness(0.5);z-index:1;">
                                            </div>
                                            <div class="video-container glow-effect mt-1"
                                                style="position:relative;z-index:2;">
                                                <div class="video-header">
                                                    <div class="video-icon">
                                                        <i class="fa-brands fa-youtube"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="video-title">{{ $movie->title }} | Official Trailer
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="video-wrapper">
                                                    <div class="responsive-iframe"
                                                        style="border-radius: 1rem; overflow: hidden; background: #222;">
                                                        @if($movie->trailer_url)
                                                        <iframe
                                                            data-src="{{ getYoutubeEmbedUrl((string)$movie->trailer_url) }}"
                                                            allowfullscreen style="width:100%;"></iframe>
                                                        @else
                                                        <img src="{{ asset( 'storage/404.webp') }}" alt="404 Not Found"
                                                            style="width:100%;height:360px;object-fit:contain;background:#fff;">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center"
                                            style="z-index:3;position:relative;">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng
                                                trailer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">Hiện chưa có phim hot nào.</div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            {{--
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center align-items-center mb-3">
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
            --}}
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center align-items-center mb-3">
                <div class="pager_wrapper gc_blog_pagination">
                    {{ $promotions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('show.bs.modal', function () {
            var iframe = modal.querySelector('iframe');
            if (iframe) {
                iframe.src = iframe.getAttribute('data-src');
            }
        });
        modal.addEventListener('hidden.bs.modal', function () {
            var iframe = modal.querySelector('iframe');
            if (iframe) {
                iframe.src = '';
            }
        });
    });
</script>
@endscript