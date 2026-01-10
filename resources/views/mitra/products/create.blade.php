@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h1>
        <a href="{{ route('mitra.products.index') }}" class="text-gray-500 hover:text-gray-900">Batal</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('mitra.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            {{-- Nama Produk --}}
            
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-gray-700">Kategori Produk <span class="text-red-500">*</span></label>
                
                {{-- OPSI 1: DROPDOWN (Default) --}}
                <div id="category-select-wrapper" class="{{ old('new_category') ? 'hidden' : '' }}">
                    <div class="relative">
                        <select id="category_id" name="category_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50 appearance-none bg-white">
                            <option value="" disabled selected>Pilih Kategori...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                            <i class="material-icons text-gray-500">expand_more</i>
                        </div>
                    </div>
                    <button type="button" onclick="toggleCategoryInput()" class="text-xs text-brand-gold font-bold mt-1 hover:underline focus:outline-none">
                        + Kategori tidak ada? Buat baru
                    </button>
                </div>

                {{-- OPSI 2: INPUT TEXT (Hidden by default) --}}
                <div id="category-input-wrapper" class="{{ old('new_category') ? '' : 'hidden' }}">
                    <input type="text" id="new_category" name="new_category" value="{{ old('new_category') }}" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50" 
                        placeholder="Ketik nama kategori baru...">
                    
                    <button type="button" onclick="toggleCategoryInput()" class="text-xs text-red-500 font-bold mt-1 hover:underline focus:outline-none">
                        Batal (Kembali pilih kategori)
                    </button>
                </div>

                @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                @error('new_category') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50" placeholder="Contoh: Keripik Singkong Balado">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Harga --}}
                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50" placeholder="15000">
                    @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Stok --}}
                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-gray-700">Stok Awal <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50">
                    @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-gray-700">Deskripsi Produk</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-brand-gold/50" placeholder="Jelaskan rasa, bahan, dan keunggulan produk...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- 1. PREVIEW COVER IMAGE (SINGLE) --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-gray-700">Cover Produk (Gambar Utama) <span class="text-red-500">*</span></label>
                
                <div class="mt-1 relative flex justify-center items-center h-64 border-2 border-gray-300 border-dashed rounded-xl hover:bg-gray-50 transition overflow-hidden">
                    
                    {{-- Input File Utama --}}
                    <input id="cover_image" name="cover_image" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">
                    
                    {{-- Placeholder --}}
                    <div id="cover-placeholder" class="space-y-1 text-center">
                        <i class="material-icons text-gray-400 text-3xl">image</i>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <span class="relative bg-white rounded-md font-medium text-brand-gold hover:text-yellow-600">
                                Upload Cover
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">Gambar halaman depan</p>
                    </div>

                    {{-- Image Preview Container --}}
                    <div id="cover-preview-container" class="hidden absolute inset-0 w-full h-full bg-gray-100 flex items-center justify-center z-0">
                        <img id="cover-preview-img" src="#" alt="Preview Cover" class="h-full w-full object-contain p-2">
                        <div class="absolute bottom-2 bg-black/50 text-white text-xs px-2 py-1 rounded-md backdrop-blur-sm z-20">
                            Klik untuk ganti
                        </div>
                    </div>
                </div>
                @error('cover_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- 2. PREVIEW GALLERY IMAGES (MULTIPLE) --}}
            <div class="space-y-1 mt-6">
                <label class="block text-sm font-semibold text-gray-700">Galeri Foto Tambahan (Opsional)</label>
                
                {{-- Input File Multiple --}}
                <div class="flex items-center justify-center w-full">
                    <label for="gallery_images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="material-icons text-gray-400 mb-2">add_photo_alternate</i>
                            <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> banyak foto</p>
                            <p class="text-xs text-gray-500">Bisa pilih lebih dari 1 file sekaligus</p>
                        </div>
                        <input id="gallery_images" name="gallery_images[]" type="file" multiple class="hidden" accept="image/*" />
                    </label>
                </div>

                {{-- Container Preview Galeri (Grid Layout) --}}
                <div id="gallery-preview-container"
                    class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden">
                </div>
                {{-- Modal Fullscreen --}}
                <div id="image-modal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[9999]">
                    <img id="modal-image" class="max-w-full max-h-full object-contain rounded-lg shadow-xl" />
                </div>

                @error('gallery_images.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t">
                <button type="submit" class="w-full md:w-auto px-6 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    // 1. Logic untuk Preview Cover Image (Single)
    const coverInput = document.getElementById('cover_image');
    const coverPlaceholder = document.getElementById('cover-placeholder');
    const coverPreviewContainer = document.getElementById('cover-preview-container');
    const coverPreviewImg = document.getElementById('cover-preview-img');

    coverInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                coverPreviewImg.src = e.target.result;
                coverPlaceholder.classList.add('hidden');
                coverPreviewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            coverPreviewImg.src = '#';
            coverPlaceholder.classList.remove('hidden');
            coverPreviewContainer.classList.add('hidden');
        }
    });

    // 2. Logic untuk Preview Gallery Images (Multiple)
    // ========== MULTIPLE PREVIEW + MODAL + DRAG SORT ==========

