<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Nếu là "Nhân viên" (type_accounts_id == 3)
        if ($user->type_accounts_id == 3 && $user->part) {
            $partName = strtolower($user->part->name);

            if (str_contains($partName, 'bán hàng') || str_contains($partName, 'phục vụ')) {
                return redirect()->intended('/ban-hang/giao-dich');
            } elseif (str_contains($partName, 'kho')) {
                return redirect()->intended('/nhap-hang');
            } elseif (str_contains($partName, 'pha chế')) {
                return redirect()->intended('/ban-hang/thuc-don');
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
