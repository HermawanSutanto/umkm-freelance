@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Edit Profil</h2>
        <a href="{{ route('profile.show') }}" class="text-gray-500 hover:text-gray-900 text-sm">Batal</a>
    </div>

    {{-- Menampilkan Pesan Sukses --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT') {{-- PENTING: Untuk method update --}}

            {{-- 1. Data Akun (Semua Role Punya) --}}
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Informasi Akun</h3>
                
                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- 2. Data Spesifik (Gunakan @if blade, tidak perlu JS toggle karena role sudah fix) --}}
            
            {{-- FORM KHUSUS MITRA --}}
            @if($user->role === 'mitra')
                <div class="space-y-4 pt-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Detail Usaha (Mitra)</h3>
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700"> Nomor Telepon</label>
                        {{-- Menggunakan optional chaining (?->) untuk jaga-jaga jika profil belum ada --}}
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->mitraProfile?->phone_number) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">
                        @error('phone_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Gambar Toko</label>
                        
                        <div class="mt-1 relative flex justify-center items-center h-64 border-2 border-gray-300 border-dashed rounded-xl hover:bg-gray-50 transition overflow-hidden">
                            
                            {{-- Input File --}}
                            <input id="shop_image" name="shop_image" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">
                            
                            {{-- Logic Tampilan Awal: Cek apakah user sudah punya gambar? --}}
                            @php
                                $hasImage = $user->mitraProfile && $user->mitraProfile->shop_image;
                                $imageUrl = $hasImage ? asset('storage/' . $user->mitraProfile->shop_image) : '#';
                            @endphp

                            {{-- Placeholder (Tampil jika BELUM ada gambar) --}}
                            <div id="toko-placeholder" class="space-y-1 text-center {{ $hasImage ? 'hidden' : '' }}">
                                <i class="material-icons text-gray-400 text-3xl">store</i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="relative bg-white rounded-md font-medium text-brand-gold hover:text-yellow-600">
                                        Upload Foto Toko
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP max 2MB</p>
                            </div>

                            {{-- Preview Image (Tampil jika SUDAH ada gambar atau setelah upload) --}}
                            <div id="toko-preview-container" class="{{ $hasImage ? '' : 'hidden' }} absolute inset-0 w-full h-full bg-gray-100 flex items-center justify-center z-0">
                                <img id="toko-preview-img" src="{{ $imageUrl }}" alt="Preview toko" class="h-full w-full object-cover">
                                <div class="absolute bottom-2 bg-black/50 text-white text-xs px-2 py-1 rounded-md backdrop-blur-sm z-20">
                                    Klik untuk ganti gambar
                                </div>
                            </div>
                        </div>
                        @error('shop_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Nama Toko</label>
                        {{-- Menggunakan optional chaining (?->) untuk jaga-jaga jika profil belum ada --}}
                        <input type="text" name="shop_name" value="{{ old('shop_name', $user->mitraProfile?->shop_name) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">
                        @error('shop_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Alamat</label>
                        <textarea name="shop_address" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">{{ old('shop_address', $user->mitraProfile?->shop_address) }}</textarea>
                        @error('shop_address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Deskripsi Toko</label>
                        <textarea name="shop_description" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">{{ old('shop_description', $user->mitraProfile?->shop_description) }}</textarea>
                        @error('shop_description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Jam Operasional</label>
                        
                        {{-- Logic untuk memecah string "08:00 - 17:00" menjadi dua variabel --}}
                        @php
                            $hours = $user->mitraProfile?->operational_hours;
                            $open = '';
                            $close = '';
                            
                            // Cek jika data ada dan memiliki pemisah " - "
                            if ($hours && str_contains($hours, ' - ')) {
                                [$open, $close] = explode(' - ', $hours);
                            }
                        @endphp

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Input Jam Buka --}}
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Jam Buka</label>
                                <input 
                                    type="time" 
                                    name="open_time" 
                                    value="{{ old('open_time', $open) }}" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none"
                                >
                            </div>

                            {{-- Input Jam Tutup --}}
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Jam Tutup</label>
                                <input 
                                    type="time" 
                                    name="close_time" 
                                    value="{{ old('close_time', $close) }}" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none"
                                >
                            </div>
                        </div>

                        {{-- Error handling --}}
                        @error('operational_hours') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">link Google Map</label>
                        <textarea name="gmaps_link" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">{{ old('gmaps_link', $user->mitraProfile?->gmaps_link) }}</textarea>
                        @error('gmaps_link') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- FORM KHUSUS FREELANCER --}}
            @if($user->role === 'freelancer')
                <div class="space-y-4 pt-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Profil Freelancer</h3>
                    
                    {{-- 1. Headline & Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Headline Profesional</label>
                            <input type="text" name="headline" value="{{ old('headline', $user->freelancerProfile?->headline) }}" placeholder="Contoh: React Native Developer" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">
                            @error('headline') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Status Ketersediaan</label>
                            <select name="is_available" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none bg-white">
                                <option value="1" {{ old('is_available', $user->freelancerProfile?->is_available) == 1 ? 'selected' : '' }}>Open for Job (Tersedia)</option>
                                <option value="0" {{ old('is_available', $user->freelancerProfile?->is_available) == 0 ? 'selected' : '' }}>Sedang Sibuk (Busy)</option>
                            </select>
                        </div>
                    </div>

                    {{-- 2. Bio --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Bio / Tentang Saya</label>
                        <textarea name="bio" rows="4" placeholder="Ceritakan pengalaman dan keahlian Anda secara singkat..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">{{ old('bio', $user->freelancerProfile?->bio) }}</textarea>
                        @error('bio') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- 3. Skills --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Keahlian (Skills)</label>
                        <input type="text" name="skills" value="{{ old('skills', $user->freelancerProfile?->skills) }}" placeholder="Contoh: Laravel, VueJS, Figma (Pisahkan dengan koma)" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">
                        <p class="text-xs text-gray-400">Pisahkan dengan tanda koma.</p>
                        @error('skills') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <h4 class="text-sm font-bold text-gray-900 pt-2">Tautan Profesional (Opsional)</h4>
                    
                    {{-- 4. Social Links --}}
                    <div class="space-y-3">
                        {{-- Portfolio --}}
                        <div class="flex rounded-xl border border-gray-200 overflow-hidden focus-within:ring-2 focus-within:ring-brand-gold/50">
                            <span class="bg-gray-50 px-3 py-3 text-gray-500 border-r border-gray-200 flex items-center">
                                <i class="material-icons text-sm">link</i>
                            </span>
                            <input type="url" name="portfolio_link" value="{{ old('portfolio_link', $user->freelancerProfile?->portfolio_link) }}" placeholder="Link Website / Portofolio" class="w-full px-4 py-2 outline-none">
                        </div>

                        {{-- LinkedIn --}}
                        <div class="flex rounded-xl border border-gray-200 overflow-hidden focus-within:ring-2 focus-within:ring-brand-gold/50">
                            <span class="bg-gray-50 px-3 py-3 text-gray-500 border-r border-gray-200 flex items-center">
                                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/linkedin/linkedin-original.svg" class="w-4 h-4 grayscale opacity-60">
                            </span>
                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $user->freelancerProfile?->linkedin_url) }}" placeholder="Link Profil LinkedIn" class="w-full px-4 py-2 outline-none">
                        </div>
                    </div>
                </div>
            @endif
            {{-- FORM KHUSUS ADMIN --}}
            @if($user->role === 'admin')
                <div class="space-y-4 pt-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Profil Admin</h3>
                    {{-- 1. phone_number & Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Nomor Telepon</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->adminProfile?->phone_number) }}" placeholder="08..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold outline-none">
                            @error('phone_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            @endif


            <div class="pt-4">
                <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3.5 px-4 rounded-xl hover:bg-gray-800 transition shadow-lg transform hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    const tokoInput = document.getElementById('shop_image');
    const tokoPlaceholder = document.getElementById('toko-placeholder');
    const tokoPreviewContainer = document.getElementById('toko-preview-container');
    const tokoPreviewImg = document.getElementById('toko-preview-img');

    if (tokoInput) { // Cek agar tidak error di halaman freelancer
        tokoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    tokoPreviewImg.src = e.target.result;
                    tokoPlaceholder.classList.add('hidden');
                    tokoPreviewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endsection