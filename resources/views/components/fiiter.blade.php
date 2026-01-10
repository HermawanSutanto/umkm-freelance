<footer>
    <div class="bg-gray-800 text-gray-300 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-10">
            <div>
                <h4 class="text-lg font-semibold text-brand-gold mb-4">Layanan</h4>
                <a href="{{ url('/produk') }}" class="block hover:text-white transition duration-200 mb-2">Toko UMKM</a>
                <a href="{{ url('/freelancer') }}" class="block hover:text-white transition duration-200 mb-2">Cari Freelancer</a>
                <a href="{{ url('/daftar-freelancer') }}" class="block hover:text-white transition duration-200 mb-2">Jadi Freelancer</a>
            </div>

            <div>
                <h4 class="text-lg font-semibold text-brand-gold mb-4">Perusahaan</h4>
                <a href="{{ url('/tentang') }}" class="block hover:text-white transition duration-200 mb-2">Tentang Kami</a>
                <a href="{{ url('/kontak') }}" class="block hover:text-white transition duration-200 mb-2">Hubungi Kami</a>
                <a href="{{ url('/kebijakan') }}" class="block hover:text-white transition duration-200 mb-2">Kebijakan Privasi</a>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold text-brand-gold mb-4">Dukungan</h4>
                <a href="{{ url('/faq') }}" class="block hover:text-white transition duration-200 mb-2">FAQ</a>
                <a href="{{ url('/bantuan') }}" class="block hover:text-white transition duration-200 mb-2">Pusat Bantuan</a>
                <a href="{{ url('/karir') }}" class="block hover:text-white transition duration-200 mb-2">Karir</a>
            </div>

            <div>
                <h4 class="text-lg font-semibold text-brand-gold mb-4">Ikuti Kami</h4>
                <div class="social-icons flex space-x-4">
                    <a href="#" class="text-2xl hover:text-white transition duration-200"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="text-2xl hover:text-white transition duration-200"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="text-2xl hover:text-white transition duration-200"><i class="fa-brands fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-gray-900 text-gray-500 text-center py-4 text-sm border-t border-gray-700">
        &copy; {{ date('Y') }} Lokalitas Market. All rights reserved.
    </div>
</footer>