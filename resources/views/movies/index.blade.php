@php
    // This view is rendered both by MoviesController::index and ::search.
    // The search action does not compute promotions, so default it here
    // rather than letting the template hit an undefined variable.
    $promotionsEnabled = $promotionsEnabled ?? false;
    $rvQuery = request('query');
    $rvIsSearch = filled($rvQuery);
@endphp

@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => $rvIsSearch
        ? 'Wyniki wyszukiwania: ' . $rvQuery . ' - RentalVOD'
        : 'Filmy do wypożyczenia - RentalVOD',
    'metaDescription' => $rvIsSearch
        ? 'Wyniki wyszukiwania filmów dla frazy "' . $rvQuery . '" w wypożyczalni RentalVOD.'
        : 'Pełny katalog filmów dostępnych do wypożyczenia w RentalVOD. Filtruj według gatunku, ceny, oceny i długości filmu.',
    // Search result pages carry no independent SEO value and would create
    // near-duplicate URLs, so keep them out of the index.
    'robots' => $rvIsSearch ? 'noindex, follow' : 'index, follow',
    'canonical' => $rvIsSearch ? route('movies.index') : route('movies.index'),
])

<head>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.1/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.1/nouislider.min.css">
    <link rel="stylesheet" href="{{ asset('css/styleMovie.css') }}">
</head>

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [
            ['label' => 'Filmy', 'url' => route('movies.index')],
            ...($rvIsSearch ? [['label' => 'Wyniki wyszukiwania']] : []),
        ]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        @include('layouts.success')
        @include('layouts.errors')

        <header class="rv-page-header">
            <h1>
                @if ($rvIsSearch)
                    Wyniki wyszukiwania: „{{ $rvQuery }}”
                @else
                    Wszystkie filmy
                @endif
            </h1>
            <p>
                {{ $movies->total() }}
                {{ $movies->total() === 1 ? 'film' : 'filmów' }}
                @if ($rvIsSearch) pasuje do wyszukiwanej frazy @else w katalogu @endif.
            </p>
        </header>

        @unless ($rvIsSearch)
            <section class="rv-section" style="margin-top: 0;" aria-labelledby="filters-heading">
                <form action="{{ route('movies.index') }}" method="GET">
                    <fieldset>
                        <legend id="filters-heading">Filtruj katalog</legend>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="species" class="form-label">Gatunek</label>
                                <select name="species" id="species" class="form-select">
                                    <option value="">Wszystkie gatunki</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->species }}" @selected(request('species') == $category->species)>
                                            {{ $category->species }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="sort_by" class="form-label">Sortuj według</label>
                                <select name="sort_by" id="sort_by" class="form-select">
                                    <option value="">Brak sortowania</option>
                                    <option value="release1" @selected(request('sort_by') == 'release1')>Od najstarszego</option>
                                    <option value="release2" @selected(request('sort_by') == 'release2')>Od najnowszego</option>
                                    <option value="rate1" @selected(request('sort_by') == 'rate1')>Ocena rosnąco</option>
                                    <option value="rate2" @selected(request('sort_by') == 'rate2')>Ocena malejąco</option>
                                    <option value="length1" @selected(request('sort_by') == 'length1')>Długość filmu malejąca</option>
                                    <option value="length2" @selected(request('sort_by') == 'length2')>Długość filmu rosnąca</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <span class="form-label d-block" id="price_range_label">Zakres cenowy</span>
                                <div class="slider-container" aria-describedby="price-range-help">
                                    <div id="price-slider"></div>

                                    {{-- The slider writes into these hidden inputs. The
                                         number inputs below give keyboard and screen
                                         reader users an equivalent, labelled control. --}}
                                    <input type="hidden" id="price_min" name="price_min" value="{{ request('price_min', 0) }}">
                                    <input type="hidden" id="price_max" name="price_max" value="{{ request('price_max', 100) }}">

                                    <div class="row g-3 mt-2">
                                        <div class="col-sm-6">
                                            <label for="price_min_input" class="form-label">Cena od (zł)</label>
                                            <input type="number" id="price_min_input" class="form-control" min="0" max="100" step="1" value="{{ request('price_min', 0) }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="price_max_input" class="form-label">Cena do (zł)</label>
                                            <input type="number" id="price_max_input" class="form-control" min="0" max="100" step="1" value="{{ request('price_max', 100) }}">
                                        </div>
                                    </div>

                                    <p class="rv-field-hint" id="price-range-help" aria-live="polite">
                                        Cena: <span id="price-range-display">{{ request('price_min', 0) }} - {{ request('price_max', 100) }}</span> zł
                                    </p>
                                </div>
                            </div>

                            <div class="col-12 rv-cluster">
                                <button type="submit" class="btn custom-btn">Filtruj</button>
                                <a href="{{ route('movies.index') }}" class="btn btn-secondary">Wyczyść filtry</a>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </section>
        @endunless

        <section class="rv-section" aria-labelledby="results-heading">
            <h2 id="results-heading" class="visually-hidden">Lista filmów</h2>

            <div class="product-grid">
                @forelse ($movies as $movie)
                    @include('components.movie-card', ['movie' => $movie, 'promotionsEnabled' => $promotionsEnabled])
                @empty
                    <div class="rv-empty">
                        <h3>Brak wyników</h3>
                        @if ($rvIsSearch)
                            <p>Nie znaleźliśmy filmów pasujących do frazy „{{ $rvQuery }}”. Spróbuj innego słowa kluczowego lub przejrzyj cały katalog.</p>
                        @else
                            <p>Żaden film nie spełnia wybranych kryteriów. Zmień filtry lub wyczyść je, aby zobaczyć cały katalog.</p>
                        @endif
                        <a href="{{ route('movies.index') }}" class="btn custom-btn">Zobacz wszystkie filmy</a>
                    </div>
                @endforelse
            </div>

            @if ($movies->hasPages())
                <nav class="mt-5 d-flex justify-content-center" aria-label="Paginacja listy filmów">
                    {{ $movies->onEachSide(1)->links('vendor.pagination.rentalvod') }}
                </nav>
            @endif
        </section>
    </main>

    <div id="imageOverlay" class="image-overlay" style="display: none;" role="dialog" aria-modal="true" aria-label="Powiększony plakat">
        <button type="button" class="close-btn" aria-label="Zamknij powiększenie">&times;</button>
        <img class="overlay-image" src="" alt="Powiększony plakat filmu">
    </div>

    @include('layouts.footer', ['fixedBottom' => false])

    <script defer src="{{ asset('js/priceSlider.js') }}"></script>
    <script defer src="{{ asset('js/magnification.js') }}"></script>
</body>

</html>
