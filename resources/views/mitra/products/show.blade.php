@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Header: Tombol Kembali & Judul --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('mitra.products.index') }}" class="p-2 rounded-full hover:bg-gray-100 transition">
                <i class="material-icons text-gray-600">arrow_back</i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Detail Produk</h1>
        </div>
        
        <div class="flex gap-2">
            {{-- Tombol Edit (Nanti diarahkan ke route edit) --}}
            <a href="#" class="px-4 py-2 bg-yellow-100 text-yellow-700 font-bold rounded-lg hover:bg-yellow-200 transition flex items-center gap-2">
                <i class="material-icons text-sm">edit</i> Edit
            </a>

            {{-- Tombol Hapus --}}
            <form action="{{ route('mitra.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini selamanya?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 font-bold rounded-lg hover:bg-red-200 transition flex items-center gap-2">
                    <i class="material-icons text-sm">delete</i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3">
            
            {{-- KOLOM KIRI: Tampilan Gambar --}}
            <div class="p-6 bg-gray-50 border-r border-gray-100">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Visual Produk</h3>
                
                {{-- 1. Cover Image (Gambar Utama) --}}
                <div class="aspect-square w-full rounded-xl overflow-hidden border border-gray-200 bg-white mb-4">
                    <img src="{{ $product->cover_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                <p class="text-xs text-center text-gray-400 mb-4">Cover Utama</p>

                {{-- 2. Galeri Tambahan --}}
                @if($product->images->count() > 0)
                    <h4 class="text-xs font-bold text-gray-500 mb-2">Galeri Tambahan:</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($product->images as $image)
                            <div class="aspect-square rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ $image->image_url }}" class="w-full h-full object-cover cursor-pointer hover:opacity-75 transition">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">Tidak ada gambar tambahan.</p>
                @endif
            </div>

            {{-- KOLOM KANAN: Informasi Detail --}}
            <div class="p-8 md:col-span-2 space-y-6">
                
                {{-- Status & Nama --}}
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        @if($product->is_active)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                Aktif / Tayang
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                Diarsipkan / Sembunyi
                            </span>
                        @endif
                        <span class="text-xs text-gray-400">Dibuat: {{ $product->created_at->format('d M Y') }}</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h2>
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            @if($product->highlight_level > 0 && $product->highlight_expires_at > now())
                                <h4 class="font-bold text-indigo-900 flex items-center gap-2">
                                    <i class="material-icons text-yellow-500">verified</i>
                                    Produk Sedang Dipromosikan!
                                </h4>
                                <p class="text-sm text-indigo-600 mt-1">
                                    Level {{ $product->highlight_level }}. Berakhir: {{ \Carbon\Carbon::parse($product->highlight_expires_at)->format('d M Y') }}
                                </p>
                            @else
                                <h4 class="font-bold text-indigo-900">Tingkatkan Penjualan Anda!</h4>
                                <p class="text-sm text-indigo-600 mt-1">Promosikan produk ini agar tampil di halaman utama.</p>
                            @endif
                        </div>
                        
                        {{-- Tombol Aksi --}}
                        @if($product->highlight_level > 0 && $product->highlight_expires_at > now())
                            <a href="{{ route('mitra.products.promote', $product->id) }}" class="px-4 py-2 bg-white text-indigo-700 border border-indigo-200 font-bold rounded-lg hover:bg-indigo-50 transition shadow-sm text-sm whitespace-nowrap">
                                Perpanjang / Upgrade
                            </a>
                        @else
                            <a href="{{ route('mitra.products.promote', $product->id) }}" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20 flex items-center gap-2 whitespace-nowrap">
                                <i class="material-icons text-sm">rocket_launch</i>
                                Promosikan Sekarang
                            </a>
                        @endif
                    </div>
                    {{-- =========================================== --}}
                </div>

                <div class="grid grid-cols-2 gap-6 border-y border-gray-100 py-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Harga Satuan</p>
                        <p class="text-2xl font-bold text-brand-gold">{{ $product->price_format }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Stok Tersedia</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $product->stock }} <span class="text-sm font-normal text-gray-500">unit</span></p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Deskripsi Produk</h3>
                    <div class="prose prose-sm text-gray-600 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        {{-- nl2br(e(...)) berguna agar enter/baris baru terbaca --}}
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                {{-- Area Preview Tombol Beli (Simulasi) --}}
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 mb-3">Preview Link WhatsApp:</p>
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-lg border border-green-200 cursor-not-allowed opacity-75">
                        <i class="fab fa-whatsapp"></i>
                        Halo, saya tertarik dengan {{ $product->name }}...
                    </a>
                    <p class="text-xs text-gray-400 mt-2">*Ini adalah simulasi tombol yang akan dilihat pembeli.</p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection