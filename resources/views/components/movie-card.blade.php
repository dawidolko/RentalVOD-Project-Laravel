{{--
    Movie card used by the home page and the movie listing.

    Expects:
      $movie             - Movie model with its category relation loaded
      $promotionsEnabled - bool (optional, defaults to false)
--}}
@php
    $rvPromotionsEnabled = $promotionsEnabled ?? false;
@endphp

<article class="showcase">
    <div class="showcase-banner">
        <img src="{{ asset('storage/' . $movie->img_path) }}"
             alt="Plakat filmu {{ $movie->title }}"
             class="product-img default"
             loading="lazy" width="340" height="510">

        <div class="showcase-actions">
            <button type="button" class="btn-action magnification" aria-label="Powiększ plakat filmu {{ $movie->title }}">
                <i class="bi bi-eye" aria-hidden="true"></i>
            </button>
            <a href="{{ route('movies.show', ['id' => $movie->id]) }}" class="btn-action" aria-label="Zobacz szczegóły filmu {{ $movie->title }}">
                <i class="bi bi-bag-plus" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    <div class="showcase-content">
        <a href="{{ route('movies.index', ['category' => $movie->category->id]) }}" class="showcase-category">
            {{ $movie->category->species }}
        </a>

        <a href="{{ route('movies.show', ['id' => $movie->id]) }}">
            <h3 class="showcase-title">{{ $movie->title }}</h3>
        </a>

        <ul class="list-group list-group-flush rv-meta-list">
            <li class="list-group-item">
                <span>Reżyser</span><strong>{{ $movie->director }}</strong>
            </li>
            <li class="list-group-item">
                <span>Rok premiery</span>
                <strong><time datetime="{{ $movie->release_year }}">{{ $movie->release_year }}</time></strong>
            </li>
            <li class="list-group-item">
                <span>Ocena</span>
                <strong><span class="rv-rating"><i class="bi bi-star-fill" aria-hidden="true"></i>{{ $movie->rate }}<span class="visually-hidden"> na 10</span></span></strong>
            </li>
        </ul>

        <div class="rv-price">
            @if (!empty($movie->super_promo_price))
                <h4 class="card-title">
                    {{ $movie->price_day }} zł
                    <del>{{ $movie->old_price }} zł<span class="visually-hidden"> (cena przed obniżką)</span></del>
                </h4>
            @elseif ($rvPromotionsEnabled && empty($movie->old_price))
                <h4 class="card-title">
                    <del>{{ $movie->promo_price }} zł<span class="visually-hidden"> (cena przed obniżką)</span></del>
                    {{ $movie->price_day }} zł
                </h4>
            @else
                <h4 class="card-title">{{ $movie->price_day }} zł</h4>
            @endif
            <span class="rv-text-sm">za dzień wypożyczenia</span>
        </div>

        <div class="card-body">
            <a href="{{ route('movies.show', ['id' => $movie->id]) }}" class="btn custom-btn w-100">
                Przejdź do filmu<span class="visually-hidden"> {{ $movie->title }}</span>
            </a>
        </div>
    </div>
</article>
