@extends('components.layouts.client')

@section('title', 'Câu hỏi thường gặp - SE7ENCinema')

@push('styles')
@vite('resources/css/faq.css')
@endpush

@section('content')

<div class="scRender scFaq">
    <div class="faq">
        <!-- Breadcrumb -->
        <div class="faq__breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="faq__title-heading" style="padding-top:20px;">
                            <ul>
                                <li><a href="{{ route('client.index') }}"><i class="fas fa-home"></i></a></li>
                                <li>Câu hỏi thường gặp</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="faq__content container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-4 faq__sidebar">
                    <div class="faq__sidebar-menu">
                        <h3 class="faq__sidebar-title">Danh mục</h3>
                        <ul>
                            <li><a class="faq__sidebar-link" href="#section-food">Đồ ăn & thức uống</a></li>
                            <li><a class="faq__sidebar-link" href="#section-booking">Đặt vé online</a></li>
                            <li><a class="faq__sidebar-link" href="#section-recording">Chụp hình & ghi âm</a></li>
                            <li><a class="faq__sidebar-link" href="#section-discount">Chính sách giảm giá</a></li>
                            <li><a class="faq__sidebar-link" href="#section-combo">Combo & ưu đãi</a></li>
                            <li><a class="faq__sidebar-link" href="#section-rules">Quy định xem phim</a></li>
                            <li><a class="faq__sidebar-link" href="#section-seats">Vị trí ghế</a></li>
                            <li><a class="faq__sidebar-link" href="#section-pets">Thú cưng & hút thuốc</a></li>
                            <li><a class="faq__sidebar-link" href="#section-refund">Hoàn tiền & đổi vé</a></li>
                            <li><a class="faq__sidebar-link" href="#section-censorship">Kiểm duyệt phim</a></li>
                            <li><a class="faq__sidebar-link" href="#section-formats">Định dạng phim</a></li>
                            <li><a class="faq__sidebar-link" href="#section-payment">Thanh toán online</a></li>
                            <li><a class="faq__sidebar-link" href="#section-contact">Liên hệ hỗ trợ</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-md-8">
                    <div class="faq__main-content">
                        <h1 class="faq__title">Câu hỏi thường gặp (FAQ)</h1>

                        <!-- Food & Drinks Section -->
                        <section id="section-food" class="faq__section">
                            <h2 class="faq__section-title">Đồ ăn & thức uống</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Tôi có được mang đồ ăn từ bên ngoài vào không?</h3>
                                    <div class="faq__answer">
                                        <p>Nhằm đảm bảo chất lượng phục vụ bao gồm vệ sinh an toàn thực phẩm và tránh gây nhầm lẫn về đồ ăn bên ngoài và được bán ở rạp, quý khách vui lòng gửi đồ ăn tại quầy Con hoặc tiêu dùng hết trước khi vào bộ phận soát vé.</p>
                                        <div class="faq__important-notice">
                                            <p><strong>SE7ENCinema rất cám ơn sự hợp tác của quý khách.</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Online Booking Section -->
                        <section id="section-booking" class="faq__section">
                            <h2 class="faq__section-title">Đặt vé online</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Hướng dẫn đặt vé online</h3>
                                    <div class="faq__answer">
                                        <div class="faq__benefits">
                                            <h4>💓 Cùng điểm qua vài lợi ích khi đặt vé online nhé!</h4>
                                            <ul>
                                                <li>🔸 Được ở nhà nằm điều hòa, chọn sương sương chục bộ phim và ghế ngồi trước khi Quy ết định đặt mà không lo người phía sau phải đợi chờ.</li>
                                                <li>🔸 Không phải xếp hàng dài như sông Volga để đợi mua vé xem phim bom tấn mà vẫn nơm nớp lo hết vé.</li>
                                                <li>🔸 Được giảm đến 10.000 đ khi mua combo bỏng+nước online. 🍿🍿</li>
                                            </ul>
                                        </div>

                                        <div class="faq__steps">
                                            <h4>Còn chần gì nữa mà không làm theo hướng dẫn sau đây để đặt vé online một cách nhanh nhất nào:</h4>

                                            <div class="faq__step">
                                                <h5>Bước 1: Đăng nhập tài khoản thành viên</h5>
                                                <p>Nếu Quý khách chưa có tài khoản, vui lòng điền thông tin đăng ký <a href="{{ route('login') }}">TẠI ĐÂY!</a></p>
                                            </div>

                                            <div class="faq__step">
                                                <h5>Bước 2: Chọn phim muốn xem, tiếp tục chọn suất chiếu và ghế</h5>
                                                <p>Quý khách có thể chon phim muốn xem, tiếp tục chọn suất chiếu và ghế trên websile SE7ENCinema.</p>
                                            </div>

                                            <div class="faq__step">
                                                <h5>Bước 3: Kiểm tra lại thông tin đặt vé trước khi thanh toán</h5>
                                                <p><strong>Lưu ý:</strong> Vé đã thanh toán thành công sẽ không được đổi trả hay hoàn lại. Quý khách vui lòng check kỹ các thông tin sau:</p>
                                                <ul>
                                                    {{-- <li>Cụm rạp muốn xem</li> --}}
                                                    <li>Thời gian suất chiếu và vị trí ghế ngồi</li>
                                                    <li>Lưu ý về độ tuổi Quy  định của phim</li>
                                                </ul>
                                            </div>

                                            <div class="faq__step">
                                                <h5>Bước 4: Xác nhận thanh toán</h5>
                                                <p>Khách hàng có thể lựa chọn các hình thức thanh toán sau:</p>
                                                <ul>
                                                    {{-- <li>Điểm tích lũy thành viên</li> --}}
                                                    <li>Thanh toán bằng Ví điện tử MoMo</li>
                                                    {{-- <li>Thẻ ATM (Thẻ ghi nợ/thanh toán/trả trước nội địa)</li> --}}
                                                    {{-- <li>Thẻ tín dụng thẻ, thẻ ghi nợ, thẻ trả trước quốc tế</li> --}}
                                                </ul>
                                                <p>Hiện tại các giá vé ưu đãi áp dụng cho học sinh, sinh viên, người cao tuổi, trẻ em chưa thể được áp dụng trên hệ thống bán vé online. Nếu muốn sử dụng các loại ưu đãi này, Quý khách vui lòng tới mua vé tại quầy BOX OFFICE tại rạp SE7ENCinema nhé!</p>
                                            </div>

                                            <div class="faq__step">
                                                <h5>Bước 5: Nhận thông tin đặt vé thành công</h5>
                                                <p>Sau khi xác nhận thanh toán thành công, thông tin vé đã đặt sẽ được gửi về qua email của Quý Khách.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Recording Section -->
                        <section id="section-recording" class="faq__section">
                            <h2 class="faq__section-title">Chụp hình & ghi âm</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Vấn đề chụp hình, ghi âm tại rạp?</h3>
                                    <div class="faq__answer">
                                        <p>Việc quay phim, chụp hình trong phòng chiếu là vi phạm Luật sở hữu trí tuệ của nước CHXH CN Việt Nam, theo khung xử phạt hành chính lên đến 35.000.000 VNĐ.</p>
                                        <div class="faq__warning">
                                            <p><strong>⚠️ Lưu ý:</strong> SE7ENCinema nghiêm cấm mọi hành vi quay phim, chụp ảnh trong phòng chiếu để bảo vệ Quy ền sở hữu trí tuệ.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Discount Section -->
                        <section id="section-discount" class="faq__section">
                            <h2 class="faq__section-title">Chính sách giảm giá</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Chính sách giảm giá cho HSSV, trẻ em và người già?</h3>
                                    <div class="faq__answer">
                                        <p>SE7ENCinema áp dụng giá vé ưu đãi giành cho các đối tượng như trên. Chi tiết các bạn có thể liên hệ quầy vé.</p>

                                        <div class="faq__discount-details">
                                            <h4>Chi tiết chính sách:</h4>
                                            <ul>
                                                <li><strong>Trẻ em dưới 0,7m:</strong> Miễn phí vé hoàn toàn. Bé sẽ ngồi chung ghế với bố mẹ khi xem phim.</li>
                                                <li><strong>Trẻ em cao từ 0,7m đến 1,3m:</strong> Áp dụng giá vé trẻ em</li>
                                                <li><strong>Người cao tuổi từ 55 tuổi trở lên:</strong> Vui lòng xuất trình CMND khi mua vé</li>
                                                <li><strong>Đối với sinh viên học sinh:</strong> Vui lòng xuất trình Thẻ HSSV hoặc CMND dưới 22 tuổi khi mua vé. Mỗi thẻ chỉ được áp dụng trên 1 vé.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Combo Section -->
                        <section id="section-combo" class="faq__section">
                            <h2 class="faq__section-title">Combo & ưu đãi</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">SE7ENCinema: Việc mua combo ở Quầy vé có lợi gì?</h3>
                                    <div class="faq__answer">
                                        <p>Combo là một bộ bao gồm đồ ăn và nước uống.</p>
                                        <div class="faq__combo-benefits">
                                            <h4>Lợi ích khi mua combo:</h4>
                                            <ul>
                                                <li>Đối với các khách hàng mua vé kèm combo bỏng nước tại quầy vé sẽ được giảm 5k/combo</li>
                                                <li>Đối với các khách hàng mua vé online qua app SE7ENCinema hoặc Website thì khi mua thêm combo bỏng nước sẽ được giảm 10k/combo</li>
                                                <li>Mua combo kèm vé sẽ tiết kiệm thời gian giao dịch 1 lần, khách hàng chỉ cần cầm phiếu đổi sang quầy Con đổi mà không phải xếp hàng thanh toán</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Rules Section -->
                        <section id="section-rules" class="faq__section">
                            <h2 class="faq__section-title">Quy  định xem phim</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">"Quy định khi xem phim" là gì?</h3>
                                    <div class="faq__answer">
                                        <p>"Quy định khi xem phim" áp dụng khi các hãng phim hay nhà phát hành bộ phim yêu cầu khán giả không sử dụng các thiết bị ghi âm điện tử (máy ảnh, điện thoại di động, máy tính, ...) trong phòng chiếu phim. Các thiết bị điện tử của bạn sẽ được cất trữ an toàn và gửi lại bạn sau khi bộ phim kết thúc. Quy định này thường áp dụng cho các tuần khởi chiếu của phim.</p>

                                        <div class="faq__3d-notice">
                                            <h4>Lưu ý về kính 3D:</h4>
                                            <p>Khi xem phim 3D, bạn cần phải giữ kính 3D cẩn thận. Trong trường hợp làm mất mát hoặc hư hỏng kính, bạn sẽ phải đền bù. Do đó, khi nhận kính 3D từ nhân viên SE7ENCinema trước khi vào rạp chiếu phim, các bạn vui lòng kiểm tra kính cẩn thận.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Seats Section -->
                        <section id="section-seats" class="faq__section">
                            <h2 class="faq__section-title">Vị trí ghế</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Các vị trí ghế có gì khác nhau?</h3>
                                    <div class="faq__answer">
                                        <p>Hiện tại rạp có 3 loại ghế trong một phòng, trong đó:</p>

                                        <div class="faq__seat-types">
                                            <div class="faq__seat-item">
                                                <h5>Ghế đôi</h5>
                                                <p>SE7ENCinema được biết đến với hệ thống ghế đôi, hay còn gọi là "Ghế Tình Nhân" đặc biệt. Hãy tận hưởng cảm giác thoải mái và ấm cúng cùng người ấy với ghế đôi của SE7ENCinema. Tuyệt hơn nữa, ghế đôi được đặt ở vị trí rất thuận lợi để hai bạn có thể thưởng thức những thước phim hay với tầm nhìn và vị thế đẹp nhất.</p>
                                            </div>
                                            <div class="faq__seat-item">
                                                <h5>Ghế VIP</h5>
                                                <p>Là ghế ở khu vực trung tâm của rạp chiếu phim, là vị trí khá là tốt để bạn có thể thưởng thức trọn vẹn bộ phim mà mình yêu thích.</p>
                                            </div>
                                            <div class="faq__seat-item">
                                                <h5>Ghế thường</h5>
                                                <p>Là ghế ở khu vực phía trước, gần màn hình hơn</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Pets Section -->
                        <section id="section-pets" class="faq__section">
                            <h2 class="faq__section-title">Thú cưng & hút thuốc</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Tại sao không được mang thú cưng vào rạp cũng như hút thuốc trong rạp?</h3>
                                    <div class="faq__answer">
                                        <p>Để đảm bảo vệ sinh và sức khỏe cho cộng đồng, các bạn vui lòng không mang thú cưng và hút thuốc vào trong rạp.</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Refund Section -->
                        <section id="section-refund" class="faq__section">
                            <h2 class="faq__section-title">Hoàn tiền & đổi vé</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Tôi có được hoàn lại tiền vé hoặc thay đổi suất chiếu?</h3>
                                    <div class="faq__answer">
                                        <p>Vé đã mua rồi không thể hủy hoặc thay đổi.</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Censorship Section -->
                        <section id="section-censorship" class="faq__section">
                            <h2 class="faq__section-title">Kiểm duyệt phim</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Trước khi trình chiếu ở Việt Nam, các bộ phim được kiểm duyệt thế nào?</h3>
                                    <div class="faq__answer">
                                        <p>Tất cả những phim được trình chiếu tại các rạp chiếu phim ở Việt Nam phải được kiểm duyệt, sau đó được cấp giấy phép phát hành và phạm vi phổ biến phim bởi Cục Điện Ảnh thuộc Bộ Văn Hóa, Thể Thao và Du Lịch Việt Nam. Do đó, một số cảnh quay có thể được yêu cầu phải cắt bỏ bởi Cục Điện Ảnh để phù hợp với văn hóa của Việt Nam trước khi lưu hành. Tuy nhiên, không ngoại trừ một số phim sẽ không được cấp phép phát hành tại Việt Nam.</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Formats Section -->
                        <section id="section-formats" class="faq__section">
                            <h2 class="faq__section-title">Định dạng phim</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Các định dạng phim khác nhau chỗ nào? Tôi nên lựa chọn phim sao cho hợp lý?</h3>
                                    <div class="faq__answer">
                                        <p>Nhiều phim bom tấn mới ra mắt dưới nhiều định dạng khác nhau cho khán giả lựa chọn.</p>

                                        <div class="faq__format-types">
                                            <div class="faq__format-item">
                                                <h5>2D Digital</h5>
                                                <p>Phim được chiếu ở định dạng 2D Digital với hình ảnh sáng hơn, sắc nét hơn, âm thanh sống động hơn.</p>
                                            </div>
                                            <div class="faq__format-item">
                                                <h5>3D</h5>
                                                <p>Phim được chiếu ở định dạng 3D với hình ảnh và âm thanh hoàn toàn sắc nét, sống động. Bạn cần đến mắt kiếng chuyên dụng 3D để xem phim.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="faq__item">
                                    <h3 class="faq__question">2D Digital là gì?</h3>
                                    <div class="faq__answer">
                                        <p>Cuộc cách mạng số được tiếp diễn với công nghệ phim 2D Digital. Rất nhiều khách hàng đã hỏi chúng tôi về sự khác nhau giữa phim 2D tiêu chuẩn 35mm và 2D Digital và đây là câu trả lời dành cho các bạn:</p>
                                        <p>Không giống như phim 2D thông thường với các bản phim và máy chiếu tiêu chuẩn, 2D Digital sử dụng các thiết bị kỹ thuật số tương tự như công nghệ 3D Digital chỉ khác là không có hiệu ứng 3D. Điều này có nghĩa là bạn sẽ được thưởng thức hiệu ứng hình ảnh sáng hơn, sắc nét hơn của công nghệ 3D với âm thanh digital sống động.</p>
                                        <p><strong>Hãy đón xem 2D Digital để có trải nghiệm điện ảnh thật sự tuyệt vời.</strong></p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Payment Section -->
                        <section id="section-payment" class="faq__section">
                            <h2 class="faq__section-title">Thanh toán online</h2>
                            <div class="faq__section-content">
                                <div class="faq__item">
                                    <h3 class="faq__question">Làm sao để thanh toán Online?</h3>
                                    <div class="faq__answer">
                                        <p>Hiện tại SE7ENCinema chỉ hỗ trợ thanh toán qua Ví điện tử MoMo. Để thanh toán thành công, bạn cần:</p>
                                        <ul>
                                            <li>Có tài khoản MoMo đã được xác thực</li>
                                            <li>Có đủ số dư trong ví MoMo để thanh toán</li>
                                            <li>Nhập chính xác thông tin thanh toán</li>
                                            <li>Xác nhận giao dịch qua mã OTP từ MoMo</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="faq__item">
                                    <h3 class="faq__question">Tại sao giao dịch thanh toán qua MoMo không thành công?</h3>
                                    <div class="faq__answer">
                                        <p>Giao dịch thanh toán qua MoMo không thành công có thể do các nguyên nhân sau:</p>
                                        <ul>
                                            <li>Tài khoản MoMo chưa được xác thực đầy đủ</li>
                                            <li>Số dư trong ví MoMo không đủ để thanh toán</li>
                                            <li>Nhập sai thông tin xác thực (OTP)</li>
                                            <li>Mạng internet không ổn định</li>
                                            <li>Ứng dụng MoMo gặp sự cố tạm thời</li>
                                        </ul>
                                        <p><strong>Để biết nguyên nhân chính xác, vui lòng liên hệ với MoMo hoặc gọi hotline SE7ENCinema để được hỗ trợ.</strong></p>
                                    </div>
                                </div>

                                <div class="faq__item">
                                    <h3 class="faq__question">SE7ENCinema có hỗ trợ các phương thức thanh toán khác không?</h3>
                                    <div class="faq__answer">
                                        <p>Hiện tại SE7ENCinema chỉ hỗ trợ thanh toán qua Ví điện tử MoMo cho dịch vụ đặt vé online. Nếu bạn muốn sử dụng các phương thức thanh toán khác như tiền mặt, thẻ ATM, hoặc thẻ tín dụng, vui lòng đến mua vé trực tiếp tại quầy BOX OFFICE của rạp.</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Contact Section -->
                        <section id="section-contact" class="faq__section">
                            <h2 class="faq__section-title">Liên hệ hỗ trợ</h2>
                            <div class="faq__section-content">
                                <div class="faq__contact-info">
                                    <h4>Nếu Quý khách có gặp bất cứ vấn đề nào liên quan tới việc đặt vé online, vui lòng liên hệ:</h4>

                                    <div class="faq__contact-methods">
                                        <div class="faq__contact-item">
                                            <h5>Hotline</h5>
                                            <p><strong>1900 6017</strong></p>
                                            <p>Phục vụ 24/7</p>
                                        </div>
                                        <div class="faq__contact-item">
                                            <h5>Email</h5>
                                            <p><strong>support@se7encinema.com.vn</strong></p>
                                            <p>Phản hồi trong 24h</p>
                                        </div>
                                        <div class="faq__contact-item">
                                            <h5>Văn phòng</h5>
                                            <p>29-13 Ng. 4 Đ. Vân Canh,</p>
                                            <p>Vân Canh, Từ Liêm, Hà Nội</p>
                                        </div>
                                        <div class="faq__contact-item">
                                            <h5>Live Chat</h5>
                                            <p>Website: se7encinema.com.vn</p>
                                            <p>Ứng dụng SE7ENCinema</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Footer -->
                        <div class="faq__footer">
                            <div class="faq__footer-note">
                                <p><strong>Trang FAQ này được cập nhật lần cuối: {{ date('d/m/Y H:i') }}</strong></p>
                                <p>SE7ENCinema có Quy ền cập nhật thông tin này bất cứ lúc nào. Mọi thay đổi sẽ được thông báo trên website và có hiệu lực ngay sau khi đăng tải.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
