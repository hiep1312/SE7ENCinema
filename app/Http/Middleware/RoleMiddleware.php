<?php

  namespace App\Http\Middleware;

  use Closure;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;

  class RoleMiddleware
  {
      public function handle(Request $request, Closure $next, ...$roles): mixed
      {
          // Đảm bảo $roles là mảng và không rỗng
          if (empty($roles)) {
              abort(500, 'Middleware role cần ít nhất một vai trò.');
          }

          // Kiểm tra xác thực
          if (!Auth::check()) {
              abort(401, 'Vui lòng đăng nhập.');
          }

          // Lấy role của người dùng và đảm bảo là chuỗi
          $userRole = (string) Auth::user()->role; // Chuyển đổi thành chuỗi để tránh lỗi mảng

          // Kiểm tra vai trò
          if (!in_array($userRole, $roles)) {
              abort(403, 'Không có quyền truy cập.');
          }

          return $next($request);
      }
  }
