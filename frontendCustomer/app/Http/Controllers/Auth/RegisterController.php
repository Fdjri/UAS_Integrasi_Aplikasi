<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        //  dd($request->all());
        $apiBase = config('services.backend.url');

        $response = Http::post("{$apiBase}/register/customer", [
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful()) {
            // redirect ke halaman login dengan pesan sukses
            return redirect()
                ->route('login')
                ->with('success', 'Registrasi berhasil, silakan login.');
        }

        // Ambil pesan error jika tersedia
        $message = $response->json('message', 'Registrasi gagal, silakan coba lagi.');

        return back()->withErrors(['register' => $message])->withInput();
    }
}
