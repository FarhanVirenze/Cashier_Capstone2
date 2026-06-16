<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - GoScan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: radial-gradient(circle at top right, #eff6ff, #f8fafc);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInDown 0.8s ease-out; }
    </style>
</head>
<body class="flex items-center justify-center h-screen antialiased overflow-hidden">
    <div class="max-w-md w-full px-6 animate-fade-in">
        <div class="glass-card rounded-[2.5rem] shadow-2xl shadow-blue-200/50 p-10 text-center border border-white/50 relative overflow-hidden">
            
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-60"></div>

            <div class="flex items-center justify-center space-x-3 mb-10">
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-400 rounded-xl blur opacity-20"></div>
                    <img src="{{ asset('/images/logo.png') }}" alt="Logo" class="relative w-12 h-12 object-contain shadow-sm">
                </div>
                <div class="text-left">
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-none">GoScan</h2>
                    <p class="text-[10px] uppercase tracking-[0.2em] font-semibold text-blue-500 mt-1">Sistem Kasir</p>
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <span class="text-[11px] font-extrabold tracking-[0.15em] text-blue-600 uppercase px-4 py-1.5 bg-blue-100/50 rounded-full border border-blue-200">
                        Error Code: 404
                    </span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-6 mb-3">Halaman Hilang</h1>
                <p class="text-slate-500 text-sm leading-relaxed px-2">
                    Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan ke alamat lain.
                </p>
            </div>

            <div class="relative z-10">
                <a href="{{ route('dashboard') }}" 
                   class="group relative flex items-center justify-center w-full py-4 
                   bg-gradient-to-r from-blue-600 to-cyan-500 
                   text-white font-bold rounded-2xl 
                   shadow-lg shadow-cyan-500/30 
                   hover:shadow-cyan-500/50 hover:scale-[1.02] 
                   active:scale-95 
                   transition-all duration-300">
                    
                    <span class="mr-2">Kembali ke Dashboard</span>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <button onclick="window.history.back()"
                    class="mt-4 text-sm font-semibold text-slate-400 hover:text-blue-500 transition-colors">
                    kembali ke halaman sebelumnya
                </button>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-100">
                <p class="text-[11px] text-slate-400">
                    &copy; {{ date('Y') }} GoScan - Universitas Muhammadiyah Yogyakarta. All rights reserved.
                </p>
            </div>
        </div>
    </div>

</body>
</html>