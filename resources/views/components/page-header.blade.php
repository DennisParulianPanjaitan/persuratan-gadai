@props([
    'title' => '',
    'breadcrumb' => null,
    'buttonText' => null,
    'buttonUrl' => null,
])

<div class="page-header">
    <div class="page-header__content">
        @if ($breadcrumb)
            <div class="page-header__breadcrumb">
                {{ $breadcrumb }}
            </div>
        @endif

        <h1 class="page-header__title">
            {{ $title }}
        </h1>
    </div>

    @if ($buttonText)
        <a href="{{ $buttonUrl ?? '#' }}" class="button button--primary page-header__action">
            {{ $buttonText }}
        </a>
    @endif
</div>
