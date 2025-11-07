<nav x-data="{ open: false, userMenu: false }" class="bg-white border-b border-gray-200 shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-[97%] mx-auto px-3 sm:px-6 lg:px-6">
        <div class="flex justify-between h-20 items-center">
            <!-- Left Side: Logo & Links -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('/images/logo.png') }}" alt="Logo" class="block h-10 w-10">
                        <span
                            class="text-xl font-bold text-gray-800 hover:text-blue-600 transition duration-150 ease-in-out">
                            Warung Golpal
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @can('admin')
                        <x-nav-link :href="route('user.index')" :active="request()->routeIs('user.index')">
                            {{ __('Kasir') }}
                        </x-nav-link>

                        <x-nav-link :href="route('product.index')" :active="request()->routeIs('product.index')">
                            {{ __('Produk') }}
                        </x-nav-link>
                    @endcan

                    <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.index')">
                        {{ __('Point of Sale') }}
                    </x-nav-link>

                    <x-nav-link :href="route('transaksi.index')" :active="request()->routeIs('transaksi.index')">
                        {{ __('Laporan Penjualan') }}
                    </x-nav-link>

                    @can('admin')
                        <x-nav-link :href="route('detailpenjualan.index')"
                            :active="request()->routeIs('detailpenjualan.index')">
                            {{ __('Transaksi Penjualan') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Right Side: Settings Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 rounded-md hover:bg-blue-50 hover:text-blue-600 focus:outline-none transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-gray-500 group-hover:text-blue-600"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu (Mobile) -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Tambahkan style ini sekali di <head> layout utama -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Responsive Navigation Menu -->
    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 transform -translate-y-5"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-400"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-5"
        class="sm:hidden bg-white border-t border-gray-200 shadow-md rounded-b-xl">
        <div class="pt-2 pb-3 space-y-1">

            <!-- ✅ Mobile User Dropdown -->
            <div class="border-b border-gray-200">
                <button @click="userMenu = !userMenu"
                    class="w-full flex justify-between items-center px-4 py-3 text-left text-gray-800 font-medium hover:bg-blue-50 transition duration-300 ease-in-out">
                    <span>{{ Auth::user()->name }}</span>
                    <svg class="w-5 h-5 transform transition-transform duration-300" :class="{ 'rotate-180': userMenu }"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="userMenu" x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2" x-collapse class="space-y-1 pb-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- 🌟 Main Links -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @can('admin')
                <x-responsive-nav-link :href="route('user.index')" :active="request()->routeIs('user.index')">
                    {{ __('Kasir') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('product.index')" :active="request()->routeIs('product.index')">
                    {{ __('Produk') }}
                </x-responsive-nav-link>
            @endcan

            <x-responsive-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.index')">
                {{ __('Point of Sale') }}
            </x-responsive-nav-link>

             <x-responsive-nav-link :href="route('transaksi.index')" :active="request()->routeIs('transaksi.index')">
                {{ __('Laporan Penjualan') }}
            </x-responsive-nav-link>

            @can('admin')
                <x-responsive-nav-link :href="route('detailpenjualan.index')"
                    :active="request()->routeIs('detailpenjualan.index')">
                    {{ __('Transaksi Penjualan') }}
                </x-responsive-nav-link>
            @endcan

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Logout') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>

</nav>