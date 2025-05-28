<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DetailController extends Controller
{
    public function show(Request $request, $serviceId)
    {
        $user = Session::get('user');
        $token = Session::get('token');

        if (!$user || !$token) {
            // Redirect ke login jika belum login
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat detail layanan.');
        }

        $apiBase = config('services.backend.url');

        try {
            $response = Http::withToken($token)->get("{$apiBase}/services/{$serviceId}");

            if (!$response->successful()) {
                abort(404, 'Layanan tidak ditemukan');
            }

            $service = $response->json('service', []);

        } catch (\Exception $e) {
            // Log error atau tampilkan halaman error sesuai kebutuhan
            abort(500, 'Terjadi kesalahan saat mengambil data layanan.');
        }

        return view('customer.detail', compact('service', 'user'));
    }
}
