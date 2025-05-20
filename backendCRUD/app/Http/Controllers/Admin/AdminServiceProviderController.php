<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ServiceProviderProfile;
use App\Models\Service;

class AdminServiceProviderController extends Controller
{
    public function __construct()
    {
        // Middleware hanya admin yang bisa akses
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Ambil semua service provider user beserta profil dan services mereka
        $serviceProviders = User::with(['serviceProviderProfile', 'services'])
            ->where('role', 'service_provider')
            ->get();

        return view('admin.service_providers.index', compact('serviceProviders'));
    }

    public function show($id)
    {
        // Ambil user service provider lengkap dengan profile dan services
        $provider = User::with(['serviceProviderProfile', 'services'])
            ->where('role', 'service_provider')
            ->findOrFail($id);

        return view('admin.service_providers.show', compact('provider'));
    }
}
