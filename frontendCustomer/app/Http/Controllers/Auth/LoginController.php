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

        $response = Http::post("{$apiBase}/login", [
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

        // Simpan token dan data user di session untuk autentikasi frontend
        Session::put('token', $token);
        if ($user) {
            Session::put('user', $user);
        }

        // **Tambahkan login Laravel agar middleware 'auth' mengenali user**
        if ($user) {
            $userModel = \App\Models\User::where('email', $user['email'])->first();
            if ($userModel) {
                \Illuminate\Support\Facades\Auth::login($userModel);
            }
        }

        if (isset($user['role']) && $user['role'] === 'customer') {
            return redirect()->route('customer.landingpage');
        }

        return redirect()->route('landing');
    }

    // Logout: hapus session
    public function logout()
    {
        Session::flush();
        return redirect()->route('landing');
    }
}
