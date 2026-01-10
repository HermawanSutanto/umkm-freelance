<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lokalitas Market</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 2px;
            background-color: #c59d5f; /* brand-gold */
            transition: width 0.3s;
        }
        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">
    
    <x-header />

    <main class="pt-24 min-h-screen pb-10">
        @yield('content') 
    </main>


    <x-footer />

</body>
<script src="//unpkg.com/alpinejs" defer></script> 
</html>