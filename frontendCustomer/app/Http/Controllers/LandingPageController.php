<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    public function landingPage(Request $request)
    {
        $services = [];
        $user = session('user');
        $isAuthenticated = !empty($user);
        $token = session('token'); // token jika login
        $apiBase = config('services.backend.url');

        // Ambil service_type dari query param, default 'hotel'
        $serviceType = $request->query('service_type', 'hotel');
        $endpoint = $isAuthenticated ? 'services' : 'services/public';

        try {
            if ($isAuthenticated && $token) {
                $response = Http::withToken($token)->get("{$apiBase}/{$endpoint}", [
                    'service_type' => $serviceType,
                ]);
            } else {
                $response = Http::get("{$apiBase}/{$endpoint}", [
                    'service_type' => $serviceType,
                ]);
            }

            if ($response->successful()) {
                $services = $response->json('services', []);
            } else {
                Log::warning('API services request failed, status: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error fetching services: ' . $e->getMessage());
        }

        return view('customer.landingpage', compact('services', 'user', 'serviceType'));
    }
}
