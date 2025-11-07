<x-guest-layout>
    <!-- Tombol Back -->
    <div class="absolute top-4 left-4">
        <a href="{{ route('user.index') }}" 
           class="flex items-center text-gray-700 dark:text-white hover:text-gray-300 transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> 
            <span class="font-small">Kembali</span>
        </a>
    </div>

    <!-- Judul -->
    <div class="flex justify-center mb-2 mt-10">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Register Kasir</h1>
    </div>

    <!-- Logo -->
    <div class="flex justify-center mb-6 mt-6">
        <img src="{{ asset('/images/logo.png') }}" alt="Logo" class="h-24 w-auto">
    </div>

    <form method="POST" action="{{ route('admin.register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <div class="relative mt-1">
                <!-- Icon -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-black">
                    <i class="fas fa-user"></i>
                </span>

                <x-text-input 
                    id="name" 
                    class="block w-full pl-10 pr-3 py-2 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-500 dark:bg-gray-800 dark:text-black"
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    placeholder="Masukkan Nama"
                    required 
                    autofocus 
                    autocomplete="name" 
                />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-1">
                <!-- Icon -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-black">
                    <i class="fas fa-envelope"></i>
                </span>

                <x-text-input 
                    id="email" 
                    class="block w-full pl-10 pr-3 py-2 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-500 dark:bg-gray-800 dark:text-black"
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    placeholder="Masukkan Email"
                    required 
                    autocomplete="username" 
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative mt-1">
                <!-- Icon -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-black">
                    <i class="fas fa-lock"></i>
                </span>

                <x-text-input 
                    id="password" 
                    class="block w-full pl-10 pr-10 py-2 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-500 dark:bg-gray-800 dark:text-black"
                    type="password" 
                    name="password" 
                    placeholder="Masukkan Password"
                    required 
                    autocomplete="new-password" 
                />

                <!-- Toggle Password Visibility -->
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500" onclick="togglePassword('password', 'togglePasswordIcon1')">
                    <i id="togglePasswordIcon1" class="fas fa-eye"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <!-- Icon -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-black">
                    <i class="fas fa-lock"></i>
                </span>

                <x-text-input 
                    id="password_confirmation" 
                    class="block w-full pl-10 pr-10 py-2 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-500 dark:bg-gray-800 dark:text-black"
                    type="password" 
                    name="password_confirmation" 
                    placeholder="Konfirmasi Password"
                    required 
                    autocomplete="new-password" 
                />

                <!-- Toggle Password Visibility -->
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500" onclick="togglePassword('password_confirmation', 'togglePasswordIcon2')">
                    <i id="togglePasswordIcon2" class="fas fa-eye"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Tombol -->
        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Script Toggle Password -->
    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</x-guest-layout>
