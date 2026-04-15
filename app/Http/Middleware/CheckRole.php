<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: Route::middleware('role:warehouse') hoặc 'role:sales'
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Load relationships nếu chưa load
        $user->loadMissing('part', 'typeAccount');

        $allowed = match ($role) {
            'super_admin' => $user->isSuperAdmin(),
            'admin'     => $user->isSuperAdminOrAdmin(),
            'warehouse' => $user->canAccessWarehouse(),
            'sales'     => $user->canAccessSales(),
            'bartender' => $user->canAccessBartender(),
            'server'    => $user->canAccessServer(),
            default     => false,
        };

        if (!$allowed) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
