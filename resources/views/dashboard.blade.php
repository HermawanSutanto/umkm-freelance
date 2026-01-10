@extends('layouts.app')

@section('content')
{{-- UBAH 1: max-w-7xl agar lebih lebar di desktop, p-4 untuk HP --}}
<div class="p-4 md:p-8 lg:p-10 max-w-7xl mx-auto">
    
    {{-- UBAH 2: Padding card lebih kecil di HP (p-6) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        
        {{-- Header Dashboard --}}
        {{-- UBAH 3: Flex-col di HP (stack ke bawah), flex-row di tablet ke atas --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-500 mt-1 text-sm md:text-base">Selamat datang kembali, <span class="text-brand-gold font-semibold">{{ Auth::user()->name }}</span>!</p>
            </div>
            
            {{-- Shortcut Profil --}}
            {{-- w-full di HP agar tombol mudah dipencet --}}
            <a href="{{ route('profile.show') }}" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition shadow-sm font-medium w-full sm:w-auto">
                <i class="material-icons text-sm">person</i>
                Lihat Profil
            </a>
        </div>

        {{-- STATUS & SHORTCUT UTAMA --}}
        <div class="p-6 bg-blue-50 rounded-xl border border-blue-100 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            {{-- Status Akun --}}
            <div>
                <p class="text-blue-800 font-medium flex items-center gap-2">
                    <i class="material-icons text-blue-600">verified_user</i>
                    Status Akun: <span class="uppercase tracking-wider font-bold bg-blue-200 text-blue-800 px-2 py-0.5 rounded text-xs">{{ Auth::user()->role }}</span>
                </p>
                <p class="text-sm text-blue-600/80 mt-1">
                    {{ Auth::user()->email }}
                </p>
            </div>

            {{-- LOGIC TOMBOL KHUSUS MITRA --}}
            @if(Auth::user()->role === 'mitra')
                <a href="{{ route('mitra.products.index') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition shadow-lg shadow-gray-900/20 font-bold transform hover:-translate-y-0.5 w-full md:w-auto">
                    <i class="material-icons">inventory_2</i>
                    Kelola Produk
                </a>
            @endif

            {{-- LOGIC TOMBOL KHUSUS FREELANCER --}}
            @if(Auth::user()->role === 'freelancer')
                <a href="{{ url('/portofolio/my') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition shadow-lg shadow-gray-900/20 font-bold transform hover:-translate-y-0.5 w-full md:w-auto">
                    <i class="material-icons">work</i>
                    Update Portofolio
                </a>
            @endif
        </div>

        {{-- AREA KONTEN LAIN (Statistik Sederhana) --}}
        {{-- =========================================== --}}
        {{-- AREA KHUSUS MITRA --}}
        {{-- =========================================== --}}
        @if(Auth::user()->role === 'mitra')
        <div class="mt-10">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Produk Anda</h2>
                    <p class="text-gray-500 text-sm mt-1">Kelola daftar produk yang Anda jual di sini.</p>
                </div>
                
                @if(isset($products) && count($products) > 0)
                <a href="{{ route('mitra.products.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition shadow-md shadow-indigo-200 w-full md:w-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Produk Baru
                </a>
                @endif
            </div>

            @if(isset($products) && count($products) > 0)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto"> {{-- Penting: Agar tabel bisa discroll horizontal di HP --}}
                    <table class="w-full text-left border-collapse min-w-[600px]"> {{-- Min-width agar tabel tidak hancur --}}
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Harga</th>
                                <th class="px-6 py-4 text-center">Stok</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($products as $product)
                            <tr class="group hover:bg-gray-50/80 transition duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                                            <img src="{{ $product->cover_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $product->name }}</h3>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 mt-1">Umum</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->stock > 10)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">{{ $product->stock }} Unit</span>
                                    @elseif($product->stock > 0)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">Sisa {{ $product->stock }}</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">Habis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('mitra.products.edit', $product->id) }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"><i class="material-icons text-sm">edit</i></a>
                                        <form action="{{ route('mitra.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition"><i class="material-icons text-sm">delete</i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $products->links() }}
                </div>
                @endif
            </div>

            @else
            <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-8 md:p-12 text-center shadow-sm">
                <div class="mx-auto h-16 w-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-4">
                    <i class="material-icons text-3xl">inventory_2</i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Belum ada produk</h3>
                <p class="text-gray-500 mt-2 max-w-sm mx-auto mb-6">Mulai tambahkan produk jualan Anda.</p>
                <a href="{{ route('mitra.products.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-semibold">
                    <i class="material-icons text-sm">add</i> Tambah Produk Pertama
                </a>
            </div>
            @endif

        </div>
        @endif

        {{-- =========================================== --}}
        {{-- AREA KHUSUS FREELANCER --}}
        {{-- =========================================== --}}
        @if(Auth::user()->role === 'freelancer')
            
            {{-- UBAH: Layout Grid Responsif (1 kolom di HP, 3 kolom di Desktop besar) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 mt-8">
                
                {{-- KOLOM KIRI: KARTU PROFIL --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sticky top-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-900">Profil Saya</h3>
                            <a href="{{ route('profile.edit') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                        </div>

                        @if(Auth::user()->freelancerProfile)
                            <div class="mb-4">
                                <h4 class="text-lg font-bold text-gray-800">{{ Auth::user()->freelancerProfile->headline ?? 'Belum ada Headline' }}</h4>
                                <p class="text-gray-500 text-sm mt-1 line-clamp-3">
                                    {{ Auth::user()->freelancerProfile->bio ?? 'Deskripsi diri belum diisi.' }}
                                </p>
                            </div>

                            <div class="flex items-center gap-4 py-4 border-t border-b border-gray-100 mb-4">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wide">Rate/Jam</p>
                                    <p class="text-gray-900 font-bold">{{ Auth::user()->freelancerProfile->formatted_rate ?? 'Rp 0' }}</p>
                                </div>
                                <div class="w-px h-8 bg-gray-200"></div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wide">Status</p>
                                    @if(Auth::user()->freelancerProfile->is_available)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700"><span class="w-2 h-2 rounded-full bg-green-500"></span> Open</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600"><span class="w-2 h-2 rounded-full bg-gray-500"></span> Sibuk</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wide mb-2">Keahlian</p>
                                <div class="flex flex-wrap gap-2">
                                    @if(Auth::user()->freelancerProfile->skills)
                                        @foreach(explode(',', Auth::user()->freelancerProfile->skills) as $skill)
                                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-semibold rounded-lg border border-indigo-100">{{ trim($skill) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-sm text-gray-400 italic">Belum ada skill</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="material-icons">priority_high</i>
                                </div>
                                <p class="text-gray-600 text-sm mb-3">Profil freelancer belum lengkap.</p>
                                <a href="{{ route('profile.edit') }}" class="block w-full py-2 px-4 bg-gray-900 text-white text-sm font-bold rounded-lg hover:bg-gray-800 transition">Lengkapi</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KOLOM KANAN: DAFTAR PORTOFOLIO --}}
                <div class="lg:col-span-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Portofolio Terbaru</h2>
                            <p class="text-gray-500 text-sm">Tunjukkan hasil karya terbaikmu.</p>
                        </div>
                        @if(isset($portfolios) && count($portfolios) > 0)
                        <a href="{{ url('/portofolio/create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-md shadow-indigo-200 w-full sm:w-auto">
                            <i class="material-icons text-sm">add</i> Tambah
                        </a>
                        @endif
                    </div>

                    @if(isset($portfolios) && count($portfolios) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($portfolios as $portfolio)
                            <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition duration-300">
                                <div class="relative h-48 bg-gray-100 overflow-hidden">
                                    @if($portfolio->thumbnail)
                                        <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="material-icons text-4xl">image</i></div>
                                    @endif
                                    
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                        <a href="{{ url('/portofolio/edit/'.$portfolio->id) }}" class="p-2 bg-white text-gray-900 rounded-lg hover:bg-gray-100"><i class="material-icons text-sm">edit</i></a>
                                        <form action="{{ url('/portofolio/'.$portfolio->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600"><i class="material-icons text-sm">delete</i></button>
                                        </form>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-gray-900 line-clamp-1 group-hover:text-indigo-600 transition">{{ $portfolio->title }}</h4>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $portfolio->description }}</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-xs font-medium px-2 py-1 bg-gray-100 text-gray-600 rounded">{{ $portfolio->category ?? 'Umum' }}</span>
                                        <span class="text-xs text-gray-400">{{ $portfolio->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6">{{ $portfolios->links() }}</div>
                    @else
                        <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-8 md:p-10 text-center">
                            <div class="mx-auto h-14 w-14 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-4"><i class="material-icons text-2xl">collections</i></div>
                            <h3 class="font-bold text-gray-900">Belum ada portofolio</h3>
                            <p class="text-gray-500 text-sm mt-1 mb-6">Upload hasil karya terbaikmu.</p>
                            <a href="{{ url('/portofolio/create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-bold shadow-lg shadow-indigo-600/20">
                                <i class="material-icons text-sm">add_photo_alternate</i> Upload Portofolio
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        {{-- =========================================== --}}
        {{-- AREA KHUSUS ADMIN --}}
        {{-- =========================================== --}}
        @elseif(Auth::user()->role === 'admin')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <h2 class="text-xl font-bold text-gray-900">Overview Sistem</h2>
            
            {{-- TOMBOL BARU: Menuju Halaman Promosi --}}
            <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/20 transition transform hover:-translate-y-0.5">
                <i class="material-icons text-sm">notifications_active</i>
                <span>Cek Permintaan Promosi</span>
                
                {{-- Badge Hitungan Pending (Optional: Jika data dikirim controller) --}}
                @if(isset($adminStats['pending_promotions']) && $adminStats['pending_promotions'] > 0)
                    <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full ml-1">
                        {{ $adminStats['pending_promotions'] }}
                    </span>
                @endif
            </a>
        </div>
        {{-- 1. STATISTIK RINGKAS (Total User) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total User Aktif</p>
                    {{-- Mengambil data dari $adminStats yang dikirim controller --}}
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ $adminStats['total_users'] ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Gabungan Mitra & Freelancer</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="material-icons text-xl">groups</i>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Produk Highlight</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ \App\Models\Product::where('highlight_level', '>', 0)->count() }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Sedang dipromosikan</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <i class="material-icons text-xl">stars</i>
                </div>
            </div>
            {{-- Placeholder Statistik Lain (Bisa diisi nanti) --}}
            <div class="p-6 bg-white rounded-xl border border-dashed border-gray-300 flex items-center justify-center text-gray-400">
                <span class="text-sm">Statistik Lain Segera Hadir</span>
            </div>
        </div>
        @if(auth()->user()->role == 'mitra' || auth()->user()->role == 'admin')
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                📦 Status Pesanan Masuk
                </h3>
                <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/20 transition transform hover:-translate-y-0.5">
                <i class="material-icons text-sm">shopping_cart</i>
                <span>Cek pesanan masuk</span>
                
                {{-- Badge Hitungan Pending (Optional: Jika data dikirim controller) --}}
                @if(isset($adminStats['pending_promotions']) && $adminStats['pending_promotions'] > 0)
                    <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full ml-1">
                        {{ $adminStats['pending_promotions'] }}
                    </span>
                @endif
            </a>
            </div>
            
            
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('orders.index') }}" 
                class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition flex flex-col items-center justify-center text-center group">
                    <div class="relative">
                        <span class="text-3xl mb-2 block">💸</span>
                        @if($transactionStats['waiting_confirmation'] > 0)
                            <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">
                                {{ $transactionStats['waiting_confirmation'] }}
                            </span>
                        @endif
                    </div>
                    <span class="font-bold text-gray-700 group-hover:text-blue-600">Perlu Verifikasi</span>
                    <span class="text-xs text-gray-500">Cek Bukti Transfer</span>
                </a>

                <a href="{{-- route('transactions.index', ['status' => 'processed']) --}}" 
                class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition flex flex-col items-center justify-center text-center group">
                    <div class="relative">
                        <span class="text-3xl mb-2 block">📦</span>
                        @if($transactionStats['processed'] > 0)
                            <span class="absolute -top-2 -right-3 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ $transactionStats['processed'] }}
                            </span>
                        @endif
                    </div>
                    <span class="font-bold text-gray-700 group-hover:text-blue-600">Perlu Dikemas</span>
                    <span class="text-xs text-gray-500">Siap Kirim</span>
                </a>

                <a href="{{-- route('transactions.index', ['status' => 'shipped']) --}}" 
                class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition flex flex-col items-center justify-center text-center">
                    <span class="text-3xl mb-2 block">🚚</span>
                    <span class="font-bold text-gray-700">{{ $transactionStats['shipped'] }}</span>
                    <span class="text-xs text-gray-500">Sedang Dikirim</span>
                </a>

                <a href="{{-- route('transactions.index', ['status' => 'completed']) --}}" 
                class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition flex flex-col items-center justify-center text-center">
                    <span class="text-3xl mb-2 block">✅</span>
                    <span class="font-bold text-gray-700">{{ $transactionStats['completed'] }}</span>
                    <span class="text-xs text-gray-500">Pesanan Selesai</span>
                </a>
            </div>
        </div>
        @endif

        {{-- 2. TABEL SEMUA PRODUK (GLOBAL) --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Global Produk</h2>
                    <p class="text-sm text-gray-500">Memantau semua produk yang diupload Mitra.</p>
                </div>
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                    Admin Mode
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Produk</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Penjual 
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Harga</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-center">Stok</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Info Produk --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded bg-gray-200 overflow-hidden shrink-0">
                                        {{-- Cek jika ada gambar, jika tidak pakai placeholder --}}
                                        @if($product->cover_url)
                                            <img src="{{ $product->cover_url }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-400"><i class="material-icons text-sm">image</i></div>
                                        @endif
                                    </div>
                                    <span class="font-bold text-gray-900 text-sm">{{ $product->name }}</span>
                                </div>
                            </td>

                            {{-- Info Penjual (Owner) - Menggunakan relasi 'user' --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                                        {{ substr($product->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $product->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>

                            {{-- Harga --}}
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            {{-- Stok --}}
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-bold px-2 py-1 rounded {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <button class="text-red-600 hover:text-red-800 text-sm font-medium transition" onclick="alert('Fitur hapus oleh admin belum aktif')">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Belum ada produk di database.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($products, 'links'))
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $products->links() }}
            </div>
            @endif
        </div>
        @endif


        {{-- =========================================== --}}
        {{-- AKHIR AREA ADMIN --}}
        {{-- =========================================== --}}
    </div>
        
        
</div>
@endsection