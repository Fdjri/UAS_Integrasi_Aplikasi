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
        // Validasi input: pakai email & password
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Baca base URL dari config/services.php (services.backend.url)
        $apiBase = config('services.backend.url');

        // Kirim POST ke http://127.0.0.1:8000/api/login
        $response = Http::post("{$apiBase}/login", [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        // Jika gagal, kembali dengan error
        if (! $response->successful()) {
            return back()->withErrors([
                'email' => 'Login gagal, silakan periksa kembali email & password Anda.'
            ]);
        }

        // Ambil respons JSON
        $data = $response->json();

        // Token & user data sesuai respons API Anda
        $token = $data['access_token'] ?? null;
        $user  = $data['user']         ?? null;

        if (! $token) {
            return back()->withErrors([
                'email' => 'Login gagal: token tidak ditemukan pada respons API.'
            ]);
        }

        // Simpan ke session
        Session::put('token', $token);
        if ($user) {
            Session::put('user', $user);
        }

        // Redirect berdasar role
        if (isset($user['role']) && $user['role'] === 'customer') {
            return redirect()->route('customer.landingpage');
        }
    }

    // Logout: hapus session
    public function logout()
    {
        Session::flush();
        return redirect()->route('landing');
    }
}
