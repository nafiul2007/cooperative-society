<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary text-uppercase fw-semibold px-4 py-2']) }}>
    {{ $slot }}
</button>
