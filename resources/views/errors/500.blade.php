@include('layouts.error-page', [
    'code' => 500,
    'heading' => 'Błąd wewnętrzny serwera',
    'body' => 'Coś poszło nie tak po naszej stronie. Pracujemy nad rozwiązaniem problemu — spróbuj ponownie za chwilę.',
    'exception' => $exception ?? null,
])
