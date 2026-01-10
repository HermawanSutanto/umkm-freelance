@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Produk</h1>
        <a href="{{ route('mitra.products.index') }}" class="text-gray-500 hover:text-gray-900">Kembali</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        {{-- Form mengarah ke UPDATE (PUT) --}}
        <form action="{{ route('mitra.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT') 
            
            {{-- Nama Produk --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-gray-700">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Harga --}}
                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-gray-700">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50">
                    @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Stok --}}
                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-gray-700">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50">
                    @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-gray-700">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <hr class="border-gray-100 my-6">

            {{-- BAGIAN EDIT COVER IMAGE --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cover Saat Ini</label>
                    <div class="aspect-square rounded-xl overflow-hidden border border-gray-200 relative">
                        <img src="{{ $product->cover_url }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/10"></div>
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Cover (Opsional)</label>
                    
                    {{-- Input Cover Baru --}}
                    <div class="relative flex justify-center items-center h-48 border-2 border-gray-300 border-dashed rounded-xl hover:bg-gray-50 transition overflow-hidden">
                        <input id="cover_image" name="cover_image" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" onchange="previewSingleImage(this, 'cover-preview-img', 'cover-placeholder')">
                        
                        <div id="cover-placeholder" class="space-y-1 text-center">
                            <i class="material-icons text-gray-400 text-3xl">cloud_upload</i>
                            <p class="text-xs text-gray-500">Klik untuk upload cover baru</p>
                        </div>

                        <img id="cover-preview-img" src="#" class="hidden h-full w-full object-contain p-2 z-0">
                    </div>
                    @error('cover_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-100 my-6">

            {{-- BAGIAN EDIT GALERI --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-4">Galeri Foto</label>

                {{-- 1. Daftar Gambar Lama (Bisa Dihapus) --}}
                @if($product->images->count() > 0)
                    <p class="text-xs text-gray-500 mb-2">Foto yang sudah ada (Klik ikon sampah untuk menghapus):</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @foreach($product->images as $image)
                            <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200">
                                <img src="{{ $image->image_url }}" class="w-full h-full object-cover">
                                
                                {{-- Tombol Hapus Gambar --}}
                                <button type="button" onclick="deleteImageConfirmation('{{ route('mitra.product-images.destroy', $image->id) }}')" 
                                    class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition transform hover:scale-110">
                                    <i class="material-icons text-sm">delete</i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- 2. Upload Gambar Baru --}}
                <label class="block text-xs font-semibold text-gray-500 mb-2">Tambah Foto Baru:</label>
                <div class="flex items-center justify-center w-full">
                    <label for="gallery_images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="material-icons text-gray-400 mb-2">add_photo_alternate</i>
                            <p class="text-xs text-gray-500">Klik untuk menambah foto lagi</p>
                        </div>
                        <input id="gallery_images" name="gallery_images[]" type="file" multiple class="hidden" accept="image/*" />
                    </label>
                </div>
                {{-- Preview Grid untuk gambar BARU --}}
                <div id="new-gallery-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden"></div>
            </div>

            <div class="pt-6 border-t">
                <button type="submit" class="w-full px-6 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- FORM TERSEMBUNYI UNTUK DELETE IMAGE --}}
<form id="delete-image-form" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

{{-- SCRIPT JS --}}
<script>
    // 1. Preview Single Image (Cover Baru)
    function previewSingleImage(input, imgId, placeholderId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(imgId).src = e.target.result;
                document.getElementById(imgId).classList.remove('hidden');
                document.getElementById(placeholderId).classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // 2. Preview Multiple Images (Galeri Baru)
    const galleryInput = document.getElementById('gallery_images');
    const galleryContainer = document.getElementById('new-gallery-preview');

    galleryInput.addEventListener('change', function(event) {
        galleryContainer.innerHTML = '';
        const files = event.target.files;

        if (files.length > 0) {
            galleryContainer.classList.remove('hidden');
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group h-24 w-full rounded-xl overflow-hidden border border-gray-200 shadow-sm';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    galleryContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
        });
        }
    });

    // 3. Konfirmasi Hapus Gambar Lama
    function deleteImageConfirmation(url) {
        if(confirm('Hapus gambar ini dari galeri?')) {
            const form = document.getElementById('delete-image-form');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection