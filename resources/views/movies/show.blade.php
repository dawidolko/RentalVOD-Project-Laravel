@php
    $rvIsAdmin = Auth::check() && Auth::user()->role_id == 1;

    $rvEffectivePrice = !empty($movie->super_promo_price)
        ? $movie->super_promo_price
        : (($promotionsEnabled && !empty($promoPrice)) ? $promoPrice : $movie->price_day);

    // Movie schema for rich results. Only include an aggregateRating when
    // there is at least one opinion behind it.
    $rvSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Movie',
        'name' => $movie->title,
        'description' => $movie->description,
        'image' => asset('storage/' . $movie->img_path),
        'url' => route('movies.show', ['id' => $movie->id]),
        'genre' => $movie->category->species,
        'director' => ['@type' => 'Person', 'name' => $movie->director],
        'datePublished' => (string) $movie->release_year,
        'duration' => 'PT' . (int) $movie->duration . 'M',
        'inLanguage' => 'pl-PL',
        'offers' => [
            '@type' => 'Offer',
            'price' => number_format((float) $rvEffectivePrice, 2, '.', ''),
            'priceCurrency' => 'PLN',
            'availability' => $movie->available === 'dostępny'
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock',
            'url' => route('movies.show', ['id' => $movie->id]),
        ],
    ];

    if ($movie->opinions->count() > 0) {
        $rvSchema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => (string) $movie->rate,
            'bestRating' => '10',
            'worstRating' => '0',
            'ratingCount' => $movie->opinions->count(),
        ];
    }
@endphp

@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => $movie->title . ' - wypożycz online w RentalVOD',
    'metaDescription' => \Illuminate\Support\Str::limit(
        $movie->title . ' (' . $movie->release_year . '), reż. ' . $movie->director . '. ' . $movie->description,
        155
    ),
    'ogImage' => asset('storage/' . $movie->img_path),
    'ogType' => 'video.movie',
    'canonical' => route('movies.show', ['id' => $movie->id]),
    'jsonLd' => $rvSchema,
])

<head>
    <link rel="stylesheet" href="{{ asset('css/movieStyle.css') }}">
