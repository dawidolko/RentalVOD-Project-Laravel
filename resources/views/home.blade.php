@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'RentalVOD - wypożyczalnia filmów online',
    'metaDescription' => 'Wypożycz filmy online w RentalVOD. Setki tytułów w atrakcyjnych cenach, jakość premium, ranking TOP 10 i punkty lojalnościowe za każde wypożyczenie.',
    'canonical' => route('home'),
])

<body>
    @include('layouts.navbar')

    <main id="main-content" class="rv-page">
        @include('layouts.success')
        @include('layouts.errors')

        <header class="rv-page-header">
            <h1>Wypożyczalnia filmów online</h1>
            <p>Przeglądaj katalog, wypożyczaj na dowolną liczbę dni i zbieraj punkty lojalnościowe za każde zamówienie.</p>
        </header>

        @include('layouts.slider')

        @include('layouts.categories')

        @include('layouts.top10')

        <section class="rv-section" aria-labelledby="rent-today-heading">
            <div class="rv-section-title">
                <h2 id="rent-today-heading">Wypożycz już dziś</h2>
                <a href="{{ route('movies.index') }}" class="btn custom-btn btn-sm">Zobacz wszystkie filmy</a>
            </div>

            <div class="product-grid">
                @forelse ($movies as $movie)
                    @include('components.movie-card', ['movie' => $movie, 'promotionsEnabled' => $promotionsEnabled])
                @empty
                    <div class="rv-empty">
                        <h3>Brak dostępnych filmów</h3>
                        <p>W tej chwili żaden film nie jest dostępny do wypożyczenia. Zajrzyj do nas ponownie za jakiś czas.</p>
                        <a href="{{ route('movies.index') }}" class="btn custom-btn">Przeglądaj katalog</a>
                    </div>
                @endforelse
            </div>
        </section>

        @if (!empty($topRecommendedMovies) && count($topRecommendedMovies) > 0)
            <section class="rv-section" aria-labelledby="recommended-heading">
                <div class="rv-section-title">
                    <h2 id="recommended-heading">Rekomendowane dla Ciebie</h2>
                </div>

                <div class="product-grid">
                    @foreach ($topRecommendedMovies as $movie)
                        <article class="showcase">
                            <div class="showcase-banner">
                                <img src="{{ asset('storage/' . $movie->img_path) }}"
                                     alt="Plakat filmu {{ $movie->title }}"
                                     class="product-img default" loading="lazy" width="340" height="510">
                            </div>
                            <div class="showcase-content">
                                <a href="{{ route('movies.show', ['id' => $movie->id]) }}">
                                    <h3 class="showcase-title">{{ $movie->title }}</h3>
                                </a>
                                <ul class="list-group list-group-flush rv-meta-list">
                                    <li class="list-group-item">
                                        <span>Ocena</span>
                                        <strong><span class="rv-rating"><i class="bi bi-star-fill" aria-hidden="true"></i>{{ $movie->rate }}<span class="visually-hidden"> na 10</span></span></strong>
                                    </li>
                                    <li class="list-group-item">
                                        <span>Liczba rekomendacji</span><strong>{{ $movie->recommendations_count }}</strong>
                                    </li>
                                </ul>
                                <div class="card-body">
                                    <a href="{{ route('movies.show', ['id' => $movie->id]) }}" class="btn custom-btn w-100">
                                        Przejdź do filmu<span class="visually-hidden"> {{ $movie->title }}</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    {{-- Lightbox for the poster magnifier. --}}
    <div id="imageOverlay" class="image-overlay" style="display: none;" role="dialog" aria-modal="true" aria-label="Powiększony plakat">
        <button type="button" class="close-btn" aria-label="Zamknij powiększenie">&times;</button>
        <img class="overlay-image" src="" alt="Powiększony plakat filmu">
    </div>

    @include('layouts.footer', ['fixedBottom' => false])

    <script defer src="{{ asset('js/magnification.js') }}"></script>
    <script defer src="{{ asset('js/topten.js') }}"></script>
</body>

</html>
