@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
])

@php
    $variants = [
        'primary' => 'button--primary',
        'secondary' => 'button--secondary',
        'danger' => 'button--danger',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'button '.($variants[$variant] ?? $variants['primary'])]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'button '.($variants[$variant] ?? $variants['primary'])]) }}>
        {{ $slot }}
    </button>
@endif
