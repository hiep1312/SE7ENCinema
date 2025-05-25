<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']);
</head>

<body>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Avatar</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created_at</th>
                <th>Updated_at</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listUser as $user)
                <tr>
                    <td>{{ $user['id'] }}</td>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ $user['password'] }}</td>
                    <td>{{ $user['phone'] }}</td>
                    <td>{{ $user['address'] }}</td>
                    <td>{{ $user['avatar'] }}</td>
                    <td>{{ $user['birthday'] }}</td>
                    <td>{{ $user['gender'] }}</td>
                    <td>{{ $user['role'] }}</td>
                    <td>{{ $user['status'] }}</td>
                    <td>{{ $user['created_at'] }}</td>
                    <td>{{ $user['updated_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>