<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Tampilkan List Pembayaran (Admin Dashboard)
     */
    public function index()
    {
        $methods = PaymentMethod::all();
        return view('admin.payment.index', compact('methods'));
    }

    /**
     * Tampilkan Form Tambah
     */
    public function create()
    {
        return view('admin.payment.create');
    }

    /**
     * Simpan Data Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'account_number' => 'nullable|string',
            'account_holder' => 'nullable|string',
            'type'           => 'required',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'instructions'   => 'nullable|string',
            'admin_fee'      => 'nullable|numeric',
            'is_active'      => 'required|boolean'

        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('payment-logos', 'public');
            $validated['logo'] = $path;
        }

        $validated['is_active'] = true; // Default aktif

        PaymentMethod::create($validated);

        // PERBAIKAN DI SINI: Ganti 'payment-methods.index' jadi 'payments.index'
        return redirect()->route('admin.payment.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    /**
     * Tampilkan Form Edit
     */
    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return view('admin.payment.edit', compact('method'));
    }

    /**
     * Update Data
     */
    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'account_number' => 'nullable|string',
            'account_holder' => 'nullable|string',
            'type'           => 'required',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'instructions'   => 'nullable|string',
            'admin_fee'      => 'nullable|numeric',
            'is_active'      => 'required|boolean'
        ]);

        if ($request->hasFile('logo')) {
            // Hapus logo lama
            if ($method->logo && Storage::disk('public')->exists($method->logo)) {
                Storage::disk('public')->delete($method->logo);
            }
            // Upload baru
            $path = $request->file('logo')->store('payment-logos', 'public');
            $validated['logo'] = $path;
        }

        $method->update($validated);

        // PERBAIKAN DI SINI: Ganti 'payment-methods.index' jadi 'payments.index'
        return redirect()->route('admin.payment.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    /**
     * Hapus Data
     */
    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);

        if ($method->logo && Storage::disk('public')->exists($method->logo)) {
            Storage::disk('public')->delete($method->logo);
        }

        $method->delete();

        // PERBAIKAN DI SINI: Ganti 'payment-methods.index' jadi 'payments.index'
        return redirect()->route('admin.payment.index')
            ->with('success', 'Metode pembayaran dihapus.');
    }
}