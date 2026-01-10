@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900">Ajukan Promosi Produk</h1>
        <p class="mt-2 text-gray-600">Pilih paket, lakukan transfer, dan upload bukti pembayaran. Admin akan memverifikasi permintaan Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI: Detail Produk --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 class="font-bold text-gray-900 mb-4">Produk yang Dipromosikan</h3>
                <div class="aspect-video rounded-lg bg-gray-100 overflow-hidden mb-4 border border-gray-200">
                    @if($product->cover_image)
                        <img src="{{ Storage::url($product->cover_image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400"><i class="material-icons">image</i></div>
                    @endif
                </div>
                <h4 class="font-bold text-lg text-gray-900">{{ $product->name }}</h4>
                <p class="text-gray-500 text-sm mb-4">{{ Str::limit($product->description, 100) }}</p>
                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-500 uppercase font-bold">Harga Produk</p>
                    <p class="text-lg font-bold text-brand-gold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Form Pilihan Paket & Upload --}}
        <div class="lg:col-span-2">
            {{-- PENTING: enctype multipart/form-data wajib ada untuk upload file --}}
            <form action="{{ route('mitra.products.submit_promotion', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- STEP 1: PILIH PAKET --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="bg-gray-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                        Pilih Paket Highlight
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Bronze --}}
                        <label class="cursor-pointer relative">
                            <input type="radio" name="plan" value="bronze" class="peer sr-only" required>
                            <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition text-center h-full flex flex-col justify-between">
                                <div>
                                    <span class="block font-bold text-gray-800">Bronze</span>
                                    <span class="text-xs text-gray-500">Standard</span>
                                </div>
                                <div class="mt-2 font-bold text-lg text-orange-600">Rp 50.000</div>
                            </div>
                        </label>

                        {{-- Silver --}}
                        <label class="cursor-pointer relative">
                            <input type="radio" name="plan" value="silver" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-gray-500 peer-checked:bg-gray-50 transition text-center h-full flex flex-col justify-between">
                                <div>
                                    <span class="block font-bold text-gray-800">Silver</span>
                                    <span class="text-xs text-gray-500">Priority</span>
                                </div>
                                <div class="mt-2 font-bold text-lg text-gray-600">Rp 100.000</div>
                            </div>
                        </label>

                        {{-- Gold --}}
                        <label class="cursor-pointer relative">
                            <input type="radio" name="plan" value="gold" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition text-center h-full flex flex-col justify-between">
                                <div class="absolute -top-2 right-2 bg-yellow-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">BEST</div>
                                <div>
                                    <span class="block font-bold text-gray-800">Gold</span>
                                    <span class="text-xs text-gray-500">Maximum View</span>
                                </div>
                                <div class="mt-2 font-bold text-lg text-yellow-600">Rp 200.000</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- STEP 2: INFORMASI TRANSFER --}}
                <div class="bg-blue-50 rounded-xl border border-blue-100 p-6 mb-6">
                    <h3 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                        Transfer Pembayaran
                    </h3>
                    <p class="text-sm text-blue-700 mb-4">Silakan transfer sesuai nominal paket yang dipilih ke rekening berikut:</p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="bg-white p-3 rounded-lg border border-blue-100 flex-1">
                            <p class="text-xs text-gray-500">Bank BCA</p>
                            <p class="font-mono font-bold text-lg text-gray-800">123-456-7890</p>
                            <p class="text-xs text-gray-500">a.n PT UMKM Maju</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-blue-100 flex-1">
                            <p class="text-xs text-gray-500">Bank Mandiri</p>
                            <p class="font-mono font-bold text-lg text-gray-800">987-000-1111</p>
                            <p class="text-xs text-gray-500">a.n PT UMKM Maju</p>
                        </div>
                    </div>
                </div>

                {{-- STEP 3: UPLOAD BUKTI --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="bg-gray-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                        Upload Bukti Transfer
                    </h3>
                    
                    <div class="mt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Bukti (JPG, PNG - Max 2MB)</label>
                        <input type="file" name="payment_proof" accept="image/*" required
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100
                                      cursor-pointer border border-gray-300 rounded-lg p-2">
                        @error('payment_proof')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('mitra.products.index') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-600 font-bold hover:bg-gray-50">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-gray-800 shadow-lg shadow-gray-900/20">
                        Kirim Permintaan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection 