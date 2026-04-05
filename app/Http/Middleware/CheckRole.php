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
            'admin'     => $user->isAdmin(),
            'manager'   => $user->isAdminOrManager(),
            'warehouse' => $user->canAccessWarehouse(),
            'sales'     => $user->canAccessSales(),
            'finance'   => $user->canAccessFinance(),
            'hr'        => $user->canAccessHR(),
            default     => false,
        };

        if (!$allowed) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
