<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPromotion;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{   
   public function index()
    {
        $user = Auth::user();
        Log::info('Role Checker', ['role' => Auth::user()->role === 'admin']);

        // Inisialisasi variabel default
        $products = [];
        $portfolios = [];
        $adminStats = [];
        $pendingUsers = [];
        
        // Statistik Transaksi (Default 0)
        $transactionStats = [
            'waiting_confirmation' => 0, // Perlu Cek Bukti Bayar
            'processed' => 0,            // Perlu Dikemas
            'shipped' => 0,              // Sedang Dikirim
            'completed' => 0,            // Selesai
        ];

        // --- LOGIC MITRA (PENJUAL PRODUK) ---
        if ($user->role === 'mitra') {
            $products = $user->products()->latest()->paginate(5);

            // Hitung transaksi yang item-nya adalah milik Mitra ini
            // Query: Ambil Transaksi -> Cek Item -> Cek Produk -> Cek User ID pemilik produk
            $baseTransactionQuery = Transaction::whereHas('items.product', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

            $transactionStats['waiting_confirmation'] = (clone $baseTransactionQuery)->where('status', 'waiting_confirmation')->count();
            $transactionStats['processed']            = (clone $baseTransactionQuery)->where('status', 'processed')->count();
            $transactionStats['shipped']              = (clone $baseTransactionQuery)->where('status', 'shipped')->count();
            $transactionStats['completed']            = (clone $baseTransactionQuery)->where('status', 'completed')->count();
        } 
        
        // --- LOGIC FREELANCER ---
        elseif ($user->role === 'freelancer') {
            $portfolios = $user->portfolios()->latest()->paginate(6);
            // Jika freelancer punya sistem order jasa, tambahkan logic serupa di sini
        } 
        
        // --- LOGIC ADMIN ---
        elseif ($user->role === 'admin') {
            $products = Product::latest()->paginate(5);
            $total_user = User::whereIn('role', ['mitra', 'freelancer'])->count();
            
            // Statistik Umum
            $adminStats = [
                'total_users' => $total_user,
                'total_mitra' => User::where('role', 'mitra')->count(),
                'total_freelancer' => User::where('role', 'freelancer')->count(),
                'pending_promotions' => ProductPromotion::where('status', 'pending')->count(),
            ];

            // Admin melihat SEMUA transaksi
            $transactionStats['waiting_confirmation'] = Transaction::where('status', 'waiting_confirmation')->count();
            $transactionStats['processed']            = Transaction::where('status', 'processed')->count();
        }

        return view("dashboard", compact(
            "products", 
            "portfolios", 
            "adminStats", 
            "pendingUsers",
            "transactionStats" // Passing variabel baru ke view
        ));
    }
}
