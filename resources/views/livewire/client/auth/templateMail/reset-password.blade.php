<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - SE7ENCinema</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #374151;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color: #f8fafc; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                    style="max-width: 700px; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <tr>
                        <td style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); padding: 40px 40px 30px; text-align: center; position: relative;">
                            <img src="https://i.postimg.cc/s2zZhYzs/Black-and-White-Filmstrip-Modern-Logo.png" alt="Logo"
                                style="width: 180px; height: auto; margin-bottom: 20px; filter: brightness(0) invert(1);">

                            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.15); border-radius: 50%; margin: 0 auto 20px; text-align: center; line-height: 70px; backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.2); font-size: 32px; color: white; vertical-align: middle;">
                                🔐
                            </div>

                            <h1 style="margin: 0; color: white; font-size: 28px; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                Đặt Lại Mật Khẩu
                            </h1>
                            <p style="margin: 10px 0 0; color: rgba(255,255,255,0.9); font-size: 16px; font-weight: 400;">
                                Yêu cầu thay đổi mật khẩu cho tài khoản của bạn
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px;">
                            <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left: 4px solid #3b82f6; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                                <h2 style="margin: 0 0 10px; color: #1e40af; font-size: 24px; font-weight: 600;">
                                    👋 Xin chào {{ $user->name }}!
                                </h2>
                                <p style="margin: 0; color: #1e40af; font-size: 16px;">
                                    Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu của bạn.
                                </p>
                            </div>

                            <div style="margin-bottom: 30px;">
                                <p style="margin: 0 0 20px; font-size: 16px; color: #374151; line-height: 1.6;">
                                    Để bảo mật tài khoản của bạn, chúng tôi cần xác nhận danh tính. Vui lòng nhấp vào
                                    nút bên dưới để tiếp tục quá trình đặt lại mật khẩu:
                                </p>

                                <div style="text-align: center; margin: 30px 0;">
                                    <a href="{{ $url }}"
                                        style="display: inline-block; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px;">
                                        🔑 Đặt Lại Mật Khẩu
                                    </a>
                                </div>

                                <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 20px 0;">
                                    <p style="margin: 0 0 10px; font-size: 14px; color: #6b7280; font-weight: 600;">
                                        Nếu nút không hoạt động, hãy sao chép và dán liên kết sau vào trình duyệt:
                                    </p>
                                    <p style="margin: 0; word-break: break-all; font-size: 12px; color: #3b82f6; background: white; padding: 10px; border-radius: 4px; border: 1px solid #e5e7eb;">
                                        {{ $url }}
                                    </p>
                                </div>
                            </div>

                            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 15px; color: #92400e; font-size: 18px; font-weight: 600; display: flex; align-items: center;">
                                    ⚠️ Lưu ý bảo mật
                                </h3>
                                <ul style="margin: 0; padding-left: 20px; color: #92400e;">
                                    <li style="margin-bottom: 8px;">Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua
                                        email này</li>
                                    <li style="margin-bottom: 8px;">Không chia sẻ liên kết này với bất kỳ ai</li>
                                    <li style="margin-bottom: 8px;">Liên kết này sẽ hết hạn sau 60 phút</li>
                                    <li style="margin-bottom: 0;">Nếu bạn nghi ngờ có hoạt động đáng ngờ, hãy liên hệ
                                        với chúng tôi ngay lập tức</li>
                                </ul>
                            </div>

                            <div style="background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; text-align: center;">
                                <h3 style="margin: 0 0 15px; color: #374151; font-size: 16px; font-weight: 600;">
                                    💬 Cần hỗ trợ?
                                </h3>
                                <p style="margin: 0 0 15px; color: #6b7280; font-size: 14px;">
                                    Nếu bạn gặp khó khăn hoặc có câu hỏi, đội ngũ hỗ trợ của chúng tôi luôn sẵn sàng
                                    giúp đỡ.
                                </p>
                                <a href="mailto:support@example.com"
                                    style="color: #3b82f6; text-decoration: none; font-weight: 600; font-size: 14px;">
                                    📧 support@example.com
                                </a>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td style="background: #f8fafc; padding: 30px 40px; border-top: 1px solid #e5e7eb;">
                            <div style="text-align: center; margin-bottom: 20px;">
                                <p style="margin: 0 0 15px; color: #6b7280; font-size: 14px; font-weight: 600;">
                                    Kết nối với chúng tôi
                                </p>
                                <div style="display: inline-block;">
                                    <a href="#"
                                        style="display: inline-block; width: 40px; height: 40px; background: #3b82f6; color: white; text-decoration: none; border-radius: 50%; margin: 0 5px; line-height: 40px; text-align: center; font-size: 16px;">📘</a>
                                    <a href="#"
                                        style="display: inline-block; width: 40px; height: 40px; background: #1da1f2; color: white; text-decoration: none; border-radius: 50%; margin: 0 5px; line-height: 40px; text-align: center; font-size: 16px;">🐦</a>
                                    <a href="#"
                                        style="display: inline-block; width: 40px; height: 40px; background: #0077b5; color: white; text-decoration: none; border-radius: 50%; margin: 0 5px; line-height: 40px; text-align: center; font-size: 16px;">💼</a>
                                    <a href="#"
                                        style="display: inline-block; width: 40px; height: 40px; background: #25d366; color: white; text-decoration: none; border-radius: 50%; margin: 0 5px; line-height: 40px; text-align: center; font-size: 16px;">📱</a>
                                </div>
                            </div>

                            <div style="text-align: center; margin-bottom: 20px;">
                                <a href="#"
                                    style="color: #3b82f6; text-decoration: none; font-size: 12px; margin: 0 10px; font-weight: 500;">Chính
                                    sách bảo mật</a>
                                <span style="color: #d1d5db;">|</span>
                                <a href="#"
                                    style="color: #3b82f6; text-decoration: none; font-size: 12px; margin: 0 10px; font-weight: 500;">Điều
                                    khoản sử dụng</a>
                                <span style="color: #d1d5db;">|</span>
                                <a href="#"
                                    style="color: #3b82f6; text-decoration: none; font-size: 12px; margin: 0 10px; font-weight: 500;">Liên
                                    hệ</a>
                            </div>

                            <div style="text-align: center; border-top: 1px solid #e5e7eb; padding-top: 20px;">
                                <p style="margin: 0 0 5px; font-size: 11px; color: #9ca3af;">
                                    © {{ date('Y') }} SE7ENCinema. Tất cả quyền được bảo lưu.
                                </p>
                                <p style="margin: 0; font-size: 11px; color: #9ca3af;">
                                    📍 Tòa nhà FPT Polytechnic, P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội, Việt Nam
                                </p>
                            </div>

                            <div
                                style="text-align: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
                                <p style="margin: 0; font-size: 10px; color: #9ca3af;">
                                    Đây là email tự động. Vui lòng không trả lời email này.
                                </p>
                                <p style="margin: 5px 0 0; font-size: 10px; color: #9ca3af;">
                                    Nếu bạn không muốn nhận email này,
                                    <a href="#" style="color: #6b7280; text-decoration: underline;">hủy đăng ký
                                        tại đây</a>
                                </p>
                            </div>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
