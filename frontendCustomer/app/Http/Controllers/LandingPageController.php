<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LandingPageController extends Controller
{
    public function landingPage()
    {
        // Base URL API yang sudah kamu definisikan di config/services.php
        $apiBase = config('services.backend.url');

        // Coba ambil daftar services dari backend
        try {
            // Jika butuh token:
            $response = Http::withToken(Session::get('token'))
                            ->get("{$apiBase}/services");

            if ($response->successful()) {
                // Asumsikan API mereturn { data: […] }
                $services = $response->json('data', []);
            } else {
                $services = [];
            }
        } catch (\Exception $e) {
            // kalau error koneksi/API
            $services = [];
        }

        // Kirim $services ke view
        return view('customer.landingpage', compact('services'));
    }
}
