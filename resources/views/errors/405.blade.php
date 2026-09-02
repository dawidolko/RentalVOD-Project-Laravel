@include('layouts.error-page', [
    'code' => 405,
    'heading' => 'Metoda niedozwolona',
    'body' => 'Tej operacji nie można wykonać w ten sposób. Wróć na poprzednią stronę i spróbuj ponownie.',
    'exception' => $exception ?? null,
])
