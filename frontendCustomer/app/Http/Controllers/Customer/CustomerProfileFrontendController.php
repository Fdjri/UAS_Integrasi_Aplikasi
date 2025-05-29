<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CustomerProfileFrontendController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_base_url', 'http://localhost:8000') . '/api';
    }

    // ✅ Ambil data profile dari API
    public function index()
    {
        // Ambil token API yang disimpan saat login frontend
        $token = session('token');

        // Panggil API backend untuk ambil profil
        $response = Http::withToken($token)
            ->acceptJson()
            ->get($this->apiBaseUrl . '/customer/profile');

        // Jika gagal, tampilkan pesan error
        if ($response->failed()) {
            return redirect()->back()->withErrors('Gagal mengambil data profil.');
        }

        // Ambil isi response sebagai array profil
        $profile = $response->json();

        return view('customer.profiles.profile', compact('profile'));
    }

    // ✅ Update data profile ke API
    public function update(Request $request)
    {
        $token = session('token'); // Ambil token API dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan. Silakan login ulang.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
        ]);

        $response = Http::withToken($token)
            ->acceptJson()
            ->put($this->apiBaseUrl . '/customer/profile', $validatedData);

        if ($response->failed()) {
            return redirect()->back()->withErrors('Gagal memperbarui profil.');
        }

        return redirect()->route('customer.profile.frontend')->with('success', 'Profil berhasil diperbarui.');
    }
}
