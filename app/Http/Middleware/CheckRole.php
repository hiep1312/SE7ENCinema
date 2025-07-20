<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) return redirect()->route('login');
        $user = Auth::user();
        $routerID = $request->route('user');
        if ($routerID !== null) {
            if ((int)$routerID === (int)$user->id) {
                return $next($request);
            } else {
                abort(403, 'Bạn không có quyền truy cập.');
            }
        }
        if (in_array($user->role, $roles)) return $next($request);
        abort(403, 'Bạn không có quyền truy cập.');
    }
}
