<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Member Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="shadow-md rounded-lg overflow-hidden border border-white/10"
                style="background-image: url('{{ asset('images/card1.png') }}');
                       background-size: cover;
                       background-position: center;
                       background-repeat: no-repeat;">

                <div class="p-6 text-gray-800 dark:text-gray-100 space-y-6">

                    {{-- ❌ Notifikasi Error Validasi --}}
                    @if ($errors->any())
                        <div x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 4000)"
                            class="mb-4 p-4 rounded-lg bg-red-50 border border-red-300 text-red-700">
                            <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customers.store') }}" method="POST"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @csrf

                        {{-- Nama Member --}}
                        <div class="relative">
                            <x-input-label for="nama" value="Nama Member"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    {{-- Icon User --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.121 17.804A9 9 0 1118.879 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama') }}"
                                    placeholder="Masukkan Nama Member"
                                    class="mt-1 block w-full border border-white rounded-md
                                           text-sm px-10 py-2 bg-white text-black
                                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="relative">
                            <x-input-label for="kelamin" value="Jenis Kelamin"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    {{-- Icon Gender --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14a4 4 0 100-8 4 4 0 000 8z" />
                                    </svg>
                                </div>
                                <select name="kelamin" id="kelamin"
                                    class="mt-1 block w-full border border-white rounded-md
                                           text-sm px-10 py-2 bg-white text-black
                                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        {{-- No Telepon --}}
                        <div class="relative">
                            <x-input-label for="no_telepon" value="No Telepon"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    {{-- Icon Phone --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5h2l3 7-1.5 1.5a11 11 0 005 5L13 17l7 3v2a2 2 0 01-2 2A16 16 0 013 5z" />
                                    </svg>
                                </div>
                                <input type="text" name="no_telepon" id="no_telepon"
                                    value="{{ old('no_telepon') }}"
                                    placeholder="08xxxxxxxxxx"
                                    class="mt-1 block w-full border border-white rounded-md
                                           text-sm px-10 py-2 bg-white text-black
                                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="relative col-span-full">
                            <x-input-label for="alamat" value="Alamat"
                                class="text-sm text-gray-700 dark:text-gray-200" />
                            <div class="relative">
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    {{-- Icon Location --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 11a3 3 0 100-6 3 3 0 000 6z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.5 10.5c0 7-7.5 11-7.5 11s-7.5-4-7.5-11a7.5 7.5 0 1115 0z" />
                                    </svg>
                                </div>
                                <textarea name="alamat" id="alamat" rows="3"
                                    placeholder="Masukkan alamat Member"
                                    class="mt-1 block w-full border border-white rounded-md
                                           text-sm pl-10 pr-3 py-2 bg-white text-black
                                           focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('alamat') }}</textarea>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="col-span-full flex justify-end gap-2 pt-2">
                            <button type="submit"
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-700
                                       text-white text-sm font-semibold rounded-md shadow">
                                Tambah Member
                            </button>

                            <a href="{{ route('customers.index') }}"
                                class="px-5 py-2 bg-gray-300 hover:bg-gray-400
                                       text-gray-800 text-sm font-semibold rounded-md shadow">
                                Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
