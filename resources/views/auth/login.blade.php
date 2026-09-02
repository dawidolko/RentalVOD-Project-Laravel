@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Logowanie - RentalVOD',
    'metaDescription' => 'Zaloguj się do swojego konta RentalVOD, aby wypożyczać filmy i korzystać z punktów lojalnościowych.',
    'robots' => 'noindex, follow',
    'canonical' => route('login'),
])

<body>
    @include('layouts.navbar')

    <main id="main-content" class="rv-page">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <header class="text-center mb-4">
                    <img src="{{ asset('storage/img/logo.webp') }}" alt="" width="120" height="120" style="max-width: 120px; height: auto;">
                    <h1 class="mt-3">Zaloguj się</h1>
                    <p class="rv-text-muted">Wpisz dane swojego konta, aby kontynuować.</p>
                </header>

                @include('layouts.success')
                @include('layouts.errors')

                <div class="card">
                    <div class="card-body" style="padding: var(--rv-space-5);">
                        <form method="POST" action="{{ route('login.authenticate') }}" class="rv-stack">
                            @csrf

                            <div>
                                <label for="email" class="form-label">Adres e-mail</label>
                                <input id="email" name="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       autocomplete="email"
                                       required
                                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                                @include('layouts.field-error', ['field' => 'email'])
                            </div>

                            <div>
                                <label for="password" class="form-label">Hasło</label>
                                <input id="password" name="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       autocomplete="current-password"
                                       required
                                       @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                                @include('layouts.field-error', ['field' => 'password'])
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" @checked(old('remember'))>
                                <label class="form-check-label" for="remember">Zapamiętaj mnie</label>
                            </div>

                            <button class="btn custom-btn w-100" type="submit">Zaloguj się</button>
                        </form>
                    </div>
                </div>

                <p class="text-center mt-4">
                    Nie masz konta? <a href="{{ route('register') }}">Zarejestruj się</a>
                </p>
            </div>
        </div>
    </main>

    @include('layouts.footer', ['fixedBottom' => false])
</body>

</html>
