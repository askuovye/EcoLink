<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EcoLink</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-inter antialiased overflow-x-hidden min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-50">
        @include('layouts.navigation')
    </nav>

    {{-- Header --}}
    @isset($header)
        <header class="bg-gradient-to-r from-green-100 to-green-50 shadow-sm">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Conteúdo da Página --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 py-8 text-center text-gray-600 shadow-inner">
        <div class="flex justify-center space-x-4 mb-4">
            <i data-lucide="instagram" class="w-5 h-5 hover:text-green-600 transition"></i>
            <i data-lucide="linkedin" class="w-5 h-5 hover:text-green-600 transition"></i>
            <i data-lucide="github" class="w-5 h-5 hover:text-green-600 transition"></i>
        </div>
        <p>&copy; {{ date('Y') }} <span class="text-green-600 font-semibold">EcoLink</span>. Todos os direitos reservados.</p>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
