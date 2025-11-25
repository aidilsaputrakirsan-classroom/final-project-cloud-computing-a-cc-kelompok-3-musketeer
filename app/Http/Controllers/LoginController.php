<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth_manual.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // -------- Activity Log: LOGIN ADMIN --------
            if ($user && $user->is_admin) {
                app(ActivityLogService::class)->log(
                    $user,
                    'auth.login',
                    "Admin {$request->email} berhasil login",
                    [
                        'email'  => $request->email,
                        'method' => 'password',
                        'ip'     => $request->ip(),
                    ],
                    [
                        'context' => 'auth',
                    ]
                );
            }
            // -------------------------------------------

            // Jika admin -> arahkan ke dashboard admin
            if ($user->is_admin) {
                return redirect()->route('admin.reports.index');
            }

            // Selain admin -> dashboard biasa
            return redirect()->intended('/dashboard');
        }

        return back()->with('failed', 'Login gagal, email atau password salah.');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        // -------- Activity Log: LOGOUT ADMIN --------
        if ($user && $user->is_admin) {
            app(ActivityLogService::class)->log(
                $user,
                'auth.logout',
                "{$user->name} logout",
                [
                    'via' => 'button',
                    'ip'  => $request->ip(),
                ],
                [
                    'context' => 'auth',
                ]
            );
        }
        // --------------------------------------------

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
