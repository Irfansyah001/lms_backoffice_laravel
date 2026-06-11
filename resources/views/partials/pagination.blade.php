@if ($paginator->hasPages())
    <div class="pagination">
        @if ($paginator->onFirstPage())
            <span>Sebelumnya</span>
        @else
            <a class="btn secondary small" href="{{ $paginator->previousPageUrl() }}">Sebelumnya</a>
        @endif

        <span>Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a class="btn secondary small" href="{{ $paginator->nextPageUrl() }}">Berikutnya</a>
        @else
            <span>Berikutnya</span>
        @endif
    </div>
@endif
