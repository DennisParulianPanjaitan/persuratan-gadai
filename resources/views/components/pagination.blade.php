{{-- Reusable Pagination Component --}}
{{-- Usage: <x-pagination :paginator="$barangList" /> --}}

@props([
    'paginator',
])

@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last    = $paginator->lastPage();

        // Selalu tampilkan 3 nomor, aktif di tengah
        $start = max(1, $current - 1);
        $end   = min($last, $start + 2);

        // Jika end mentok di halaman terakhir, geser start ke kiri
        $start = max(1, $end - 2);
    @endphp

    <div class="pagination-bar">
        <div class="pagination-bar__info">
            Menampilkan <strong>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong>
            dari <strong>{{ $paginator->total() }}</strong> data
        </div>

        <div class="pagination-bar__nav">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn is-disabled">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn">Prev</a>
            @endif

            {{-- 3 Nomor Halaman --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <span class="pagination-btn is-active">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="pagination-btn">{{ $page }}</a>
                @endif
            @endfor

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn">Next</a>
            @else
                <span class="pagination-btn is-disabled">Next</span>
            @endif
        </div>
    </div>
@else
    <div class="pagination-bar">
        <div class="pagination-bar__info">
            Menampilkan <strong>{{ $paginator->count() }}</strong> data
        </div>
    </div>
@endif
