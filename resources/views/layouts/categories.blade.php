<section class="rv-section" id="unique-categories" aria-labelledby="categories-heading">
    <div class="unique-wrapper">
        <div class="rv-section-title">
            <h2 class="unique-title" id="categories-heading">Kategorie</h2>
        </div>

        @if ($categories->isEmpty())
            <div class="rv-empty">
                <h3>Brak kategorii</h3>
                <p>Nie dodano jeszcze żadnych kategorii filmów.</p>
            </div>
        @else
            <div class="unique-slider-container">
                @foreach ($categories as $category)
                    <a href="{{ route('movies.index', ['category' => $category->id]) }}" class="unique-frame">
                        {{-- The label is rendered as text in the overlay, so the
                             image itself is decorative for assistive tech. --}}
                        <img src="{{ asset('storage/img/categories/' . $category->species . '.webp') }}" alt="" loading="lazy" width="192" height="120">
                        <span class="unique-overlay">{{ $category->species }}</span>
                    </a>
                @endforeach
            </div>
            <div class="rv-cluster justify-content-end mt-3">
                <button type="button" class="unique-scroll-left" aria-label="Przewiń kategorie w lewo">
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                </button>
                <button type="button" class="unique-scroll-right" aria-label="Przewiń kategorie w prawo">
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        @endif
    </div>
</section>
