@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Ustawienia serwisu - panel administratora - RentalVOD',
    'robots' => 'noindex, nofollow',
])

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [
            ['label' => 'Panel administratora'],
            ['label' => 'Ustawienia'],
        ]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        @include('layouts.success')
        @include('layouts.errors')

        <header class="rv-page-header">
            <h1>Ustawienia serwisu</h1>
            <p>Skonfiguruj zasady rekomendacji filmów oraz globalne promocje.</p>
        </header>

        <div class="row g-5">
            <div class="col-lg-7">
                <section class="card h-100" aria-labelledby="recommendations-heading">
                    <div class="card-body" style="padding: var(--rv-space-5);">
                        <h2 id="recommendations-heading" style="font-size: var(--rv-text-xl);">Zasady rekomendacji</h2>
                        <p class="rv-text-muted">Film trafia do sekcji rekomendowanych, gdy spełnia oba poniższe warunki.</p>

                        <form action="{{ route('admin.updateRule') }}" method="POST" class="rv-stack mt-3">
                            @csrf

                            <div>
                                <label for="rate" class="form-label">Minimalna ocena filmu</label>
                                <input type="number" id="rate" name="rate"
                                       class="form-control @error('rate') is-invalid @enderror"
                                       value="{{ old('rate', $currentRate) }}"
                                       min="0" max="10" step="0.1" required
                                       aria-describedby="rate-hint @error('rate') rate-error @enderror">
                                <span class="rv-field-hint" id="rate-hint">Wartość od 0 do 10, z dokładnością do 0,1.</span>
                                @include('layouts.field-error', ['field' => 'rate'])
                            </div>

                            <div>
                                <label for="recommendations_count" class="form-label">Minimalna liczba rekomendacji</label>
                                <input type="number" id="recommendations_count" name="recommendations_count"
                                       class="form-control @error('recommendations_count') is-invalid @enderror"
                                       value="{{ old('recommendations_count', $currentRecommendationsCount) }}"
                                       min="0" max="1000" required
                                       aria-describedby="recommendations_count-hint @error('recommendations_count') recommendations_count-error @enderror">
                                <span class="rv-field-hint" id="recommendations_count-hint">Ile razy film musi zostać polecony przez użytkowników.</span>
                                @include('layouts.field-error', ['field' => 'recommendations_count'])
                            </div>

                            <button type="submit" class="btn custom-btn align-self-start">Zaktualizuj zasadę</button>
                        </form>
                    </div>
                </section>
            </div>

            <div class="col-lg-5">
                <section class="card h-100" aria-labelledby="promotions-heading">
                    <div class="card-body" style="padding: var(--rv-space-5);">
                        <h2 id="promotions-heading" style="font-size: var(--rv-text-xl);">Promocje</h2>

                        <p role="status">
                            Promocje są obecnie
                            <strong>{{ $promotionsEnabled ? 'włączone' : 'wyłączone' }}</strong>.
                        </p>

                        <form action="{{ route('admin.togglePromotions') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn custom-btn">
                                {{ $promotionsEnabled ? 'Wyłącz promocje' : 'Włącz promocje' }}
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>

    @include('layouts.footer', ['fixedBottom' => false])
</body>

</html>
