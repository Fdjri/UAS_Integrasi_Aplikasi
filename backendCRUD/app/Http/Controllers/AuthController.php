<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CustomerProfile;

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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mencari user berdasarkan email dan role
        $user = User::where('email', $request->email)
                    ->whereIn('role', ['customer', 'admin', 'service_provider'])
                    ->first();

        // Jika user tidak ditemukan atau password tidak cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah, atau Anda tidak punya akses.',
            ], 401);
        }

        // Login untuk admin & service_provider via session Laravel
        if (in_array($user->role, ['admin', 'service_provider'])) {
            Auth::login($user);  
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard'); 
            }
            if ($user->role === 'service_provider') {
                return redirect()->route('provider.dashboard'); 
            }
        }
    }

    public function loginCustomer(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mencari user dengan role customer
        $user = User::where('email', $request->email)
                    ->where('role', 'customer') 
                    ->first();

        // Jika user tidak ditemukan atau password tidak cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah, atau Anda tidak punya akses.',
            ], 401);
        }

        // Hapus token lama
        $user->tokens()->delete();

        // Buat token baru untuk customer
        $token = $user->createToken('auth_token')->plainTextToken;

        // Kembalikan response dengan token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'message' => 'Login berhasil',
        ]);
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

    // Registrasi khusus customer (API)
    public function registerCustomer(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // nama untuk customer_profiles
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Simpan ke tabel users
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        // Simpan ke tabel customer_profiles
        CustomerProfile::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'address' => null,
            'phone_number' => null,
            'birth_date' => null,
        ]);

        // Buat token login otomatis
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

}
