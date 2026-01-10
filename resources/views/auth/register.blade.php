@extends('layouts.auth')

@section('content')

<h2 class="text-3xl font-bold text-gray-900 tracking-tight">Bergabung Sekarang</h2>
<p class="mt-2 text-sm text-gray-500 mb-8">
    Sudah punya akun?
    <a href="{{ route('login') }}" class="font-bold text-brand-gold hover:text-yellow-700 transition-colors">
        Masuk di sini
    </a>
</p>

<form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf
    
    <div class="space-y-3">
        <label class="block text-sm font-semibold text-gray-700">Saya ingin mendaftar sebagai:</label>
        <div class="grid grid-cols-2 gap-4">
            {{-- Menggunakan Blade untuk memastikan nilai radio tetap saat terjadi error validasi --}}
            @php $role = old('role', 'mitra'); @endphp
            
            <label class="relative cursor-pointer group">
                <input type="radio" name="role" value="mitra" class="peer sr-only" {{ $role == 'mitra' ? 'checked' : '' }}>
                <div class="p-5 rounded-2xl border-2 border-gray-100 bg-white hover:border-brand-gold/30 peer-checked:border-brand-gold peer-checked:bg-brand-gold/5 peer-checked:shadow-md transition-all duration-200 text-center h-full flex flex-col items-center justify-center relative overflow-hidden">
                    <i class="material-icons text-2xl mb-3 text-blue-600">store</i>
                    <span class="block text-sm font-bold text-gray-900">Pemilik UMKM</span>
                </div>
                <div class="absolute top-3 right-3 text-brand-gold opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100">
                    <i class="material-icons text-xl">check_circle</i>
                </div>
            </label>

            <label class="relative cursor-pointer group">
                <input type="radio" name="role" value="freelancer" class="peer sr-only" {{ $role == 'freelancer' ? 'checked' : '' }}>
                <div class="p-5 rounded-2xl border-2 border-gray-100 bg-white hover:border-brand-gold/30 peer-checked:border-brand-gold peer-checked:bg-brand-gold/5 peer-checked:shadow-md transition-all duration-200 text-center h-full flex flex-col items-center justify-center relative overflow-hidden">
                    <i class="material-icons text-2xl mb-3 text-purple-600">person_search</i>
                    <span class="block text-sm font-bold text-gray-900">Freelancer</span>
                </div>
                <div class="absolute top-3 right-3 text-brand-gold opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100">
                    <i class="material-icons text-xl">check_circle</i>
                </div>
            </label>
        </div>
        @error('role') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label for="name" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required 
            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold shadow-sm sm:text-sm bg-gray-50/50 focus:bg-white @error('name') border-red-500 @enderror" 
            placeholder="Contoh: Budi Santoso"
        >
        @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required 
            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold shadow-sm sm:text-sm bg-gray-50/50 focus:bg-white @error('email') border-red-500 @enderror" 
            placeholder="nama@email.com"
        >
        @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-1">
        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
        <input id="password" name="password" type="password" required 
            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold shadow-sm sm:text-sm bg-gray-50/50 focus:bg-white @error('password') border-red-500 @enderror" 
            placeholder="Minimal 8 karakter"
        >
        @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
    
    <div class="space-y-1">
        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required 
            class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold shadow-sm sm:text-sm bg-gray-50/50 focus:bg-white" 
            placeholder="Ulangi password"
        >
    </div>
    {{-- AREA INPUT KHUSUS MITRA --}}
    <div id="mitra-fields" class="space-y-3 {{ old('role', 'mitra') === 'mitra' ? '' : 'hidden' }}">
        <div class="space-y-1">
            <label for="shop_name" class="block text-sm font-semibold text-gray-700">Nama Toko / Usaha <span class="text-red-500">*</span></label>
            <input id="shop_name" name="shop_name" type="text" value="{{ old('shop_name') }}"
                class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold shadow-sm sm:text-sm bg-gray-50/50 focus:bg-white @error('shop_name') border-red-500 @enderror"
                placeholder="Contoh: Kopi Senja Jember">
            
            {{-- MENAMPILKAN ERROR SPESIFIK --}}
            @error('shop_name')
                <p class="text-sm text-red-500 mt-1 flex items-center gap-1">
                    <i class="material-icons text-sm">error</i> {{ $message }}
                </p>
            @enderror
        </div>

        <div class="space-y-1">
            <label for="shop_address" class="block text-sm font-semibold text-gray-700">Alamat Lengkap Toko</label>
            <textarea id="shop_address" name="shop_address" rows="2"
                class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold shadow-sm sm:text-sm bg-gray-50/50 focus:bg-white @error('shop_address') border-red-500 @enderror"
                placeholder="Jalan Kalimantan No..."
            >{{ old('shop_address') }}</textarea>
             @error('shop_address') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- AREA INPUT KHUSUS FREELANCER --}}
    <div id="freelancer-fields" class="space-y-3 {{ old('role') === 'freelancer' ? '' : 'hidden' }}">
        <div class="space-y-1">
            <label for="skills" class="block text-sm font-semibold text-gray-700">Keahlian Utama <span class="text-red-500">*</span></label>
            <input id="skills" name="skills" type="text" value="{{ old('skills') }}"
                class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold shadow-sm sm:text-sm bg-gray-50/50 focus:bg-white @error('skills') border-red-500 @enderror"
                placeholder="Contoh: Web Design, Photography, Content Writer">
            
            {{-- MENAMPILKAN ERROR SPESIFIK --}}
            @error('skills')
                <p class="text-sm text-red-500 mt-1 flex items-center gap-1">
                    <i class="material-icons text-sm">error</i> {{ $message }}
                </p>
            @enderror
        </div>

        <div class="space-y-1">
            <label for="portofolio_link" class="block text-sm font-semibold text-gray-700">Link Portofolio (Opsional)</label>
            <input id="portofolio_link" name="portofolio_link" type="url" value="{{ old('portofolio_link') }}"
                class="block w-full px-4 py-3.5 border border-gray-200 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-gold/50 focus:border-brand-gold shadow-sm sm:text-sm bg-gray-50/50 focus:bg-white @error('portofolio_link') border-red-500 @enderror"
                placeholder="https://behance.net/karyasaya">
            @error('portofolio_link') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex items-start pt-2">
        <div class="flex items-center h-5">
            <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-brand-gold focus:ring-brand-gold border-gray-300 rounded cursor-pointer">
        </div>
        <div class="ml-3 text-sm">
            <label for="terms" class="font-medium text-gray-600 select-none cursor-pointer">
                Saya setuju dengan <a href="#" class="text-brand-gold hover:underline font-bold">Syarat & Ketentuan</a>.
            </label>
        </div>
    </div>

    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-brand-gold/30 text-sm font-bold text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-300 transform hover:-translate-y-0.5">
        Buat Akun Baru
    </button>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleInputs = document.querySelectorAll('input[name="role"]');
        const mitraFields = document.getElementById('mitra-fields');
        const freelancerFields = document.getElementById('freelancer-fields');

        function toggleFields() {
            const selectedRole = document.querySelector('input[name="role"]:checked').value;
            
            if (selectedRole === 'mitra') {
                mitraFields.classList.remove('hidden');
                freelancerFields.classList.add('hidden');
            } else {
                mitraFields.classList.add('hidden');
                freelancerFields.classList.remove('hidden');
            }
        }

        // Jalankan saat ada perubahan
        roleInputs.forEach(input => {
            input.addEventListener('change', toggleFields);
        });
        
        // Jalankan sekali saat halaman dimuat (untuk menangani old input jika ada error)
        toggleFields();
    });
</script>
@endsection