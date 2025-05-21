<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ServiceProviderProfile;
use App\Models\User;
use Illuminate\Validation\Rule;

class ServiceProviderProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:service_provider']);
    }

    /**
     * Tampilkan halaman profil (overview).
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil profile service provider sesuai user_id
        $profile = ServiceProviderProfile::where('user_id', $user->id)->first();

        return view('provider.profile.index', compact('user', 'profile'));
    }

    /**
     * Tampilkan form edit profile dengan data saat ini.
     */
    public function edit()
    {
        //
    }

    /**
     * Update data profil service provider, termasuk password jika diisi.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = ServiceProviderProfile::where('user_id', $user->id)->first();

        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'business_phone' => ['required', 'string', 'max:20'],
            'business_address' => ['required', 'string', 'max:500'],
            'service_type' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', 'min:6'],
        ]);

        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if (!$profile) {
            $profile = new ServiceProviderProfile();
            $profile->user_id = $user->id;
        }
        $profile->company_name = $request->company_name;
        $profile->business_phone = $request->business_phone;
        $profile->business_address = $request->business_address;
        $profile->service_type = $request->service_type;
        $profile->save();

        return redirect()->route('provider.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
