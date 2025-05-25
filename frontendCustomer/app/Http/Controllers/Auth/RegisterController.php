<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    // Tampilkan form register
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Proses register: kirim data ke API backendCRUD
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $apiBase = config('services.backend.url');
        $response = Http::post("{$apiBase}/register/customer", [
            'username'              => $request->username,
            'email'                 => $request->email,
            'password'              => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful()) {
            // redirect ke halaman login
            return redirect()
                ->route('login')
                ->with('success', 'Registrasi berhasil, silakan login.');
        }

        return back()->withErrors([
            'register' => 'Registrasi gagal, silakan coba lagi.'
        ]);
    }

}
