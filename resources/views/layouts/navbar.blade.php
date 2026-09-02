@include('layouts.skip-link')

<header>
    <nav class="navbar navbar-expand-lg sticky-top" aria-label="Nawigacja główna">
        <div class="container-fluid">
            <a class="navbar-brand red-after" href="{{ route('home') }}">
                <b>RentalVOD</b>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Rozwiń menu nawigacji">
                <span class="navbar-toggler-icon" aria-hidden="true"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto my-2 my-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link red-after dropdown-toggle" href="{{ route('movies.index') }}" id="rentalDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Wypożycz
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="rentalDropdown">
                            <li><a class="dropdown-item red-after" href="{{ route('movies.index', ['sort_by' => 'rate2']) }}">Najwyżej oceniane</a></li>
                            <li><a class="dropdown-item red-after" href="{{ route('movies.index', ['sort_by' => 'rate1']) }}">Najniżej oceniane</a></li>
                            <li><a class="dropdown-item red-after" href="{{ route('movies.index', ['sort_by' => 'length1']) }}">Najdłuższe</a></li>
                            <li><a class="dropdown-item red-after" href="{{ route('movies.index', ['sort_by' => 'length2']) }}">Najkrótsze</a></li>
                            <li><a class="dropdown-item red-after" href="{{ route('movies.index', ['sort_by' => 'release2']) }}">Najnowsze</a></li>
                            <li><a class="dropdown-item red-after" href="{{ route('movies.index', ['sort_by' => 'release1']) }}">Najstarsze</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link red-after dropdown-toggle" href="{{ route('movies.index') }}" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kategorie
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            @foreach ($categories as $category)
                                <li><a class="dropdown-item red-after" href="{{ route('movies.index', ['category' => $category->id]) }}">{{ $category->species }}</a></li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link red-after" href="{{ route('regulamin') }}">Regulamin</a>
                    </li>
                </ul>

                {{-- Search: the field is always in the DOM with a real label, so
                     screen-reader and keyboard users never depend on the toggle. --}}
                <form action="{{ route('movies.search') }}" method="GET" class="d-flex align-items-center gap-2 my-2 my-lg-0 me-lg-3" role="search">
                    <label for="searchInput" class="visually-hidden">Szukaj filmów</label>
                    <input type="search" class="form-control" placeholder="Szukaj filmu..." name="query" id="searchInput" value="{{ request('query') }}" style="min-width: 12rem;">
                    <button class="btn custom-btn" type="submit" id="searchSubmit">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <span class="visually-hidden">Szukaj</span>
                    </button>
                </form>

                @can('is-admin')
                    <ul class="navbar-nav d-none d-lg-flex" id="admin-links" aria-label="Panel administratora">
                        <li class="nav-item">
                            <a class="nav-link red-after" href="{{ route('admin.orders') }}">Wypożyczenia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link red-after" href="{{ route('admin.users') }}">Użytkownicy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link red-after" href="{{ route('admin.movies') }}">Filmy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link red-after" href="{{ route('admin.settings') }}">Ustawienia</a>
                        </li>
                    </ul>
                @endcan

                <ul class="navbar-nav align-items-lg-center gap-2">
                    <li class="nav-item">
                        <button class="nav-link" id="theme-toggle" type="button" aria-pressed="false">
                            <i class="fas fa-moon" id="theme-icon" aria-hidden="true"></i>
                            <span class="visually-hidden" id="theme-toggle-text">Przełącz na tryb jasny</span>
                        </button>
                    </li>

                    @if (Auth::check() && Auth::user()->role_id != 1)
                        <li class="nav-item">
                            <span class="rv-points-badge">
                                <i class="bi bi-star-fill" aria-hidden="true"></i>
                                Punkty: {{ Auth::user()->loyaltyPoints->points ?? 0 }}
                            </span>
                        </li>
                    @endif
                </ul>

                @if (Auth::check() && Auth::user()->role_id != 2)
                    <label for="admin-select" class="visually-hidden">Przejdź do sekcji administratora</label>
                    <select class="form-select d-lg-none mt-2" id="admin-select" style="max-width: 100%;">
                        @can('is-admin')
                            <option value="">Panel administratora…</option>
                            <option value="{{ route('admin.orders') }}">Wypożyczenia</option>
                            <option value="{{ route('admin.users') }}">Edycja użytkowników</option>
                            <option value="{{ route('admin.movies') }}">Edycja filmów</option>
                            <option value="{{ route('admin.settings') }}">Ustawienia</option>
                        @endcan
                    </select>
                @endif

                <div class="dropdown ms-lg-3 mt-2 mt-lg-0" id="navbar-user">
                    <a class="dropdown-toggle d-flex align-items-center gap-2 text-decoration-none nav-link" href="#" id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ url(Auth::user() ? Auth::user()->avatar : 'storage/img/user.png') }}"
                             class="rounded-circle" width="30" height="30"
                             alt="{{ Auth::check() ? 'Awatar użytkownika ' . Auth::user()->first_name . ' ' . Auth::user()->last_name : 'Awatar niezalogowanego użytkownika' }}"
                             loading="lazy">
                        @if (Auth::check())
                            <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                        @else
                            <span>Konto</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                        @if (Auth::check())
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}">Mój profil</a></li>
                            @if (Auth::user()->role_id != 1)
                                <li><a class="dropdown-item" href="{{ route('cart.show') }}">Koszyk</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('settings') }}">Ustawienia</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Wyloguj się</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}">Zaloguj się</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Zarejestruj się</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

@include('layouts.success-toast')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
