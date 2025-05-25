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

    // Tampilkan halaman register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)
            ->whereIn('role', ['admin', 'service_provider', 'customer'])
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah, atau Anda tidak punya akses.',
            ], 401);
        }

        // Jika admin atau service_provider: redirect ke dashboard (web)
        if (in_array($user->role, ['admin', 'service_provider'])) {
            Auth::login($user);
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role === 'service_provider') {
                return redirect()->route('provider.dashboard');
            }
        }

        // Jika customer: buat token API dan kembalikan JSON (untuk SPA frontend)
        if ($user->role === 'customer') {
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'message' => 'Login berhasil',
            ]);
        }

        // Fallback (jika ada role lain tidak diketahui)
        return response()->json([
            'message' => 'Role tidak dikenal',
        ], 403);
    }

    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    
    // Proses registrasi khusus service provider
    public function register(Request $request)
    {
        // Validasi input registrasi
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Buat user baru dengan role service_provider
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'service_provider', // hanya role service_provider
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        return redirect()->route('provider.dashboard')->with('success', 'Registrasi berhasil, selamat datang!');
    }

    // Proses registrasi customer
    public function registerCustomer(Request $request)
    {
        // Validasi input registrasi
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Buat user baru dengan role customer
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // hanya role customer
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        return redirect()->route('customer.dashboard')->with('success', 'Registrasi berhasil, selamat datang!');
    }
}
