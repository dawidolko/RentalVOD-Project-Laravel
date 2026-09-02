{{--
    Shared error page shell.

    Expects:
      $code        - HTTP status code
      $heading     - short human explanation (Polish)
      $body        - longer guidance paragraph
      $exception   - Laravel's exception instance (optional)
--}}
@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => $code . ' - ' . $heading . ' - RentalVOD',
    'metaDescription' => $heading . ' Wróć na stronę główną RentalVOD lub przejrzyj katalog filmów.',
    // Error pages must never be indexed.
    'robots' => 'noindex, nofollow',
])

<body>
    @include('layouts.navbar')

    <main id="main-content" class="rv-page">
        @include('layouts.error')

        <div class="rv-error">
            {{-- The code is decorative typography; the <h1> carries the meaning. --}}
            <p class="rv-error-code" aria-hidden="true">{{ $code }}</p>

            <h1>{{ $heading }}</h1>

            <p>{{ $body }}</p>

            @if (App::environment('local') && isset($exception) && $exception->getMessage())
                <p class="rv-error-detail">
                    <strong>Szczegóły (tylko środowisko lokalne):</strong>
                    {{ $exception->getMessage() }}
                </p>
            @endif

            <div class="rv-error-actions">
                <a href="{{ url('/') }}" class="btn custom-btn">
                    <i class="bi bi-house" aria-hidden="true"></i>
                    Wróć na stronę główną
                </a>
                <a href="{{ route('movies.index') }}" class="btn btn-secondary">
                    <i class="bi bi-film" aria-hidden="true"></i>
                    Przeglądaj filmy
                </a>
                <a href="mailto:rentalVOD@contact.com" class="btn btn-secondary">
                    <i class="bi bi-envelope" aria-hidden="true"></i>
                    Skontaktuj się z nami
                </a>
            </div>
        </div>

        @include('layouts.errors')
    </main>

    @include('layouts.footer', ['fixedBottom' => false])
</body>

</html>
