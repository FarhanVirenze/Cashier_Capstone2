@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 pt-1 border-b-2 border-blue-500 text-sm font-semibold leading-5 text-blue-600 dark:text-blue-400 focus:outline-none focus:border-blue-600 transition duration-200 ease-in-out'
            : 'inline-flex items-center px-3 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 dark:text-gray-700 hover:text-blue-500 dark:hover:text-blue-400 hover:border-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400 focus:border-blue-500 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
