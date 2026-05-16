<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (session('admin_authenticated') === true) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login', [
            'title' => 'Вход в админку',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Введите логин.',
            'password.required' => 'Введите пароль.',
        ]);

        $expectedLogin = env('ADMIN_LOGIN', 'admin');
        $expectedPassword = env('ADMIN_PASSWORD', 'Travel2026!');

        if (
            hash_equals($expectedLogin, (string) $credentials['login']) &&
            hash_equals($expectedPassword, (string) $credentials['password'])
        ) {
            $request->session()->put('admin_authenticated', true);
            $request->session()->regenerate();

            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('status', 'Вход выполнен.');
        }

        return back()
            ->withErrors(['login' => 'Неверный логин или пароль.'])
            ->onlyInput('login');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('status', 'Вы вышли из админки.');
    }
}
