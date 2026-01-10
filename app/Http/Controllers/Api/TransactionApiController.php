<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TransactionApiController extends Controller
{
    // 1. Ambil Daftar Transaksi User
    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->with(['items.product']) // Eager load item & produk
            ->orderBy('created_at', 'desc')
            ->get();

        // Append accessor URL bukti bayar & format rupiah
        $transactions->each(function ($trx) {
            // 1. Append atribut milik Transaksi
            $trx->append(['grand_total_format', 'payment_proof_url']);
            
            // 2. Loop setiap items di dalam transaksi untuk append atribut Produk
            $trx->items->each(function ($item) {
                if ($item->product) {
                    $item->product->append(['cover_url', 'price_format']);
                }
            });
        });
        Log::info('data transaksi', ['data' => $transactions]);

        return response()->json([
            'data' => $transactions
        ]);
    }

    // 2. Ambil Detail 1 Transaksi
    public function show($invoiceCode)
    {
        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->where('user_id', Auth::id())
            ->with(['items.product'])
            ->firstOrFail();

        $transaction->append(['grand_total_format', 'payment_proof_url']);
        // Loop items untuk append atribut produk
        $transaction->items->each(function ($item) {
            if ($item->product) {
                $item->product->append(['cover_url', 'price_format']);
            }
        });
        return response()->json([
            'data' => $transaction
        ]);
    }

    // 3. Upload Bukti Bayar
    public function uploadProof(Request $request, $invoiceCode)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->hasFile('payment_proof')) {
            // Hapus file lama jika ada
            if ($transaction->payment_proof) {
                Storage::delete('public/' . $transaction->payment_proof);
            }

            // Simpan file baru
            $path = $request->file('payment_proof')->store('payments', 'public');

            $transaction->update([
                'payment_proof' => $path,
                'status' => 'waiting_confirmation' // Update status agar admin tahu
            ]);
        }

        return response()->json(['message' => 'Bukti pembayaran berhasil diupload.']);
    }
}