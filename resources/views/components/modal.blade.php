@props([
    'name',
    'show' => false,
    'maxWidth' => 'modal-lg' // Bootstrap modal size classes: modal-sm, modal-lg, modal-xl
])

@php
$modalId = 'modal-' . $name;
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $maxWidth }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

@push('scripts')
    @if($show)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = new bootstrap.Modal(document.getElementById('{{ $modalId }}'));
                modal.show();
            });
        </script>
    @endif
@endpush
