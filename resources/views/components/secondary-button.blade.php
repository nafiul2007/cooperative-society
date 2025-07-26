<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'btn btn-secondary',
        'disabled' => $attributes->get('disabled') ? true : false,
    ]) }}>
    {{ $slot }}
</button>
