@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Header / Judul Halaman --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Edit Metode Pembayaran</h2>
        <p class="text-sm text-gray-500">Isi formulir di bawah untuk menambahkan metode pembayaran baru.</p>
    </div>

    {{-- Card Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Form Start --}}
        {{-- PERBAIKAN: Menggunakan route 'admin.payment.store' sesuai web.php --}}
        <form action="{{ route('admin.payment.update', $method->id) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
            @csrf
            @method('PUT') {{-- PENTING: Untuk method update --}}


            {{-- Grid 2 Kolom untuk Nama & Kode --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Nama Metode</label>
                    <input type="text" name="name" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5" value="{{ old('name', $method->name) }}" placeholder="Contoh: Bank BCA" required>
                     @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

                </div>
                
            </div>

            {{-- Select Tipe --}}
            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700">Tipe Pembayaran</label>
                {{-- KOREKSI: Hapus atribut value di sini --}}
                <select name="type" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5">
                    {{-- KOREKSI: Logika 'selected' dipindah ke option --}}
                    <option value="bank_transfer" {{ old('type') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="ewallet" {{ old('type') == 'ewallet' ? 'selected' : '' }}>E-Wallet (Gopay/OVO/Dana)</option>
                    <option value="virtual_account" {{ old('type') == 'virtual_account' ? 'selected' : '' }}>Virtual Account</option>
                </select>
                @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Grid 2 Kolom untuk Info Rekening --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Nomor Rekening / No. HP</label>
                    <input type="text" name="account_number"value="{{ old('account_number', $method->account_number) }}"  class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5" placeholder="0123456789">
                    @error('account_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

                </div>
                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-700">Atas Nama</label>
                    <input type="text" name="account_holder" value="{{ old('account_holder', $method->account_holder) }}" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5" placeholder="PT Lokalitas Market">
                    @error('account_holder') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

                </div>
            </div>
            {{-- Input Biaya Admin (BARU DITAMBAHKAN) --}}
            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700">Biaya Admin (Fee)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                    </div>
                    <input type="number" name="admin_fee" value="{{ old('admin_fee', $method->admin_fee) }}" class="pl-10 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5" placeholder="0" min="0" value="0">
                    @error('admin_fee') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                
                </div>
                <p class="mt-1 text-xs text-gray-500">Biaya layanan tambahan untuk metode ini (Opsional).</p>
            </div>

            {{-- Upload Logo --}}
            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700">Logo Bank/E-Wallet</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" name="logo" type="file">
                @error('logo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, SVG (Max. 2MB).</p>
            </div>
            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold text-gray-700">Status Keaktifan</label>
                {{-- KOREKSI: Hapus atribut value di sini --}}
                <select name="is_active" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5">
                    <option value="1" {{ old('is_active', $method->is_active) == 1 ? 'selected' : '' }}>Sedang Aktif</option>
                    <option value="0" {{ old('is_active', $method->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('is_active') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Textarea Instruksi --}}
            <div class="mb-8">
                <label class="block mb-2 text-sm font-bold text-gray-700">Instruksi Pembayaran</label>
                
                {{-- PERBAIKAN: Pindahkan {{ old(...) }} ke tengah-tengah tag --}}
                <textarea 
                    name="instructions" 
                    rows="4" 
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" 
                    placeholder="Tuliskan langkah-langkah pembayaran di sini...">{{ old('instructions', $method->instructions) }}</textarea>

                @error('instructions') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-lg shadow-indigo-600/20 transition transform hover:-translate-y-0.5">
                    Simpan Data
                </button>
                
                {{-- PERBAIKAN: Menggunakan route 'admin.payment.index' sesuai web.php --}}
                <a href="{{ route('admin.payment.index') }}" class="text-gray-700 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:bg-gray-50 transition">
                    Batal & Kembali
                </a>
            </div>

        </form>
    </div>
</div>
@endsection