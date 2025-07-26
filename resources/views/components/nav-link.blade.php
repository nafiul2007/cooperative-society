@props(['active'])

@php
$classes = ($active ?? false)
    ? 'nav-link active fw-semibold text-primary'
    : 'nav-link text-secondary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
