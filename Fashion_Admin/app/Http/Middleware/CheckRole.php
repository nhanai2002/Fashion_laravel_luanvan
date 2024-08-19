<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role_id != 2) {
            return $next($request);
        }
        return redirect()->route('login')->with('error', 'Bạn không có quyền truy cập vào trang này.');
    }
}
