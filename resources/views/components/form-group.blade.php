{{-- Reusable Form Group Component --}}
{{-- Usage:
    <x-form-group label="Nama" name="nama" required>
        <input type="text" name="nama" class="form-input" value="{{ old('nama') }}">
    </x-form-group>
--}}

@props([
    'label' => '',
    'name' => '',
    'required' => false,
    'hint' => null,
])

<div class="form-group">
    <label for="{{ $name }}" class="form-group__label">
        {{ $label }}
        @if ($required)
            <span class="form-group__required">*</span>
        @endif
    </label>

    {{ $slot }}

    @if ($hint)
        <div class="form-group__hint">{{ $hint }}</div>
    @endif

    @error($name)
        <div class="form-group__error">{{ $message }}</div>
    @enderror
</div>
