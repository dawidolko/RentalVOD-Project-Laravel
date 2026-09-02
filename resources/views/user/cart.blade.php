@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Koszyk - RentalVOD',
    'metaDescription' => 'Twój koszyk w RentalVOD. Ustal daty wypożyczenia i przejdź do płatności.',
    'robots' => 'noindex, nofollow',
])

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [['label' => 'Koszyk']]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        @if (Auth::check())
            @php
                $user = Auth::user();
                $loyaltyPoints = $user->loyaltyPoints->points ?? 0;
                $canRentForFree = $loyaltyPoints >= 50;
                $cart = session('cart', []);
                $total = 0;
            @endphp

            <header class="rv-page-header">
                <h1>Twój koszyk</h1>
                <p>
                    Masz {{ $loyaltyPoints }}
                    {{ $loyaltyPoints === 1 ? 'punkt' : 'punktów' }} lojalnościowych.
                </p>
            </header>

            @include('layouts.success')
            @include('layouts.errors')

            @if (count($cart) > 0)
                <section aria-labelledby="cart-items-heading">
                    <h2 id="cart-items-heading" class="visually-hidden">Pozycje w koszyku</h2>

                    <div class="table-responsive" tabindex="0">
                        <table class="table table-hover">
                            <caption>
                                Filmy dodane do koszyka wraz z okresem wypożyczenia i kosztem całkowitym.
                            </caption>
                            <thead>
                                <tr>
                                    <th scope="col">Plakat</th>
                                    <th scope="col">Tytuł filmu</th>
                                    <th scope="col">Cena za dzień</th>
                                    <th scope="col">Data rozpoczęcia</th>
                                    <th scope="col">Data zakończenia</th>
                                    <th scope="col">Całkowity koszt</th>
                                    <th scope="col">Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $id => $details)
                                    @php
                                        $startDate = new DateTime($details['start'] ?? now());
                                        $endDate = new DateTime($details['end'] ?? now());
                                        $diff = $startDate > $endDate ? 0 : $startDate->diff($endDate)->days + 1;
                                        $subTotal = $details['price'] * $diff;
                                        $total += $subTotal;
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ asset($details['image']) }}"
                                                 alt="Plakat filmu {{ $details['name'] }}"
                                                 width="60" height="90"
                                                 style="object-fit: cover; border-radius: var(--rv-radius-sm);">
                                        </td>
                                        <th scope="row" style="font-weight: var(--rv-weight-semibold);">
                                            {{ $details['name'] }}
                                        </th>
                                        <td class="price-per-day">{{ $details['price'] }} zł</td>
                                        <td>
                                            <label for="start-{{ $id }}" class="visually-hidden">
                                                Data rozpoczęcia wypożyczenia filmu {{ $details['name'] }}
                                            </label>
                                            <input type="date" id="start-{{ $id }}" form="update-form-{{ $id }}"
                                                   name="start" class="date-input form-control"
                                                   value="{{ $details['start'] ?? '' }}">
                                        </td>
                                        <td>
                                            <label for="end-{{ $id }}" class="visually-hidden">
                                                Data zakończenia wypożyczenia filmu {{ $details['name'] }}
                                            </label>
                                            <input type="date" id="end-{{ $id }}" form="update-form-{{ $id }}"
                                                   name="end" class="date-input form-control"
                                                   value="{{ $details['end'] ?? '' }}">
                                        </td>
                                        <td class="total-cost">{{ number_format($subTotal, 2) }} zł</td>
                                        <td>
                                            <div class="rv-cluster">
                                                <form id="update-form-{{ $id }}"
                                                      action="{{ route('cart.update', ['movie_id' => $id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        Aktualizuj<span class="visually-hidden"> daty filmu {{ $details['name'] }}</span>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('cart.remove', $id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        Usuń<span class="visually-hidden"> film {{ $details['name'] }} z koszyka</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="mt-4" style="font-size: var(--rv-text-xl); font-weight: var(--rv-weight-bold);">
                        <strong id="total-display" aria-live="polite">Razem: {{ number_format($total, 2) }} zł</strong>
                    </p>
                </section>

                <section class="rv-section" aria-labelledby="checkout-heading">
                    <h2 id="checkout-heading">Podsumowanie i płatność</h2>

                    @if ($canRentForFree)
                        <div class="alert alert-info" role="status">
                            <span class="rv-alert-title">Możesz wypożyczyć za punkty</span>
                            Masz wystarczająco punktów, aby wypożyczyć film za darmo. Przy płatności zostanie odjęte 50 punktów.
                        </div>
                        <form action="{{ route('checkout') }}" method="post">
                            @csrf
                            <input type="hidden" name="usePoints" value="1">
                            <button type="submit" class="btn btn-success">Wypożycz za punkty</button>
                        </form>
                    @else
                        <button id="checkout-button" type="button" class="btn custom-btn">
                            Przejdź do płatności
                        </button>

                        <div class="payment-section card mt-4" id="payment-section" style="display: none;">
                            <div class="card-body" style="padding: var(--rv-space-5);">
                                <h3>Informacje o płatności</h3>
                                <form action="{{ route('checkout') }}" method="post" class="rv-stack">
                                    @csrf
                                    <input type="hidden" name="total" value="{{ $total }}">

                                    <div>
                                        <label for="cardNumber" class="form-label">Numer karty</label>
                                        <input type="text" id="cardNumber" name="cardNumber" required
                                               pattern="\d{16}" inputmode="numeric" autocomplete="cc-number"
                                               class="form-control" aria-describedby="cardNumber-hint">
                                        <span class="rv-field-hint" id="cardNumber-hint">16 cyfr, bez spacji.</span>
                                    </div>

                                    <div>
                                        <label for="cvv" class="form-label">Kod CVV</label>
                                        <input type="text" id="cvv" name="cvv" required
                                               pattern="\d{3}" inputmode="numeric" autocomplete="cc-csc"
                                               class="form-control" aria-describedby="cvv-hint">
                                        <span class="rv-field-hint" id="cvv-hint">3 cyfry z tyłu karty.</span>
                                    </div>

                                    <div>
                                        <label for="expiryDate" class="form-label">Data ważności</label>
                                        <input type="month" id="expiryDate" name="expiryDate" required
                                               autocomplete="cc-exp" class="form-control">
                                    </div>

                                    <button type="submit" class="btn custom-btn">Zapłać {{ number_format($total, 2) }} zł</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </section>
            @else
                <div class="rv-empty">
                    <h2>Twój koszyk jest pusty</h2>
                    <p>Nie dodałeś jeszcze żadnego filmu. Przejrzyj katalog i wybierz coś dla siebie.</p>
                    <a href="{{ route('movies.index') }}" class="btn custom-btn">Przeglądaj filmy</a>
                </div>
            @endif
        @else
            <div class="rv-empty">
                <h1>Zaloguj się, aby zobaczyć koszyk</h1>
                <p>Koszyk jest dostępny tylko dla zalogowanych użytkowników.</p>
                <a href="{{ route('login') }}" class="btn custom-btn">Zaloguj się</a>
            </div>
        @endif
    </main>

    @include('layouts.footer', ['fixedBottom' => false])
    <script defer src="{{ asset('js/cart.js') }}"></script>
</body>

</html>
