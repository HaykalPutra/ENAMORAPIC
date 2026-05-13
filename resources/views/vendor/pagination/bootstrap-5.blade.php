@if ($paginator->hasPages())
<div class="d-flex justify-content-center">
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0" style="gap: 4px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" style="font-size: 0.875rem; padding: 6px 10px; border-radius: 6px; cursor: not-allowed; display: inline-flex; align-items: center; height: 32px; line-height: 1;">
                        <i class="bi bi-chevron-left" style="font-size: 0.75rem; line-height: 1;"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="font-size: 0.875rem; padding: 6px 10px; border-radius: 6px; transition: all 0.2s; display: inline-flex; align-items: center; height: 32px; line-height: 1; color: #1a2332;">
                        <i class="bi bi-chevron-left" style="font-size: 0.75rem; line-height: 1;"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link" style="font-size: 0.875rem; padding: 6px 10px; border-radius: 6px; display: inline-flex; align-items: center; height: 32px; line-height: 1;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        {{-- "Next" Page Link --}}
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link" style="font-size: 0.875rem; padding: 6px 10px; border-radius: 6px; background: #1a2332; border-color: #1a2332; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; height: 32px; line-height: 1; color: white; min-width: 32px;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="font-size: 0.875rem; padding: 6px 10px; border-radius: 6px; color: #1a2332; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; height: 32px; line-height: 1; min-width: 32px;">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="font-size: 0.875rem; padding: 6px 10px; border-radius: 6px; transition: all 0.2s; display: inline-flex; align-items: center; height: 32px; line-height: 1; color: #1a2332;">
                        <i class="bi bi-chevron-right" style="font-size: 0.75rem; line-height: 1;"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" style="font-size: 0.875rem; padding: 6px 10px; border-radius: 6px; cursor: not-allowed; display: inline-flex; align-items: center; height: 32px; line-height: 1;">
                        <i class="bi bi-chevron-right" style="font-size: 0.75rem; line-height: 1;"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
</div>

<style>
    .page-link {
        color: #1a2332;
        border: 1px solid #ddd;
        line-height: 1;
    }

    .page-link:hover:not(.disabled) {
        color: #fff;
        background-color: #1a2332;
        border-color: #1a2332;
    }

    .page-item.active .page-link {
        background-color: #1a2332;
        border-color: #1a2332;
        color: white;
    }

    .page-item.disabled .page-link {
        color: #999;
        background-color: #f5f5f5;
        border-color: #ddd;
        cursor: not-allowed;
    }
</style>
@endif
