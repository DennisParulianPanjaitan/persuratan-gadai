@props([
    'title' => '',
    'id' => 'modal',
])

<div id="{{ $id }}" class="modal-overlay hidden">
    <div class="modal-card">
        <div class="modal-card__header">
            <h2 class="modal-card__title">{{ $title }}</h2>
            <button type="button" class="modal-card__close" onclick="document.getElementById('{{ $id }}').classList.add('hidden')">
                &times;
            </button>
        </div>
        <div class="modal-card__body">
            {{ $slot }}
        </div>
    </div>
</div>
