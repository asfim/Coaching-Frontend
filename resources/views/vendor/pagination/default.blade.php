@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display:flex;align-items:center;justify-content:space-between;width:100%;flex-wrap:wrap;gap:12px;">
        <div style="font-size:0.84rem;color:var(--gray-600);">
            Showing <strong style="color:var(--gray-900);font-weight:700;">{{ $paginator->firstItem() }}</strong> to <strong style="color:var(--gray-900);font-weight:700;">{{ $paginator->lastItem() }}</strong> of <strong style="color:var(--gray-900);font-weight:700;">{{ $paginator->total() }}</strong> records
        </div>

        <div class="pagination" style="display:flex;gap:6px;align-items:center;margin:0;list-style:none;padding:0;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="page-item disabled" aria-disabled="true" style="width:36px;height:36px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;border:1.5px solid var(--gray-200);color:var(--gray-400);background:#fff;opacity:0.5;cursor:not-allowed;">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="page-item" rel="prev" style="width:36px;height:36px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;border:1.5px solid var(--gray-200);color:var(--gray-700);background:#fff;text-decoration:none;transition:var(--transition);">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="page-item disabled" aria-disabled="true" style="width:36px;height:36px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;border:1.5px solid var(--gray-200);color:var(--gray-400);background:#fff;opacity:0.5;cursor:not-allowed;">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-item active" aria-current="page" style="width:36px;height:36px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;border:1.5px solid var(--primary);color:#fff;background:var(--primary);font-weight:700;box-shadow:0 2px 6px rgba(37,99,235,0.25);">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-item" style="width:36px;height:36px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;border:1.5px solid var(--gray-200);color:var(--gray-700);background:#fff;text-decoration:none;font-weight:600;transition:var(--transition);">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="page-item" rel="next" style="width:36px;height:36px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;border:1.5px solid var(--gray-200);color:var(--gray-700);background:#fff;text-decoration:none;transition:var(--transition);">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="page-item disabled" aria-disabled="true" style="width:36px;height:36px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;border:1.5px solid var(--gray-200);color:var(--gray-400);background:#fff;opacity:0.5;cursor:not-allowed;">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
