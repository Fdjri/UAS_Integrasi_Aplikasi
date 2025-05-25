<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LandingPageController extends Controller
{
    public function landingPage()
{
    $services = [];

    // Ambil token dari session untuk autentikasi API jika perlu
    $token = session('token');

    $apiBase = config('services.backend.url');

    try {
        $response = Http::withToken($token)->get("{$apiBase}/services");

        if ($response->successful()) {
            $services = $response->json('data', []);
        }
    } catch (\Exception $e) {
        $services = [];
    }

    $user = session('user');

    return view('customer.landingpage', compact('services', 'user'));
}

}
