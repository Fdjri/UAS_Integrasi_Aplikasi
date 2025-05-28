<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    // Tampilkan profile customer yang sedang login
    public function show()
    {
        $userId = Auth::id();

        $profile = CustomerProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found'
            ], 404);
        }

        return response()->json($profile);
    }

    // Update profile customer
    public function update(Request $request)
    {
        $userId = Auth::id();

        $profile = CustomerProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
        ]);

        $profile->update($validatedData);

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile,
        ]);
    }
}
