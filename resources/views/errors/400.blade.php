@include('layouts.error-page', [
    'code' => 400,
    'heading' => 'Nieprawidłowe żądanie',
    'body' => 'Serwer nie mógł przetworzyć tego żądania. Sprawdź adres strony i spróbuj ponownie.',
    'exception' => $exception ?? null,
])
