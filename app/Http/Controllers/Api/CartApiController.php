<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Untuk generate invoice code acak
use Symfony\Component\HttpFoundation\Response; // Import Response status code

class CartApiController extends Controller
{
    /**
     * Tampilkan Detail Keranjang Belanja Pengguna.
     * GET /api/cart
     */
    public function index()
    {
        // Ambil user yang sedang login melalui Sanctum
        $user = Auth::user();

        // Cari atau buat keranjang, lalu muat item dan produk terkait
        $cart = $user->cart()
                     ->with(['items' => function($query) {
                        $query->with('product:id,name,slug,cover_image,price,stock,user_id'); // Eager load kolom penting Product
                     }])
                     ->firstOrCreate(['user_id' => $user->id]);

        $cart->items->each(function ($item) {
        if ($item->product) {
            $item->product->append('cover_url');
        }
        });
        $methods = PaymentMethod::where('is_active', true)
            ->orderBy('id', 'asc') // atau orderBy('name')
            ->get();

        // return response()->json([
        //     'success' => true,
        //     'data'    => $methods
        // ]);
        // Mengembalikan keranjang dengan total harga yang dihitung via accessor
        return response()->json([
            'message' => 'Detail keranjang berhasil dimuat.',
            'cart' => $cart,
            'total_price_formatted' => $cart->total_price_format, // Menggunakan Accessor
            'total_price_numeric' => $cart->total_price,
            'payment_method'=>$cart->method
            
        ], Response::HTTP_OK);
    }

    /**
     * Tambahkan Produk ke Keranjang atau Update Kuantitas.
     * POST /api/cart/add
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $productId = $validated['product_id'];
        $quantity = $validated['quantity'];
        
        $product = Product::findOrFail($productId);
        
        // Keamanan: Cek status dan stok produk
        if (!$product->is_active) {
            return response()->json(['message' => 'Produk tidak aktif.'], Response::HTTP_BAD_REQUEST);
        }
        if ($product->stock < $quantity) {
             return response()->json(['message' => 'Stok produk tidak mencukupi.'], Response::HTTP_BAD_REQUEST);
        }

        // 2. Ambil/Buat Keranjang
        $cart = $user->cart()->firstOrCreate(['user_id' => $user->id]);

        DB::beginTransaction();
        try {
            // 3. Cek apakah Item sudah ada
            $existingItem = $cart->items()->where('product_id', $productId)->first();

            if ($existingItem) {
                // Update: Tambahkan kuantitas baru
                $newQuantity = $existingItem->quantity + $quantity;
                
                if ($product->stock < $newQuantity) {
                    DB::rollBack();
                    return response()->json(['message' => 'Permintaan melebihi stok yang tersedia.'], Response::HTTP_BAD_REQUEST);
                }

                $existingItem->update([
                    'quantity' => $newQuantity,
                    // Opsional: Update price_at_purchase jika harga produk berubah
                    'price_at_purchase' => $product->price, 
                ]);

            } else {
                // Insert: Tambah Item Baru
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price_at_purchase' => $product->price, 
                ]);
            }

            DB::commit();
            
            // Muat ulang keranjang untuk mendapatkan total terbaru
            $cart->load('items.product');

            return response()->json([
                'message' => 'Produk berhasil ditambahkan/diperbarui di keranjang.',
                'cart' => $cart,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal memproses keranjang: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Hapus Item dari Keranjang.
     * DELETE /api/cart/remove/{cartItemId}
     */
    public function destroy($cartItemId)
    {
        $user = Auth::user();
        
        // Cari item dan pastikan kepemilikannya (Double check security)
        $item = CartItem::with('cart')->findOrFail($cartItemId);

        if ($item->cart->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak.'], Response::HTTP_FORBIDDEN);
        }

        $item->delete();

        // Muat ulang keranjang untuk mendapatkan total terbaru
        $cart = $user->cart()->with('items.product')->first();

        return response()->json([
            'message' => 'Item berhasil dihapus dari keranjang.',
            'cart' => $cart
        ], Response::HTTP_OK);
    }

    /**
     * Update Kuantitas Spesifik Item di Keranjang.
     * PUT /api/cart/update/{cartItemId}
     */
    public function updateQuantity(Request $request, $cartItemId)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $newQuantity = $validated['quantity'];

        $item = CartItem::with('cart', 'product')->findOrFail($cartItemId);

        // Keamanan: Cek kepemilikan
        if ($item->cart->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak.'], Response::HTTP_FORBIDDEN);
        }
        
