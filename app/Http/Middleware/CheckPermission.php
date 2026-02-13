<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $feature, string $action = 'read')
    {
        $user = Auth::user();

        // if (!$user || !$user->hasPermission($action, $feature)) {
        //     if ($request->expectsJson()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Anda tidak memiliki akses untuk fitur ini'
        //         ], 403);
        //     }
        //     abort(403, 'Anda tidak memiliki akses untuk fitur ini');
        // }

        return $next($request);
    }
}