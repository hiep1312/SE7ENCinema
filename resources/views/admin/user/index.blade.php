<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/e14b23f1dc.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .name {
            color: #000;
        }

        .name:hover {
            color: #0d6efd;
            !important
        }

        i {
            color: #8a969c;
        }

        td {

            padding: 15px !important;
        }

        .table-user {
            align-items: center;
            justify-content: center;
        }

        .table-user img {
            width: 30px;
            height: 30px;
        }

        .button {
            background: none;
            border: none;
            cursor: pointer;
        }
    </style>
    @livewireStyles
</head>

<body>
    <livewire:admin.users.user-index />
    @livewireScripts
</body>

</html>