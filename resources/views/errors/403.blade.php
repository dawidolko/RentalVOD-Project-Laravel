@include('layouts.error-page', [
    'code' => 403,
    'heading' => 'Brak uprawnień',
    'body' => 'Nie masz uprawnień do wyświetlenia tej strony. Jeśli uważasz, że to pomyłka, skontaktuj się z pomocą techniczną.',
    'exception' => $exception ?? null,
])
