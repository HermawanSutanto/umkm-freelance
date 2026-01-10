@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Profil Saya</h2>
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-900 text-sm">
            &larr; Kembali ke Dashboard\
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Banner & Foto Profil (Opsional/Placeholder) --}}
        <div class="h-32 bg-linear-to-r from-gray-900 to-gray-800"></div>
        <div class="px-8 pb-8">
            <div class="-mt-12 mb-6">
                <div class="h-24 w-24 rounded-full bg-white p-1 shadow-lg">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="Avatar" class="h-full w-full rounded-full object-cover">
                </div>
            </div>

            {{-- Info Utama --}}
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-brand-gold/10 text-brand-gold text-xs font-bold rounded-full uppercase tracking-wide border border-brand-gold/20">
                        {{ $user->role }}
                    </span>
                </div>
                
                {{-- Tombol Edit --}}
                <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium transition shadow-sm">
                    Edit Profil
                </a>
            </div>

            <hr class="my-6 border-gray-100">

            {{-- Detail Khusus Role --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($user->role === 'mitra' && $user->mitraProfile)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Nama Toko</h3>
                        <p class="text-gray-900 font-medium">{{ $user->mitraProfile->shop_name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Alamat Toko</h3>
                        <p class="text-gray-900">{{ $user->mitraProfile->shop_address ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Nomor Telepon</h3>
                        <p class="text-gray-900">{{ $user->mitraProfile->phone_number ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Deskripsi Toko</h3>
                        <p class="text-gray-900">{{ $user->mitraProfile->shop_description ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Jam Operasional</h3>
                        <p class="text-gray-900">{{ $user->mitraProfile->operational_hours ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Link Google Map</h3>
                        <p class="text-gray-900">{{ $user->mitraProfile->gmap_link ?? '-' }}</p>
                    </div>
                @elseif($user->role === 'freelancer' && $user->freelancerProfile)
                    
                    {{-- 1. Bio (Full Width) --}}
                    <div class="md:col-span-2">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Bio / Tentang Saya</h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 p-4 rounded-xl border border-gray-100">
                            {{ $user->freelancerProfile->bio ?? 'Belum ada deskripsi diri.' }}
                        </p>
                    </div>

                    {{-- 2. Status & Rate --}}
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Status Ketersediaan</h3>
                        @if($user->freelancerProfile->is_available)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Open for Job
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-gray-500"></span> Sedang Sibuk
                            </span>
                        @endif
                    </div>
                    {{-- 3. Skills (Tags Style) --}}
                    <div class="md:col-span-2">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keahlian (Skills)</h3>
                        <div class="flex flex-wrap gap-2">
                            @if($user->freelancerProfile->skills)
                                @foreach(explode(',', $user->freelancerProfile->skills) as $skill)
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-sm font-medium rounded-lg border border-indigo-100">
                                        {{ trim($skill) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-400 italic text-sm">Belum ditambahkan</span>
                            @endif
                        </div>
                    </div>

                    {{-- 4. Tautan / Social Media --}}
                    <div class="md:col-span-2">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tautan Profesional</h3>
                        <div class="flex flex-col sm:flex-row gap-4">
                            {{-- Portfolio Link --}}
                            @if($user->freelancerProfile->portfolio_link)
                                <a href="{{ $user->freelancerProfile->portfolio_link }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:border-indigo-300 hover:text-indigo-600 transition">
                                    <i class="material-icons text-sm">link</i> Website/Portofolio
                                </a>
                            @endif

                            {{-- Github Link --}}
                            @if($user->freelancerProfile->github_url)
                                <a href="{{ $user->freelancerProfile->github_url }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:border-gray-400 hover:text-black transition">
                                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/github/github-original.svg" class="w-4 h-4" alt="Github"> Github
                                </a>
                            @endif

                            {{-- LinkedIn Link --}}
                            @if($user->freelancerProfile->linkedin_url)
                                <a href="{{ $user->freelancerProfile->linkedin_url }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:border-blue-300 hover:text-blue-600 transition">
                                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/linkedin/linkedin-original.svg" class="w-4 h-4" alt="LinkedIn"> LinkedIn
                                </a>
                            @endif
                            
                            @if(!$user->freelancerProfile->portfolio_link && !$user->freelancerProfile->github_url && !$user->freelancerProfile->linkedin_url)
                                <p class="text-gray-400 text-sm italic">Tidak ada tautan terlampir.</p>
                            @endif
                        </div>
                    </div>

                @else
                    {{-- Jika Profil Belum Lengkap --}}
                    <div class="md:col-span-2 text-center py-8">
                        <p class="text-gray-500 mb-4">Profil detail Anda belum dilengkapi.</p>
                        <a href="{{ route('profile.edit') }}" class="inline-block px-6 py-2 bg-brand-gold text-white font-bold rounded-lg hover:bg-yellow-600 transition">
                            Lengkapi Profil Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection