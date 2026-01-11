<header x-data="{ open: false }" class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            {{-- KIRI: LOGO --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 text-black bg-brand-gold rounded-lg flex items-center justify-center shadow-md group-hover:rotate-6 transition-transform">
                    <i class="material-icons">storefront</i>
                </div>
                <span class="text-2xl font-bold text-gray-900 tracking-tight">Lokalitas<span class="text-brand-gold">Market</span></span>
            </a>
            
            {{-- TENGAH: NAVIGASI DESKTOP (Hidden on Mobile) --}}
            <nav class="hidden md:flex space-x-8">
                @php
                    $navClass = "nav-link text-gray-600 hover:text-brand-gold font-medium transition-colors duration-200 relative py-2";
                    $activeClass = "active text-brand-gold font-bold";
                @endphp

                <a href="{{ url('/dashboard') }}" class="{{ $navClass }} {{ request()->is('/') ? $activeClass : '' }}">
                    Home
                </a>

                @guest
                    <a href="{{ url('/produk') }}" class="{{ $navClass }} {{ request()->is('produk*') ? $activeClass : '' }}">
                        Toko UMKM
                    </a>
                    <a href="{{ url('/freelancer') }}" class="{{ $navClass }} {{ request()->is('freelancer*') ? $activeClass : '' }}">
                        Cari Freelancer
                    </a>
                @endguest

                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.payment.index') }}" class="{{ $navClass }} {{ request()->is('methods*') ? $activeClass : '' }}">
                            Metode Pembayaran
                        </a>
                    @endif
                    @if(Auth::user()->role === 'mitra')
                        <a href="{{ route('mitra.products.index') }}" class="{{ $navClass }} {{ request()->is('products*') ? $activeClass : '' }}">
                            Kelola Produk
                        </a>
                    @endif

                    @if(Auth::user()->role === 'freelancer')
                        <a href="{{ url('/dashboard') }}" class="{{ $navClass }} {{ request()->is('projects*') ? $activeClass : '' }}">
                            Profil saya
                        </a>
                        <a href="{{ route('freelancer.portfolios.index') }}" class="{{ $navClass }} {{ request()->is('   *') ? $activeClass : '' }}">
                            Portofolio Saya
                        </a>
                    @endif
                @endauth

                
            </nav>
            
            {{-- KANAN: User Menu & Mobile Toggle --}}
            <div class="flex items-center space-x-4">
                
                {{-- Search & Cart (Desktop Only) --}}
                {{-- <div class="hidden md:flex items-center space-x-4 border-r border-gray-200 pr-4">
                    <button class="text-gray-500 hover:text-brand-gold transition-colors">
                        <i class="material-icons">search</i>
                    </button>
                    <button class="text-gray-500 hover:text-brand-gold transition-colors relative">
                        <i class="material-icons">shopping_bag</i>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">2</span>
                    </button>
                </div> --}}
                
                {{-- User Auth (Desktop) --}}
                <div class="hidden md:block">
                    @guest
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-900 hover:text-brand-gold transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-colors shadow-md">Daftar</a>
                        </div>
                    @else
                        <div class="relative group">
                            <button class="flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-brand-gold transition-colors focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden border border-gray-300">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="material-icons text-base">expand_more</i>
                            </button>
                            {{-- Dropdown Menu --}}
                            <div class="absolute right-0 w-48 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                                <div class="py-1">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">{{ Auth::user()->role }}</p>
                                    </div>
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-gold">Dashboard</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-brand-gold">Edit Profil</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>

                {{-- TOMBOL HAMBURGER (Mobile Only) --}}
                <div class="flex md:hidden items-center">
                    <button @click="open = !open" type="button" class="text-gray-500 hover:text-gray-900 focus:outline-none p-2 rounded-md hover:bg-gray-100">
                        <i class="material-icons text-2xl" x-show="!open">menu</i>
                        <i class="material-icons text-2xl" x-show="open" x-cloak>close</i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU (Dropdown) --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden border-t border-gray-100 bg-white absolute w-full shadow-lg"
         x-cloak>
        
        <div class="px-4 pt-4 pb-6 space-y-2 max-h-[80vh] overflow-y-auto">
            
            {{-- Link Navigasi Mobile --}}
            <a href="{{ url('/') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-gold hover:bg-gray-50 {{ request()->is('/') ? 'bg-gray-50 text-brand-gold' : '' }}">Home</a>
            
            @guest
                <a href="{{ url('/produk') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-gold hover:bg-gray-50">Toko UMKM</a>
                <a href="{{ url('/freelancer') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-gold hover:bg-gray-50">Cari Freelancer</a>
            @endguest

            @auth
                @if(Auth::user()->role === 'mitra')
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-gold hover:bg-gray-50">Kelola Toko</a>
                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-gold hover:bg-gray-50">Pesanan Masuk</a>
                @endif
                @if(Auth::user()->role === 'freelancer')
                    <a href="{{ url('/projects') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-gold hover:bg-gray-50">Cari Proyek</a>
                @endif
                @if(Auth::user()->role === 'admin')
                    <a href="{{ url('/payment-methods') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-gold hover:bg-gray-50">Metode Pembayaran</a>
                @endif
            @endauth

            <a href="{{ url('/tentang') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-brand-gold hover:bg-gray-50">Tentang Kami</a>

            {{-- Divider --}}
            <div class="border-t border-gray-200 my-2"></div>

            {{-- Auth Section Mobile --}}
            @guest
                <div class="grid grid-cols-2 gap-4 px-3 py-2">
                    <a href="{{ route('login') }}" class="text-center py-2 border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50">Masuk</a>
                    <a href="{{ route('register') }}" class="text-center py-2 bg-gray-900 text-white rounded-lg font-bold hover:bg-gray-800">Daftar</a>
                </div>
            @else
                <div class="px-3 py-2">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 uppercase">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-md">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-md">Edit Profil</a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 bg-red-50 hover:bg-red-100 rounded-md font-bold">Logout</button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</header>

{{-- Pastikan Alpine.js sudah diload di layout utama Anda --}}
{{-- Jika belum, tambahkan script ini di <head> layout utama: --}}
{{-- <script src="//unpkg.com/alpinejs" defer></script> --}}