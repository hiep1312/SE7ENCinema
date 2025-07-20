<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Cinema Booking')</title>

    <!-- CSS Bootstrap (hoặc bạn dùng css khác) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @livewireStyles
    <!-- Các CSS khác nếu cần -->
</head>
<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1 class="mb-0">SE7EN Cinema</h1>
            <nav>
                <a href="/" class="text-white me-3">Trang chủ</a>
                <a href="{{ route('booking.select_showtime') }}" class="text-white">Đặt vé</a>
                {{-- Menu khác nếu có --}}
            </nav>
        </div>
    </header>

    <main class="container my-4">
        {{ $slot }} {{-- Nội dung các Livewire component được chèn vào đây --}}
    </main>

    <footer class="bg-light text-center p-3 mt-auto">
        <div class="container">
            &copy; {{ date('Y') }} SE7EN Cinema. Bản quyền thuộc về SE7EN.
        </div>
    </footer>

    <!-- Script Bootstrap (hoặc các script khác) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts
    <!-- Các script khác nếu cần -->
</body>
</html>
