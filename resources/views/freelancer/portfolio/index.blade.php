@extends('layouts.app') 
{{-- Asumsi Anda menggunakan layout default Laravel/Breeze/Jetstream --}}

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">
                Portofolio Kami
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                Kumpulan karya terbaik yang telah kami kerjakan dengan dedikasi dan kreativitas.
            </p>
        </div>

        {{-- Filter Section --}}
        <div class="flex justify-center mb-10 space-x-2 overflow-x-auto pb-4">
            {{-- PERBAIKAN: Tambahkan prefix 'freelancer.' --}}
            <a href="{{ route('freelancer.portfolios.index') }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !request('category') ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border' }}">
                All
            </a>
            <a href="{{ route('freelancer.portfolios.create') }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !request('category') ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border' }}">
                Tambahkan portofolio
            </a>
            @foreach($categories as $cat)
                {{-- PERBAIKAN: Tambahkan prefix 'freelancer.' --}}
                <a href="{{ route('freelancer.portfolios.index', ['category' => $cat]) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('category') == $cat ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100 border' }}">
                    {{ ucfirst($cat) }}
                </a>
            @endforeach
        </div>

        {{-- Grid Content --}}
        @if($portfolios->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($portfolios as $portfolio)
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                    
                    {{-- Thumbnail Image --}}
                    <div class="relative h-56 overflow-hidden bg-gray-200">
                        {{-- PERBAIKAN: Tambahkan prefix 'freelancer.' --}}
                        <a href="{{ route('freelancer.portfolios.show', $portfolio) }}">
                            <img src="{{ $portfolio->thumbnail_url }}" 
                                 alt="{{ $portfolio->title }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text=No+Image';">
                        </a>
                        <span class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-indigo-600 shadow-sm">
                            {{ ucfirst($portfolio->category) }}
                        </span>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex-grow">
                            {{-- PERBAIKAN: Tambahkan prefix 'freelancer.' --}}
                            <a href="{{ route('freelancer.portfolios.show', $portfolio) }}" class="block mt-2">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                    {{ $portfolio->title }}
                                </h3>
                                <p class="mt-3 text-base text-gray-500 line-clamp-3">
                                    {{ Str::limit($portfolio->description, 100) }}
                                </p>
                            </a>
                        </div>
                        
                        {{-- Card Footer --}}
                        <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="sr-only">{{ $portfolio->user->name }}</span>
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                        {{ substr($portfolio->user->name, 0, 2) }}
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-900">
                                        {{ $portfolio->user->name }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $portfolio->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            
                            @if($portfolio->project_url)
                                <a href="{{ $portfolio->project_url }}" target="_blank" class="text-gray-400 hover:text-indigo-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $portfolios->links() }} 
            </div>

        @else
            {{-- Empty State --}}
            <div class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada portofolio</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat proyek baru.</p>
            </div>
        @endif
    </div>
</div>
@endsection