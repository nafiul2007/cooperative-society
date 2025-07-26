@props(['status'])

@if ($status)
    <div class="container mt-3">
        <div {{ $attributes->merge(['class' => 'alert alert-success mb-0']) }}>
            {{ $status }}
        </div>
    </div>
@endif
