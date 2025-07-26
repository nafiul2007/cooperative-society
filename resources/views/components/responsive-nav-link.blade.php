@props(['active'])

@php
$activeClasses = 'd-block w-100 ps-3 pe-4 py-2 border-start border-4 border-primary text-start text-base fw-semibold text-primary bg-primary bg-opacity-10';
$inactiveClasses = 'd-block w-100 ps-3 pe-4 py-2 border-start border-4 border-transparent text-start text-base fw-medium text-secondary text-decoration-none';

$classes = ($active ?? false) ? $activeClasses : $inactiveClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
