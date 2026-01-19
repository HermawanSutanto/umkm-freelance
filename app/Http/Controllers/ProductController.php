<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Import Storage untuk hapus gambar
use Illuminate\Support\Str; // Import Str untuk bikin slug
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class ProductController extends Controller
{
    /**
     * Tampilkan Daftar Produk Mitra
     */
    public function index()
    {
        // Ambil produk milik user yang sedang login saja
        $products = Auth::user()->products()->latest()->paginate(10);
        
        return view('mitra.products.index', compact('products'));
    }

    /**
     * Tampilkan Form Tambah Produk
     */
    public function create()
    {
        $categories = Category::all();
        return view('mitra.products.create', compact('categories'));
    }

    /**
     * Simpan Produk ke Database
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'category_id'  => 'required_without:new_category|nullable|exists:categories,id',
            'new_category' => 'required_without:category_id|nullable|string|max:50',            
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'real_price' => 'nullable|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'description'   => 'nullable|string',
            
            // Validasi Cover (Wajib/Opsional tergantung kebutuhan)
            'cover_image'   => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            
            // Validasi Galeri (Array of images)
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'category_id.required_without' => 'Silakan pilih kategori atau buat baru.',
            'new_category.required_without' => 'Silakan pilih kategori atau buat baru.',
        ]);

        DB::beginTransaction(); // Pakai transaksi biar aman kalau upload gagal

        try {
            // --- LOGIKA KATEGORI BARU ---
            $categoryId = $request->category_id;

            // Jika user mengisi kolom kategori baru
            if ($request->filled('new_category')) {
                // Buat kategori baru atau pakai yang sudah ada (firstOrCreate mencegah duplikat nama)
                $category = Category::firstOrCreate(
                    ['slug' => Str::slug($request->new_category)], // Cek berdasarkan slug
                    ['name' => $request->new_category]             // Data untuk dibuat jika belum ada
                );
                $categoryId = $category->id;
            }
            // 2. Upload Cover Image (Gambar Utama)
            $coverPath = null;
            if ($request->hasFile('cover_image')) {
                // Panggil fungsi helper 'uploadImage'
                $coverPath = $this->uploadImage($request->file('cover_image'), 'products/covers');
            }

            // 3. Simpan Data Produk Utama
            $product = Auth::user()->products()->create([
                'category_id' => $categoryId, // Gunakan ID hasil logika di atas // Simpan kategori
                'name'        => $validated['name'],
                'slug'        => Str::slug($validated['name']) . '-' . Str::random(5),
                'price'       => $validated['price'],
                'real_price' => $validated['real_price'],
                'stock'       => $validated['stock'],
                'description' => $validated['description'],
                'cover_image' => $coverPath, // Simpan path cover di sini
                'is_active'   => true,
            ]);

            // 4. Upload Gambar Galeri (Looping)
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    // Panggil fungsi helper 'uploadImage'
                    $path = $this->uploadImage($image, 'products/gallery');
                    
                    $product->images()->create(['image_path' => $path]);
                }
            }

            DB::commit();
            return redirect()->route('mitra.products.index')->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan produk: ' . $e->getMessage()]);
        }
    }

    // app/Http/Controllers/ProductController.php

    public function show($id)
    {
        // Cari produk berdasarkan ID
        $product = Product::with('images')->findOrFail($id);

        // Keamanan: Pastikan produk ini milik Mitra yang sedang login
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak melihat produk ini.');
        }

        return view('mitra.products.show', compact('product'));
    }
    public function promote($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Keamanan: Pastikan produk ini milik Mitra yang sedang login
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mempromosikan produk ini.');
        }

        return view('mitra.products.promote', compact('product'));
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        // Hapus file gambar dari storage (Opsional tapi disarankan agar hemat space)
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }
        foreach ($product->images as $gallery) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        // Hapus data di database
        $product->delete();

        return redirect()->route('mitra.products.index')->with('success', 'Produk berhasil dihapus.');
    }


    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        // Keamanan
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        return view('mitra.products.edit', compact('product', 'categories'));    
    }
    public function submit_promotion(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        // Validasi input
        $request->validate([
            'plan' => 'required|in:bronze,silver,gold',
            'payment_proof' => 'required|image|max:2048', // Wajib upload bukti
        ]);

        // Upload Bukti Bayar
        $proofPath = $request->file('payment_proof')->store('payments', 'public');

        // Tentukan Harga & Durasi (Bisa dibuat dinamis dari database settings)
        $prices = ['bronze' => 50000, 'silver' => 100000, 'gold' => 200000];
        $price = $prices[$request->plan];

        // Simpan ke tabel PROMOTIONS (Bukan update product langsung)
        ProductPromotion::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'plan' => $request->plan,
            'duration_days' => 7,
            'price_paid' => $price,
            'payment_proof' => $proofPath,
            'status' => 'pending', // Default Pending
        ]);

        return redirect()->route('mitra.products.index')
            ->with('success', 'Permintaan promosi dikirim! Menunggu verifikasi Admin.');
    }

    /**
     * Update Data Produk
     */
   public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    if ($product->user_id !== Auth::id()) {
        abort(403);
    }

    // 1. Validasi (Tambahkan real_price)
    $validated = $request->validate([
        'category_id'    => 'required_without:new_category|nullable|exists:categories,id',
        'new_category'   => 'required_without:category_id|nullable|string|max:50',
        'name'           => 'required|string|max:255',
        'price'          => 'required|numeric|min:0',
        'real_price'     => 'nullable|numeric|min:0', // Validasi real_price
        'stock'          => 'required|integer|min:0',
        'description'    => 'nullable|string',
        'cover_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'is_active'      => 'boolean'
    ]);

    DB::beginTransaction();

    try {
        // --- LOGIKA KATEGORI ---
        $categoryId = $request->category_id;
        if ($request->filled('new_category')) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($request->new_category)],
                ['name' => $request->new_category]
            );
            $categoryId = $category->id;
        }

        // Siapkan data update
        $dataToUpdate = [
            'category_id' => $categoryId,
            'name'        => $validated['name'],
            'price'       => $validated['price'],
            'real_price'  => $validated['real_price'] ?? null, // FIX: Masukkan real_price
            'stock'       => $validated['stock'],
            'description' => $validated['description'],
        ];

        // Update Slug HANYA jika nama berubah (Opsional, demi SEO)
        if ($product->name !== $validated['name']) {
            $dataToUpdate['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        }

        // 2. Handle Cover Image
        if ($request->hasFile('cover_image')) {
            // Hapus lama
            if ($product->cover_image) {
                Storage::disk('public')->delete($product->cover_image);
            }
            // Upload baru & Masukkan ke array update
            $path = $this->uploadImage($request->file('cover_image'), 'products/covers');
            $dataToUpdate['cover_image'] = $path; 
        }

        // 3. Eksekusi Update Text & Cover
        $product->update($dataToUpdate);

        // 4. Handle Gallery Images (Append)
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $this->uploadImage($image, 'products/gallery');
                $product->images()->create(['image_path' => $path]);
            }
        }

        DB::commit();
        return redirect()->route('mitra.products.index')->with('success', 'Produk berhasil diperbarui!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
    }
}

    /**
     * Hapus Satu Gambar Galeri
     */
    public function deleteImage($id)
    {
        // Cari gambar berdasarkan ID (bukan ID produk, tapi ID gambar)
        $image = \App\Models\ProductImage::findOrFail($id);

        // Cek kepemilikan via relasi product -> user
        if ($image->product->user_id !== Auth::id()) {
            abort(403);
        }

        // Hapus file fisik
        Storage::disk('public')->delete($image->image_path);

        // Hapus record db
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }
    private function uploadImage($file, $path)
    {
        // 1. Inisialisasi Manager dengan Driver GD
        $manager = new ImageManager(new Driver());

        // 2. Baca gambar yang diupload
        $image = $manager->read($file);

        // 3. (Opsional) Resize gambar jika terlalu besar (misal lebar max 1000px)
        // scaleDown memastikan gambar tidak ditarik paksa (hanya mengecilkan)
        $image->scaleDown(width: 1000);

        // 4. Encode ke format WebP dengan kualitas 75%
        $encoded = $image->toWebp(quality: 75);

        // 5. Buat nama file unik
        $filename = Str::random(40) . '.webp';
        $fullPath = $path . '/' . $filename;

        // 6. Simpan menggunakan Storage Laravel (agar kompatibel dengan folder public)
        Storage::disk('public')->put($fullPath, (string) $encoded);

        return $fullPath;
    }



}