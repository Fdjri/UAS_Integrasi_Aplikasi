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
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $apiBase = config('services.backend.url');

        $response = Http::post("{$apiBase}/register/customer", [
            'name'                  => $request->name,
            'username'              => $request->username,
            'email'                 => $request->email,
            'password'              => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful()) {
            return redirect()
                ->route('login')
                ->with('success', 'Registrasi berhasil, silakan login.');
        }

        $message = $response->json('message', 'Registrasi gagal, silakan coba lagi.');
        return back()->withErrors(['register' => $message])->withInput();
    }
}
