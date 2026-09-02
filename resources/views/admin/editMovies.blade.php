@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Edycja filmów - panel administratora - RentalVOD',
    'robots' => 'noindex, nofollow',
])

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [
            ['label' => 'Panel administratora'],
            ['label' => 'Filmy'],
        ]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        @include('layouts.success')
        @include('layouts.errors')

        <header class="rv-page-header">
            <h1>Wszystkie filmy</h1>
            <p>Dodawaj, edytuj i usuwaj pozycje z katalogu oraz ustawiaj ceny promocyjne.</p>
        </header>

        <div class="rv-cluster mb-4">
            <button type="button" class="btn custom-btn" aria-expanded="false" aria-controls="add-panel-movie"
                    onclick="toggleAddPanel(event, 'movie', this)">
                Dodaj film
            </button>
            <button type="button" class="btn custom-btn" aria-expanded="false" aria-controls="add-panel-category"
                    onclick="toggleAddPanel(event, 'category', this)">
                Dodaj kategorię
            </button>
        </div>

        {{-- Add movie --}}
        <section id="add-panel-movie" class="card mb-4" hidden aria-labelledby="add-movie-heading">
            <div class="card-body" style="padding: var(--rv-space-5);">
                <h2 id="add-movie-heading" style="font-size: var(--rv-text-xl);">Nowy film</h2>

                <form action="{{ route('admin.addMovie') }}" method="POST" enctype="multipart/form-data" class="rv-stack mt-3">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="new_title" class="form-label">Tytuł</label>
                            <input type="text" class="form-control" id="new_title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="new_category_id" class="form-label">Kategoria</label>
                            <select class="form-select" id="new_category_id" name="category_id" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->species }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="new_description" class="form-label">Opis</label>
                            <textarea class="form-control" id="new_description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="new_director" class="form-label">Reżyser</label>
                            <input type="text" class="form-control" id="new_director" name="director" value="{{ old('director') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="new_release_year" class="form-label">Rok produkcji</label>
                            <input type="number" class="form-control" id="new_release_year" name="release_year"
                                   min="1888" max="2100" value="{{ old('release_year') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="new_duration" class="form-label">Czas filmu (minuty)</label>
                            <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                   id="new_duration" name="duration" min="1" value="{{ old('duration') }}" required
                                   aria-describedby="new_duration-hint @error('duration') duration-error @enderror">
                            <span class="rv-field-hint" id="new_duration-hint">Liczba całkowita, np. 128.</span>
                            @include('layouts.field-error', ['field' => 'duration'])
                        </div>

                        <div class="col-md-6">
                            <label for="new_rate" class="form-label">Ocena</label>
                            <input type="number" step="0.01" min="0" max="10" class="form-control"
                                   id="new_rate" name="rate" value="{{ old('rate') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="new_img_path" class="form-label">Plakat</label>
                            <input type="file" class="form-control" id="new_img_path" name="img_path" accept="image/*" required>
                        </div>

                        <div class="col-md-6">
                            <label for="new_video_path" class="form-label">Identyfikator filmu w YouTube</label>
                            <input type="text" class="form-control" id="new_video_path" name="video_path"
                                   value="{{ old('video_path') }}" required aria-describedby="new_video_path-hint">
                            <span class="rv-field-hint" id="new_video_path-hint">Sam identyfikator, np. dQw4w9WgXcQ.</span>
                        </div>

                        <div class="col-md-6">
                            <label for="new_price_day" class="form-label">Cena za dzień (zł)</label>
                            <input type="number" step="0.01" min="0" class="form-control"
                                   id="new_price_day" name="price_day" value="{{ old('price_day') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="new_available" class="form-label">Dostępność</label>
                            <select class="form-select" id="new_available" name="available" required>
                                <option value="dostępny">Dostępny</option>
                                <option value="niedostępny">Niedostępny</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn custom-btn">Dodaj film</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- Add category --}}
        <section id="add-panel-category" class="card mb-4" hidden aria-labelledby="add-category-heading">
            <div class="card-body" style="padding: var(--rv-space-5);">
                <h2 id="add-category-heading" style="font-size: var(--rv-text-xl);">Nowa kategoria</h2>

                <form action="{{ route('admin.addCategory') }}" method="POST" class="rv-stack mt-3">
                    @csrf
                    <div>
                        <label for="genre" class="form-label">Nazwa kategorii</label>
                        <input type="text" class="form-control" id="genre" name="genre" value="{{ old('genre') }}" required>
                    </div>
                    <button type="submit" class="btn custom-btn align-self-start">Dodaj kategorię</button>
                </form>
            </div>
        </section>

        @if ($movies->isEmpty())
            <div class="rv-empty">
                <h2>Brak filmów w katalogu</h2>
                <p>Dodaj pierwszy film, aby pojawił się w serwisie.</p>
            </div>
        @else
            <div class="table-responsive" tabindex="0">
                <table class="table table-striped table-hover">
                    <caption>Katalog filmów z możliwością edycji, usunięcia i ustawienia ceny promocyjnej.</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Plakat</th>
                            <th scope="col">Tytuł</th>
                            <th scope="col">Kategoria</th>
                            <th scope="col">Reżyser</th>
                            <th scope="col">Rok</th>
                            <th scope="col">Czas</th>
                            <th scope="col">Ocena</th>
                            <th scope="col">Cena/dzień</th>
                            <th scope="col">Dostępność</th>
                            <th scope="col">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movies as $movie)
                            <tr>
                                <th scope="row">{{ $movie->id }}</th>
                                <td>
                                    <img src="{{ asset('storage/' . $movie->img_path) }}"
                                         alt="Plakat filmu {{ $movie->title }}"
                                         width="60" height="90"
                                         style="object-fit: cover; border-radius: var(--rv-radius-sm);" loading="lazy">
                                </td>
                                <td>{{ $movie->title }}</td>
                                <td>{{ $movie->category->species ?? $movie->category_id }}</td>
                                <td>{{ $movie->director }}</td>
                                <td><time datetime="{{ $movie->release_year }}">{{ $movie->release_year }}</time></td>
                                <td>{{ $movie->duration }} min</td>
                                <td>{{ $movie->rate }}</td>
                                <td>{{ $movie->price_day }} zł</td>
                                <td>{{ $movie->available }}</td>
                                <td>
                                    <div class="rv-stack" style="gap: var(--rv-space-2);">
                                        <button type="button" class="btn btn-success btn-sm"
                                                aria-expanded="false" aria-controls="edit-panel-{{ $movie->id }}"
                                                onclick="openEditPanel({{ $movie->id }}, this)">
                                            Edytuj<span class="visually-hidden"> film {{ $movie->title }}</span>
                                        </button>

                                        <form action="{{ route('admin.deleteMovie', ['id' => $movie->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100"
                                                    onclick="return confirm('Czy na pewno chcesz usunąć film {{ $movie->title }}?')">
                                                Usuń<span class="visually-hidden"> film {{ $movie->title }}</span>
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-warning btn-sm"
                                                aria-expanded="false" aria-controls="promo-slider-{{ $movie->id }}"
                                                onclick="togglePromoSlider({{ $movie->id }}, this)">
                                            Promocja<span class="visually-hidden"> dla filmu {{ $movie->title }}</span>
                                        </button>

                                        <div id="promo-slider-{{ $movie->id }}" hidden>
                                            <form action="{{ route('movies.setSuperPromoPrice', ['id' => $movie->id]) }}" method="POST" class="rv-stack" style="gap: var(--rv-space-2);">
                                                @csrf
                                                <label for="promo-input-{{ $movie->id }}" class="form-label">
                                                    Cena promocyjna filmu {{ $movie->title }} (zł)
                                                </label>
                                                {{-- A number input rather than a bare range: it is keyboard
                                                     operable, announces its value, and still posts the same
                                                     super_promo_price field. --}}
                                                <input type="number" id="promo-input-{{ $movie->id }}"
                                                       name="super_promo_price" class="form-control"
                                                       min="0" max="{{ $movie->price_day }}" step="0.01"
                                                       value="{{ $movie->super_promo_price ?? $movie->price_day }}"
                                                       aria-describedby="promo-hint-{{ $movie->id }}">
                                                <span class="rv-field-hint" id="promo-hint-{{ $movie->id }}">
                                                    Maksymalnie {{ $movie->price_day }} zł.
                                                </span>
                                                <button type="submit" class="btn btn-primary btn-sm">Zapisz</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr id="edit-panel-{{ $movie->id }}" hidden>
                                <td colspan="11">
                                    <form id="edit-form-{{ $movie->id }}" method="POST"
                                          action="{{ route('admin.updateMovie', ['id' => $movie->id]) }}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <fieldset>
                                            {{-- Every id below is suffixed with the movie id: the panels are
                                                 rendered once per row, so unshared ids would collide and
                                                 point every label at the first film's fields. --}}
                                            <legend>Edycja filmu {{ $movie->title }}</legend>

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <label for="title-{{ $movie->id }}" class="form-label">Tytuł</label>
                                                    <input type="text" class="form-control" id="title-{{ $movie->id }}"
                                                           name="title" value="{{ $movie->title }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="category_name-{{ $movie->id }}" class="form-label">Kategoria</label>
                                                    <select class="form-select" id="category_name-{{ $movie->id }}" name="category_id" required>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" @selected($movie->category_id == $category->id)>
                                                                {{ $category->species }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12">
                                                    <label for="description-{{ $movie->id }}" class="form-label">Opis</label>
                                                    <textarea class="form-control" id="description-{{ $movie->id }}"
                                                              name="description" rows="4" required>{{ $movie->description }}</textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="director-{{ $movie->id }}" class="form-label">Reżyser</label>
                                                    <input type="text" class="form-control" id="director-{{ $movie->id }}"
                                                           name="director" value="{{ $movie->director }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="release_year-{{ $movie->id }}" class="form-label">Rok produkcji</label>
                                                    <input type="number" class="form-control" id="release_year-{{ $movie->id }}"
                                                           name="release_year" min="1888" max="2100"
                                                           value="{{ $movie->release_year }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="duration-{{ $movie->id }}" class="form-label">Czas filmu (minuty)</label>
                                                    <input type="number" class="form-control" id="duration-{{ $movie->id }}"
                                                           name="duration" min="1" value="{{ $movie->duration }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="rate-{{ $movie->id }}" class="form-label">Ocena</label>
                                                    <input type="number" step="0.01" min="0" max="10" class="form-control"
                                                           id="rate-{{ $movie->id }}" name="rate" value="{{ $movie->rate }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="video_path-{{ $movie->id }}" class="form-label">Identyfikator filmu w YouTube</label>
                                                    <input type="text" class="form-control" id="video_path-{{ $movie->id }}"
                                                           name="video_path" value="{{ $movie->video_path }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="price_day-{{ $movie->id }}" class="form-label">Cena za dzień (zł)</label>
                                                    <input type="number" step="0.01" min="0" class="form-control"
                                                           id="price_day-{{ $movie->id }}" name="price_day"
                                                           value="{{ $movie->price_day }}" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="available-{{ $movie->id }}" class="form-label">Dostępność</label>
                                                    <select class="form-select" id="available-{{ $movie->id }}" name="available" required>
                                                        <option value="dostępny" @selected($movie->available == 'dostępny')>Dostępny</option>
                                                        <option value="niedostępny" @selected($movie->available == 'niedostępny')>Niedostępny</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="img_path-{{ $movie->id }}" class="form-label">Nowy plakat (opcjonalnie)</label>
                                                    <input type="file" class="form-control @error('img_path') is-invalid @enderror"
                                                           id="img_path-{{ $movie->id }}" name="img_path" accept="image/*"
                                                           @error('img_path') aria-invalid="true" aria-describedby="img_path-error" @enderror>
                                                    @include('layouts.field-error', ['field' => 'img_path'])
                                                </div>

                                                <div class="col-12 rv-cluster">
                                                    <button type="submit" class="btn custom-btn">Zapisz zmiany</button>
                                                    <button type="button" class="btn btn-secondary"
                                                            onclick="closeEditPanel({{ $movie->id }})">Anuluj</button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($movies->hasPages())
                <nav class="mt-4 d-flex justify-content-center" aria-label="Paginacja listy filmów">
                    {{ $movies->onEachSide(1)->links('vendor.pagination.rentalvod') }}
                </nav>
            @endif
        @endif
    </main>

    @include('layouts.footer', ['fixedBottom' => false])
    <script defer src="{{ asset('js/admin.js') }}"></script>
</body>

</html>
