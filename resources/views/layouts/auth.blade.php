<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Lokalitas Market' }}</title>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="sticky min-h-screen flex flex-row-reverse bg-gray-50">
        <div class="hidden lg:block relative w-0 flex-1 bg-gray-900">
            <img class="absolute inset-0 h-full w-full object-cover opacity-50" src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&q=80&w=1000" alt="Teamwork">
            <div class="absolute inset-0 bg-linear-to-r from-gray-900 via-gray-900/80 to-brand-gold/30 mix-blend-multiply"></div>
            
            <div class="absolute inset-0 flex flex-col justify-center p-20 z-20">
                <h3 class="text-4xl font-extrabold text-white mb-6 leading-tight">Mulai Langkah Digital Anda.</h3>
                <p class="text-lg text-gray-300 max-w-lg leading-relaxed">Bergabunglah dengan komunitas profesional dan pengusaha yang saling mendukung.</p>
            </div>
        </div>

        <div class="flex-1 flex flex-col py-12 px-4 sm:px-6 lg:px-20 xl:px-24 bg-white overflow-y-auto h-screen relative z-10">
            <div class="mx-auto w-full max-w-md">
                <div class="mb-10 text-center lg:text-left">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group transition-opacity hover:opacity-80">
                        <div class="w-10 h-10 bg-brand-gold rounded-xl flex items-center justify-center text-black shadow-lg shadow-brand-gold/30">
                            <i class="material-icons">storefront</i>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 tracking-tight">Lokalitas<span class="text-brand-gold">Market</span></span>
                    </a>
                </div>

                {{-- Konten Form akan di-inject di sini --}}
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>