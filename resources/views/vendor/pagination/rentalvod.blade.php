{{--
    Accessible pagination.

    Differences from pagination::bootstrap-4:
      - every control has a text label in its aria-label, not just an arrow
      - the current page is announced with aria-current="page"
      - disabled controls are <span>s, so they are not focusable dead ends
      - a visually hidden summary states the current position
--}}
@if ($paginator->hasPages())
    <div class="w-100">
        <p class="visually-hidden" aria-live="polite">
            Strona {{ $paginator->currentPage() }} z {{ $paginator->lastPage() }}.
        </p>

        <ul class="pagination">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-hidden="true">
                    <span class="page-link">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        <span class="d-none d-sm-inline ms-1">Poprzednia</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       aria-label="Przejdź do poprzedniej strony, strona {{ $paginator->currentPage() - 1 }}">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        <span class="d-none d-sm-inline ms-1">Poprzednia</span>
                    </a>
                </li>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">
                            {{ $element }}
                            <span class="visually-hidden">pominięte strony</span>
                        </span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">
                                    <span class="visually-hidden">Strona </span>{{ $page }}
                                    <span class="visually-hidden">, bieżąca strona</span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" aria-label="Przejdź do strony {{ $page }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                       aria-label="Przejdź do następnej strony, strona {{ $paginator->currentPage() + 1 }}">
                        <span class="d-none d-sm-inline me-1">Następna</span>
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-hidden="true">
                    <span class="page-link">
                        <span class="d-none d-sm-inline me-1">Następna</span>
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </span>
                </li>
            @endif
        </ul>
    </div>
@endif
