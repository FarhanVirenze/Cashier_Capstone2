@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-blue-500 text-start text-base font-semibold text-blue-600 bg-blue-50 focus:outline-none focus:text-blue-700 focus:bg-blue-100 focus:border-blue-600 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-500 focus:outline-none focus:text-blue-700 focus:bg-blue-100 focus:border-blue-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
