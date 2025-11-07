<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Warung Golpal') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
  body {
    background-image: url('{{ asset('images/card.png') }}');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    font-family: 'Instrument Sans', sans-serif;
    color: #fff; /* opsional agar teks lebih kontras */
  }

  /* Efek animasi tetap */
  [data-animate] {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.7s ease-out;
  }

  [data-animate].animated {
    opacity: 1;
    transform: translateY(0);
  }
</style>

</head>
<body class="font-sans text-gray-900 antialiased flex items-center justify-center min-h-screen px-4">
  <div class="w-full max-w-md bg-white/10 backdrop-blur-md shadow-2xl rounded-2xl px-8 py-10 border border-blue-300">
      {{ $slot }}
  </div>

  <script>
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });

    document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
  </script>
  @stack('scripts')

</body>
</html>
