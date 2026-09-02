@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Mój profil - RentalVOD',
    'metaDescription' => 'Twój profil w RentalVOD: aktualne wypożyczenia, znajomi, polecone filmy i punkty lojalnościowe.',
    'robots' => 'noindex, nofollow',
])

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [['label' => 'Mój profil']]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        @if (Auth::check())
            @php $rvUser = Auth::user(); @endphp

            @include('layouts.success')
            @include('layouts.error')
            @include('layouts.errors')

            {{-- Loyalty point notifications are pushed here by profile.js. --}}
            <div id="snackbar" role="status" aria-live="polite"></div>

            <h1 class="visually-hidden">Profil użytkownika {{ $rvUser->first_name }} {{ $rvUser->last_name }}</h1>

            {{-- Account summary --}}
            <section class="card mb-5" aria-labelledby="account-heading">
                <div class="card-body" style="padding: var(--rv-space-5);">
                    <div class="row g-5 align-items-start">
                        <div class="col-md-8">
                            <h2 id="account-heading">{{ $rvUser->first_name }} {{ $rvUser->last_name }}</h2>

                            <dl class="row mt-4">
                                <dt class="col-sm-4">E-mail</dt>
                                <dd class="col-sm-8">{{ $rvUser->email }}</dd>

                                <dt class="col-sm-4">Adres</dt>
                                <dd class="col-sm-8">{{ $rvUser->address }}</dd>

                                @if ($rvUser->role_id != 1)
                                    <dt class="col-sm-4">Punkty lojalnościowe</dt>
                                    <dd class="col-sm-8">{{ $rvUser->loyaltyPoints->points ?? 0 }}</dd>

                                    <dt class="col-sm-4">Kod polecający</dt>
                                    <dd class="col-sm-8"><code>{{ $referralCode }}</code></dd>
                                @endif
                            </dl>

                            <div class="rv-cluster">
                                <a href="{{ route('settings') }}" class="btn custom-btn">Edytuj dane</a>
                                @if ($rvUser->role_id != 1)
                                    <a href="{{ route('cart.show') }}" class="btn btn-secondary">Koszyk</a>
                                @endif
                                <a href="{{ route('logout') }}" class="btn btn-secondary">Wyloguj się</a>
                            </div>
                        </div>

                        <div class="col-md-4 text-center">
                            <img src="{{ url($rvUser->avatar ?: 'storage/img/user.png') }}"
                                 class="rounded-circle"
                                 width="120" height="120"
                                 style="object-fit: cover;"
                                 alt="Awatar użytkownika {{ $rvUser->first_name }} {{ $rvUser->last_name }}"
                                 loading="lazy">

                            <form method="POST" action="{{ route('user.update_avatar') }}"
                                  enctype="multipart/form-data" class="rv-stack mt-4" id="avatarForm">
                                @csrf
                                @method('PUT')

                                <div class="text-start">
                                    <label for="avatar" class="form-label">Zmień awatar</label>
                                    <input id="avatar" name="avatar" type="file" accept="image/*"
                                           class="form-control @error('avatar') is-invalid @enderror" required
                                           aria-describedby="@error('avatar') avatar-error @enderror">
                                    @include('layouts.field-error', ['field' => 'avatar'])
                                </div>

                                <button type="submit" class="btn custom-btn">Zaktualizuj awatar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            @if ($rvUser->role_id != 1)
                {{-- Friends --}}
                <section class="card mb-5" aria-labelledby="add-friend-heading">
                    <div class="card-body" style="padding: var(--rv-space-5);">
                        <h2 id="add-friend-heading">Dodaj znajomego</h2>

                        <form method="POST" action="{{ route('friends.sendRequest') }}" class="rv-stack mt-3">
                            @csrf
                            <div>
                                <label for="email" class="form-label">E-mail znajomego</label>
                                <input type="email" id="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="Wpisz e-mail znajomego" required
                                       autocomplete="off"
                                       role="combobox" aria-expanded="false"
                                       aria-autocomplete="list" aria-controls="emailList"
                                       aria-describedby="email-hint @error('email') email-error @enderror">
                                <span class="rv-field-hint" id="email-hint">
                                    Po wpisaniu trzech znaków zobaczysz podpowiedzi.
                                </span>
                                @include('layouts.field-error', ['field' => 'email'])

                                <div id="emailList" role="listbox" aria-label="Podpowiedzi adresów e-mail"></div>
                                <p class="visually-hidden" id="emailListStatus" aria-live="polite"></p>
                            </div>

                            <button type="submit" class="btn custom-btn align-self-start">Wyślij zaproszenie</button>
                        </form>
                    </div>
                </section>

                <div class="row g-5 mb-5">
                    <div class="col-lg-4">
                        <section class="card h-100" aria-labelledby="requests-heading">
                            <div class="card-body">
                                <h2 id="requests-heading" style="font-size: var(--rv-text-xl);">Zaproszenia do znajomych</h2>

                                @if ($friendRequests->isEmpty())
                                    <p class="rv-text-muted">Brak zaproszeń do znajomych.</p>
                                @else
                                    <div class="table-responsive" tabindex="0">
                                        <table class="table">
                                            <caption>Otrzymane zaproszenia oczekujące na Twoją decyzję.</caption>
                                            <thead>
                                                <tr>
                                                    <th scope="col">E-mail</th>
                                                    <th scope="col">Akcje</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($friendRequests as $request)
                                                    <tr>
                                                        <th scope="row" style="font-weight: var(--rv-weight-normal);">
                                                            {{ $request->user->email }}
                                                        </th>
                                                        <td>
                                                            <div class="rv-cluster">
                                                                <form action="{{ route('friends.acceptRequest', $request->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success btn-sm">
                                                                        Akceptuj<span class="visually-hidden"> zaproszenie od {{ $request->user->email }}</span>
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('friends.declineRequest', $request->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                                        Odrzuć<span class="visually-hidden"> zaproszenie od {{ $request->user->email }}</span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-4">
                        <section class="card h-100" aria-labelledby="pending-heading">
                            <div class="card-body">
                                <h2 id="pending-heading" style="font-size: var(--rv-text-xl);">Oczekujące zaproszenia</h2>

                                @if ($pendingRequests->isEmpty())
                                    <p class="rv-text-muted">Brak oczekujących zaproszeń.</p>
                                @else
                                    <div class="table-responsive" tabindex="0">
                                        <table class="table">
                                            <caption>Zaproszenia wysłane przez Ciebie.</caption>
                                            <thead>
                                                <tr>
                                                    <th scope="col">E-mail</th>
                                                    <th scope="col">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pendingRequests as $request)
                                                    <tr>
                                                        <th scope="row" style="font-weight: var(--rv-weight-normal);">
                                                            {{ $request->friend->email }}
                                                        </th>
                                                        <td>Oczekuje na akceptację</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-4">
                        <section class="card h-100" aria-labelledby="friends-heading">
                            <div class="card-body">
                                <h2 id="friends-heading" style="font-size: var(--rv-text-xl);">Twoi znajomi</h2>

                                @if ($friends->isEmpty())
                                    <p class="rv-text-muted">Nie masz jeszcze znajomych.</p>
                                @else
                                    <div class="table-responsive" tabindex="0">
                                        <table class="table">
                                            <caption>Lista Twoich znajomych.</caption>
                                            <thead>
                                                <tr>
                                                    <th scope="col">E-mail</th>
                                                    <th scope="col">Akcje</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($friends as $friend)
                                                    <tr>
                                                        <th scope="row" style="font-weight: var(--rv-weight-normal);">
                                                            {{ $friend->email }}
                                                        </th>
                                                        <td>
                                                            <form action="{{ route('friends.removeFriend', $friend->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    Usuń<span class="visually-hidden"> znajomego {{ $friend->email }}</span>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>
                </div>

                {{-- Expenses chart --}}
                <section class="card mb-5" aria-labelledby="expenses-heading">
                    <div class="card-body" style="padding: var(--rv-space-5);">
                        <h2 id="expenses-heading">Wydatki w poszczególnych dniach</h2>

                        <div class="chart-container" style="position: relative; height: 40vh; min-height: 260px;">
                            <canvas id="expensesChart"
                                    role="img"
                                    aria-label="Wykres słupkowy wydatków w wybranym tygodniu. Dokładne wartości są dostępne w tabeli wypożyczeń poniżej."></canvas>
                        </div>

                        <p class="visually-hidden" id="expensesStatus" aria-live="polite"></p>

                        <div class="rv-cluster justify-content-between mt-4">
                            <button id="prevWeek" type="button" class="btn custom-btn">Poprzedni tydzień</button>
                            <button id="nextWeek" type="button" class="btn custom-btn">Następny tydzień</button>
                        </div>
                    </div>
                </section>

                {{-- Current loans --}}
                <section class="rv-section" aria-labelledby="loans-heading">
                    <div class="rv-section-title">
                        <h2 id="loans-heading">Aktualne wypożyczenia</h2>
                    </div>

                    @if ($loans->isEmpty())
                        <div class="rv-empty">
                            <h3>Brak wypożyczonych filmów</h3>
                            <p>Nie masz jeszcze żadnego wypożyczenia. Przejrzyj katalog i wybierz pierwszy film.</p>
                            <a href="{{ route('movies.index') }}" class="btn custom-btn">Przeglądaj filmy</a>
                        </div>
                    @else
                        <div class="table-responsive" tabindex="0">
                            <table class="table">
                                <caption>Twoje wypożyczenia wraz z okresem, kosztem i dostępnymi akcjami.</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Film</th>
                                        <th scope="col">Data rozpoczęcia</th>
                                        <th scope="col">Data zakończenia</th>
                                        <th scope="col">Cena całkowita</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loans as $loan)
                                        @foreach ($loan->movies as $movie)
                                            @php
                                                $premiumMovie = \App\Models\PremiumMovie::where('movie_id', $movie->id)
                                                    ->where('user_id', auth()->id())
                                                    ->first();
                                                $existingOpinion = \App\Models\Opinion::where('movie_id', $movie->id)
                                                    ->where('user_id', auth()->id())
                                                    ->first();
                                            @endphp
                                            <tr>
                                                <th scope="row" style="font-weight: var(--rv-weight-semibold);">
                                                    @if ($loan->status !== 'zwrócone')
                                                        @if ($premiumMovie)
                                                            <a href="{{ route('loans.premium', $movie->id) }}">{{ $movie->title }}</a>
                                                        @else
                                                            <a href="{{ route('loans.show', $movie->id) }}">{{ $movie->title }}</a>
                                                        @endif
                                                    @else
                                                        <span class="disabled-link">{{ $movie->title }}</span>
                                                    @endif
                                                </th>
                                                <td>
                                                    <time datetime="{{ \Carbon\Carbon::parse($loan->start)->toDateString() }}">
                                                        {{ \Carbon\Carbon::parse($loan->start)->format('d.m.Y') }}
                                                    </time>
                                                </td>
                                                <td>
                                                    <time datetime="{{ \Carbon\Carbon::parse($loan->end)->toDateString() }}">
                                                        {{ \Carbon\Carbon::parse($loan->end)->format('d.m.Y') }}
                                                    </time>
                                                </td>
                                                <td>{{ number_format($loan->price, 2) }} zł</td>
                                                <td>{{ $loan->status }}</td>
                                                <td>
                                                    <div class="rv-stack" style="gap: var(--rv-space-2);">
                                                        {{-- Opinion --}}
                                                        @if ($existingOpinion)
                                                            <p class="rv-text-muted rv-text-sm mb-0">Dodałeś już opinię dla tego filmu.</p>
                                                        @else
                                                            <button type="button" class="btn btn-info btn-sm"
                                                                    aria-expanded="false" aria-controls="review-form-{{ $loan->id }}"
                                                                    onclick="toggleReviewForm({{ $loan->id }}, this)">
                                                                Dodaj opinię<span class="visually-hidden"> o filmie {{ $movie->title }}</span>
                                                            </button>

                                                            <div id="review-form-{{ $loan->id }}" hidden>
                                                                <form action="{{ route('opinions.store') }}" method="POST" class="rv-stack" style="gap: var(--rv-space-2);">
                                                                    @csrf
                                                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                                                    <label for="opinion-{{ $loan->id }}" class="form-label">
                                                                        Twoja opinia o filmie {{ $movie->title }}
                                                                    </label>
                                                                    <textarea id="opinion-{{ $loan->id }}" name="content" rows="3"
                                                                              class="form-control" placeholder="Wpisz swoją opinię" required></textarea>
                                                                    <button type="submit" class="btn btn-primary btn-sm">Wyślij opinię</button>
                                                                </form>
                                                            </div>
                                                        @endif

                                                        {{-- Premium --}}
                                                        @if ($premiumMovie)
                                                            <p class="rv-text-muted rv-text-sm mb-0">Jakość premium odblokowana.</p>
                                                        @elseif ($loan->status !== 'zwrócone')
                                                            <button type="button" class="btn btn-warning btn-sm"
                                                                    aria-expanded="false" aria-controls="payment-form-{{ $loan->id }}"
                                                                    onclick="togglePaymentForm({{ $loan->id }}, this)">
                                                                Kup jakość premium<span class="visually-hidden"> dla filmu {{ $movie->title }}</span>
                                                            </button>

                                                            @if (($rvUser->loyaltyPoints->points ?? 0) >= 50)
                                                                <form method="POST" action="{{ route('user.upgradeToPremium', $movie->id) }}"
                                                                      id="points-form-{{ $loan->id }}">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                                                        Kup premium za punkty<span class="visually-hidden"> dla filmu {{ $movie->title }}</span>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            <form method="POST" action="{{ route('user.upgradeToPremium', $movie->id) }}"
                                                                  id="payment-form-{{ $loan->id }}" hidden>
                                                                @csrf
                                                                <fieldset>
                                                                    <legend style="font-size: var(--rv-text-base);">
                                                                        Dane karty kredytowej
                                                                    </legend>

                                                                    <div class="rv-stack" style="gap: var(--rv-space-3);">
                                                                        <div>
                                                                            <label for="cardNumber-{{ $loan->id }}" class="form-label">Numer karty</label>
                                                                            <input type="text" id="cardNumber-{{ $loan->id }}" name="cardNumber"
                                                                                   class="form-control" pattern="\d{16}" inputmode="numeric"
                                                                                   autocomplete="cc-number" required
                                                                                   aria-describedby="cardNumber-hint-{{ $loan->id }}">
                                                                            <span class="rv-field-hint" id="cardNumber-hint-{{ $loan->id }}">16 cyfr, bez spacji.</span>
                                                                        </div>

                                                                        <div>
                                                                            <label for="expiryDate-{{ $loan->id }}" class="form-label">Data ważności</label>
                                                                            <input type="month" id="expiryDate-{{ $loan->id }}" name="expiryDate"
                                                                                   class="form-control" autocomplete="cc-exp" required>
                                                                        </div>

                                                                        <div>
                                                                            <label for="cvv-{{ $loan->id }}" class="form-label">Kod CVV</label>
                                                                            <input type="text" id="cvv-{{ $loan->id }}" name="cvv"
                                                                                   class="form-control" pattern="\d{3}" inputmode="numeric"
                                                                                   autocomplete="cc-csc" required
                                                                                   aria-describedby="cvv-hint-{{ $loan->id }}">
                                                                            <span class="rv-field-hint" id="cvv-hint-{{ $loan->id }}">3 cyfry z tyłu karty.</span>
                                                                        </div>

                                                                        <button type="submit" class="btn btn-primary btn-sm">Zapłać</button>
                                                                    </div>
                                                                </fieldset>
                                                            </form>
                                                        @endif

                                                        {{-- Recommend --}}
                                                        <button type="button" class="btn btn-success btn-sm"
                                                                aria-expanded="false" aria-controls="recommendation-form-{{ $loan->id }}"
                                                                onclick="toggleRecommendationForm({{ $loan->id }}, this)">
                                                            Poleć film<span class="visually-hidden"> {{ $movie->title }} znajomemu</span>
                                                        </button>

                                                        <div id="recommendation-form-{{ $loan->id }}" hidden>
                                                            @if ($friends->isEmpty())
                                                                <p class="rv-text-muted rv-text-sm mb-0">
                                                                    Nie masz znajomych, którym możesz polecić film.
                                                                </p>
                                                            @else
                                                                <form action="{{ route('movies.recommend', $movie->id) }}" method="POST"
                                                                      class="rv-stack" style="gap: var(--rv-space-2);">
                                                                    @csrf
                                                                    <label for="friend_id-{{ $loan->id }}" class="form-label">Wybierz znajomego</label>
                                                                    <select name="friend_id" id="friend_id-{{ $loan->id }}" class="form-select" required>
                                                                        @foreach ($friends as $friend)
                                                                            <option value="{{ $friend->id }}">{{ $friend->email }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <button type="submit" class="btn btn-primary btn-sm">Poleć</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($loans->hasPages())
                            <nav class="mt-4 d-flex justify-content-center" aria-label="Paginacja listy wypożyczeń">
                                {{ $loans->onEachSide(1)->links('vendor.pagination.rentalvod') }}
                            </nav>
                        @endif
                    @endif
                </section>

                {{-- Recommendations --}}
                <section class="rv-section" aria-labelledby="recommendations-heading">
                    <div class="rv-section-title">
                        <h2 id="recommendations-heading">Polecone filmy</h2>
                    </div>

                    @if ($recommendations->isEmpty())
                        <div class="rv-empty">
                            <h3>Brak poleconych filmów</h3>
                            <p>Gdy znajomi polecą Ci film, pojawi się on w tym miejscu.</p>
                        </div>
                    @else
                        <div class="table-responsive" tabindex="0">
                            <table class="table">
                                <caption>Filmy polecone Ci przez znajomych.</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Plakat</th>
                                        <th scope="col">Film</th>
                                        <th scope="col">Polecający</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recommendations as $recommendation)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('storage/' . $recommendation->movie->img_path) }}"
                                                     alt="Plakat filmu {{ $recommendation->movie->title }}"
                                                     width="60" height="90"
                                                     style="object-fit: cover; border-radius: var(--rv-radius-sm);"
                                                     loading="lazy">
                                            </td>
                                            <th scope="row" style="font-weight: var(--rv-weight-semibold);">
                                                {{ $recommendation->movie->title }}
                                            </th>
                                            <td>{{ $recommendation->user->email }}</td>
                                            <td>{{ $recommendation->status }}</td>
                                            <td>
                                                <a href="{{ route('movies.show', $recommendation->movie->id) }}" class="btn btn-primary btn-sm">
                                                    Zobacz film<span class="visually-hidden"> {{ $recommendation->movie->title }}</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            @endif
        @else
            <div class="rv-empty">
                <h1>Zaloguj się, aby zobaczyć profil</h1>
                <p>Profil jest dostępny tylko dla zalogowanych użytkowników.</p>
                <a href="{{ route('login') }}" class="btn custom-btn">Zaloguj się</a>
            </div>
        @endif
    </main>

    @include('layouts.footer', ['fixedBottom' => false])

    @auth
        @if (Auth::user()->role_id != 1)
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Server-supplied data for profile.js.
                window.rvProfileData = {
                    expenses: @json($expensesData),
                    searchUsersUrl: @json(url('/search-users')),
                    pointsMessage: @json(session('points_message')),
                };
            </script>
            <script defer src="{{ asset('js/profile.js') }}"></script>
        @endif
    @endauth
</body>

</html>
