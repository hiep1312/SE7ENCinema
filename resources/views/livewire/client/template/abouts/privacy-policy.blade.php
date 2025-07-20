@extends('clienttest')

@section('title', 'Chính sách bảo mật - SE7ENCinema')

@section('content')
@assets
    @vite('resources/css/app.css')
@endassets

<div class="scRender">
    <div class="privacy-policy-page">
        <!-- Breadcrumb -->
        <div class="privacy-breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="prs_title_heading_wrapper" style="padding-top:15px;">
                            <ul>
                                <li><a href="{{ route('client.index') }}" style="color: black;padding-top:0px; !important;"><i class="fas fa-home"></i></a></li>
                                <li>&nbsp;&nbsp; >&nbsp;&nbsp; Chính sách bảo mật</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="privacy-content">
            <div class="container">
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-lg-3 col-md-4 privacy-sidebar">
                        <div class="sidebar-menu">
                            <h3 class="sidebar-title">Danh mục</h3>
                            <ul>
                                <li><a href="#section-intro">Giới thiệu chung</a></li>
                                <li><a href="#section-scope">Các loại dữ liệu cá nhân</a></li>
                                <li><a href="#section-collection">Thu thập dữ liệu khác</a></li>
                                <li><a href="#section-cookies">Chính sách Cookie</a></li>
                                <li><a href="#section-cctv">Hệ thống giám sát</a></li>
                                <li><a href="#section-children">Thu thập thông tin trẻ em</a></li>
                                <li><a href="#section-purpose">Mục đích xử lý</a></li>
                                <li><a href="#section-storage">Lưu giữ và bảo mật</a></li>
                                <li><a href="#section-rights">Quyền của khách hàng</a></li>
                                <li><a href="#section-sharing">Chia sẻ thông tin</a></li>
                                <li><a href="#section-contact">Thông tin liên hệ</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="col-lg-9 col-md-8">
                        <div class="privacy-main-content">
    <h1 class="privacy-title">Chính sách bảo mật thông tin</h1>

    <!-- Introduction -->
    <section id="section-intro" class="privacy-section">
        <h2 class="section-title">Giới thiệu chung</h2>
        <div class="section-content">
            <p><strong>CÔNG TY TNHH SE7ENCINEMA</strong> (gọi tắt là "SE7ENCinema") cam kết bảo vệ thông tin cá nhân của khách hàng. Chính sách bảo mật này giải thích cách chúng tôi thu thập, sử dụng, lưu trữ và bảo vệ thông tin cá nhân của bạn khi sử dụng dịch vụ tại hệ thống rạp chiếu phim SE7ENCinema.</p>

            <div class="company-info-box">
                <h4>Thông tin công ty:</h4>
                <p><strong>Tên công ty:</strong> CÔNG TY TNHH SE7ENCINEMA</p>
                <p><strong>Địa chỉ:</strong> 29-13 Ng. 4 Đ. Vân Canh, Vân Canh, Từ Liêm, Hà Nội</p>
                <p><strong>Điện thoại:</strong> 028.3636.5757</p>
                <p><strong>Email:</strong> info@se7encinema.com.vn</p>
                <p><strong>Website:</strong> www.se7encinema.com.vn</p>
            </div>

            <div class="important-notice">
                <p><strong>Bằng việc sử dụng dịch vụ của SE7ENCinema, bạn đồng ý với các điều khoản trong chính sách bảo mật này.</strong></p>
            </div>
        </div>
    </section>

    <!-- Section 1 -->
    <section id="section-scope" class="privacy-section">
        <h2 class="section-title">I. THÔNG TIN CHÚNG TÔI THU THẬP</h2>
        <div class="section-content">
            <p>SE7ENCinema thu thập các loại thông tin sau từ khách hàng:</p>

            <div class="data-categories">
                <div class="data-category">
                    <h4>1.1. Thông tin cá nhân cơ bản:</h4>
                    <ul>
                        <li>Họ và tên đầy đủ</li>
                        <li>Số điện thoại</li>
                        <li>Địa chỉ email</li>
                        <li>Ngày sinh</li>
                        <li>Giới tính</li>
                        <li>Địa chỉ liên hệ</li>
                    </ul>
                </div>

                <div class="data-category">
                    <h4>1.2. Thông tin giao dịch:</h4>
                    <ul>
                        <li>Lịch sử đặt vé và mua hàng</li>
                        <li>Thông tin thanh toán (không lưu trữ thông tin thẻ tín dụng)</li>
                        <li>Hóa đơn và biên lai</li>
                        <li>Điểm tích lũy và ưu đãi</li>
                    </ul>
                </div>

                <div class="data-category">
                    <h4>1.3. Thông tin kỹ thuật:</h4>
                    <ul>
                        <li>Địa chỉ IP</li>
                        <li>Loại trình duyệt và thiết bị</li>
                        <li>Hệ điều hành</li>
                        <li>Thời gian truy cập website</li>
                        <li>Cookies và dữ liệu tương tự</li>
                    </ul>
                </div>

                <div class="data-category">
                    <h4>1.4. Thông tin từ CCTV:</h4>
                    <ul>
                        <li>Hình ảnh và video từ camera an ninh tại rạp</li>
                        <li>Thời gian ra vào rạp chiếu phim</li>
                        <li>Hoạt động tại các khu vực công cộng trong rạp</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2 -->
    <section id="section-collection" class="privacy-section">
        <h2 class="section-title">II. CÁCH THỨC THU THẬP THÔNG TIN</h2>
        <div class="section-content">
            <p>Chúng tôi thu thập thông tin của bạn thông qua các cách sau:</p>

            <ul>
                <li><strong>Đăng ký tài khoản:</strong> Khi bạn tạo tài khoản thành viên trên website hoặc ứng dụng</li>
                <li><strong>Đặt vé trực tuyến:</strong> Thông qua hệ thống đặt vé online</li>
                <li><strong>Mua vé tại quầy:</strong> Khi giao dịch trực tiếp tại rạp</li>
                <li><strong>Tham gia khuyến mãi:</strong> Khi đăng ký các chương trình ưu đãi</li>
                <li><strong>Liên hệ hỗ trợ:</strong> Qua email, điện thoại hoặc chat</li>
                <li><strong>Camera an ninh:</strong> Tự động ghi nhận khi bạn có mặt tại rạp</li>
                <li><strong>Website và ứng dụng:</strong> Thông qua cookies và công nghệ theo dõi</li>
            </ul>
        </div>
    </section>

    <!-- Section 3 -->
    <section id="section-cookies" class="privacy-section">
        <h2 class="section-title">III. SỬ DỤNG COOKIES VÀ CÔNG NGHỆ TƯƠNG TỰ</h2>
        <div class="section-content">
            <p>SE7ENCinema sử dụng cookies và các công nghệ tương tự để:</p>

            <ul>
                <li>Ghi nhớ thông tin đăng nhập và tùy chọn của bạn</li>
                <li>Cải thiện trải nghiệm sử dụng website</li>
                <li>Phân tích lưu lượng truy cập và hành vi người dùng</li>
                <li>Cung cấp quảng cáo và nội dung phù hợp</li>
                <li>Đảm bảo an ninh và ngăn chặn gian lận</li>
            </ul>

            <p>Bạn có thể quản lý cookies thông qua cài đặt trình duyệt. Tuy nhiên, việc tắt cookies có thể ảnh hưởng đến một số chức năng của website.</p>
        </div>
    </section>

    <!-- Section 4 -->
    <section id="section-cctv" class="privacy-section">
        <h2 class="section-title">IV. HỆ THỐNG CAMERA AN NINH (CCTV)</h2>
        <div class="section-content">
            <p>Tại các rạp chiếu phim SE7ENCinema, chúng tôi sử dụng hệ thống camera an ninh để:</p>

            <ul>
                <li><strong>Đảm bảo an ninh:</strong> Bảo vệ khách hàng, nhân viên và tài sản</li>
                <li><strong>Ngăn chặn tội phạm:</strong> Phòng chống trộm cắp, phá hoại</li>
                <li><strong>Giải quyết tranh chấp:</strong> Cung cấp bằng chứng khi cần thiết</li>
                <li><strong>Tuân thủ pháp luật:</strong> Hỗ trợ cơ quan chức năng khi có yêu cầu</li>
            </ul>

            <div class="important-notice">
                <p><strong>Lưu ý:</strong> Camera chỉ được lắp đặt tại các khu vực công cộng. Chúng tôi không ghi hình trong phòng chiếu, toilet hoặc các khu vực riêng tư.</p>
            </div>

            <p>Dữ liệu từ camera được lưu trữ trong thời gian tối đa 30 ngày và chỉ được truy cập bởi nhân viên được ủy quyền.</p>
        </div>
    </section>

    <!-- Section 5 -->
    <section id="section-children" class="privacy-section">
        <h2 class="section-title">V. BẢO VỆ THÔNG TIN TRẺ EM</h2>
        <div class="section-content">
            <p>SE7ENCinema đặc biệt chú trọng bảo vệ thông tin của trẻ em dưới 16 tuổi:</p>

            <ul>
                <li>Không cố ý thu thập thông tin cá nhân của trẻ em dưới 16 tuổi</li>
                <li>Yêu cầu sự đồng ý của cha mẹ/người giám hộ cho các giao dịch của trẻ em</li>
                <li>Sẽ xóa ngay thông tin nếu phát hiện thu thập từ trẻ em mà không có sự đồng ý</li>
                <li>Khuyến khích cha mẹ giám sát hoạt động trực tuyến của con em</li>
            </ul>

            <div class="important-notice">
                <p><strong>Nếu bạn là cha mẹ/người giám hộ và phát hiện con em mình đã cung cấp thông tin cho chúng tôi, vui lòng liên hệ ngay để chúng tôi xử lý.</strong></p>
            </div>
        </div>
    </section>

    <!-- Section 6 -->
    <section id="section-purpose" class="privacy-section">
        <h2 class="section-title">VI. MỤC ĐÍCH SỬ DỤNG THÔNG TIN</h2>
        <div class="section-content">
            <p>SE7ENCinema sử dụng thông tin cá nhân của bạn cho các mục đích sau:</p>

            <div class="purpose-categories">
                <div class="purpose-category">
                    <h4>6.1. Cung cấp dịch vụ:</h4>
                    <ul>
                        <li>Xử lý đặt vé và thanh toán</li>
                        <li>Quản lý tài khoản thành viên</li>
                        <li>Cung cấp dịch vụ khách hàng</li>
                        <li>Xử lý khiếu nại và hỗ trợ</li>
                    </ul>
                </div>

                <div class="purpose-category">
                    <h4>6.2. Marketing và truyền thông:</h4>
                    <ul>
                        <li>Gửi thông tin khuyến mãi và ưu đãi</li>
                        <li>Thông báo phim mới và sự kiện</li>
                        <li>Khảo sát ý kiến khách hàng</li>
                        <li>Chương trình tích điểm và thưởng</li>
                    </ul>
                </div>

                <div class="purpose-category">
                    <h4>6.3. Cải thiện dịch vụ:</h4>
                    <ul>
                        <li>Phân tích hành vi và sở thích khách hàng</li>
                        <li>Cải thiện website và ứng dụng</li>
                        <li>Phát triển sản phẩm và dịch vụ mới</li>
                        <li>Tối ưu hóa trải nghiệm khách hàng</li>
                    </ul>
                </div>

                <div class="purpose-category">
                    <h4>6.4. Tuân thủ pháp luật:</h4>
                    <ul>
                        <li>Thực hiện nghĩa vụ pháp lý</li>
                        <li>Phòng chống gian lận và tội phạm</li>
                        <li>Hỗ trợ cơ quan chức năng khi có yêu cầu</li>
                        <li>Giải quyết tranh chấp pháp lý</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 7 -->
    <section id="section-storage" class="privacy-section">
        <h2 class="section-title">VII. LƯU TRỮ VÀ BẢO MẬT THÔNG TIN</h2>
        <div class="section-content">
            <h3 class="subsection-title">7.1. Thời gian lưu trữ:</h3>
            <ul>
                <li><strong>Thông tin tài khoản:</strong> Cho đến khi bạn yêu cầu xóa tài khoản</li>
                <li><strong>Lịch sử giao dịch:</strong> 5 năm theo quy định pháp luật</li>
                <li><strong>Dữ liệu CCTV:</strong> Tối đa 30 ngày</li>
                <li><strong>Logs hệ thống:</strong> 12 tháng</li>
            </ul>

            <h3 class="subsection-title">7.2. Biện pháp bảo mật:</h3>
            <ul>
                <li>Mã hóa dữ liệu nhạy cảm bằng SSL/TLS</li>
                <li>Hệ thống tường lửa và chống xâm nhập</li>
                <li>Kiểm soát truy cập nghiêm ngặt</li>
                <li>Sao lưu dữ liệu định kỳ</li>
                <li>Đào tạo nhân viên về bảo mật thông tin</li>
                <li>Kiểm tra bảo mật định kỳ</li>
            </ul>

            <div class="important-notice">
                <p><strong>Mặc dù chúng tôi áp dụng các biện pháp bảo mật tốt nhất, không có hệ thống nào là hoàn toàn an toàn 100%. Chúng tôi khuyến khích bạn bảo vệ thông tin đăng nhập của mình.</strong></p>
            </div>
        </div>
    </section>

    <!-- Section 8 -->
    <section id="section-rights" class="privacy-section">
        <h2 class="section-title">VIII. QUYỀN CỦA KHÁCH HÀNG</h2>
        <div class="section-content">
            <p>Bạn có các quyền sau đối với thông tin cá nhân của mình:</p>

            <div class="rights-list">
                <h4>8.1. Quyền truy cập và chỉnh sửa:</h4>
                <ul>
                    <li>Xem thông tin cá nhân chúng tôi đang lưu trữ</li>
                    <li>Cập nhật, sửa đổi thông tin không chính xác</li>
                    <li>Yêu cầu sao chép dữ liệu cá nhân</li>
                </ul>
            </div>

            <div class="rights-list">
                <h4>8.2. Quyền xóa và hạn chế:</h4>
                <ul>
                    <li>Yêu cầu xóa thông tin cá nhân</li>
                    <li>Hạn chế việc xử lý dữ liệu</li>
                    <li>Rút lại sự đồng ý đã cung cấp</li>
                </ul>
            </div>

            <div class="rights-list">
                <h4>8.3. Quyền phản đối:</h4>
                <ul>
                    <li>Từ chối nhận email marketing</li>
                    <li>Phản đối việc xử lý dữ liệu cho mục đích marketing</li>
                    <li>Khiếu nại về việc xử lý thông tin</li>
                </ul>
            </div>

            <p><strong>Để thực hiện các quyền trên, vui lòng liên hệ với chúng tôi qua thông tin trong mục X.</strong></p>
        </div>
    </section>

    <!-- Section 9 -->
    <section id="section-sharing" class="privacy-section">
        <h2 class="section-title">IX. CHIA SẺ THÔNG TIN VỚI BÊN THỨ BA</h2>
        <div class="section-content">
            <p>SE7ENCinema chỉ chia sẻ thông tin của bạn trong các trường hợp sau:</p>

            <ul>
                <li><strong>Đối tác thanh toán:</strong> Ngân hàng, ví điện tử để xử lý giao dịch</li>
                <li><strong>Nhà cung cấp dịch vụ:</strong> Email marketing, SMS, analytics</li>
                <li><strong>Cơ quan pháp luật:</strong> Khi có yêu cầu hợp pháp từ cơ quan chức năng</li>
                <li><strong>Bảo vệ quyền lợi:</strong> Để bảo vệ quyền, tài sản và an toàn</li>
                <li><strong>Sáp nhập/mua bán:</strong> Trong trường hợp chuyển nhượng doanh nghiệp</li>
            </ul>

            <div class="important-notice">
                <p><strong>Chúng tôi không bán, cho thuê hoặc trao đổi thông tin cá nhân của bạn với bên thứ ba cho mục đích thương mại.</strong></p>
            </div>

            <p>Tất cả đối tác của chúng tôi đều phải ký cam kết bảo mật và tuân thủ các tiêu chuẩn bảo vệ dữ liệu nghiêm ngặt.</p>
        </div>
    </section>

    <!-- Section 10 -->
    <section id="section-contact" class="privacy-section">
        <h2 class="section-title">X. THÔNG TIN LIÊN HỆ</h2>
        <div class="section-content">
            <div class="contact-section">
                <h4>Nếu bạn có bất kỳ câu hỏi nào về chính sách bảo mật này, vui lòng liên hệ:</h4>

                <div class="company-info-box">
                    <h4>CÔNG TY TNHH SE7ENCINEMA</h4>
                    <p><strong>Địa chỉ:</strong> 29-13 Ng. 4 Đ. Vân Canh, Vân Canh, Từ Liêm, Hà Nội</p>
                    <p><strong>Điện thoại:</strong> 028.3636.5757</p>
                    <p><strong>Hotline:</strong> 1900 6017</p>
                    <p><strong>Email:</strong> privacy@se7encinema.com.vn</p>
                    <p><strong>Website:</strong> www.se7encinema.com.vn</p>
                </div>

                <div class="contact-methods">
                    <div class="contact-item">
                        <h5>Bộ phận Bảo mật Thông tin</h5>
                        <p><strong>Email:</strong> security@se7encinema.com.vn</p>
                        <p><strong>Thời gian xử lý:</strong> 7-15 ngày làm việc</p>
                    </div>
                    <div class="contact-item">
                        <h5>Chăm sóc Khách hàng</h5>
                        <p><strong>Email:</strong> support@se7encinema.com.vn</p>
                        <p><strong>Thời gian hỗ trợ:</strong> 8:00 - 22:00 hàng ngày</p>
                    </div>
                    <div class="contact-item">
                        <h5>Khiếu nại và Góp ý</h5>
                        <p><strong>Email:</strong> feedback@se7encinema.com.vn</p>
                        <p><strong>Phản hồi trong:</strong> 24-48 giờ</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class="privacy-footer">
        <div class="footer-note">
            <p><strong>Chính sách bảo mật này có hiệu lực từ ngày {{ date('d/m/Y') }}</strong></p>
            <p>SE7ENCinema có quyền cập nhật chính sách này bất cứ lúc nào. Mọi thay đổi sẽ được thông báo trên website và có hiệu lực ngay sau khi đăng tải.</p>
            <p><em>Phiên bản cập nhật cuối: {{ date('d/m/Y H:i') }}</em></p>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple smooth scrolling for sidebar links
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection
