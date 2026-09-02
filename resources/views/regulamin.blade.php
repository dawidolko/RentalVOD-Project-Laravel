@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Regulamin - RentalVOD',
    'metaDescription' => 'Regulamin wypożyczalni filmów online RentalVOD: zasady rejestracji, wypożyczeń, opłat, zwrotów oraz programu punktów lojalnościowych.',
    'canonical' => route('regulamin'),
])

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [['label' => 'Regulamin']]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        <header class="rv-page-header">
            <h1>Regulamin</h1>
            <p>
                Zasady korzystania z serwisu RentalVOD. Ostatnia aktualizacja:
                <time datetime="2024-01-01">1 stycznia 2024</time>.
            </p>
        </header>

        <nav aria-labelledby="toc-heading" class="card mb-5">
            <div class="card-body">
                <h2 id="toc-heading" style="font-size: var(--rv-text-lg);">Spis treści</h2>
                <ol class="rv-stack" style="gap: var(--rv-space-2); margin-bottom: 0; padding-left: var(--rv-space-5);">
                    <li><a href="#rozdzial-1">Rejestracja</a></li>
                    <li><a href="#rozdzial-2">Wybór filmów</a></li>
                    <li><a href="#rozdzial-3">Zamówienie i wypożyczenie</a></li>
                    <li><a href="#rozdzial-4">Opłaty i płatności</a></li>
                    <li><a href="#rozdzial-5">Dostawa i zwrot</a></li>
                    <li><a href="#rozdzial-6">Kary za opóźnienie</a></li>
                    <li><a href="#rozdzial-7">Odpowiedzialność za utratę lub uszkodzenie treści cyfrowych</a></li>
                    <li><a href="#rozdzial-8">Zakończenie umowy</a></li>
                    <li><a href="#rozdzial-9">Zmiany w regulaminie</a></li>
                    <li><a href="#rozdzial-10">Punkty lojalnościowe</a></li>
                    <li><a href="#rozdzial-11">Postanowienia końcowe</a></li>
                </ol>
            </div>
        </nav>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-1">
            <h2 id="rozdzial-1">1. Rejestracja</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>Każdy użytkownik pragnący korzystać z usług RentalVOD musi zarejestrować się, podając swoje rzeczywiste dane osobowe, w tym imię, nazwisko, adres zamieszkania oraz adres e-mail.</li>
                <li>Użytkownik zobowiązany jest do aktualizacji swoich danych osobowych w serwisie w przypadku ich zmiany.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-2">
            <h2 id="rozdzial-2">2. Wybór filmów</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>Użytkownicy mają dostęp do szerokiej oferty filmów dostępnych w RentalVOD.</li>
                <li>Ceny wypożyczenia są wyraźnie oznaczone przy każdym filmie.</li>
                <li>Filmy mogą być dodawane do koszyka w celu późniejszego wypożyczenia.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-3">
            <h2 id="rozdzial-3">3. Zamówienie i wypożyczenie</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>Wypożyczenie filmu jest możliwe przez skompletowanie zamówienia i dokonanie płatności online.</li>
                <li>Po złożeniu zamówienia użytkownik otrzymuje potwierdzenie wraz z szczegółami dotyczącymi płatności i terminu wypożyczenia.</li>
                <li>Maksymalny czas wypożyczenia jednego filmu wynosi 14 dni.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-4">
            <h2 id="rozdzial-4">4. Opłaty i płatności</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>Opłata za wypożyczenie jest naliczana według ceny określonej przy każdym filmie.</li>
                <li>Użytkownik zobowiązany jest do zapłaty za wypożyczenie przed jego rozpoczęciem.</li>
                <li>Za przetrzymanie filmów po wyznaczonym terminie naliczane są dodatkowe opłaty.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-5">
            <h2 id="rozdzial-5">5. Dostawa i zwrot</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>Filmy są dostarczane cyfrowo, bezpośrednio na platformę użytkownika po dokonaniu płatności.</li>
                <li>Użytkownik zobowiązany jest do &quot;zwrotu&quot; filmu poprzez usunięcie go ze swojej biblioteki cyfrowej po upływie okresu wypożyczenia.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-6">
            <h2 id="rozdzial-6">6. Kary za opóźnienie</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>Za każdy dzień opóźnienia w usunięciu filmu z konta użytkownika naliczane są kary w wysokości 600% stawki dziennej.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-7">
            <h2 id="rozdzial-7">7. Odpowiedzialność za utratę lub uszkodzenie treści cyfrowych</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>Użytkownik jest odpowiedzialny za utratę dostępu lub uszkodzenie plików filmowych wynikające z jego działań.</li>
                <li>W przypadku utraty lub uszkodzenia treści, użytkownik może być obciążony kosztami związanymi z przywróceniem dostępu do filmu.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-8">
            <h2 id="rozdzial-8">8. Zakończenie umowy</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>Użytkownik może zakończyć umowę z RentalVOD w dowolnym momencie, z zastrzeżeniem uregulowania wszelkich zobowiązań finansowych.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-9">
            <h2 id="rozdzial-9">9. Zmiany w regulaminie</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>RentalVOD zastrzega sobie prawo do wprowadzania zmian w regulaminie. Użytkownicy zostaną poinformowani o wszelkich zmianach przez aktualizacje na stronie internetowej.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-10">
            <h2 id="rozdzial-10">10. Punkty lojalnościowe</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>Użytkownicy mogą zdobywać punkty lojalnościowe na następujących zasadach:</li>
                <li>+10 punktów za każde wypożyczenie filmu.</li>
                <li>+20 punktów za rejestrację z użyciem kodu polecającego innego użytkownika.</li>
                <li>Punkty mogą być wymieniane na różne korzyści:</li>
                <li>Wykupienie jakości premium za punkty lojalnościowe.</li>
                <li>Wypożyczenie filmu za 50 punktów.</li>
            </ul>
        </section>

        <section class="rv-section" style="margin-top: var(--rv-space-6);" aria-labelledby="rozdzial-11">
            <h2 id="rozdzial-11">11. Postanowienia końcowe</h2>
            <ul class="rv-stack" style="gap: var(--rv-space-2);">
                <li>W przypadku sporów decydujące jest prawo obowiązujące w jurysdykcji siedziby firmy.</li>
                <li>RentalVOD nie ponosi odpowiedzialności za szkody wynikłe z użytkowania serwisu poza zakresem gwarancji usługodawcy.</li>
            </ul>
        </section>

    </main>

    @include('layouts.footer', ['fixedBottom' => false])
</body>

</html>
