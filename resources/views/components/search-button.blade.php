<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-blue-500 dark:bg-blue-500 border border-transparent rounded-lg font-semibold text-xs text-white dark:text-white tracking-widest hover:bg-blue-600 dark:hover:bg-blue-600 focus:bg-gray-700 dark:focus:bg-blue active:bg-blue-900 dark:active:bg-blue-300 focus:outline-none focus:ring-2 focus:ringblue-500 focus:ring-offset-2 dark:focus:ring-offset-blue-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
