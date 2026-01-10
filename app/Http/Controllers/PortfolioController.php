<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    /**
     * Menampilkan daftar portofolio.
     */
    public function index(Request $request)
    {
        // Mengambil kategori dari parameter URL (?category=web)
        $category = $request->query('category');

        // Query data
        $portfolios = Portfolio::with('user')
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        // Mengambil list kategori unik
        $categories = Portfolio::select('category')->distinct()->pluck('category');

        // PERBAIKAN: Gunakan array untuk mengirim data, jangan gunakan panah (->) di dalam compact
        return view('freelancer.portfolio.index', [
            'portfolios' => $portfolios,
            'categories' => $categories,
            'currentCategory' => $category
        ]);
        
        // Catatan: Jika file view Anda ada di folder resources/views/freelancer/portfolio/index.blade.php
        // Maka ganti menjadi: view('freelancer.portfolio.index', [...])
    }
     public function create()
    {
        // Sesuaikan nama view jika folder Anda berbeda
        return view('freelancer.portfolio.create');
    }

    /**
     * Menyimpan portofolio baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:50',
            'description' => 'required|string',
            'project_url' => 'nullable|url',
            'thumbnail'   => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // 2. Handle Upload Gambar
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            // Simpan di storage/app/public/portfolios
            $thumbnailPath = $request->file('thumbnail')->store('portfolios', 'public');
        }

        // 3. Buat Data di Database
        // Menggunakan relasi user() agar user_id terisi otomatis
        Auth::user()->portfolios()->create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title . '-' . time()), // Buat slug unik
            'category'    => $request->category,
            'description' => $request->description,
            'project_url' => $request->project_url,
            'thumbnail'   => $thumbnailPath,
        ]);

        // 4. Redirect kembali ke index dengan pesan sukses
        return redirect()->route('freelancer.portfolios.index')
            ->with('success', 'Portofolio berhasil ditambahkan!');
    }
    /**
     * Menampilkan detail portofolio.
     */
    public function show(Portfolio $portfolio)
    {
        return view('portfolio.show', compact('portfolio'));
    }

    // Method lain (store, update, destroy) bisa ditambahkan di sini...
}