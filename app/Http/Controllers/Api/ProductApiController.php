<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Menampilkan Daftar Produk
     * Fitur: Pagination, Search (by Name), Filter (by Category Slug)
     */
    public function index(Request $request)
    {
        // 1. Mulai Query (Hanya produk aktif)
        // Kita load 'category' dan 'user.mitraProfile' untuk info toko
        $query = Product::with(['category', 'user.mitraProfile'])
            ->where('is_active', true);

        // 2. Fitur SEARCH (Berdasarkan Nama Produk)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        // 3. Fitur FILTER KATEGORI (Berdasarkan Slug Kategori)
        // Contoh: ?category=kuliner-makanan
        if ($request->has('category') && $request->category != '') {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }
        // 4. LOGIKA SORTING (BARU)
        // Parameter: 'terbaru', 'terlama', 'harga_rendah', 'harga_tinggi'
        $sort = $request->get('sort', 'terbaru'); // Default 'terbaru'
        switch ($sort) {
        case 'harga_rendah':
            // Urutkan berdasarkan PRICE (Harga Jual), bukan real_price
            $query->orderBy('price', 'asc'); 
            break;
        case 'harga_tinggi':
            $query->orderBy('price', 'desc');
            break;
        case 'terlama':
            $query->orderBy('created_at', 'asc');
            break;
        case 'terbaru':
        default:
            $query->latest(); // created_at desc
            break;
        }

        // 4. Urutkan dari yang terbaru
        $query->latest();

        // 5. Eksekusi dengan Pagination (10 per halaman)
        $products = $query->paginate(10);

        // 6. Custom Response (Agar struktur JSON rapi & URL gambar lengkap)
        // Kita map data collection-nya untuk memastikan URL gambar benar
        $data = $products->getCollection()->transform(function ($product) {
            return [
                'id'            => $product->id,
                'name'          => $product->name,
                'slug'          => $product->slug,
                'price'         => $product->price,
                'real_price'    => $product->real_price_format,
                'price_format'  => $product->price_format, // Dari Accessor Model
                'stock'         => $product->stock,
                'cover_url'     => $product->cover_url,    // Dari Accessor Model (URL Lengkap)
                'category_name' => $product->category ? $product->category->name : 'Uncategorized',
                'shop_name'     => $product->user->mitraProfile->shop_name ?? 'Toko UMKM',
                'shop_city'     => 'Jember', // Hardcode atau ambil dari profile jika ada column city
            ];
        });

        // Set data yang sudah ditransform kembali ke paginator
        $products->setCollection($data);

        return response()->json([
            'success' => true,
            'message' => 'Daftar produk berhasil diambil',
            'data'    => $products
        ], 200);
    }

    /**
     * Menampilkan Detail Produk
     * Termasuk: Galeri Gambar & Info Penjual Lengkap
     */
    public function show($slug)
    {
        // Cari produk berdasarkan SLUG
        $product = Product::with(['category', 'images', 'user.mitraProfile'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        // Format data detail
        $detail = [
            'id'           => $product->id,
            'name'         => $product->name,
            'slug'         => $product->slug,
            'description'  => $product->description,
            'price'        => $product->price,
            'real_price'   => $product->real_price_format,
            'price_format' => $product->price_format,
            'stock'        => $product->stock,
            'cover_url'    => $product->cover_url,
            'category'     => [
                'id'   => $product->category_id,
                'name' => $product->category ? $product->category->name : null,
            ],
            // Map Galeri Gambar (Ambil URL lengkap dari Accessor ProductImage)
            'gallery'      => $product->images->map(function ($img) {
                return [
                    'id'  => $img->id,
                    'url' => $img->image_url // Pastikan model ProductImage punya getImageUrlAttribute
                ];
            }),
            // Info Penjual (Mitra)
            'seller'       => [
                'name'         => $product->user->mitraProfile->shop_name ?? $product->user->name,
                'address'      => $product->user->mitraProfile->shop_address ?? '-',
                'phone'        => $product->user->mitraProfile->phone_number ?? '-',
                'whatsapp_url' => $this->generateWaUrl($product), // Helper function di bawah
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail produk berhasil diambil',
            'data'    => $detail
        ], 200);
    }

    /**
     * Helper: Generate Link WA Otomatis
     */
    private function generateWaUrl($product)
    {
        $phone = $product->user->mitraProfile->phone_number ?? '';
        
        if (empty($phone)) return null;

        // Ubah 08xx jadi 628xx
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }

        $text = "Halo, saya tertarik dengan produk *{$product->name}* yang ada di Lokalitas Market.";
        return "https://wa.me/{$phone}?text=" . urlencode($text);
    }
    public function getCategories()
{
    // Ambil id, name, slug, dan hitung jumlah produk di dalamnya
    $categories = Category::select('id', 'name', 'slug')
        ->withCount(['products' => function ($query) {
            $query->where('is_active', true); // Hanya hitung produk aktif
        }])
        ->orderBy('name', 'asc')
        ->get();

    return response()->json([
        'success' => true,
        'message' => 'Daftar kategori berhasil diambil',
        'data'    => $categories
    ], 200);
}

}