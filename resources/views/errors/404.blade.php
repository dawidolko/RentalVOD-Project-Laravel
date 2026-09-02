@include('layouts.error-page', [
    'code' => 404,
    'heading' => 'Nie znaleziono strony',
    'body' => 'Strona, której szukasz, nie istnieje lub została przeniesiona. Sprawdź adres albo skorzystaj z wyszukiwarki filmów.',
    'exception' => $exception ?? null,
])
