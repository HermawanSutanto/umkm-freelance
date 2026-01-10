@extends('layouts.auth')

@section('content')

<h2 class="text-3xl font-bold text-gray-900 tracking-tight">Masuk ke Akun Anda</h2>
<p class="mt-2 text-sm text-gray-500 mb-8">
    Belum punya akun?
    <a href="{{ route('register') }}" class="font-bold text-brand-gold hover:text-yellow-700 transition-colors">
        Daftar di sini
    </a>
</p>

<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <div class="space-y-1">
        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required 
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

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-brand-gold focus:ring-brand-gold border-gray-300 rounded">
            <label for="remember_me" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
        </div>

        {{-- <div class="text-sm">
            <a href="{{ route('password.request') }}" class="font-medium text-brand-gold hover:text-yellow-700">Lupa password?</a>
        </div> --}}
    </div>

    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-brand-gold/30 text-sm font-bold text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all duration-300 transform hover:-translate-y-0.5">
        Masuk
    </button>
</form>
@endsection