        $product = $item->product;

        // Cek Stok untuk kuantitas baru
        if ($product->stock < $newQuantity) {
            return response()->json(['message' => 'Stok produk tidak mencukupi untuk kuantitas ini.'], Response::HTTP_BAD_REQUEST);
        }

        $item->update(['quantity' => $newQuantity]);

        // Muat ulang keranjang untuk mendapatkan total terbaru
        $cart = $user->cart()->with('items.product')->first();
        
        return response()->json([
            'message' => 'Kuantitas item berhasil diperbarui.',
            'cart' => $cart
        ], Response::HTTP_OK);
    }

    /**
     * Kosongkan seluruh Keranjang (Clear Cart).
     * DELETE /api/cart
     */
    public function clearCart()
    {
        $user = Auth::user();
        $cart = $user->cart()->first();

        if (!$cart) {
            return response()->json(['message' => 'Keranjang sudah kosong.'], Response::HTTP_NOT_FOUND);
        }

        // Hapus semua item yang memiliki cart_id ini
        $cart->items()->delete();
        
        // Muat ulang keranjang
        $cart->load('items');

        return response()->json([
            'message' => 'Keranjang berhasil dikosongkan.',
            'cart' => $cart
        ], Response::HTTP_OK);
    }
    /**
     * Proses Checkout (Membuat Transaksi dari Keranjang).
     * POST /api/checkout
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            // 'shipping_address' => 'required|string|max:500',
            'courier'          => 'nullable|string', // JNE, J&T, dll (Bisa null dulu jika belum ada fitur cek ongkir)
            'service'          => 'nullable|string', // REG, YES, dll
            'shipping_price'   => 'nullable|numeric|min:0', // Ongkir (Bisa di-pass dari frontend atau hitung di backend)
        ]);

        // 2. Ambil Keranjang User
        $cart = $user->cart()->with('items.product')->first();
        $profile = $user->customerProfile;
        if ($profile) {
            $shippingAddress = implode(', ', array_filter([
            $profile->address,
            $profile->city,
            $profile->province,
            $profile->postal_code
        ]));
        }else{
            return response()->json(['message'=> 'Harap isi data profil terlebih dahulu'], Response::HTTP_NOT_FOUND);
        }
        
        // Cek apakah keranjang kosong
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Keranjang belanja kosong.'], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            // 3. Cek Stok Terakhir Sebelum Transaksi (PENTING!)
            // Mencegah user checkout barang yang sudah habis duluan dibeli orang lain
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception("Stok produk '{$item->product->name}' tidak mencukupi. Sisa stok: {$item->product->stock}");
                }
            }

            // 4. Hitung Total
            $totalPrice = $cart->items->sum(function($item) {
                return $item->quantity * $item->product->price; // Pakai harga asli produk saat ini
            });

            $shippingPrice = $request->shipping_price ?? 0; // Default 0 jika tidak dikirim
            $grandTotal = $totalPrice + $shippingPrice;

            // 5. Buat Record Transaksi Utama (Invoice)
            $transaction = Transaction::create([
                'user_id'          => $user->id,
                'invoice_code'     => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'total_price'      => $totalPrice,
                'shipping_price'   => $shippingPrice,
                'grand_total'      => $grandTotal,
                'payment_method'   => 'manual_transfer', // Default sesuai request Anda
                'status'           => 'pending_payment', // Status awal: Menunggu Pembayaran/Upload Bukti
                'shipping_address' => $shippingAddress,
                'courier'          => $request->courier,
                'service'          => $request->service,
            ]);

            // 6. Pindahkan Item Keranjang ke Item Transaksi & Kurangi Stok
            foreach ($cart->items as $item) {
                TransactionItem::create([
                    'transaction_id'    => $transaction->id,
                    'product_id'        => $item->product_id,
                    'quantity'          => $item->quantity,
                    'price_at_purchase' => $item->product->price, // Simpan harga SAAT INI (Snapshot)
                ]);

                // KURANGI STOK PRODUK
                $item->product->decrement('stock', $item->quantity);
            }

            // 7. Kosongkan Keranjang
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'message' => 'Checkout berhasil. Silakan lakukan pembayaran.',
                'transaction_id' => $transaction->id,
                'invoice_code' => $transaction->invoice_code,
                'grand_total' => $transaction->grand_total_format, // Format Rupiah dari Model
                'redirect_url' => '/transaksi/' . $transaction->invoice_code // Opsional: Untuk redirect frontend
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memproses checkout: ' . $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}