@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb / Header --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Tambah Portofolio</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Tunjukkan karya terbaik Anda kepada klien potensial.
                </p>
            </div>
            <a href="{{ route('freelancer.portfolios.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center">
                &larr; Kembali
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('freelancer.portfolios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Judul Proyek --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Proyek <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('title') border-red-500 @enderror"
                            placeholder="Contoh: Website E-Commerce Toko Baju">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori & Project URL (Grid) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kategori --}}
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                            <input type="text" name="category" id="category" value="{{ old('category') }}" 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category') border-red-500 @enderror"
                                placeholder="Contoh: Web Development, Design, Mobile App">
                            <p class="mt-1 text-xs text-gray-400">Gunakan kategori umum agar mudah dicari.</p>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Project URL --}}
                        <div>
                            <label for="project_url" class="block text-sm font-medium text-gray-700">Link Proyek (Opsional)</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    https://
                                </span>
                                <input type="text" name="project_url" id="project_url" value="{{ old('project_url') }}" 
                                    class="flex-1 block w-full rounded-none rounded-r-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="www.contoh.com">
                            </div>
                            @error('project_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Proyek <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="4" 
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-500 @enderror"
                                placeholder="Jelaskan tantangan, solusi, dan teknologi yang Anda gunakan...">{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Thumbnail Upload Area (Disatukan) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail Portfolio <span class="text-red-500">*</span></label>
                        
                        {{-- Container Drag & Drop --}}
                        <div class="mt-1 relative flex justify-center items-center h-64 border-2 border-gray-300 border-dashed rounded-xl hover:bg-gray-50 transition overflow-hidden bg-white">
                            
                            {{-- Input File Utama (Opacity 0 tapi ada di paling atas z-20 agar bisa diklik) --}}
                            <input id="thumbnail" name="thumbnail" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*">
                            
                            {{-- Tampilan Placeholder (Default) --}}
                            <div id="thumbnail-placeholder" class="space-y-1 text-center pointer-events-none">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="relative bg-white rounded-md font-medium text-indigo-600">
                                        Upload Thumbnail
                                    </span> 
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>

                            {{-- Tampilan Preview (Hidden by default) --}}
                            <div id="thumbnail-preview-container" class="hidden absolute inset-0 w-full h-full bg-gray-50 flex items-center justify-center z-10 pointer-events-none">
                                <img id="thumbnail-preview-img" src="#" alt="Preview Cover" class="h-full w-full object-contain p-2">
                                <div class="absolute bottom-2 bg-black/60 text-white text-xs px-3 py-1.5 rounded-full backdrop-blur-sm z-20 shadow-sm">
                                    Klik untuk ganti gambar
                                </div>
                            </div>
                        </div>
                        @error('thumbnail') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="pt-5 border-t border-gray-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('freelancer.portfolios.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan Portofolio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const coverInput = document.getElementById('thumbnail');
        const coverPlaceholder = document.getElementById('thumbnail-placeholder');
        const coverPreviewContainer = document.getElementById('thumbnail-preview-container');
        const coverPreviewImg = document.getElementById('thumbnail-preview-img');

        coverInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Set sumber gambar
                    coverPreviewImg.src = e.target.result;
                    
                    // Sembunyikan placeholder
                    coverPlaceholder.classList.add('hidden');
                    
                    // Tampilkan container preview
                    coverPreviewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                // Reset jika user membatalkan upload
                coverPreviewImg.src = '#';
                coverPlaceholder.classList.remove('hidden');
                coverPreviewContainer.classList.add('hidden');
            }
        });
    });
</script>
@endsection