@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Edycja użytkowników - panel administratora - RentalVOD',
    'robots' => 'noindex, nofollow',
])

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [
            ['label' => 'Panel administratora'],
            ['label' => 'Użytkownicy'],
        ]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        @include('layouts.success')
        @include('layouts.errors')

        <header class="rv-page-header">
            <h1>Wszyscy klienci</h1>
            <p>Edytuj dane użytkowników lub usuń konto z serwisu.</p>
        </header>

        @if ($users->isEmpty())
            <div class="rv-empty">
                <h2>Brak użytkowników</h2>
                <p>W serwisie nie ma jeszcze zarejestrowanych kont.</p>
            </div>
        @else
            <div class="table-responsive" tabindex="0">
                <table class="table table-striped table-hover">
                    <caption>Lista zarejestrowanych użytkowników z możliwością edycji i usunięcia konta.</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Awatar</th>
                            <th scope="col">Imię</th>
                            <th scope="col">Nazwisko</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Adres</th>
                            <th scope="col">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>
                                    <img src="{{ asset($user->avatar) }}"
                                         alt="Awatar użytkownika {{ $user->first_name }} {{ $user->last_name }}"
                                         width="40" height="40"
                                         style="border-radius: 50%; object-fit: cover;" loading="lazy">
                                </td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->address }}</td>
                                <td>
                                    <div class="rv-cluster">
                                        <button type="button" class="btn btn-success btn-sm"
                                                aria-expanded="false"
                                                aria-controls="edit-panel-{{ $user->id }}"
                                                onclick="toggleEditPanel('{{ $user->id }}', this)">
                                            Edytuj<span class="visually-hidden"> użytkownika {{ $user->first_name }} {{ $user->last_name }}</span>
                                        </button>

                                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Czy na pewno chcesz usunąć użytkownika {{ $user->first_name }} {{ $user->last_name }}?')">
                                                Usuń<span class="visually-hidden"> użytkownika {{ $user->first_name }} {{ $user->last_name }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <tr id="edit-panel-{{ $user->id }}" hidden>
                                <td colspan="7">
                                    <form action="{{ route('admin.updateUser', ['id' => $user->id]) }}" method="POST">
                                        @csrf
                                        <fieldset>
                                            <legend>Edycja użytkownika {{ $user->first_name }} {{ $user->last_name }}</legend>

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <label for="first_name-{{ $user->id }}" class="form-label">Imię</label>
                                                    <input type="text" class="form-control" id="first_name-{{ $user->id }}"
                                                           name="first_name" required value="{{ $user->first_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="last_name-{{ $user->id }}" class="form-label">Nazwisko</label>
                                                    <input type="text" class="form-control" id="last_name-{{ $user->id }}"
                                                           name="last_name" required value="{{ $user->last_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email-{{ $user->id }}" class="form-label">E-mail</label>
                                                    <input type="email" class="form-control" id="email-{{ $user->id }}"
                                                           name="email" required value="{{ $user->email }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="address-{{ $user->id }}" class="form-label">Adres</label>
                                                    <input type="text" class="form-control" id="address-{{ $user->id }}"
                                                           name="address" required value="{{ $user->address }}">
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="admin-{{ $user->id }}" name="admin" @checked($user->isAdmin())>
                                                        <label class="form-check-label" for="admin-{{ $user->id }}">
                                                            Konto administratora
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn custom-btn">Zapisz zmiany</button>
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

            @if ($users->hasPages())
                <nav class="mt-4 d-flex justify-content-center" aria-label="Paginacja listy użytkowników">
                    {{ $users->onEachSide(1)->links('vendor.pagination.rentalvod') }}
                </nav>
            @endif
        @endif
    </main>

    @include('layouts.footer', ['fixedBottom' => false])

    <script>
        // Toggles the inline edit row and keeps aria-expanded in sync with it.
        function toggleEditPanel(userId, trigger) {
            const editPanel = document.getElementById('edit-panel-' + userId);
            if (!editPanel) {
                return;
            }

            const willOpen = editPanel.hidden;
            editPanel.hidden = !willOpen;

            if (trigger) {
                trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            }

            if (willOpen) {
                const firstField = editPanel.querySelector('input, select, textarea');
                if (firstField) {
                    firstField.focus();
                }
            }
        }
    </script>
</body>

</html>
