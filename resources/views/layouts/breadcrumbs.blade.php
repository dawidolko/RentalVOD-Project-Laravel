{{--
    Breadcrumbs + matching BreadcrumbList JSON-LD.

    Usage:
      @include('layouts.breadcrumbs', ['crumbs' => [
          ['label' => 'Filmy', 'url' => route('movies.index')],
          ['label' => $movie->title],           // last entry = current page
      ]])

    The home crumb is prepended automatically.
--}}
@php
    $rvCrumbs = array_merge(
        [['label' => 'Strona główna', 'url' => route('home')]],
        $crumbs ?? []
    );
    $rvLast = count($rvCrumbs) - 1;
@endphp

<nav class="rv-breadcrumb" aria-label="Ścieżka nawigacji">
    <ol>
        @foreach ($rvCrumbs as $rvIndex => $rvCrumb)
            <li>
                @if ($rvIndex === $rvLast || empty($rvCrumb['url']))
                    <span aria-current="page">{{ $rvCrumb['label'] }}</span>
                @else
                    <a href="{{ $rvCrumb['url'] }}">{{ $rvCrumb['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

<script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($rvCrumbs)->values()->map(fn ($rvCrumb, $rvI) => array_filter([
            '@type' => 'ListItem',
            'position' => $rvI + 1,
            'name' => $rvCrumb['label'],
            'item' => $rvCrumb['url'] ?? null,
        ]))->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
