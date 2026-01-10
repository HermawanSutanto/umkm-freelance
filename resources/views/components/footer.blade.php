<footer class="bg-gray-900 text-gray-300 pt-16 pb-8 mt-auto border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            {{-- KOLOM 1: Brand & Deskripsi --}}
            <div class="space-y-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 text-black bg-brand-gold rounded-lg flex items-center justify-center shadow-lg group-hover:rotate-6 transition-transform">
                        <i class="material-icons">storefront</i>
                    </div>
                    <span class="text-2xl font-bold text-white tracking-tight">Lokalitas<span class="text-brand-gold">Market</span></span>
                </a>
                <p class="text-sm leading-relaxed text-gray-400">
                    Platform digital yang menghubungkan UMKM lokal dan freelancer profesional untuk berkolaborasi, berkarya, dan tumbuh bersama demi ekonomi kreatif yang lebih kuat.
                </p>
                <div class="flex space-x-4 pt-2">
                    {{-- Social Media Icons --}}
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-brand-gold hover:text-black transition-all duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-brand-gold hover:text-black transition-all duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-brand-gold hover:text-black transition-all duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-brand-gold hover:text-black transition-all duration-300">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            {{-- KOLOM 2: Quick Links --}}
            <div>
                <h3 class="text-white font-bold text-lg mb-6 relative inline-block">
                    Jelajahi
                    <span class="absolute bottom-0 left-0 w-1/2 h-1 bg-brand-gold rounded-full"></span>
                </h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-brand-gold transition-colors flex items-center gap-2"><i class="material-icons text-xs text-gray-600">chevron_right</i> Beranda</a></li>
                    <li><a href="{{ url('/produk') }}" class="hover:text-brand-gold transition-colors flex items-center gap-2"><i class="material-icons text-xs text-gray-600">chevron_right</i> Toko UMKM</a></li>
                    <li><a href="{{ url('/freelancer') }}" class="hover:text-brand-gold transition-colors flex items-center gap-2"><i class="material-icons text-xs text-gray-600">chevron_right</i> Cari Freelancer</a></li>
                    <li><a href="{{ url('/tentang') }}" class="hover:text-brand-gold transition-colors flex items-center gap-2"><i class="material-icons text-xs text-gray-600">chevron_right</i> Tentang Kami</a></li>
                </ul>
            </div>

            {{-- KOLOM 3: Kontak --}}
            <div>
                <h3 class="text-white font-bold text-lg mb-6 relative inline-block">
                    Hubungi Kami
                    <span class="absolute bottom-0 left-0 w-1/2 h-1 bg-brand-gold rounded-full"></span>
                </h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <i class="material-icons text-brand-gold mt-1">location_on</i>
                        <span>Jl. Kalimantan No. 37,<br>Kampus Tegalboto, Jember,<br>Jawa Timur 68121</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="material-icons text-brand-gold">email</i>
                        <a href="mailto:halo@lokalitasmarket.id" class="hover:text-brand-gold transition-colors">halo@lokalitasmarket.id</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="material-icons text-brand-gold">phone</i>
                        <a href="tel:+6281234567890" class="hover:text-brand-gold transition-colors">+62 812-3456-7890</a>
                    </li>
                </ul>
            </div>

            {{-- KOLOM 4: Newsletter --}}
            <div>
                <h3 class="text-white font-bold text-lg mb-6 relative inline-block">
                    Berita Terbaru
                    <span class="absolute bottom-0 left-0 w-1/2 h-1 bg-brand-gold rounded-full"></span>
                </h3>
                <p class="text-sm text-gray-400 mb-4">Dapatkan info terbaru mengenai promo UMKM dan tips freelancer setiap minggunya.</p>
                <form class="space-y-2">
                    <div class="relative">
                        <input type="email" placeholder="Alamat Email Anda" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-gold text-white placeholder-gray-500 transition-colors">
                        <button type="button" class="absolute right-2 top-1.5 bg-brand-gold text-black p-1.5 rounded-md hover:bg-yellow-600 transition-colors">
                            <i class="material-icons text-sm">send</i>
                        </button>
                    </div>
                </form>
            </div>

        </div>

        {{-- COPYRIGHT SECTION --}}
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-500 text-center md:text-left">
                &copy; {{ date('Y') }} <span class="text-gray-300 font-semibold">Lokalitas Market</span>. All rights reserved.
            </p>
            <div class="flex space-x-6 text-sm text-gray-500">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-white transition-colors">Sitemap</a>
            </div>
        </div>
    </div>
</footer>