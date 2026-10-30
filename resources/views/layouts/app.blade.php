<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Home')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script>
        (() => {
            const theme = {{ Illuminate\Support\Js::from($interfacePreferences['theme'] ?? 'system') }};
            document.documentElement.classList.toggle('dark', theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches));
        })();
    </script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-950">
        @auth
            @include('layouts.navigation')
        @endauth

        <!-- Page Heading -->
        @hasSection('header')
            <header class="bg-white dark:bg-gray-900 shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html> 