@include('layouts.html')
@include('layouts.head', [
    'pageTitle' => 'Wszystkie wypożyczenia - panel administratora - RentalVOD',
    'robots' => 'noindex, nofollow',
])

<body>
    @include('layouts.navbar')

    <div class="rv-page">
        @include('layouts.breadcrumbs', ['crumbs' => [
            ['label' => 'Panel administratora'],
            ['label' => 'Wypożyczenia'],
        ]])
    </div>

    <main id="main-content" class="rv-page" style="padding-top: 0;">
        @include('layouts.success')
        @include('layouts.errors')

        <header class="rv-page-header">
            <h1>Wszystkie wypożyczenia</h1>
            <p>Lista zamówień złożonych przez użytkowników serwisu.</p>
        </header>

        <section class="rv-section" style="margin-top: 0;" aria-labelledby="chart-heading">
            <h2 id="chart-heading">Rozkład cen zamówień</h2>
            <div class="card">
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="priceHistogram"
                                role="img"
                                aria-label="Wykres słupkowy przedstawiający ceny kolejnych zamówień na bieżącej stronie. Dokładne wartości znajdują się w tabeli poniżej."></canvas>
                    </div>
                </div>
            </div>
        </section>

        <section class="rv-section" aria-labelledby="orders-heading">
            <h2 id="orders-heading">Lista zamówień</h2>

            @if ($loans->isEmpty())
                <div class="rv-empty">
                    <h3>Brak zamówień</h3>
                    <p>Nikt jeszcze nie złożył zamówienia w serwisie.</p>
                </div>
            @else
                <div class="table-responsive" tabindex="0">
                    <table class="table table-hover table-striped">
                        <caption>Wypożyczenia wraz z ceną, użytkownikiem, okresem i statusem.</caption>
                        <thead>
                            <tr>
                                <th scope="col">Plakat</th>
                                <th scope="col">Tytuł filmu</th>
                                <th scope="col">Cena całkowita</th>
                                <th scope="col">E-mail użytkownika</th>
                                <th scope="col">Data rozpoczęcia</th>
                                <th scope="col">Data zakończenia</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loans as $loan)
                                @foreach ($loan->movies as $movie)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('storage/' . $movie->img_path) }}"
                                                 alt="Plakat filmu {{ $movie->title }}"
                                                 width="60" height="90"
                                                 style="object-fit: cover; border-radius: var(--rv-radius-sm);"
                                                 loading="lazy">
                                        </td>
                                        <th scope="row" style="font-weight: var(--rv-weight-semibold);">{{ $movie->title }}</th>
                                        <td>{{ $loan->price }} zł</td>
                                        <td>{{ $loan->user->email }}</td>
                                        <td>
                                            <time datetime="{{ \Carbon\Carbon::parse($loan->start)->toDateString() }}">
                                                {{ \Carbon\Carbon::parse($loan->start)->format('d.m.Y') }}
                                            </time>
                                        </td>
                                        <td>
                                            <time datetime="{{ \Carbon\Carbon::parse($loan->end)->toDateString() }}">
                                                {{ \Carbon\Carbon::parse($loan->end)->format('d.m.Y') }}
                                            </time>
                                        </td>
                                        <td>{{ $loan->status }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($loans->hasPages())
                    <nav class="mt-4 d-flex justify-content-center" aria-label="Paginacja listy zamówień">
                        {{ $loans->onEachSide(1)->links('vendor.pagination.rentalvod') }}
                    </nav>
                @endif
            @endif
        </section>
    </main>

    @include('layouts.footer', ['fixedBottom' => false])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('priceHistogram');
            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            const prices = @json($loans->getCollection()->pluck('price')->toArray());

            // Read the themed colours so the chart matches light and dark mode.
            const styles = getComputedStyle(document.documentElement);
            const textColor = styles.getPropertyValue('--rv-text').trim() || '#333';
            const gridColor = styles.getPropertyValue('--rv-border-soft').trim() || '#ccc';

            new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: prices.map((price, index) => `Zamówienie ${index + 1}`),
                    datasets: [{
                        label: 'Cena zamówienia (zł)',
                        data: prices,
                        backgroundColor: 'rgba(179, 18, 42, 0.65)',
                        borderColor: 'rgba(179, 18, 42, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    // Honour the visitor's motion preference.
                    animation: window.matchMedia('(prefers-reduced-motion: reduce)').matches
                        ? false
                        : undefined,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Cena (zł)', color: textColor },
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        },
                        x: {
                            title: { display: true, text: 'Zamówienia', color: textColor },
                            ticks: { color: textColor },
                            grid: { color: gridColor }
                        }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { color: textColor } },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return ` ${context.raw} zł`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
