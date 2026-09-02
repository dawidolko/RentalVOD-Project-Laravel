@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Rejestracja - RentalVOD',
    'metaDescription' => 'Załóż darmowe konto w RentalVOD i zacznij wypożyczać filmy online. Za rejestrację z kodem polecającym otrzymasz punkty lojalnościowe.',
    'robots' => 'noindex, follow',
    'canonical' => route('register'),
])

<body>
    @include('layouts.navbar')

    <main id="main-content" class="rv-page">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <header class="text-center mb-4">
                    <img src="{{ asset('storage/img/logo.webp') }}" alt="" width="120" height="120" style="max-width: 120px; height: auto;">
                    <h1 class="mt-3">Zarejestruj się</h1>
                    <p class="rv-text-muted">Wszystkie pola oprócz kodu polecającego są wymagane.</p>
                </header>

                @include('layouts.success')
                @include('layouts.errors')

                <div class="card">
                    <div class="card-body" style="padding: var(--rv-space-5);">
                        <form method="POST" action="{{ route('register') }}" class="rv-stack" id="registerForm">
                            @csrf

                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label for="first_name" class="form-label">Imię</label>
                                    <input id="first_name" name="first_name" type="text"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           value="{{ old('first_name') }}"
                                           autocomplete="given-name" required
                                           @error('first_name') aria-invalid="true" aria-describedby="first_name-error" @enderror>
                                    @include('layouts.field-error', ['field' => 'first_name'])
                                </div>

                                <div class="col-sm-6">
                                    <label for="last_name" class="form-label">Nazwisko</label>
                                    <input id="last_name" name="last_name" type="text"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           value="{{ old('last_name') }}"
                                           autocomplete="family-name" required
                                           @error('last_name') aria-invalid="true" aria-describedby="last_name-error" @enderror>
                                    @include('layouts.field-error', ['field' => 'last_name'])
                                </div>
                            </div>

                            <div>
                                <label for="email" class="form-label">Adres e-mail</label>
                                <input id="email" name="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       autocomplete="email" required
                                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                                @include('layouts.field-error', ['field' => 'email'])
                            </div>

                            <div>
                                <label for="address" class="form-label">Adres zamieszkania</label>
                                <input id="address" name="address" type="text"
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address') }}"
                                       autocomplete="street-address" required
                                       @error('address') aria-invalid="true" aria-describedby="address-error" @enderror>
                                @include('layouts.field-error', ['field' => 'address'])
                            </div>

                            <div>
                                <label for="password" class="form-label">Hasło</label>
                                <input id="password" name="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       autocomplete="new-password" required
                                       aria-describedby="password-hint @error('password') password-error @enderror">
                                <span class="rv-field-hint" id="password-hint">Minimum 8 znaków.</span>
                                @include('layouts.field-error', ['field' => 'password'])
                            </div>

                            <div>
                                <label for="password_confirmation" class="form-label">Potwierdź hasło</label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                       class="form-control" autocomplete="new-password" required
                                       aria-describedby="password_confirmation-mismatch">
                                <p class="rv-field-error" id="password_confirmation-mismatch" hidden>
                                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                                    <span class="visually-hidden">Błąd:</span>
                                    Hasła nie są identyczne.
                                </p>
                            </div>

                            <div>
                                <label for="referral_code" class="form-label">Kod polecający (opcjonalnie)</label>
                                <input id="referral_code" name="referral_code" type="text"
                                       class="form-control @error('referral_code') is-invalid @enderror"
                                       value="{{ old('referral_code') }}"
                                       aria-describedby="referral-hint @error('referral_code') referral_code-error @enderror">
                                <span class="rv-field-hint" id="referral-hint">Podaj kod znajomego, aby otrzymać 20 punktów lojalnościowych.</span>
                                @include('layouts.field-error', ['field' => 'referral_code'])
                            </div>

                            <button class="btn custom-btn w-100" type="submit">Zarejestruj się</button>
                        </form>
                    </div>
                </div>

                <p class="text-center mt-4">
                    Masz już konto? <a href="{{ route('login') }}">Zaloguj się</a>
                </p>
            </div>
        </div>
    </main>

    @include('layouts.footer', ['fixedBottom' => false])
    <script defer src="{{ asset('js/register.js') }}"></script>
</body>

</html>
