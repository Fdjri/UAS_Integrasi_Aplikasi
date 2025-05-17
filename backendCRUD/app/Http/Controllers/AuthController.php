<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cari user berdasarkan email dan role yang diperbolehkan
        $user = User::where('email', $request->email)
            ->whereIn('role', ['admin', 'service_provider'])
            ->first();

        // Cek apakah user ada dan password sesuai
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // Redirect sesuai role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'service_provider') {
                return redirect()->route('provider.dashboard');
            }
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah, atau Anda tidak punya akses.',
        ])->withInput();
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
