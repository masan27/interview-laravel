<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoAuthMiddleware
{
    /**
     * Otomatis mengautentikasi user simulasi (default: User ID 1 / Instansi 1)
     * agar kandidat dapat langsung menggunakan auth()->user() atau $request->user()
     * selama sesi live coding tanpa perlu setup login manual.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $userId = $request->header('X-Demo-User-Id', 1);
            $user = User::find($userId) ?? User::first();
            if ($user) {
                Auth::setUser($user);
                $request->setUserResolver(fn () => $user);
            }
        }

        return $next($request);
    }
}
