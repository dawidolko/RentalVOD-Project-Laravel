{{--
    Shared document head.

    Every view includes this with at least a $pageTitle. Optional variables
    a view may pass to control SEO without changing any controller:
      $pageTitle       - <title> text (required, existing contract)
      $metaDescription - meta description; falls back to the site default
      $ogImage         - absolute or storage-relative social image
      $ogType          - Open Graph type, defaults to "website"
      $canonical       - canonical URL, defaults to the current URL
      $robots          - robots directive, defaults to "index, follow"
      $jsonLd          - array or array-of-arrays rendered as JSON-LD
--}}
@php
    $rvTitle = $pageTitle ?? 'RentalVOD';
    $rvDescription = $metaDescription
        ?? 'RentalVOD - wypożyczalnia filmów online. Wypożycz filmy w dobrej cenie, oglądaj w jakości premium i zbieraj punkty lojalnościowe.';
    $rvCanonical = $canonical ?? url()->current();
    $rvImage = $ogImage ?? asset('storage/img/logo.webp');
    $rvOgType = $ogType ?? 'website';
    $rvRobots = $robots ?? 'index, follow';
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Primary SEO --}}
    <title>{{ $rvTitle }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($rvDescription), 160) }}">
    <meta name="robots" content="{{ $rvRobots }}">
    <link rel="canonical" href="{{ $rvCanonical }}">
    <meta name="theme-color" content="#0e1013" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="RentalVOD">
    <meta property="og:locale" content="pl_PL">
    <meta property="og:type" content="{{ $rvOgType }}">
    <meta property="og:title" content="{{ $rvTitle }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($rvDescription), 200) }}">
    <meta property="og:url" content="{{ $rvCanonical }}">
    <meta property="og:image" content="{{ $rvImage }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $rvTitle }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($rvDescription), 200) }}">
    <meta name="twitter:image" content="{{ $rvImage }}">

    <link rel="icon" href="{{ asset('storage/img/logo.webp') }}" type="image/webp">

    {{-- Fonts: preconnect so the render-blocking stylesheet resolves faster. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Order matters: Bootstrap, then the legacy head rules, then the
         design system so its tokens win over both. --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/head.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">

    <script defer src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>

    {{-- Organisation / WebSite structured data on every page. --}}
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => url('/') . '#organization',
                    'name' => 'RentalVOD',
                    'url' => url('/'),
                    'logo' => asset('storage/img/logo.webp'),
                    'email' => 'rentalVOD@contact.com',
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/') . '#website',
                    'name' => 'RentalVOD',
                    'url' => url('/'),
                    'inLanguage' => 'pl-PL',
                    'publisher' => ['@id' => url('/') . '#organization'],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => route('movies.search') . '?query={search_term_string}',
                        ],
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    {{-- Per-page structured data supplied by the including view. --}}
    @isset($jsonLd)
        @foreach ((array_is_list($jsonLd) ? $jsonLd : [$jsonLd]) as $rvSchema)
            <script type="application/ld+json">
                {!! json_encode($rvSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            </script>
        @endforeach
    @endisset
</head>
