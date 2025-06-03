<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class CustomerServiceController extends Controller
{
    /**
     * Mengembalikan data layanan berdasarkan service_type yang diminta.
     * Endpoint publik, tanpa autentikasi.
     */
    public function publicServices(Request $request)
    {
        $serviceType = $request->input('service_type', null);

        $query = Service::with('providerProfile');

        if ($serviceType) {
            // Filter berdasarkan service_type dari relasi providerProfile
            $query->whereHas('providerProfile', function ($q) use ($serviceType) {
                $q->where('service_type', ucfirst($serviceType)); // Contoh: 'Hotel', 'Event', 'Transportasi'
            });
        }

        $services = $query->get();

        // Transform hasil agar photo_url lengkap dan sesuai frontend
        $services->transform(function ($service) {
            return [
                'service_id' => $service->service_id,
                'title' => $service->title,
                'price' => $service->price,
                'photo_url' => $service->photo ? asset('storage/' . $service->photo) : asset('images/bg1.jpg'),
                'service_address' => $service->service_address ?? null,
            ];
        });

        return response()->json(['services' => $services]);
    }

    /**
     * Endpoint dengan autentikasi, misal untuk user login.
     * Bisa diakses lewat middleware auth:sanctum.
     */
    public function index(Request $request)
    {
        $query = Service::with('providerProfile');

        if ($request->filled('service_type')) {
            $serviceType = $request->input('service_type');
            $query->whereHas('providerProfile', function ($q) use ($serviceType) {
                $q->where('service_type', ucfirst($serviceType));
            });
        }

        $services = $query->get();

        $services->transform(function ($service) {
            return [
                'service_id' => $service->service_id,
                'title' => $service->title,
                'service_type' => $service->service_type,
                'price' => $service->price,
                'photo_url' => $service->photo ? asset('storage/' . $service->photo) : asset('images/bg1.jpg'),
                'service_address' => $service->service_address ?? null,
            ];
        });

        return response()->json(['services' => $services]);
    }

    public function show($serviceId)
    {
        $service = Service::with('providerProfile')->find($serviceId);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $serviceData = [
            'service_id' => $service->service_id,
            'title' => $service->title,
            'service_type' => $service->service_type,
            'description' => $service->description,
            'price' => $service->price,
            'photo_url' => $service->photo ? asset('storage/' . $service->photo) : asset('images/bg1.jpg'),
            'service_address' => $service->service_address ?? null,
            'provider' => $service->providerProfile ? [
                'company_name' => $service->providerProfile->company_name ?? null,
                'business_phone' => $service->providerProfile->business_phone ?? null,
                'business_address' => $service->providerProfile->business_address ?? null,
            ] : null,
        ];

        return response()->json(['service' => $serviceData]);
    }

}
