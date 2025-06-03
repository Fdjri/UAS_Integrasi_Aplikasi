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
                // Ambil services, pastikan array berisi photo_url lengkap
                $services = $response->json('services', []);

                // Contoh debugging: Log::info('Services: ', $services);
            } else {
                Log::warning('API services request failed, status: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error fetching services: ' . $e->getMessage());
        }

        return view('customer.landingpage', compact('services', 'user', 'serviceType'));
    }

    public function show(Request $request, $serviceId)
    {
        $user = session('user');
        $isAuthenticated = !empty($user);
        $token = session('token'); // token jika user sudah login
        $apiBase = config('services.backend.url');

        try {
            if ($isAuthenticated && $token) {
                $response = Http::withToken($token)
                    ->get("{$apiBase}/services/{$serviceId}");
            } else {
                $response = Http::get("{$apiBase}/services/{$serviceId}");
            }

            if ($response->successful()) {
                $service = $response->json('service'); // harus ada key 'photo_url' di API
            } else {
                $service = null;
                \Log::warning('API service detail request failed, status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $service = null;
            \Log::error('Error fetching service detail: ' . $e->getMessage());
        }

        if (!$service) {
            abort(404, 'Service tidak ditemukan');
        }

        return view('customer.detail', compact('service'));
    }
}
