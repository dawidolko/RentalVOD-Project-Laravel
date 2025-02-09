@include('layouts.html')
@include('layouts.head', ['pageTitle' => 'RentalVOD - film ' . $movie->title])

<head>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <link rel="stylesheet" href="{{ asset('css/movieStyle.css') }}" />
</head>

<body>
    @include('layouts.navbar')

    <div id="imageOverlay" class="image-overlay" style="display: none">
        <span class="close-btn">&times;</span>
        <img class="overlay-image" src="" alt="Powiększone zdjęcie" />
    </div>

    <div class="produkt new-conti category-section container">

        @include('layouts.success')

        @include('layouts.errors')

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </ul>
        </div>
        @endif

        <div class="product-main">
            <div class="product-grid">
                <div class="showcase">
                    <div class="slider showcase-banner">
                        <img src="{{ asset('storage/' . $movie->img_path) }}" alt="{{ $movie->category->species }}" width="300" class="product-img hover" style="border-bottom: 1px solid var(--cultured);" />
                        <img src="{{ asset('storage/' . $movie->img_path) }}" alt="{{ $movie->category->species }}" width="300" class="product-img default" style="border-bottom: 1px solid var(--cultured);" />

                        <div class="showcase-actions">
                            <button class="btn-action magnification">
                                <ion-icon name="eye-outline"></ion-icon>
                            </button>
                            @if (Auth::check())
                            <form action="{{ route('cart.add', ['movie_id' => $movie->id]) }}" method="POST">
                                @csrf
                                <button class="btn-action bag-add" {{ Auth::user()->role_id == 1 ? 'disabled' : '' }}>
                                    <ion-icon name="bag-add-outline"></ion-icon>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn-action bag-add">
                                <ion-icon name="log-in-outline"></ion-icon>
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="showcase-content">
                        <div class="caseBox-info">
                            <div class="caseBox1">
                                <a href="{{ route('movies.show', ['id' => $movie->id]) }}" class="showcase-category card-title text-danger2">{{ $movie->category->species }}</a>
                                <a href="{{ route('movies.show', ['id' => $movie->id]) }}">
                                    <h3 class="showcase-title">{{ $movie->title }}</h3>
                                </a>
                                <ul class="list-group list-group-flush bg-secondary" style="text-align: center;">
                                    <li class="list-group-item bg-dark1"><strong>Gatunek:</strong> {{ $movie->category->species }}</li>
                                    <li class="list-group-item bg-dark2"><strong>Reżyser:</strong> {{ $movie->director }}</li>
                                    <li class="list-group-item bg-dark3"><strong>Rok premiery:</strong> {{ $movie->release_year }}</li>
                                    <li class="list-group-item bg-dark4"><strong>Czas trwania:</strong> {{ $movie->duration }} min</li>
                                    <li class="list-group-item bg-dark5"><strong>Ocena:</strong> {{ $movie->rate }}</li>
                                    <li class="list-group-item bg-dark6"><strong>Cena za dzień wypożyczenia:</strong>
                                        <div class="card-footer text-center" style="background-color: rgba(139, 0, 0, 0.8); padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                                            @if(!empty($movie->super_promo_price))
                                            <h5 class="card-title">{{ $movie->super_promo_price }} zł <del>{{ $movie->price_day }} zł</del></h5>
                                            @elseif($promotionsEnabled && !empty($promoPrice))
                                            <h5 class="card-title"><del>{{ $movie->price_day }} zł</del> {{ $promoPrice }} zł</h5>
                                            @else
                                            <h5 class="card-title">{{ $movie->price_day }} zł</h5>
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                                <hr />
                                <h4 class="card-title" style="margin: 10px;">Opis</h4>
                                <div class="product-description">{{ $movie->description }}</div>
                                <hr />
                                <div class="cart-favorite">
                                    <div class="icons">
                                        <button class="btn-action heart favoriting" data-movie-id="{{ $movie->id }}" onclick="toggleFavorite('{{ $movie->id }}')">
                                            <ion-icon name="heart-outline" id="favorite-icon-{{ $movie->id }}"></ion-icon>
                                        </button>
                                        @if (Auth::check())
                                        <form action="{{ route('cart.add', ['movie_id' => $movie->id]) }}" method="POST">
                                            @csrf
                                            <button class="btn-action bag-add carting" {{ Auth::user()->role_id == 1 ? 'disabled' : '' }}>
                                                <ion-icon name="bag-add-outline"></ion-icon>
                                            </button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="btn-action bag-add">
                                            <ion-icon name="log-in-outline"></ion-icon>
                                        </a>
                                        @endif
                                    </div>
                                    <div class="login-btn">
                                        @if (Auth::check())
                                        <form action="{{ route('cart.add', ['movie_id' => $movie->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-block custom-btn" {{ Auth::user()->role_id == 1 ? 'disabled' : '' }}>
                                                <b>Dodaj do koszyka "{{ $movie->title }}"</b>
                                            </button>
                                        </form>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-block custom-btn"><b>Zaloguj się, aby wypożyczyć "{{ $movie->title }}"</b></a>
                                        @endif
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 m-5 w-100 m-auto">
                        <div class="card-body">
                            <h4 class="card-title">Opinie osób, które już obejrzały</h4>
                            <hr>
                            @foreach ($movie->opinions as $opinion)
                            <div class="card my-2 bg-secondary">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted"><strong>Imię: </strong>{{ $opinion->user->first_name }}</h6>
                                    <p class="card-text"><strong>Opinia: </strong>{{ $opinion->content }}</p>
                                </div>
                                <div class="card-footer">
                                    <small class="text-light"><strong>Dodano: </strong>{{ $opinion->created_at->format('d-m-Y H:i') }}</small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer', ['fixedBottom' => false])
    <script defer src="{{ asset('js/magnification.js') }}"></script>
    <script defer src="{{ asset('js/favorite.js') }}"></script>
</body>

</html>
