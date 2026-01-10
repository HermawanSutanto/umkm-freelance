<?php

namespace App\Http\Controllers;

use App\Models\ProductPromotion;
use Illuminate\Http\Request;

class AdminPromotionController extends Controller
{
    //
    public function index()
    {
        $requests = ProductPromotion::with(['product', 'user'])
                    ->where('status', 'pending')
                    ->latest()
                    ->get();
                    
        return view('admin.promotions.index', compact('requests'));
    }

    // Aksi Approve
    public function approve($id)
    {
        // 1. Ambil data request promosi
        $promotion = ProductPromotion::findOrFail($id);
        
        // 2. Update status promosi jadi Approved
        $promotion->update(['status' => 'approved']);

        // 3. BARU Update Produk Asli (Aktifkan Highlight)
        $levelMap = ['bronze' => 1, 'silver' => 2, 'gold' => 3];
        
        $promotion->product->update([
            'highlight_level' => $levelMap[$promotion->plan],
            // Tambahkan durasi dari hari ini (atau perpanjang jika masih aktif)
            'highlight_expires_at' => now()->addDays($promotion->duration_days),
        ]);

        return back()->with('success', 'Produk berhasil di-highlight!');
    }

    // Aksi Reject
    public function reject(Request $request, $id)
    {
        $promotion = ProductPromotion::findOrFail($id);
        $promotion->update([
            'status' => 'rejected',
            'admin_note' => $request->note // Alasan penolakan
        ]);

        return back()->with('success', 'Permintaan ditolak.');
    }
}
