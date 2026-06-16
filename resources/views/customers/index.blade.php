<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Kelola Member') }}
            </h2>

            {{-- Tambah Member (Mobile) --}}
            <a href="{{ route('customers.create') }}"
                class="md:hidden inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-lg shadow-md hover:scale-105 transition">
                + Tambah
            </a>
        </div>
    </x-slot>

    <div class="py-6 text-gray-900">
        <div class="max-w-[97%] mx-auto px-4">
            <div
                class="bg-gradient-to-br from-blue-50 via-white to-blue-100 shadow-lg border border-blue-200 rounded-xl">

                {{-- 🔍 Search --}}
                {{-- 🔍 Pencarian --}}
                <div class="px-6 pt-6 w-full sm:w-2/3 md:w-1/2 lg:w-1/3">
                    @if (request('search'))
                        <div class="bg-blue-600 text-white text-sm font-semibold rounded-md px-4 py-2 mb-4 shadow-sm">
                            Hasil pencarian untuk:
                            <strong class="font-bold">{{ request('search') }}</strong>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('customers.index') }}" class="flex items-center mb-4 gap-2">
                        <x-text-input id="search" name="search" type="text"
                            class="w-full text-sm py-2 px-3 text-gray-900 bg-white border border-blue-300 rounded-md
                   focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm"
                            placeholder="Cari Member..." value="{{ request('search') }}" autofocus />

                        <x-search-button type="submit"
                            class="text-sm px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md shadow-md
                   transition-all duration-200">
                            {{ __('Search') }}
                        </x-search-button>

                        @if (request('search'))
                            <a href="{{ route('customers.index') }}"
                                class="text-sm px-4 py-2 rounded-md
                      bg-gradient-to-br from-amber-500 to-amber-400
                      text-white font-medium shadow-md
                      hover:scale-105 transition-all duration-300">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Notifikasi --}}
                @if (session('success'))
                    <p class="ml-6 mt-3 text-sm font-semibold text-green-700">
                        {{ session('success') }}
                    </p>
                @endif

                {{-- ===================== DESKTOP ===================== --}}
                <div x-data="{ editModal: null, deleteModal: null }"
                    class="hidden md:block bg-white rounded-xl shadow-md border border-blue-100 mx-6 my-6">

                    {{-- Header --}}
                    <div class="flex justify-between items-center px-5 py-4 border-b bg-blue-100 rounded-t-xl">
                        <h3 class="font-bold text-blue-800 text-sm uppercase">Daftar Member</h3>
                        <a href="{{ route('customers.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-xs font-semibold shadow transition">
                            + Tambah
                        </a>
                    </div>

                    {{-- Table Wrapper --}}
                    <div class="overflow-x-auto">
                        <table class="w-full table-fixed border-collapse text-sm">
                            <thead class="bg-blue-200 text-xs uppercase">
                                <tr>
                                    <th class="w-[70px] px-4 py-3 text-center">No</th>
                                    <th class="w-[200px] px-4 py-3 text-left">Nama</th>
                                    <th class="w-[120px] px-4 py-3 text-center">Kelamin</th>
                                    <th class="w-[160px] px-4 py-3 text-left">No Telp</th>
                                    <th class="px-4 py-3 text-left">Alamat</th>
                                    <th class="w-[140px] px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($customers as $c)
                                    <tr class="odd:bg-white even:bg-blue-50 border-b hover:bg-blue-100 transition">
                                        <td class="px-4 py-3 text-center font-medium text-gray-700">
                                            {{ $customers->firstItem() + $loop->index }}
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $c->nama }}</td>
                                        <td class="px-4 py-3 text-center">
                                            {{ $c->kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td class="px-4 py-3">{{ $c->no_telepon }}</td>
                                        <td class="px-4 py-3">
                                            <div class="line-clamp-2 text-gray-700">{{ $c->alamat ?? '-' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button @click="editModal = {{ $c->id_customer }}"
                                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-md text-xs font-semibold transition">
                                                    Edit
                                                </button>
                                                <button @click="deleteModal = {{ $c->id_customer }}"
                                                    class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-md text-xs font-semibold transition">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- ================= MODAL EDIT ================= --}}
                                    <div x-show="editModal === {{ $c->id_customer }}" x-cloak>
                                        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
                                            <div class="bg-white rounded-xl p-6 w-[420px] shadow-xl">
                                                <h3 class="font-semibold mb-4 text-lg text-gray-800">Edit Member
                                                </h3>
                                                <form method="POST" action="{{ route('customers.update', $c) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <label class="text-sm font-semibold">Nama</label>
                                                    <input name="nama" value="{{ $c->nama }}"
                                                        class="w-full border rounded mb-3 p-2 text-sm" required>

                                                    <label class="text-sm font-semibold">Jenis Kelamin</label>
                                                    <select name="kelamin"
                                                        class="w-full border rounded mb-3 p-2 text-sm" required>
                                                        <option value="L"
                                                            {{ $c->kelamin == 'L' ? 'selected' : '' }}>Laki-laki
                                                        </option>
                                                        <option value="P"
                                                            {{ $c->kelamin == 'P' ? 'selected' : '' }}>Perempuan
                                                        </option>
                                                    </select>

                                                    <label class="text-sm font-semibold">No Telepon</label>
                                                    <input name="no_telepon" value="{{ $c->no_telepon }}"
                                                        class="w-full border rounded mb-3 p-2 text-sm" required>

                                                    <label class="text-sm font-semibold">Alamat</label>
                                                    <textarea name="alamat" rows="3" class="w-full border rounded mb-4 p-2 text-sm">{{ $c->alamat }}</textarea>

                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="editModal=null"
                                                            class="px-4 py-2 bg-gray-200 rounded-md">Batal</button>
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-blue-500 text-white rounded-md">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ================= MODAL DELETE ================= --}}
                                    <div x-show="deleteModal === {{ $c->id_customer }}" x-cloak>
                                        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
                                            <div class="bg-white rounded-xl p-6 w-80 shadow-xl text-center">
                                                <h3 class="font-semibold mb-3 text-lg">Hapus Member</h3>
                                                <p class="mb-4 text-sm">Hapus Member <b>{{ $c->nama }}</b>?</p>
                                                <form method="POST" action="{{ route('customers.destroy', $c) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="flex gap-2">
                                                        <button type="button" @click="deleteModal=null"
                                                            class="flex-1 bg-gray-200 py-2 rounded">Batal</button>
                                                        <button type="submit"
                                                            class="flex-1 bg-red-500 text-white py-2 rounded">Hapus</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-6 text-gray-500">Data Member kosong
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Desktop --}}
                    <div class="px-6 py-4">
                        {{ $customers->links('vendor.pagination.tailwind') }}
                    </div>
                </div>

                {{-- ================= MOBILE CARD ================= --}}
                <div class="block md:hidden px-4 mt-6 pb-6">
                    @forelse ($customers as $c)
                        @php $no = $customers->firstItem() + $loop->index; @endphp
                        <div x-data="{ edit: false, del: false }"
                            class="mb-4 rounded-xl overflow-hidden shadow-md border border-blue-100 text-white p-4 bg-cover bg-center relative"
                            style="background-image: url('{{ asset('images/card1.png') }}');">
                            <div class="absolute inset-0 bg-black/40"></div>
                            <div class="relative z-10">
                                <p class="text-xs text-gray-200 mb-1">No: {{ $no }}</p>
                                <h4 class="text-lg font-semibold">{{ $c->nama }}</h4>
                                <p class="text-sm text-gray-100">{{ $c->no_telepon }}</p>
                                @if ($c->alamat)
                                    <p class="text-sm text-gray-200 mt-1 line-clamp-2">{{ $c->alamat }}</p>
                                @else
                                    <p class="text-xs text-gray-300 mt-1 italic">Alamat belum diisi</p>
                                @endif

                                <div class="flex gap-2 mt-4">
                                    <button @click="edit = true"
                                        class="bg-blue-100 text-blue-700 px-3 py-1 rounded-md text-xs font-semibold">Edit</button>
                                    <button @click="del = true"
                                        class="bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold">Hapus</button>
                                </div>
                            </div>

                            {{-- Modal Edit --}}
                            <div x-show="edit" x-cloak>
                                <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
                                    <div class="relative w-11/12 max-w-md rounded-xl overflow-hidden shadow-lg">
                                        <div class="absolute inset-0 bg-cover bg-center"
                                            style="background-image: url('{{ asset('images/card1.png') }}');"></div>
                                        <div class="absolute inset-0 bg-black/60"></div>
                                        <div class="relative z-10 p-6 text-white">
                                            <h3 class="font-semibold mb-4 text-lg">Edit Member</h3>
                                            <form method="POST" action="{{ route('customers.update', $c) }}">
                                                @csrf
                                                @method('PUT')
                                                <label class="text-xs">Nama</label>
                                                <input name="nama" value="{{ $c->nama }}"
                                                    class="w-full bg-white/90 text-gray-800 border rounded mb-3 p-2 text-sm"
                                                    required>

                                                <label class="text-xs">Jenis Kelamin</label>
                                                <select name="kelamin"
                                                    class="w-full bg-white/90 text-gray-800 border rounded mb-3 p-2 text-sm"
                                                    required>
                                                    <option value="L" {{ $c->kelamin == 'L' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="P" {{ $c->kelamin == 'P' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>

                                                <label class="text-xs">No Telepon</label>
                                                <input name="no_telepon" value="{{ $c->no_telepon }}"
                                                    class="w-full bg-white/90 text-gray-800 border rounded mb-3 p-2 text-sm"
                                                    required>

                                                <label class="text-xs">Alamat</label>
                                                <textarea name="alamat" rows="3" class="w-full bg-white/90 text-gray-800 border rounded mb-4 p-2 text-sm">{{ $c->alamat }}</textarea>

                                                <div class="flex gap-2">
                                                    <button type="button" @click="edit=false"
                                                        class="flex-1 bg-white/80 text-gray-800 py-2 rounded">Batal</button>
                                                    <button type="submit"
                                                        class="flex-1 bg-blue-500 text-white py-2 rounded">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Delete --}}
                            <div x-show="del" x-cloak>
                                <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
                                    <div class="relative w-80 rounded-xl overflow-hidden shadow-lg">
                                        <div class="absolute inset-0 bg-cover bg-center"
                                            style="background-image: url('{{ asset('images/card1.png') }}');"></div>
                                        <div class="absolute inset-0 bg-black/70"></div>
                                        <div class="relative z-10 p-6 text-white text-center">
                                            <h3 class="font-semibold mb-3 text-lg">Hapus Member</h3>
                                            <p class="text-sm mb-4">Hapus Member <b>{{ $c->nama }}</b>?</p>
                                            <form method="POST" action="{{ route('customers.destroy', $c) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="flex gap-2">
                                                    <button type="button" @click="del=false"
                                                        class="flex-1 bg-white/80 text-gray-800 py-2 rounded">Batal</button>
                                                    <button type="submit"
                                                        class="flex-1 bg-red-500 text-white py-2 rounded">Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @empty
                        <p class="text-center text-gray-500 text-sm">Data Member kosong</p>
                    @endforelse

                    {{-- Pagination Mobile --}}
                    <div class="px-4 py-4">
                        {{ $customers->links('vendor.pagination.tailwind') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