</head>

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [
            ['label' => 'Filmy', 'url' => route('movies.index')],
            ['label' => $movie->category->species, 'url' => route('movies.index', ['category' => $movie->category->id])],
            ['label' => $movie->title],
        ]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        @include('layouts.success')
        @include('layouts.errors')

        <article>
            <div class="row g-5">
                {{-- Poster --}}
                <div class="col-lg-4">
                    <div class="showcase-banner card" style="aspect-ratio: 2 / 3; position: relative;">
                        <img src="{{ asset('storage/' . $movie->img_path) }}"
                             alt="Plakat filmu {{ $movie->title }}"
                             class="product-img default"
                             style="width: 100%; height: 100%; object-fit: cover;"
                             width="400" height="600">

                        <div class="showcase-actions">
                            <button type="button" class="btn-action magnification" aria-label="Powiększ plakat filmu {{ $movie->title }}">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="col-lg-8">
                    <p class="showcase-category mb-2">
                        <a href="{{ route('movies.index', ['category' => $movie->category->id]) }}" class="showcase-category">
                            {{ $movie->category->species }}
                        </a>
                    </p>

                    <h1>{{ $movie->title }}</h1>

                    <ul class="list-group list-group-flush rv-meta-list mb-4">
                        <li class="list-group-item"><span>Gatunek</span><strong>{{ $movie->category->species }}</strong></li>
                        <li class="list-group-item"><span>Reżyser</span><strong>{{ $movie->director }}</strong></li>
                        <li class="list-group-item">
                            <span>Rok premiery</span>
                            <strong><time datetime="{{ $movie->release_year }}">{{ $movie->release_year }}</time></strong>
                        </li>
                        <li class="list-group-item">
                            <span>Czas trwania</span>
                            <strong><time datetime="PT{{ (int) $movie->duration }}M">{{ $movie->duration }} min</time></strong>
                        </li>
                        <li class="list-group-item">
                            <span>Ocena</span>
                            <strong><span class="rv-rating"><i class="bi bi-star-fill" aria-hidden="true"></i>{{ $movie->rate }}<span class="visually-hidden"> na 10</span></span></strong>
                        </li>
                        <li class="list-group-item"><span>Dostępność</span><strong>{{ $movie->available }}</strong></li>
                    </ul>

                    <div class="rv-price mb-4">
                        @if (!empty($movie->super_promo_price))
                            <h2 class="card-title">
                                {{ $movie->super_promo_price }} zł
                                <del>{{ $movie->price_day }} zł<span class="visually-hidden"> (cena przed obniżką)</span></del>
                            </h2>
                        @elseif ($promotionsEnabled && !empty($promoPrice))
                            <h2 class="card-title">
                                <del>{{ $movie->price_day }} zł<span class="visually-hidden"> (cena przed obniżką)</span></del>
                                {{ $promoPrice }} zł
                            </h2>
                        @else
                            <h2 class="card-title">{{ $movie->price_day }} zł</h2>
                        @endif
                        <span class="rv-text-sm">za dzień wypożyczenia</span>
                    </div>

                    <section aria-labelledby="description-heading">
                        <h2 id="description-heading">Opis</h2>
                        <p class="product-description">{{ $movie->description }}</p>
                    </section>

                    <div class="rv-cluster mt-4">
                        @auth
                            <button type="button" class="btn-action heart favoriting" data-movie-id="{{ $movie->id }}"
                                    data-movie-title="{{ $movie->title }}"
                                    onclick="toggleFavorite('{{ $movie->id }}')"
                                    aria-pressed="false"
                                    aria-label="Dodaj film {{ $movie->title }} do ulubionych">
                                <i class="bi bi-heart" id="favorite-icon-{{ $movie->id }}" aria-hidden="true"></i>
                            </button>

                            <form action="{{ route('cart.add', ['movie_id' => $movie->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn custom-btn" @disabled($rvIsAdmin)>
                                    <i class="bi bi-bag-plus" aria-hidden="true"></i>
                                    Dodaj do koszyka<span class="visually-hidden">: {{ $movie->title }}</span>
                                </button>
                            </form>

                            @if ($rvIsAdmin)
                                <p class="rv-field-hint mb-0">Konto administratora nie może wypożyczać filmów.</p>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn custom-btn">
                                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                                Zaloguj się, aby wypożyczyć<span class="visually-hidden"> film {{ $movie->title }}</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Opinions --}}
            <section class="rv-section" aria-labelledby="opinions-heading">
                <div class="rv-section-title">
                    <h2 id="opinions-heading">Opinie osób, które już obejrzały</h2>
                    <span class="rv-text-muted rv-text-sm">
                        {{ $movie->opinions->count() }}
                        {{ $movie->opinions->count() === 1 ? 'opinia' : 'opinii' }}
                    </span>
                </div>

                @forelse ($movie->opinions as $opinion)
                    <article class="card mb-3">
                        <div class="card-body">
                            <h3 class="card-subtitle" style="font-size: var(--rv-text-base);">
                                {{ $opinion->user->first_name }}
                            </h3>
                            <p class="card-text mt-2" style="color: var(--rv-text);">{{ $opinion->content }}</p>
                        </div>
                        <div class="card-footer">
                            <small>
                                Dodano
                                <time datetime="{{ $opinion->created_at->toIso8601String() }}">
                                    {{ $opinion->created_at->format('d.m.Y H:i') }}
                                </time>
                            </small>
                        </div>
                    </article>
                @empty
                    <div class="rv-empty">
                        <h3>Brak opinii</h3>
                        <p>Ten film nie ma jeszcze żadnej opinii. Wypożycz go i podziel się swoim zdaniem jako pierwszy.</p>
                    </div>
                @endforelse
            </section>
        </article>
    </main>

    <div id="imageOverlay" class="image-overlay" style="display: none;" role="dialog" aria-modal="true" aria-label="Powiększony plakat">
        <button type="button" class="close-btn" aria-label="Zamknij powiększenie">&times;</button>
        <img class="overlay-image" src="" alt="Powiększony plakat filmu">
    </div>

    @include('layouts.footer', ['fixedBottom' => false])

    <script defer src="{{ asset('js/magnification.js') }}"></script>
    <script defer src="{{ asset('js/favorite.js') }}"></script>
</body>

</html>
