<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @hasSection('title')
            @yield('title', 'SE7ENCinema')
        @else
            {{ $title ?? 'SE7ENCinema' }}
        @endif
    </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}">
    <!-- Template style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/animate.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/font-awesome.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/fonts.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/flaticon.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/owl.carousel.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/owl.theme.default.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/dl-menu.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/nice-select.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/magnific-popup.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/venobox.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/js/plugin/rs_slider/layers.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/js/plugin/rs_slider/navigation.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/js/plugin/rs_slider/settings.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/seat.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('client/assets/css/responsive.css') }}" />
    @vite('resources/css/app.css')
    @stack('styles')
</head>

<body>
    <div>
        @include('livewire.client.components.header')
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot }}
        @endif
        @include('livewire.client.components.footer')
    </div>
    <!-- Main JS files -->
    <script src="{{ asset('client/assets/js/jquery_min.js') }}"></script>
    {{-- <script src="{{ asset('client/assets/js/bootstrap.js') }}"></script> --}}
    <script src="{{ asset('client/assets/js/modernizr.js') }}"></script>
    <script src="{{ asset('client/assets/js/owl.carousel.js') }}"></script>
    <script src="{{ asset('client/assets/js/jquery.dlmenu.js') }}"></script>
    <script src="{{ asset('client/assets/js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('client/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('client/assets/js/jquery.bxslider.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/venobox.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/smothscroll_part1.js') }}"></script>
    <script src="{{ asset('client/assets/js/smothscroll_part2.js') }}"></script>
    <script src="{{ asset('client/assets/js/jquery.countTo.js') }}"></script>
    <script src="{{ asset('client/assets/js/jquery.inview.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/jquery.shuffle.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/jquery.themepunch.revolution.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/jquery.themepunch.tools.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.addon.snow.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.extension.actions.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.extension.carousel.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.extension.kenburn.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.extension.layeranimation.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.extension.migration.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.extension.navigation.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.extension.parallax.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.extension.slideanims.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/plugin/rs_slider/revolution.extension.video.min.js') }}"></script>
    <script src="{{ asset('client/assets/js/contact_form.js') }}"></script>
    <script src="{{ asset('client/assets/js/custom.js') }}"></script>
    @vite('resources/js/app.js')
</body>
</html>
