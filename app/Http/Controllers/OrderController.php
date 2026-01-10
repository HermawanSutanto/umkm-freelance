<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan Daftar Pesanan
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::with(['user', 'items.product'])->latest();

        // 1. Filter Khusus Mitra: Hanya tampilkan transaksi yang mengandung produk mereka
        if ($user->role === 'mitra') {
            $query->whereHas('items.product', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // 2. Filter Status (Optional, dari URL ?status=xxx)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan Detail Pesanan & Bukti Bayar
     */
    public function show($invoiceCode)
    {
        $user = Auth::user();
        
        $order = Transaction::where('invoice_code', $invoiceCode)
            ->with(['user', 'items.product'])
            ->firstOrFail();

        // Security Check: Mitra tidak boleh intip transaksi orang lain yang tidak ada hubungannya
        if ($user->role === 'mitra') {
            $isMyOrder = $order->items->contains(function ($item) use ($user) {
                return $item->product->user_id === $user->id;
            });

            if (!$isMyOrder) {
                abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
            }
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Update Status & Nomor Resi
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'resi_number' => 'nullable|string',
        ]);

        $order = Transaction::findOrFail($id);

        // Update Data
        $order->status = $request->status;
        
        // Jika status dikirim, simpan resi
        if ($request->status == 'shipped' && $request->resi_number) {
            $order->resi_number = $request->resi_number;
        }

        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}