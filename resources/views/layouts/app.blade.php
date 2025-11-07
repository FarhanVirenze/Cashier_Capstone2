<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pos-url" content="{{ route('pos.index') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/antijingoist/open-dyslexic/webkit.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-papj5pql/..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('scripts')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-white dark:bg-white">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="relative shadow" style="background-image: url('{{ asset('images/navbar.png') }}'); 
                   background-size: cover; 
                   background-position: center;">
                <div class="max-w-[97%] mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    </div>
</body>

</html>