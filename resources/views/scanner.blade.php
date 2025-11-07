<x-app-layout>
    <div class="relative w-screen h-screen bg-black overflow-hidden">

        <!-- Tombol Kembali -->
        <a href="{{ route('pos.index') }}"
            class="absolute top-4 left-4 z-50 bg-blue-600 text-white text-sm px-3 py-1 rounded-md shadow-md hover:bg-blue-700 transition duration-200">
            ← Kembali
        </a>

        <!-- Kamera Preview -->
        <video 
            id="preview"
            autoplay 
            playsinline 
            muted
            class="w-full h-full object-cover transform-none"
        ></video>

        <!-- Frame Bidikan Barcode -->
        <div class="absolute inset-0 flex items-center justify-center z-40 pointer-events-none">
            <div class="relative w-2/3 h-1/3 border-2 border-white rounded-xl overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-0.5 bg-blue-500 animate-scan-line"></div>
            </div>
        </div>

        <!-- 🔦 Tombol Flash (ikon saja, pojok kanan atas) -->
        <button id="flash-toggle"
            class="hidden absolute top-4 right-4 z-50 bg-white text-yellow-800 p-2 rounded-full 
                   shadow-md border border-white/20 hover:bg-gray-200 transition backdrop-blur-md">
            <!-- Ikon Mati (default) -->
            <svg id="flash-off" xmlns="http://www.w3.org/2000/svg" 
                class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <!-- Ikon Nyala -->
            <svg id="flash-on" xmlns="http://www.w3.org/2000/svg" 
                class="h-6 w-6 hidden" fill="currentColor" viewBox="0 0 24 24">
                <path d="M7 2v10h3v10l7-12h-4l4-8z" />
            </svg>
        </button>
    </div>

    <!-- Meta & Script -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="pos-url" content="{{ route('pos.index') }}">
    @vite(['resources/js/scanner.js'])
</x-app-layout>

<!-- Animasi CSS -->
<style>
@keyframes scan-line {
    0% { top: 0; }
    100% { top: 100%; }
}
.animate-scan-line {
    animation: scan-line 2s linear infinite alternate;
}

/* 📱 Tampilkan tombol flash hanya di perangkat mobile */
@media (max-width: 768px) {
    #flash-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
    }
}
</style>

<script>
// 🎥 Script kontrol flash (contoh sederhana)
let flashEnabled = false;
const flashButton = document.getElementById("flash-toggle");
const flashOnIcon = document.getElementById("flash-on");
const flashOffIcon = document.getElementById("flash-off");

async function toggleFlash() {
    const track = window.currentCameraTrack;
    if (!track) return;

    const capabilities = track.getCapabilities();
    if (!capabilities.torch) {
        alert("Flash tidak didukung di perangkat ini.");
        return;
    }

    flashEnabled = !flashEnabled;
    await track.applyConstraints({ advanced: [{ torch: flashEnabled }] });

    // Ubah ikon dan warna
    flashOnIcon.classList.toggle("hidden", !flashEnabled);
    flashOffIcon.classList.toggle("hidden", flashEnabled);
    flashButton.classList.toggle("bg-yellow-500", flashEnabled);
    flashButton.classList.toggle("bg-gray-800/80", !flashEnabled);
}

flashButton.addEventListener("click", toggleFlash);

// Simpan track kamera aktif (dipanggil di scanner.js)
window.setCameraTrack = (track) => {
    window.currentCameraTrack = track;
};
</script>
