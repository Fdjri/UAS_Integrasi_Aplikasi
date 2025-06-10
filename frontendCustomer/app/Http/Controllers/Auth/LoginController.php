<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login: kirim data ke API backendCRUD
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $apiBase = config('services.backend.url');

        $response = Http::post("{$apiBase}/login/customer", [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        if (!$response->successful()) {
            $message = $response->json('message', 'Login gagal, silakan coba lagi.');
            return back()->withErrors(['email' => $message])->withInput();
        }

        $data = $response->json();

        $token = $data['access_token'] ?? null;
        $user  = $data['user'] ?? null;

        if (!$token) {
            return back()->withErrors([
                'email' => 'Login gagal: token tidak ditemukan pada respons API.'
            ])->withInput();
        }

        // ✅ SIMPAN TOKEN KE SESSION
        session(['token' => $token]);

        // Opsional: Simpan user juga
        if ($user) {
            session(['user' => $user]);
        }

        // Login juga via Laravel auth lokal (agar Auth::user() tetap ada)
        $userModel = \App\Models\User::where('email', $user['email'])->first();
        if ($userModel) {
            \Illuminate\Support\Facades\Auth::login($userModel);
        }

        return redirect()->route('customer.landingpage');
    }

    // Logout: hapus session
    public function logout()
    {
        Session::flush();
        return redirect()->route('landing');
    }
}
