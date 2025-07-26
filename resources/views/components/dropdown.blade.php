@props(['align' => 'end', 'contentClasses' => ''])

<div class="dropdown">
    {{-- Trigger --}}
    <div>
        {{ $trigger }}
    </div>

    {{-- Dropdown menu --}}
    <ul class="dropdown-menu dropdown-menu-{{ $align }} {{ $contentClasses }}">
        {{ $content }}
    </ul>
</div>
