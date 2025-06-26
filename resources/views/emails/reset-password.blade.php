<body style="background-color: #f1f5f9; padding: 40px; font-family: sans-serif;">
    <table align="center" width="100%"
        style="max-width: 600px; background: white; padding: 40px; border-radius: 6px; text-align: center;">
        <tr>
            <td>
                <img src="https://i.postimg.cc/VLPg9xpq/logo3.png" alt="Logo" style="width: 200px;">
                <h2>Xin chào {{$userName}}!</h2>
                <p style="text-align: left">
                    Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu của bạn. Hãy
                    nhấp vào nút bên dưới để tiếp tục:
                </p>
                <a href="{{ $url }}"
                    style="display: inline-block; margin: 20px auto; padding: 10px 20px; background-color: #1e293b; color: white; text-decoration: none; border-radius: 4px;">
                    Đặt lại mật khẩu
                </a>
                <div style="text-align: left">
                    <p>
                        Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua
                        email này
                    </p>
                    <p>Không chia sẻ liên kết này với bất kỳ ai</p>
                    <p>
                        Nếu bạn nghi ngờ có hoạt động đáng ngờ, hãy liên hệ với
                        chúng tôi ngay lập tức
                    </p>
                </div>
                <hr>
                <p style="font-size: 11px; color: #FF4444;">Chính sách bảo mật | Điều khoản </p>
                <p style="font-size: 11px; color: #747474;">Đây là email tự động. Vui lòng không trả lời email này. </p>
            </td>
        </tr>
    </table>
</body>