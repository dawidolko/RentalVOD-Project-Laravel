@include('layouts.error-page', [
    'code' => 503,
    'heading' => 'Serwis tymczasowo niedostępny',
    'body' => 'Prowadzimy prace serwisowe. Wróć za kilka minut — powinno już wszystko działać.',
    'exception' => $exception ?? null,
])
