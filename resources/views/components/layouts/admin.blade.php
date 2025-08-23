<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'SE7ENCinema Admin' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.ico') }}">
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/mdi/css/materialdesignicons.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">

    @vite(['resources/css/app.css'])
</head>

<body data-bs-theme="dark">
    <div class="container-scroller">
        @include('livewire.admin.components.sidebar')
        <div class="container-fluid page-body-wrapper">
            @include('livewire.admin.components.header')
            <div class="main-panel">
                {{ $slot }}
                @include('livewire.admin.components.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('admin/assets/js/misc.js') }}"></script>

    @vite(['resources/js/app.js' , 'resources/js/chatbot.js'])
</body>
</html>