const galleryInput = document.getElementById('gallery_images');
const galleryContainer = document.getElementById('gallery-preview-container');
const modal = document.getElementById('image-modal');
const modalImg = document.getElementById('modal-image');

// Store file previews in memory to reorder later
let galleryFiles = [];

galleryInput.addEventListener('change', function (event) {
    galleryFiles = Array.from(event.target.files);
    renderGallery();
});

function renderGallery() {
    galleryContainer.innerHTML = '';

    if (galleryFiles.length > 0) {
        galleryContainer.classList.remove('hidden');
    } else {
        galleryContainer.classList.add('hidden');
        return;
    }

    galleryFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            
            // Create wrapper
            const item = document.createElement('div');
            item.className = `
                relative group h-40 rounded-xl overflow-hidden border shadow cursor-move
            `;
            item.setAttribute("draggable", "true");
            item.dataset.index = index;

            // Insert image
            item.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></div>
                <span class="absolute bottom-1 left-1 bg-black/50 text-white text-xs px-2 py-1 rounded">
                    ${index + 1}
                </span>
            `;

            // CLICK → SHOW MODAL
            item.addEventListener("click", () => {
                modalImg.src = e.target.result;
                modal.classList.remove("hidden");
            });

            // DRAG EVENTS
            item.addEventListener("dragstart", dragStart);
            item.addEventListener("dragover", dragOver);
            item.addEventListener("drop", drop);

            galleryContainer.appendChild(item);
        };

        reader.readAsDataURL(file);
    });
}

// HIDE MODAL WHEN CLICK OUTSIDE IMAGE
modal.addEventListener("click", () => {
    modal.classList.add("hidden");
});


// ======== DRAG & DROP ========
let draggedIndex = null;

function dragStart(e) {
    e.stopPropagation();
    draggedIndex = Number(e.currentTarget.dataset.index);
}

function dragOver(e) {
    e.preventDefault();
    e.stopPropagation();
}

function drop(e) {
    e.preventDefault();
    e.stopPropagation();

    const targetWrapper = e.currentTarget; 
    const targetIndex = Number(targetWrapper.dataset.index);

    // swap array
    const temp = galleryFiles[draggedIndex];
    galleryFiles[draggedIndex] = galleryFiles[targetIndex];
    galleryFiles[targetIndex] = temp;

    renderGallery();
}
function toggleCategoryInput() {
        const selectWrapper = document.getElementById('category-select-wrapper');
        const inputWrapper = document.getElementById('category-input-wrapper');
        const selectField = document.getElementById('category_id');
        const inputField = document.getElementById('new_category');

        if (selectWrapper.classList.contains('hidden')) {
            // Tampilkan Dropdown, Sembunyikan Input
            selectWrapper.classList.remove('hidden');
            inputWrapper.classList.add('hidden');
            inputField.value = ''; // Reset input baru
        } else {
            // Tampilkan Input, Sembunyikan Dropdown
            selectWrapper.classList.add('hidden');
            inputWrapper.classList.remove('hidden');
            selectField.value = ''; // Reset dropdown
        }
    }
</script>
@endsection