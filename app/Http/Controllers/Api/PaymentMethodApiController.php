<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodApiController extends Controller
{
    /**
     * PUBLIC: List Pembayaran untuk Customer (Halaman Checkout)
     * Hanya menampilkan yang is_active = true
     */
    public function index()
    {
        $methods = PaymentMethod::where('is_active', true)
            ->orderBy('id', 'asc') // atau orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $methods
        ]);
    }

    /**
     * ADMIN: List Semua Pembayaran (Termasuk yang Non-Aktif)
     */
    public function adminIndex()
    {
        $methods = PaymentMethod::all();
        return response()->json([
            'success' => true,
            'data'    => $methods
        ]);
    }

    /**
     * ADMIN: Tambah Metode Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|unique:payment_methods,code',
            'type'           => 'required|in:bank_transfer,ewallet,virtual_account,cod',
            'account_number' => 'nullable|string',
            'account_holder' => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'instructions'   => 'nullable|string',
            'admin_fee'      => 'nullable|numeric',
        ]);

        // Upload Logo jika ada
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('payment-logos', 'public');
            $validated['logo'] = $path;
        }

        // Default is_active = true
        $validated['is_active'] = true;

        $method = PaymentMethod::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Metode pembayaran berhasil ditambahkan.',
            'data'    => $method
        ], 201);
    }

    /**
     * ADMIN: Update Metode Pembayaran
     */
    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|unique:payment_methods,code,' . $id,
            'type'           => 'required',
            'account_number' => 'nullable|string',
            'account_holder' => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'instructions'   => 'nullable|string',
            'admin_fee'      => 'nullable|numeric',
            'is_active'      => 'boolean' // Bisa update status aktif/nonaktif disini
        ]);

        // Logic Ganti Logo
        if ($request->hasFile('logo')) {
            // 1. Hapus logo lama jika ada (dan bukan default)
            if ($method->logo && Storage::disk('public')->exists($method->logo)) {
                Storage::disk('public')->delete($method->logo);
            }
            
            // 2. Upload logo baru
            $path = $request->file('logo')->store('payment-logos', 'public');
            $validated['logo'] = $path;
        }

        $method->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
            'data'    => $method
        ]);
    }

    /**
     * ADMIN: Hapus Metode Pembayaran
     */
    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);

        // Hapus file gambar logo dari storage
        if ($method->logo && Storage::disk('public')->exists($method->logo)) {
            Storage::disk('public')->delete($method->logo);
        }

        $method->delete();

        return response()->json([
            'success' => true,
            'message' => 'Metode pembayaran berhasil dihapus.'
        ]);
    }
}