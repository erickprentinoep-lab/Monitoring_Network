<nav role="navigation" aria-label="Pagination Navigation">
    @if ($paginator->hasPages())
        <div style="display:flex;gap:6px;justify-content:center;align-items:center;padding:8px 0;">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span
                    style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1px solid #334155;color:#475569;font-size:0.82rem;">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1px solid #334155;color:#94a3b8;font-size:0.82rem;text-decoration:none;transition:all 0.2s;"
                    onmouseover="this.style.background='rgba(59,130,246,0.1)';this.style.color='#3b82f6'"
                    onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span
                        style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;color:#475569;font-size:0.82rem;">...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;background:#1e40af;color:#fff;font-size:0.82rem;font-weight:600;border:1px solid #1e40af;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1px solid #334155;color:#94a3b8;font-size:0.82rem;text-decoration:none;transition:all 0.2s;"
                                onmouseover="this.style.background='rgba(59,130,246,0.1)';this.style.color='#3b82f6'"
                                onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1px solid #334155;color:#94a3b8;font-size:0.82rem;text-decoration:none;transition:all 0.2s;"
                    onmouseover="this.style.background='rgba(59,130,246,0.1)';this.style.color='#3b82f6'"
                    onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span
                    style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1px solid #334155;color:#475569;font-size:0.82rem;">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif

            <span style="font-size:0.75rem;color:#64748b;margin-left:8px">
                {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }}
            </span>
        </div>
    @endif
</nav>