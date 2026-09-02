@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Oglądaj: ' . $movie->title . ' - RentalVOD',
    'metaDescription' => 'Odtwarzacz wypożyczonego filmu ' . $movie->title . ' w serwisie RentalVOD.',
    'robots' => 'noindex, nofollow',
])

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [
            ['label' => 'Mój profil', 'url' => route('user.profile')],
            ['label' => $movie->title],
        ]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        <article class="card">
            <div class="card-body" style="padding: var(--rv-space-5);">
                <h1>{{ $movie->title }}</h1>

                <div class="video-container">
                    @if ($movie->video_path)
                        <iframe src="https://www.youtube.com/embed/{{ $movie->video_path }}?rel=0"
                                title="Odtwarzacz filmu {{ $movie->title }}"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    @else
                        <div class="rv-empty" style="height: 100%;">
                            <h2>Film niedostępny</h2>
                            <p>Nagranie tego filmu nie jest w tej chwili dostępne. Skontaktuj się z obsługą, jeśli problem się powtarza.</p>
                        </div>
                    @endif
                </div>

                <section aria-labelledby="loan-description-heading">
                    <h2 id="loan-description-heading">Opis</h2>
                    <p>{{ $movie->description }}</p>
                </section>
            </div>
        </article>
    </main>

    @include('layouts.footer', ['fixedBottom' => false])
</body>

</html>
