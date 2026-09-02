@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Ustawienia konta - RentalVOD',
    'metaDescription' => 'Zarządzaj swoim kontem RentalVOD: zmień adres, awatar i hasło.',
    'robots' => 'noindex, nofollow',
])

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [
            ['label' => 'Mój profil', 'url' => route('user.profile')],
            ['label' => 'Ustawienia konta'],
        ]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        @if (Auth::check())
            <header class="rv-page-header">
                <h1>Ustawienia konta</h1>
                <p>Zaktualizuj swoje dane. Każdą sekcję zapisujesz osobno.</p>
            </header>

            @include('layouts.session-error')
            @include('layouts.success')
            @include('layouts.errors')

            <div class="row g-5">
                <div class="col-lg-6">
                    <section class="card h-100" aria-labelledby="address-heading">
                        <div class="card-body" style="padding: var(--rv-space-5);">
                            <h2 id="address-heading" style="font-size: var(--rv-text-xl);">Adres zamieszkania</h2>

                            <form method="POST" action="{{ route('user.update') }}" class="rv-stack mt-3">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="address" class="form-label">Ulica i numer</label>
                                    <input id="address" name="address" type="text"
                                           class="form-control @error('address') is-invalid @enderror"
                                           value="{{ old('address', Auth::user()->address) }}"
                                           autocomplete="street-address" required
                                           @error('address') aria-invalid="true" aria-describedby="address-error" @enderror>
                                    @include('layouts.field-error', ['field' => 'address'])
                                </div>

                                <button type="submit" class="btn custom-btn">Zapisz zmiany adresu</button>
                            </form>
                        </div>
                    </section>
                </div>

                <div class="col-lg-6">
                    <section class="card h-100" aria-labelledby="avatar-heading">
                        <div class="card-body" style="padding: var(--rv-space-5);">
                            <h2 id="avatar-heading" style="font-size: var(--rv-text-xl);">Awatar</h2>

                            <form method="POST" action="{{ route('user.update_avatar') }}"
                                  enctype="multipart/form-data" class="rv-stack mt-3">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="avatar" class="form-label">Wybierz nowy plik</label>
                                    <input id="avatar" name="avatar" type="file" accept="image/*"
                                           class="form-control @error('avatar') is-invalid @enderror" required
                                           aria-describedby="avatar-hint @error('avatar') avatar-error @enderror">
                                    <span class="rv-field-hint" id="avatar-hint">Obraz w formacie JPG, PNG lub WEBP.</span>
                                    @include('layouts.field-error', ['field' => 'avatar'])
                                </div>

                                <button type="submit" class="btn custom-btn">Zaktualizuj awatar</button>
                            </form>
                        </div>
                    </section>
                </div>

                <div class="col-12">
                    <section class="card" aria-labelledby="password-heading">
                        <div class="card-body" style="padding: var(--rv-space-5);">
                            <h2 id="password-heading" style="font-size: var(--rv-text-xl);">Zmiana hasła</h2>

                            <form method="POST" action="{{ route('user.change_password') }}" class="rv-stack mt-3">
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label for="current_password" class="form-label">Obecne hasło</label>
                                        <input id="current_password" name="current_password" type="password"
                                               class="form-control @error('current_password') is-invalid @enderror"
                                               autocomplete="current-password" required
                                               @error('current_password') aria-invalid="true" aria-describedby="current_password-error" @enderror>
                                        @include('layouts.field-error', ['field' => 'current_password'])
                                    </div>

                                    <div class="col-md-4">
                                        <label for="new_password" class="form-label">Nowe hasło</label>
                                        <input id="new_password" name="new_password" type="password"
                                               class="form-control @error('new_password') is-invalid @enderror"
                                               autocomplete="new-password" required
                                               aria-describedby="new_password-hint @error('new_password') new_password-error @enderror">
                                        <span class="rv-field-hint" id="new_password-hint">Minimum 8 znaków.</span>
                                        @include('layouts.field-error', ['field' => 'new_password'])
                                    </div>

                                    <div class="col-md-4">
                                        <label for="new_password_confirmation" class="form-label">Potwierdź nowe hasło</label>
                                        <input id="new_password_confirmation" name="new_password_confirmation" type="password"
                                               class="form-control" autocomplete="new-password" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn custom-btn align-self-start">Zmień hasło</button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        @else
            <div class="rv-empty">
                <h1>Zaloguj się, aby zmienić ustawienia</h1>
                <p>Ustawienia konta są dostępne tylko dla zalogowanych użytkowników.</p>
                <a href="{{ route('login') }}" class="btn custom-btn">Zaloguj się</a>
            </div>
        @endif
    </main>

    @include('layouts.footer', ['fixedBottom' => false])
</body>

</html>
