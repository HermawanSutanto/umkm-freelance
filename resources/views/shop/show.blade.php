@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
<div class="bg-gray-50 min-h-screen pb-10">
    
    {{-- 1. HEADER TOKO (Sederhana) --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between md:space-x-5">
                <div class="flex items-start space-x-5">
                    <div class="flex-shrink-0">
                        {{-- Foto Profil Toko / Placeholder --}}
                        <div class="relative">
                            <div class="h-20 w-20 rounded-full bg-brand-gold flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                {{ substr($mitra->shop_name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                    <div class="pt-1.5">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $mitra->shop_name }}</h1>
                        <p class="text-sm font-medium text-gray-500 mb-1">
                            <i class="material-icons text-sm align-middle text-gray-400">place</i> 
                            {{ $mitra->address ?? 'Lokasi belum diatur' }}
                        </p>
                        @if($mitra->operational_hours)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Buka: {{ $mitra->operational_hours }}
                            </span>
                        @endif
                    </div>
                </div>
                
                {{-- 2. TOMBOL AKSI UTAMA (CTA) --}}
                <div class="mt-6 flex flex-col-reverse justify-stretch space-y-4 space-y-reverse sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">
                    
                    {{-- Tombol WhatsApp (Paling Menonjol) --}}
                    @php
                        // Logic sederhana ubah 08xx jadi 628xx untuk link WA
                        $wa = $mitra->whatsapp_number;
                        if(substr($wa, 0, 1) == '0') {
                            $wa = '62' . substr($wa, 1);
                        }
                        $waLink = "https://wa.me/{$wa}?text=Halo%20{$mitra->shop_name},%20saya%20tertarik%20dengan%20produk%20Anda.";
                    @endphp

                    <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-xl shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:-translate-y-0.5">
                        <i class="fab fa-whatsapp text-lg mr-2"></i> Chat Penjual
                    </a>

                    @if($mitra->instagram_username)
                    <a href="https://instagram.com/{{ str_replace('@', '', $mitra->instagram_username) }}" target="_blank" class="inline-flex items-center justify-center px-6 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-gold transition-all">
                        <i class="fab fa-instagram text-lg mr-2 text-pink-600"></i> Instagram
                    </a>
                    @endif
                </div>
            </div>
            
            {{-- Deskripsi Singkat --}}
            <div class="mt-6 max-w-3xl text-sm text-gray-500">
                <p>{{ $mitra->shop_description ?? 'Belum ada deskripsi toko.' }}</p>
            </div>
        </div>
    </div>

    {{-- 3. KATALOG PRODUK (Etalase Saja) --}}
    <div class="max-w-5xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Etalase Produk</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {{-- Loop Produk --}}
            @forelse($products as $product)
            <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="aspect-w-1 aspect-h-1 bg-gray-200 w-full overflow-hidden xl:aspect-w-7 xl:aspect-h-8">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover object-center group-hover:opacity-75 transition-opacity">
                </div>
                <div class="p-4">
                    <h3 class="mt-1 text-sm font-bold text-gray-900 truncate">{{ $product->name }}</h3>
                    <p class="mt-1 text-lg font-medium text-brand-gold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    
                    {{-- Tombol Beli arahkan ke WA dengan pesan spesifik --}}
                    <a href="https://wa.me/{{ $wa }}?text=Halo%20kak,%20saya%20mau%20pesan%20{{ urlencode($product->name) }}" target="_blank" class="mt-3 block w-full text-center px-3 py-2 border border-green-600 text-green-600 text-xs font-bold rounded-lg hover:bg-green-50 transition-colors">
                        Pesan via WA
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500">Belum ada produk yang ditampilkan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection