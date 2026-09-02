<section class="rv-section top-movies-slider" id="topMoviesSlider" aria-labelledby="top10-heading">
    <div class="rv-section-title">
        <h2 class="unique-title" id="top10-heading">Top 10 najczęściej wypożyczanych</h2>
    </div>

    @if (empty($topMovies) || $topMovies->isEmpty())
        <div class="rv-empty">
            <h3>Brak danych do rankingu</h3>
            <p>Ranking pojawi się, gdy pierwsze filmy zostaną wypożyczone.</p>
        </div>
    @else
        <div>
            @foreach ($topMovies as $index => $movie)
                <div class="top-movies-slide @if ($index === 0) active @endif"
                     style="background-image: url('{{ asset('storage/' . $movie->img_path) }}');"
                     role="group"
                     aria-roledescription="slajd"
                     aria-label="{{ $index + 1 }} z {{ count($topMovies) }}: {{ $movie->title }}">
                    <div class="top-movies-content">
                        <h3>{{ $movie->title }}</h3>
                        <p>#{{ $index + 1 }} miejsce</p>
                        <p>Dostępność: {{ $movie->available }}</p>
                        <a href="{{ route('movies.show', ['id' => $movie->id]) }}" class="btn custom-btn">
                            Przejdź do filmu<span class="visually-hidden"> {{ $movie->title }}</span>
                        </a>
                    </div>
                </div>
            @endforeach

            <p class="visually-hidden" id="top-movies-status" aria-live="polite">
                Slajd 1 z {{ count($topMovies) }}
            </p>

            <div class="top-movies-controls">
                <button type="button" id="prevSlide" aria-label="Poprzedni film w rankingu">
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                </button>
                <button type="button" id="nextSlide" aria-label="Następny film w rankingu">
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    @endif
</section>
