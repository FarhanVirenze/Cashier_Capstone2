<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 dark:text-gray-200 leading-tight">
            {{ __('Kelola Akun Kasir') }}
        </h2>
    </x-slot>

    <div class="py-6 text-gray-900 dark:text-gray-100">
        <div class="max-w-[97%] mx-auto px-4 sm:px-6 lg:px-6">
            <div class="overflow-hidden bg-gradient-to-br from-blue-50 via-white to-blue-100 shadow-lg border border-blue-200 sm:rounded-xl">

                {{-- 🔍 Pencarian --}}
                <div class="px-6 pt-6 w-full sm:w-2/3 md:w-1/2 lg:w-1/3">
                    @if (request('search'))
                        <div class="bg-blue-600 text-white text-sm font-semibold rounded-md px-4 py-2 mb-4 shadow-sm">
                            Hasil pencarian untuk:
                            <strong class="font-bold">{{ request('search') }}</strong>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('user.index') }}" class="flex items-center mb-4 gap-2">
                        <x-text-input id="search" name="search" type="text"
                            class="w-full text-sm py-2 px-3 text-gray-900 bg-white border border-blue-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm"
                            placeholder="Cari berdasarkan nama kasir..." value="{{ request('search') }}" autofocus />

                        <x-search-button type="submit"
                            class="text-sm px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md shadow-md transition-all duration-200">
                            {{ __('Search') }}
                        </x-search-button>

                        @if (request('search'))
                            <a href="{{ route('user.index') }}"
                                class="text-sm px-4 py-2 rounded-md bg-gradient-to-br from-amber-500 to-amber-400 text-white font-medium shadow-md hover:scale-105 transition-all duration-300">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                {{-- ✅ Notifikasi --}}
                @if (session('success'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                        class="pb-2 ml-6 text-sm font-semibold text-green-700">
                        {{ session('success') }}
                    </p>
                @endif
                @if (session('danger'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                        class="pb-2 ml-6 text-sm font-semibold text-red-700">
                        {{ session('danger') }}
                    </p>
                @endif

                {{-- 💻 Tabel (desktop) --}}
                <div x-data="{ openModal: null }" class="hidden md:block overflow-x-auto bg-white shadow-md rounded-xl mb-6 mt-4 border border-blue-100 mx-6">
                    <div class="flex justify-between items-center px-4 py-3 border-b border-blue-200 bg-gradient-to-r from-blue-100 to-blue-200 rounded-t-xl">
                        <h3 class="text-base font-bold text-blue-800">Daftar Akun Kasir</h3>
                        <a href="{{ route('admin.register') }}"
                            class="bg-blue-500 text-white px-3 py-1.5 rounded-md text-xs font-medium shadow-md hover:bg-blue-600 transition-all duration-200">
                            + Tambah Kasir Baru
                        </a>
                    </div>

                    <table class="w-full text-sm text-left text-gray-900">
                        <thead class="text-xs text-gray-700 uppercase bg-gradient-to-r from-blue-100 to-blue-200 border-b border-blue-300">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="odd:bg-white even:bg-blue-50 hover:bg-blue-100 transition-all duration-150 border-b border-blue-100">
                                    <td class="px-4 py-2">{{ $user->id }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-800">{{ $user->name }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $user->email }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center flex-wrap gap-2">
                                            <a href="{{ route('user.edit', $user) }}"
                                                class="bg-blue-100 text-blue-600 px-2 py-1 rounded-md text-xs font-semibold hover:bg-blue-200 transition-all duration-200">
                                                Edit
                                            </a>

                                            {{-- Tombol Hapus dengan Modal --}}
                                            <button @click="openModal = {{ $user->id }}"
                                                class="bg-red-100 text-red-600 px-2 py-1 rounded-md text-xs font-semibold hover:bg-red-200 transition-all duration-200">
                                                Delete
                                            </button>

                                            @if ($user->is_admin)
                                                <form action="{{ route('user.removeadmin', $user) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-semibold hover:bg-green-200 transition-all duration-200">
                                                        Remove Admin
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('user.makeadmin', $user) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="bg-amber-100 text-amber-700 px-2 py-1 rounded-md text-xs font-semibold hover:bg-amber-200 transition-all duration-200">
                                                        Make Admin
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Hapus Desktop --}}
                                <div x-show="openModal === {{ $user->id }}" x-cloak>
                                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                        <div class="bg-white rounded-lg p-6 w-11/12 sm:w-96">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Hapus</h3>
                                            <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus akun <strong>{{ $user->name }}</strong>?</p>
                                            <div class="flex justify-end gap-3">
                                                <button @click="openModal = null"
                                                    class="px-4 py-2 bg-gray-200 rounded-md text-gray-800 hover:bg-gray-300 transition-all duration-200">
                                                    Batal
                                                </button>
                                                <form action="{{ route('user.destroy', $user) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-all duration-200">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-gray-500 font-medium">
                                        Tidak ada akun kasir.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- 📄 Pagination --}}
                    @if ($users->hasPages())
                        <div class="p-4 text-sm">
                            {{ $users->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>

                {{-- 📱 Tampilan Mobile: Card --}}
                <div class="block md:hidden px-4 pb-6">
                    @forelse ($users as $user)
                        <div class="mb-4 rounded-xl overflow-hidden shadow-md border border-blue-100 text-white p-4 bg-cover bg-center relative"
                            style="background-image: url('{{ asset('images/card1.png') }}');" x-data="{ openModal: false }">
                            <div class="mb-2">
                                <p class="text-sm text-white">ID: {{ $user->id }}</p>
                                <h4 class="text-lg text-white font-semibold">{{ $user->name }}</h4>
                                <p class="text-white text-sm">{{ $user->email }}</p>

                                <div class="flex flex-wrap gap-2 mt-3">
                                    <a href="{{ route('user.edit', $user) }}"
                                        class="bg-blue-100 text-blue-600 px-3 py-1 rounded-md text-xs font-semibold hover:bg-blue-200 transition-all duration-200">
                                        Edit
                                    </a>

                                    {{-- Tombol Delete Mobile --}}
                                    <button @click="openModal = true"
                                        class="bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold hover:bg-red-200 transition-all duration-200">
                                        Delete
                                    </button>

                                    @if ($user->is_admin)
                                        <form action="{{ route('user.removeadmin', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="bg-green-100 text-green-700 px-3 py-1 rounded-md text-xs font-semibold hover:bg-green-200 transition-all duration-200">
                                                Remove Admin
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('user.makeadmin', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="bg-amber-100 text-amber-700 px-3 py-1 rounded-md text-xs font-semibold hover:bg-amber-200 transition-all duration-200">
                                                Make Admin
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Modal Hapus Mobile --}}
                            <div x-show="openModal" x-cloak>
                                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-lg p-6 w-11/12 sm:w-96">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Hapus</h3>
                                        <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus akun <strong>{{ $user->name }}</strong>?</p>
                                        <div class="flex justify-end gap-3">
                                            <button @click="openModal = false"
                                                class="px-4 py-2 bg-gray-200 rounded-md text-gray-800 hover:bg-gray-300 transition-all duration-200">
                                                Batal
                                            </button>
                                            <form action="{{ route('user.destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-all duration-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <p class="text-center text-gray-500 text-sm py-4">
                            Tidak ada akun kasir.
                        </p>
                    @endforelse

                    {{-- 📄 Pagination Mobile --}}
                    @if ($users->hasPages())
                        <div class="p-2 text-sm">
                            {{ $users->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
