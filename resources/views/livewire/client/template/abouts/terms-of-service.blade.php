@extends('clienttest')

@section('title', 'Điều khoản sử dụng - SE7ENCinema')

@section('content')
@assets
    @vite('resources/css/app.css')
@endassets

<div class="scRender">
    <div class="terms-page">
        <div class="container">
            <!-- Header Section -->
            <div class="privacy-breadcrumb">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="prs_title_heading_wrapper" style="padding-top:15px;">
                                <ul>
                                    <li><a href="{{ route('client.index') }}" style="color: black;"><i class="fas fa-home"></i></a></li>
                                    <li>&nbsp;&nbsp; >&nbsp;&nbsp; Điều khoản sử dụng</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="terms-content">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <!-- Table of Contents -->
                        <div class="terms-toc">
                            <h4 class="toc-title">Mục lục</h4>
                            <ul class="toc-list">
                                <li><a href="#section-general" class="toc-link">1. Quy định chung</a></li>
                                <li><a href="#section-tickets" class="toc-link">2. Quy định về vé</a></li>
                                <li><a href="#section-cinema" class="toc-link">3. Quy định tại rạp</a></li>
                                <li><a href="#section-food" class="toc-link">4. Thực phẩm & đồ uống</a></li>
                                <li><a href="#section-age-rating" class="toc-link">5. Phân loại phim theo độ tuổi</a></li>
                                <li><a href="#section-membership" class="toc-link">6. Khuyến mãi</a></li>
                                <li><a href="#section-online" class="toc-link">7. Dịch vụ trực tuyến</a></li>
                                <li><a href="#section-payment" class="toc-link">8. Thanh toán</a></li>
                                <li><a href="#section-refund" class="toc-link">9. Hoàn tiền</a></li>
                                <li><a href="#section-liability" class="toc-link">10. Trách nhiệm</a></li>
                                <li><a href="#section-contact" class="toc-link">11. Liên hệ</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-9 col-md-8">
                        <div class="terms-main-content">
                            <h1 class="privacy-title">Điều khoản sử dụng</h1>
                            <!-- Section 1: Quy định chung -->
                            <section id="section-general" class="terms-section">
                                <h2 class="section-title">1. QUY ĐỊNH CHUNG</h2>
                                <div class="section-content">
                                    <div class="welcome-box">
                                        <p>Chào mừng bạn đến với <strong>SE7ENCinema</strong>! Khi sử dụng dịch vụ của chúng tôi, bạn đồng ý tuân thủ các điều khoản và quy định sau đây.</p>
                                    </div>

                                    <div class="rule-item">
                                        <h4>1.1. Phạm vi áp dụng</h4>
                                        <ul>
                                            <li>Các quy định này áp dụng cho tất cả khách hàng sử dụng dịch vụ tại SE7ENCinema</li>
                                            <li>Bao gồm: mua vé, sử dụng dịch vụ tại rạp, website</li>
                                            <li>SE7ENCinema có quyền thay đổi quy định mà không cần thông báo trước</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>1.2. Độ tuổi sử dụng dịch vụ</h4>
                                        <ul>
                                            <li>Khách hàng dưới 16 tuổi cần có sự đồng ý của phụ huynh/người giám hộ</li>
                                            <li>SE7ENCinema có quyền yêu cầu xuất trình giấy tờ tùy thân để xác minh độ tuổi</li>
                                            <li>Phụ huynh chịu trách nhiệm giám sát việc sử dụng dịch vụ của trẻ em</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>1.3. Quyền và nghĩa vụ</h4>
                                        <ul>
                                            <li>Khách hàng có quyền được phục vụ theo đúng tiêu chuẩn dịch vụ</li>
                                            <li>Khách hàng có nghĩa vụ tuân thủ các quy định của rạp chiếu phim</li>
                                            <li>Tôn trọng nhân viên và khách hàng khác</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 2: Quy định về vé -->
                            <section id="section-tickets" class="terms-section">
                                <h2 class="section-title">2. QUY ĐỊNH VỀ VÉ</h2>
                                <div class="section-content">
                                    <div class="rule-item">
                                        <h4>2.1. Mua vé</h4>
                                        <ul>
                                            <li>Vé có thể được mua tại quầy, website hoặc máy bán vé tự động</li>
                                            <li>Giá vé có thể thay đổi theo thời gian, ngày trong tuần và các chương trình khuyến mãi</li>
                                            <li>Khách hàng cần kiểm tra thông tin vé trước khi thanh toán</li>
                                            <li>Vé đã mua không thể đổi trả, trừ trường hợp đặc biệt</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>2.2. Sử dụng vé</h4>
                                        <ul>
                                            <li>Vé chỉ có giá trị cho suất chiếu, ngày giờ và ghế được ghi trên vé</li>
                                            <li>Khách hàng cần có mặt tại rạp trước giờ chiếu ít nhất 15 phút</li>
                                            <li>Vé điện tử cần được xuất trình cùng với giấy tờ tùy thân</li>
                                            <li>Không được phép chuyển nhượng vé cho mục đích thương mại</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>2.3. Vé nhóm và vé ưu đãi</h4>
                                        <ul>
                                            <li>Vé nhóm áp dụng cho từ 10 người trở lên với giá ưu đãi</li>
                                            <li>Vé học sinh, sinh viên cần xuất trình thẻ học sinh/sinh viên hợp lệ</li>
                                            <li>Vé người cao tuổi áp dụng cho khách từ 60 tuổi trở lên</li>
                                            <li>Không áp dụng đồng thời nhiều chương trình ưu đãi</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 3: Quy định tại rạp -->
                            <section id="section-cinema" class="terms-section">
                                <h2 class="section-title">3. QUY ĐỊNH TẠI RẠP</h2>
                                <div class="section-content">
                                    <div class="rule-item">
                                        <h4>3.1. Hành vi được phép</h4>
                                        <ul>
                                            <li>Giữ gìn vệ sinh chung và trật tự tại rạp</li>
                                            <li>Tắt hoặc chuyển điện thoại sang chế độ im lặng</li>
                                            <li>Tuân thủ hướng dẫn của nhân viên rạp</li>
                                            <li>Báo cáo ngay cho nhân viên khi phát hiện sự cố</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>3.2. Hành vi bị cấm</h4>
                                        <div class="prohibited-grid">
                                            <div class="prohibited-item">
                                                <div class="prohibited-icon">🚫</div>
                                                <p>Hút thuốc, sử dụng chất kích thích</p>
                                            </div>
                                            <div class="prohibited-item">
                                                <div class="prohibited-icon">📱</div>
                                                <p>Sử dụng điện thoại trong phòng chiếu</p>
                                            </div>
                                            <div class="prohibited-item">
                                                <div class="prohibited-icon">📹</div>
                                                <p>Quay phim, chụp ảnh màn hình</p>
                                            </div>
                                            <div class="prohibited-item">
                                                <div class="prohibited-icon">🔊</div>
                                                <p>Gây ồn ào, làm phiền khách khác</p>
                                            </div>
                                            <div class="prohibited-item">
                                                <div class="prohibited-icon">🍕</div>
                                                <p>Mang thức ăn từ bên ngoài vào</p>
                                            </div>
                                            <div class="prohibited-item">
                                                <div class="prohibited-icon">⚔️</div>
                                                <p>Mang vũ khí, vật nguy hiểm</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rule-item">
                                        <h4>3.3. Xử lý vi phạm</h4>
                                        <ul>
                                            <li>Nhắc nhở lần đầu: Nhân viên sẽ lịch sự nhắc nhở</li>
                                            <li>Vi phạm lần 2: Yêu cầu rời khỏi phòng chiếu</li>
                                            <li>Vi phạm nghiêm trọng: Liên hệ cơ quan chức năng</li>
                                            <li>Không hoàn tiền trong trường hợp bị đuổi khỏi rạp do vi phạm</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 4: Thực phẩm & đồ uống -->
                            <section id="section-food" class="terms-section">
                                <h2 class="section-title">4. THỰC PHẨM & ĐỒ UỐNG</h2>
                                <div class="section-content">
                                    <div class="rule-item">
                                        <h4>4.1. Quy định chung</h4>
                                        <ul>
                                            <li>Chỉ được phép sử dụng thực phẩm, đồ uống mua tại SE7ENCinema</li>
                                            <li>Không được mang thức ăn, đồ uống từ bên ngoài vào rạp</li>
                                            <li>Đồ uống có cồn bị cấm hoàn toàn tại rạp</li>
                                            <li>Thực phẩm cần được sử dụng đúng nơi quy định</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>4.2. Combo và ưu đãi</h4>
                                        <ul>
                                            <li>Combo có thời hạn sử dụng, không được hoàn tiền</li>
                                            <li>Ưu đãi thành viên áp dụng theo quy định riêng</li>
                                            <li>Không áp dụng đồng thời nhiều chương trình khuyến mãi</li>
                                            <li>Giá có thể thay đổi mà không cần thông báo trước</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>4.3. Vệ sinh an toàn thực phẩm</h4>
                                        <ul>
                                            <li>SE7ENCinema cam kết đảm bảo chất lượng thực phẩm</li>
                                            <li>Khách hàng cần kiểm tra hạn sử dụng trước khi tiêu dùng</li>
                                            <li>Báo ngay cho nhân viên nếu phát hiện bất thường</li>
                                            <li>Không sử dụng thực phẩm đã quá hạn hoặc có dấu hiệu hỏng</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 5: Phân loại phim theo độ tuổi -->
                            <section id="section-age-rating" class="terms-section">
                                <h2 class="section-title">5. PHÂN LOẠI PHIM THEO ĐỘ TUỔI</h2>
                                <div class="section-content">
                                    <div class="rule-item">
                                        <h4>5.1. Phân loại phim</h4>
                                        <p>SE7ENCinema Việt Nam thông báo tiêu chí phân loại phim theo lứa tuổi như sau:</p>

                                        <div class="age-rating-table">
                                            <div class="rating-item rating-p">
                                                <div class="rating-badge">P</div>
                                                <div class="rating-content">
                                                    <h5>Phim dành cho mọi lứa tuổi</h5>
                                                    <p>Phim được phép phổ biến đến người xem ở mọi độ tuổi.</p>
                                                </div>
                                            </div>

                                            <div class="rating-item rating-k">
                                                <div class="rating-badge">K</div>
                                                <div class="rating-content">
                                                    <h5>Phim dành cho trẻ em có người bảo hộ</h5>
                                                    <p>Phim được phổ biến đến người xem dưới 13 tuổi và có người bảo hộ đi kèm.</p>
                                                </div>
                                            </div>

                                            <div class="rating-item rating-t13">
                                                <div class="rating-badge">T13</div>
                                                <div class="rating-content">
                                                    <h5>Phim dành cho khán giả từ 13 tuổi trở lên</h5>
                                                    <p>Phim được phổ biến đến người xem từ đủ 13 tuổi trở lên (13+).</p>
                                                </div>
                                            </div>

                                            <div class="rating-item rating-t16">
                                                <div class="rating-badge">T16</div>
                                                <div class="rating-content">
                                                    <h5>Phim dành cho khán giả từ 16 tuổi trở lên</h5>
                                                    <p>Phim được phổ biến đến người xem từ đủ 16 tuổi trở lên (16+).</p>
                                                </div>
                                            </div>

                                            <div class="rating-item rating-t18">
                                                <div class="rating-badge">T18</div>
                                                <div class="rating-content">
                                                    <h5>Phim dành cho khán giả từ 18 tuổi trở lên</h5>
                                                    <p>Phim được phổ biến đến người xem từ đủ 18 tuổi trở lên (18+).</p>
                                                </div>
                                            </div>

                                            <div class="rating-item rating-c">
                                                <div class="rating-badge">C</div>
                                                <div class="rating-content">
                                                    <h5>Phim bị cấm chiếu</h5>
                                                    <p>Phim không được phép phổ biến.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rule-item">
                                        <h4>5.2. Lưu ý quan trọng</h4>
                                        <ul>
                                            <li>Quý Khách Hàng xem phim được phân loại T13, T16, T18 vui lòng mang theo giấy tờ tùy thân có ảnh nhận diện và ngày tháng năm sinh để đảm bảo việc tuân thủ theo quy định.</li>
                                            <li>SE7ENCinema có quyền yêu cầu khách hàng xuất trình Giấy khai sinh, Căn cước công dân, Thẻ học sinh, thẻ sinh viên, bằng lái xe, hoặc các giấy tờ tùy thân khác để xác định độ tuổi Quý Khách Hàng.</li>
                                            <li>Ban Quản Lý Cụm Rạp Chiếu Phim SE7ENCinema có quyền kiểm tra và từ chối khách hàng nếu không đúng quy định về độ tuổi.</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item penalty-notice">
                                        <h4>5.3. Chế tài</h4>
                                        <div class="penalty-warning">
                                            <div class="penalty-icon">⚠️</div>
                                            <div class="penalty-content">
                                                <p><strong>Phạt tiền từ 60.000.000 đồng đến 80.000.000 đồng</strong> đối với hành vi không đảm bảo người xem phim đúng độ tuổi theo phân loại phim.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 6: Khuyến mãi -->
                            <section id="section-promotion" class="terms-section">
                                <h2 class="section-title">6. KHUYẾN MÃI</h2>
                                <div class="section-content">
                                    <div class="rule-item">
                                        <h4>6.1. Quy định chung</h4>
                                        <ul>
                                            <li>Mỗi chương trình khuyến mãi có điều kiện áp dụng riêng</li>
                                            <li>Không áp dụng đồng thời nhiều chương trình ưu đãi</li>
                                            <li>SE7ENCinema có quyền thay đổi hoặc kết thúc chương trình bất kỳ lúc nào</li>
                                            <li>Ưu đãi không có giá trị quy đổi thành tiền mặt</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>6.2. Các loại khuyến mãi</h4>
                                        <div class="promotion-types">
                                            <div class="promo-item">
                                                <h5>🎫 Ưu đãi vé</h5>
                                                <p>Giảm giá vé theo ngày, giờ chiếu hoặc đối tượng khách hàng</p>
                                            </div>
                                            <div class="promo-item">
                                                <h5>🍿 Combo ưu đãi</h5>
                                                <p>Giá đặc biệt cho combo bắp nước, thức ăn nhanh</p>
                                            </div>
                                            <div class="promo-item">
                                                <h5>🎁 Quà tặng</h5>
                                                <p>Tặng kèm merchandise, poster phim hoặc voucher</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rule-item">
                                        <h4>6.3. Điều kiện sử dụng</h4>
                                        <ul>
                                            <li>Xuất trình đầy đủ giấy tờ chứng minh điều kiện ưu đãi</li>
                                            <li>Sử dụng trong thời hạn quy định</li>
                                            <li>Không chuyển nhượng cho người khác</li>
                                            <li>Tuân thủ số lượng giới hạn (nếu có)</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 7: Dịch vụ trực tuyến -->
                            <section id="section-online" class="terms-section">
                                <h2 class="section-title">7. DỊCH VỤ TRỰC TUYẾN</h2>
                                <div class="section-content">
                                    <div class="rule-item">
                                        <h4>7.1. Website</h4>
                                        <ul>
                                            <li>Đăng ký tài khoản với thông tin chính xác</li>
                                            <li>Bảo mật thông tin đăng nhập</li>
                                            <li>Không sử dụng tài khoản cho mục đích bất hợp pháp</li>
                                            <li>Báo cáo ngay khi phát hiện tài khoản bị xâm nhập</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>7.2. Đặt vé trực tuyến</h4>
                                        <ul>
                                            <li>Kiểm tra kỹ thông tin trước khi thanh toán</li>
                                            <li>Hoàn tất thanh toán trong thời gian quy định (15 phút)</li>
                                            <li>Nhận vé điện tử qua email hoặc SMS</li>
                                            <li>Xuất trình vé điện tử và CMND/CCCD khi vào rạp</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>7.3. Bảo mật thông tin</h4>
                                        <ul>
                                            <li>SE7ENCinema cam kết bảo mật thông tin khách hàng</li>
                                            <li>Không chia sẻ thông tin cho bên thứ ba không liên quan</li>
                                            <li>Sử dụng công nghệ mã hóa SSL cho giao dịch</li>
                                            <li>Khách hàng cần bảo vệ thông tin cá nhân</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 8: Thanh toán -->
                            <section id="section-payment" class="terms-section">
                                <h2 class="section-title">8. THANH TOÁN</h2>
                                <div class="section-content">
                                    <div class="rule-item">
                                        <h4>8.1. Phương thức thanh toán</h4>
                                        <div class="payment-methods">
                                            <div class="payment-item">
                                                <div class="payment-icon">💵</div>
                                                <h5>Tiền mặt</h5>
                                                <p>Thanh toán tại quầy rạp</p>
                                            </div>
                                            <div class="payment-item">
                                                <div class="payment-icon">📱</div>
                                                <h5>Ví điện tử</h5>
                                                <p>MoMo, ZaloPay, VNPay</p>
                                            </div>
                                            <div class="payment-item">
                                                <div class="payment-icon">🏦</div>
                                                <h5>Chuyển khoản</h5>
                                                <p>Internet Banking, QR Code</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rule-item">
                                        <h4>8.2. Quy định thanh toán</h4>
                                        <ul>
                                            <li>Thanh toán đầy đủ trước khi nhận vé/dịch vụ</li>
                                            <li>Kiểm tra hóa đơn, biên lai trước khi rời quầy</li>
                                            <li>Giữ lại hóa đơn để đổi trả (nếu cần)</li>
                                            <li>Phí giao dịch (nếu có) do khách hàng chịu</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>8.3. Bảo mật giao dịch</h4>
                                        <ul>
                                            <li>Không chia sẻ thông tin thẻ, mã PIN cho người khác</li>
                                            <li>Kiểm tra kỹ số tiền trước khi xác nhận</li>
                                            <li>Báo ngay cho ngân hàng nếu phát hiện giao dịch bất thường</li>
                                            <li>SE7ENCinema không chịu trách nhiệm với giao dịch không được ủy quyền</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 9: Hoàn tiền -->
                            <section id="section-refund" class="terms-section">
                                <h2 class="section-title">9. HOÀN TIỀN</h2>
                                <div class="section-content">
                                    <div class="rule-item">
                                        <h4>9.1. Trường hợp được hoàn tiền</h4>
                                        <ul>
                                            <li>Rạp hủy suất chiếu do sự cố kỹ thuật</li>
                                            <li>Phim bị cắt, thay đổi nội dung đáng kể</li>
                                            <li>Lỗi từ phía SE7ENCinema trong quá trình bán vé</li>
                                            <li>Khách hàng yêu cầu hủy vé trước giờ chiếu 2 tiếng (phí 10%)</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>9.2. Trường hợp không được hoàn tiền</h4>
                                        <ul>
                                            <li>Khách hàng đến muộn hoặc không đến xem</li>
                                            <li>Thay đổi ý định cá nhân</li>
                                            <li>Mua nhầm suất chiếu, ghế ngồi</li>
                                            <li>Vi phạm quy định rạp bị đuổi ra ngoài</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>9.3. Quy trình hoàn tiền</h4>
                                        <ul>
                                            <li>Liên hệ bộ phận chăm sóc khách hàng trong 24h</li>
                                            <li>Cung cấp đầy đủ thông tin vé, hóa đơn</li>
                                            <li>Thời gian xử lý: 3-7 ngày làm việc</li>
                                            <li>Hoàn tiền về tài khoản/phương thức thanh toán gốc</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 10: Trách nhiệm -->
                            <section id="section-liability" class="terms-section">
                                <h2 class="section-title">10. TRÁCH NHIỆM</h2>
                                <div class="section-content">
                                    <div class="rule-item">
                                        <h4>10.1. Trách nhiệm của SE7ENCinema</h4>
                                        <ul>
                                            <li>Cung cấp dịch vụ đúng chất lượng cam kết</li>
                                            <li>Đảm bảo an toàn, vệ sinh tại rạp chiếu phim</li>
                                            <li>Bảo mật thông tin khách hàng</li>
                                            <li>Hỗ trợ khách hàng khi có sự cố, khiếu nại</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>10.2. Trách nhiệm của khách hàng</h4>
                                        <ul>
                                            <li>Tuân thủ các quy định của rạp</li>
                                            <li>Giữ gìn tài sản, vệ sinh chung</li>
                                            <li>Cung cấp thông tin chính xác khi đăng ký</li>
                                            <li>Thanh toán đầy đủ, đúng hạn</li>
                                        </ul>
                                    </div>

                                    <div class="rule-item">
                                        <h4>10.3. Giới hạn trách nhiệm</h4>
                                        <ul>
                                            <li>SE7ENCinema không chịu trách nhiệm với tài sản cá nhân bị mất</li>
                                            <li>Không bồi thường thiệt hại gián tiếp</li>
                                            <li>Trách nhiệm tối đa bằng giá trị dịch vụ đã thanh toán</li>
                                            <li>Không chịu trách nhiệm với hành vi của khách hàng khác</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- Section 11: Liên hệ -->
                            <section id="section-contact" class="terms-section">
                                <h2 class="section-title">11. LIÊN HỆ</h2>
                                <div class="section-content">
                                    <div class="contact-info">
                                        <div class="contact-methods">
                                            <div class="contact-method">
                                                <div class="contact-icon">📞</div>
                                                <div class="contact-details">
                                                    <h4>Hotline</h4>
                                                    <p><strong>1900 6017</strong></p>
                                                    <p>Phục vụ 24/7</p>
                                                </div>
                                            </div>
                                            <div class="contact-method">
                                                <div class="contact-icon">📧</div>
                                                <div class="contact-details">
                                                    <h4>Email</h4>
                                                    <p><strong>support@se7encinema.com.vn</strong></p>
                                                    <p>Phản hồi trong 24h</p>
                                                </div>
                                            </div>
                                            <div class="contact-method">
                                                <div class="contact-icon">🏢</div>
                                                <div class="contact-details">
                                                    <h4>Văn phòng</h4>
                                                    <p>29-13 Ng. 4 Đ. Vân Canh, </p>
                                                    <p>Vân Canh, Từ Liêm, Hà Nội</p>
                                                </div>
                                            </div>
                                            <div class="contact-method">
                                                <div class="contact-icon">💬</div>
                                                <div class="contact-details">
                                                    <h4>Live Chat</h4>
                                                    <p>Website: se7encinema.com.vn</p>
                                                    <p>Ứng dụng SE7ENCinema</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="company-info">
                                        <h4>Thông tin công ty:</h4>
                                        <div class="company-details">
                                            <p><strong>CÔNG TY TNHH SE7ENCINEMA</strong></p>
                                            <p>MST: 0305675165</p>
                                            <p>Giấy phép kinh doanh số: 0305675165</p>
                                            <p>Ngày cấp: 15/04/2025</p>
                                            <p>Nơi cấp: Sở Kế hoạch và Đầu tư Hà Nội</p>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Footer note -->
                            <div class="terms-footer">
                                <div class="footer-note">
                                    <p><strong>Điều khoản sử dụng này có hiệu lực từ ngày {{ date('d/m/Y') }}</strong></p>
                                    <p>SE7ENCinema có quyền thay đổi, bổ sung các điều khoản mà không cần thông báo trước. Khách hàng có trách nhiệm cập nhật thông tin thường xuyên.</p>
                                    <p>Bằng việc sử dụng dịch vụ, bạn đồng ý tuân thủ toàn bộ các điều khoản trên.</p>
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
    // Smooth scrolling for table of contents links
    const tocLinks = document.querySelectorAll('.toc-link');
    tocLinks.forEach(link => {
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

    // Highlight active section in table of contents
    const sections = document.querySelectorAll('.terms-section');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                tocLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${id}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }, {
        rootMargin: '-20% 0px -70% 0px'
    });

    sections.forEach(section => {
        observer.observe(section);
    });
});
</script>
@endsection
