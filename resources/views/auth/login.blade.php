<x-guest-layout>
    <!-- Judul -->
    <div class="flex justify-center mb-2">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Login</h1>
    </div>

    <!-- Logo -->
    <div class="flex justify-center mb-6 mt-6">
        <img src="{{ asset('/images/logo.png') }}" alt="Logo" class="h-24 w-auto">
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />

            <div class="relative mt-1">
                <!-- Icon Email -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-black dark:text-black">
                    <i class="fas fa-envelope"></i>
                </span>

                <!-- Input -->
                <x-text-input 
                    id="email" 
                    class="block w-full pl-10 pr-3 py-2 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-500 dark:bg-gray-800 dark:text-black"
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    placeholder="Masukkan Email"
                    required 
                    autofocus 
                    autocomplete="username" 
                />
            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative mt-1">
                <!-- Icon Password -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-black dark:text-black">
                    <i class="fas fa-lock"></i>
                </span>

                <!-- Input Password -->
                <x-text-input 
                    id="password" 
                    class="block w-full pl-10 pr-10 py-2 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring focus:ring-indigo-500 dark:bg-gray-800 dark:text-black"
                    type="password" 
                    name="password" 
                    placeholder="Masukkan Password"
                    required 
                    autocomplete="current-password" 
                />

                <!-- Toggle Password Visibility -->
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-black dark:text-black" onclick="togglePassword()">
                    <i id="togglePasswordIcon" class="fas fa-eye"></i>
                </span>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded bg-white border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-500"
                    name="remember">
                <span class="ms-2 text-sm text-gray-800 dark:text-white">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Tombol dan Link -->
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-white dark:text-white hover:text-blue-200 dark:hover:text-blue-200" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

       <!-- Script Toggle Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');

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

