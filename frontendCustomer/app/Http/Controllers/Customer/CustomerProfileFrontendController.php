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
        // Sesuaikan ini dengan URL backend API kamu
        // Bisa dari .env misal API_BASE_URL=http://localhost:8000
        $this->apiBaseUrl = config('app.api_base_url', 'http://localhost:8000') . '/api';
    }

    public function index()
    {
        $user = Auth::user();

        // Ambil token akses user (Laravel Sanctum)
        $token = $user->currentAccessToken()?->token ?? null;

        // Jika pakai session-based auth tanpa token, kamu bisa setup cookie instead

        $response = Http::withToken($token)->get($this->apiBaseUrl . '/customer/profile');

        if ($response->failed()) {
            return redirect()->back()->withErrors('Gagal mengambil data profil.');
        }

        $profile = $response->json();

        return view('customer.profiles.profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $token = $user->currentAccessToken()?->token ?? null;

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
        ]);

        $response = Http::withToken($token)
            ->put($this->apiBaseUrl . '/customer/profile', $validatedData);

        if ($response->failed()) {
            return redirect()->back()->withErrors('Gagal memperbarui profil.');
        }

        return redirect()->route('customer.profile.frontend')->with('success', 'Profil berhasil diperbarui.');
    }
}
