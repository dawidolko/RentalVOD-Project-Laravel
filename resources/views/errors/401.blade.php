@include('layouts.error-page', [
    'code' => 401,
    'heading' => 'Wymagane logowanie',
    'body' => 'Ta strona jest dostępna tylko dla zalogowanych użytkowników. Zaloguj się na swoje konto, aby kontynuować.',
    'exception' => $exception ?? null,
])
