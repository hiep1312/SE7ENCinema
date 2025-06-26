<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Xác minh Email - Movie Pro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg,
                    #1a1a1a 0%,
                    #2d2d2d 50%,
                    #1a1a1a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 50%,
                    rgba(255, 59, 59, 0.1) 0%,
                    transparent 50%),
                radial-gradient(circle at 70% 30%,
                    rgba(255, 59, 59, 0.05) 0%,
                    transparent 50%);
            pointer-events: none;
        }

        .container {
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 59, 59, 0.2);
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 59, 59, 0.1);
            max-width: 350px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg,
                    transparent,
                    #ff3b3b,
                    transparent);
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                opacity: 0.5;
            }

            to {
                opacity: 1;
            }
        }

        .icon {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #ff3b3b, #e73c3c);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            position: relative;
            box-shadow: 0 8px 25px rgba(255, 59, 59, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 8px 25px rgba(255, 59, 59, 0.3);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 12px 35px rgba(255, 59, 59, 0.5);
            }
        }

        .icon svg {
            width: 32px;
            height: 32px;
            fill: white;
        }

        h1 {
            color: #ffffff;
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .subtitle {
            color: #b0b0b0;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .send-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff3b3b, #e73c3c);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .send-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent);
            transition: left 0.5s;
        }

        .send-btn:hover::before {
            left: 100%;
        }

        .send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 59, 59, 0.4);
            background: linear-gradient(135deg, #ff4444, #ee4444);
        }

        .send-btn:active {
            transform: translateY(0);
        }

        .send-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .send-btn:disabled::before {
            display: none;
        }

        .loading {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .success-message {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 14px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            font-weight: 500;
            animation: slideIn 0.5s ease;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .resend-section {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 59, 59, 0.2);
        }

        .resend-text {
            color: #888;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .resend-btn {
            background: transparent;
            color: #ff3b3b;
            border: 2px solid #ff3b3b;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .resend-btn:hover {
            background: #ff3b3b;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 59, 59, 0.3);
        }

        .resend-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .countdown {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 25px 20px;
                max-width: 300px;
            }

            h1 {
                font-size: 22px;
            }

            .subtitle {
                font-size: 13px;
            }
        }

        /* Particles effect */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #ff3b3b;
            border-radius: 50%;
            animation: float 6s infinite linear;
            opacity: 0.6;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 0.6;
            }

            90% {
                opacity: 0.6;
            }

            100% {
                transform: translateY(-10px) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <div class="particles">
        <div class="particle" style="left: 10%; animation-delay: 0s"></div>
        <div class="particle" style="left: 20%; animation-delay: 1s"></div>
        <div class="particle" style="left: 30%; animation-delay: 2s"></div>
        <div class="particle" style="left: 40%; animation-delay: 3s"></div>
        <div class="particle" style="left: 50%; animation-delay: 4s"></div>
        <div class="particle" style="left: 60%; animation-delay: 5s"></div>
        <div class="particle" style="left: 70%; animation-delay: 0.5s"></div>
        <div class="particle" style="left: 80%; animation-delay: 1.5s"></div>
        <div class="particle" style="left: 90%; animation-delay: 2.5s"></div>
    </div>

    <div class="container">
        <div class="icon">
            <svg viewBox="0 0 24 24">
                <path
                    d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" />
            </svg>
        </div>

        <h1>Xác minh Email</h1>
        <p class="subtitle">
            Nhấn nút bên dưới để gửi mã xác minh đến email của bạn
        </p>

        <form method="POST" action="{{route('verification.send')}}">
            @csrf
            <button class="send-btn" id="sendBtn">
                <span id="btnText">Gửi mã xác minh</span>
                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <span>Đang gửi...</span>
                </div>
            </button>
        </form>
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                <div class="success-message" id="successMessage">
                    Mã xác minh đã được gửi thành công!
                </div>
            </div>
        @endif
    </div>
</body>

</html>