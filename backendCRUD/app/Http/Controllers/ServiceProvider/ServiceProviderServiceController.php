<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ServiceProviderServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:service_provider']);
    }

    // Tampilkan daftar service milik service provider yang login
    public function index()
    {
        $userId = Auth::id();
        $services = Service::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('provider.services.index', compact('services'));
    }

    // Simpan service baru dari modal
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $service = new Service();
        $service->user_id = Auth::id();
        $service->title = $request->title;
        $service->description = $request->description;
        $service->price = $request->price;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('services', 'public');
            $service->photo = $path;
        }

        $service->save();

        return redirect()->route('provider.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    // Update service dari modal
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $service = Service::where('service_id', $id)->where('user_id', $userId)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $service->title = $request->title;
        $service->description = $request->description;
        $service->price = $request->price;

        if ($request->hasFile('photo')) {
            if ($service->photo) {
                \Storage::disk('public')->delete($service->photo);
            }
            $path = $request->file('photo')->store('services', 'public');
            $service->photo = $path;
        }

        $service->save();

        return redirect()->route('provider.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    // Hapus service dari modal
    public function destroy($id)
    {
        $userId = Auth::id();
        $service = Service::where('service_id', $id)->where('user_id', $userId)->firstOrFail();

        if ($service->photo) {
            \Storage::disk('public')->delete($service->photo);
        }

        $service->delete();

        return redirect()->route('provider.services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
