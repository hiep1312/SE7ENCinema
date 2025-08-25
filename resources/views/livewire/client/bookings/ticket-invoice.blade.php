<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css">
    <title>SE7ENCinema - Đặt vé thành công</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 20px 10px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-collapse: collapse;">
                    <tr>
                        <td style="height: 4px; background-color: #FF4444; line-height: 1px; font-size: 1px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 30px 20px 20px 20px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                <tr>
                                    <td style="text-align: center; vertical-align: middle">
                                        <img src="https://i.postimg.cc/6QdWgqPJ/CzMt3233.png" alt="Logo SE7ENCinema">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 30px;">
                            <img src="https://i.postimg.cc/c46z6j0r/unnamed-3.png" alt="Banner ghế" style="width: 100%; height: auto;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 20px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #333; line-height: 1.5;">
                                Xin chào <span style="font-weight: 600;">{{ $booking->user->name }}</span>,
                            </p>

                            <p style="margin: 0 0 10px 0; font-size: 14px; color: #666; line-height: 1.5;">
                                Cảm ơn bạn đã sử dụng dịch vụ của SE7ENCinema
                            </p>
                            <p style="margin: 0 0 10px 0; font-size: 14px; color: #666; line-height: 1.5;">
                                Chúng tôi xác nhận bạn đã đặt vé xem phim của <strong>Rạp SE7ENCinema</strong> thành công lúc <strong>{{ $booking->updated_at->format('H:i:s d/m/Y') }}</strong>.
                            </p>
                            <p style="margin: 0 0 30px 0; font-size: 14px; color: #666; line-height: 1.5;">
                                Chi tiết vé của bạn như sau:
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 30px; border-collapse: collapse;">
                                <tr>
                                    <td style="text-align: center; padding: 20px 20px 40px 20px;">
                                        <p style="margin: 0 0 15px 0; font-size: 16px; color: #666; font-weight: 600;">
                                            Mã đặt vé
                                        </p>
                                        <p style="margin: 0 0 30px 0; font-size: 28px; color: #FF4444; font-weight: bold; letter-spacing: 1px;">
                                            {{ $booking->booking_code }}
                                        </p>

                                        <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto 25px auto; border: 1px solid #e5e7eb; border-radius: 8px; border-collapse: collapse;">
                                            <tr>
                                                <td>
                                                    <img src="{{ $message->embed(public_path('storage/qrcode-booking.webp')) }}"
                                                        alt="QR code"
                                                        style="width: 150px; height: 150px; border-radius: 0;">
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin: 0 0 25px 0; font-size: 12px; color: #666; line-height: 1.4;">
                                            Đưa mã QR này đến quầy giao dịch để nhận vé
                                        </p>

                                        <p style="margin: 0 0 8px 0; font-size: 14px; color: #666;">
                                            Thời gian chiếu
                                        </p>
                                        <p style="margin: 0; font-size: 16px; color: #333; font-weight: bold;">
                                            {{ $booking->showtime->start_time->format('d/m/Y H:i') }} - {{ $booking->showtime->end_time->format('H:i') }}
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 0;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                            <tr>
                                                <td style="padding: 0; background-color: #f9f9f9; height: 8px; line-height: 1px; font-size: 1px;">&nbsp;</td>
                                            </tr>

                                            @php $movie = $booking->showtime->movie @endphp
                                            <tr>
                                                <td style="padding: 15px 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Phim</div>
                                                    <div style="color: #333; font-size: 14px; font-weight: 600;">{{ $movie->title }}</div>
                                                </td>
                                            </tr>

                                            <!-- Genre and Duration -->
                                            <tr>
                                                <td style="padding: 0; border-bottom: 1px solid #e5e7eb;">
                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                                        <tr>
                                                            <td style="padding: 15px 20px; width: 70%; vertical-align: top;">
                                                                <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Thể loại</div>
                                                                <div style="color: #333; font-size: 14px; font-weight: 500;">{{ $movie->genres->take(3)->implode('name', ', ') }}</div>
                                                            </td>
                                                            <td style="padding: 15px 20px; width: 30%; vertical-align: top;">
                                                                <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Thời lượng</div>
                                                                <div style="color: #333; font-size: 14px; font-weight: 500;">{{ $movie->duration }}p</div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <!-- Cinema, Tickets, Seats -->
                                            <tr>
                                                <td style="padding: 0; border-bottom: 1px solid #e5e7eb;">
                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                                        <tr>
                                                            <td style="padding: 15px 20px; width: 40%; vertical-align: top;">
                                                                <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Phòng Chiếu</div>
                                                                <div style="color: #333; font-size: 14px; font-weight: 500;">{{ $booking->showtime->room->name }}</div>
                                                            </td>
                                                            <td style="padding: 15px 10px; width: 20%; vertical-align: top;">
                                                                <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Số Vé</div>
                                                                <div style="color: #333; font-size: 14px; font-weight: 500;">{{ $booking->tickets->count() }}</div>
                                                            </td>
                                                            <td style="padding: 15px 20px; width: 40%; vertical-align: top;">
                                                                <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Ghế</div>
                                                                <div style="color: #333; font-size: 14px; font-weight: 500;">{{ $booking->seats->implode('seatLabel', ', ') }}</div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <!-- Enhanced Food Section -->
                                            <tr>
                                                <td style="padding: 15px 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <div style="color: #666; font-size: 14px; margin-bottom: 12px; font-weight: 600;">Thức ăn kèm</div>
                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                                        @forelse($booking->foodOrderItems as $foodOrderItem)
                                                            <tr>
                                                                <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                                                            <tr>
                                                                                <td style="width: 70%; vertical-align: top;">
                                                                                    <div style="color: #333; font-size: 14px; font-weight: 500; margin-bottom: 3px;">{{ $foodOrderItem->variant->foodItem->name }}</div>
                                                                                    <div style="color: #666; font-size: 12px; line-height: 1.5;">
                                                                                        @foreach($foodOrderItem->variant->variantAttributes as $attribute => $value)
                                                                                            <div>• {{ $attribute }}: {{ $value }}</div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </td>
                                                                                <td style="width: 30%; text-align: right; vertical-align: top;">
                                                                                    <div style="color: #d97706; font-size: 14px; font-weight: 600;">{{ number_format($foodOrderItem->price, 0, '.', '.') }}đ</div>
                                                                                    <div style="color: #999; font-size: 12px;">x {{ $foodOrderItem->quantity }}</div>
                                                                                </td>
                                                                            </tr>

                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <div style="color: #333; font-size: 14px; font-weight: 500;">Không có</div>
                                                        @endforelse

                                                        <tr>
                                                            <td style="padding: 12px 0 0 0;">
                                                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                                                    <tr>
                                                                        <td style="width: 70%;">
                                                                            <div style="color: #333; font-size: 14px; font-weight: 600;">Tổng tiền đồ ăn:</div>
                                                                        </td>
                                                                        <td style="width: 30%; text-align: right;">
                                                                            <div style="color: #d97706; font-size: 16px; font-weight: bold;">{{ number_format($booking->foodOrderItems?->sum(fn($foodOrderItem) => $foodOrderItem->price * $foodOrderItem->quantity) ?? 0, 0, '.', '.') }}đ</div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding: 15px 20px; border-bottom: 1px solid #e5e7eb;">
                                                    <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Rạp chiếu</div>
                                                    <div style="color: #333; font-size: 14px; font-weight: 600; margin-bottom: 5px;">SE7ENCinema</div>
                                                    <div style="color: #666; font-size: 13px; line-height: 1.4;">
                                                        13 P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="padding: 18px 20px; background-color: #f1f5f9;">
                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                                        <tr>
                                                            <td style="color: #333; font-size: 16px; font-weight: bold;">Tổng tiền</td>
                                                            <td style="color: #333; font-size: 18px; font-weight: bold; text-align: right;">{{ number_format($booking->total_price, 0, '.', '.') }}đ</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 20px 30px 20px;">
                            <h3 style="margin: 0 0 20px 0; font-size: 16px; color: #333; font-weight: bold;">
                                Thông tin người nhận vé
                            </h3>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #e5e7eb; border-radius: 8px; border-collapse: collapse; font-size: 14px;">
                                @php $user = $booking->user @endphp
                                <tr style="background-color: #f8f9fa;">
                                    <td colspan="2" style="padding: 15px; font-weight: bold; color: #333; border-bottom: 1px solid #e5e7eb;">
                                        Người đặt
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 15px; color: #666; border-bottom: 1px solid #e5e7eb; width: 35%;">Họ và tên</td>
                                    <td style="padding: 15px; color: #333; border-bottom: 1px solid #e5e7eb; font-weight: 600;">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 15px; color: #666; border-bottom: 1px solid #e5e7eb;">Số điện thoại</td>
                                    <td style="padding: 15px; color: #333; border-bottom: 1px solid #e5e7eb; font-weight: 600;">{{ $user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 15px; color: #666;">Email</td>
                                    <td style="padding: 15px;">
                                        <a href="mailto:{{ $user->email }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">{{ $user->email }}</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0 0 0; font-size: 12px; color: #666;">
                                Xem đơn hàng này trên trang web của SE7ENCinema
                            </p>
                            <p style="margin: 5px 0 0 0;">
                                <a href="{{ route('client.userBooking', $booking->id) }}" style="color: #FF4444; text-decoration: none; font-weight: 600; font-size: 14px;">
                                    Chi tiết đơn hàng →
                                </a>
                            </p>
                        </td>
                    </tr>

                    <!-- Policy Section -->
                    <tr>
                        <td style="padding: 0 20px 30px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 8px; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h4 style="margin: 0 0 15px 0; font-size: 14px; color: #333; font-weight: bold;">
                                            📋 Chính sách hoàn huỷ
                                        </h4>
                                        <p style="margin: 0 0 15px 0; font-size: 12px; color: #666; line-height: 1.5;">
                                            SE7ENCinema không hỗ trợ đổi trả đối với các vé xem phim đã mua thành công qua website.
                                        </p>

                                        <h4 style="margin: 15px 0 10px 0; font-size: 14px; color: #333; font-weight: bold;">
                                            ⚠️ Lưu ý
                                        </h4>
                                        <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #666; line-height: 1.4;">
                                            <li style="margin-bottom: 8px;">Khi được yêu cầu, vui lòng đưa vé để nhân viên quét và nhận vé khi xem phim tại SE7ENCinema.</li>
                                            <li style="margin-bottom: 0;">Các vé được mua qua website SE7ENCinema sẽ không được hoàn tiền dưới mọi trường hợp.</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 20px 30px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 8px; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #333; font-weight: bold;">
                                            📞 Liên hệ hỗ trợ
                                        </h4>
                                        <p style="margin: 0; font-size: 12px; color: #666; line-height: 1.4;">
                                            Trong trường hợp nhận QR lỗi hoặc gặp vấn đề về vé, bạn vui lòng liên hệ với nhân viên của SE7ENCinema qua khung chat để được hỗ trợ 24/7.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #FF4444; color: white; padding: 30px 20px; text-align: center;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                <tr>
                                    <td style="text-align: left; vertical-align: top; width: 100%;">
                                        <div style="margin: 0 0 10px 0; font-size: 16px; font-weight: bold; line-height: 1.3;">
                                            Mọi bản quyền thuộc về SE7ENCinema
                                        </div>
                                        <p style="margin: 0 0 15px 0; font-size: 13px; line-height: 1.5;">
                                            13 P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top: 1px dashed rgba(255,255,255,0.6); margin-top: 15px; padding-top: 15px; border-collapse: collapse;">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="margin-bottom: 0; margin-top: 10px; font-size: 11px; line-height: 1.4;">
                                            Bạn đang được nhận email từ tài khoản SE7ENCinema
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
