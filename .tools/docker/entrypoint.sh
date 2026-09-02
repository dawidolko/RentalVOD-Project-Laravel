#!/bin/bash
set -e

echo "🚀 Inicjalizacja projektu RentalVOD..."

# Kopiowanie .env jeśli nie istnieje
if [ ! -f "/var/www/html/.env" ]; then
    echo "📋 Kopiowanie pliku .env..."
    cp /var/www/html/.tools/docker/.env.docker /var/www/html/.env
fi

# Instalacja zależności Composer
# Sprawdzamy `autoload.php`, nie sam katalog: przerwana instalacja zostawia
# pusty `vendor/`, ktory przechodzil ten warunek jako "gotowe" — artisan
# wywalal sie potem na brakujacym autoloaderze i kontener restartowal w kolko.
if [ ! -f "/var/www/html/vendor/autoload.php" ]; then
    echo "📦 Instalacja zależności PHP (Composer)..."
    # `install`, nie `update`: lockfile przypina wersje, ktore z ta aplikacja
    # dzialaja. `composer update` ignorowal go i probowal pobrac najnowsze
    # paczki, a te sa blokowane przez doradztwa bezpieczenstwa Packagista —
    # kontener wpadal przez to w petle restartow i aplikacja nigdy nie wstawala.
    composer install --no-interaction --optimize-autoloader \
        || composer install --no-interaction --optimize-autoloader --no-scripts
else
    echo "✅ Zależności PHP już zainstalowane"
fi

# Instalacja zależności NPM
if [ ! -d "/var/www/html/node_modules/vite" ]; then
    echo "📦 Instalacja zależności Node.js..."
    npm install
else
    echo "✅ Zależności Node.js już zainstalowane"
fi

# Budowanie assets (zawsze)
if [ ! -d "/var/www/html/public/build" ]; then
    echo "🔨 Budowanie assets..."
    npm run build
else
    echo "✅ Assets już zbudowane"
fi

# Generowanie klucza aplikacji jeśli nie istnieje
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "🔑 Generowanie klucza aplikacji..."
    php artisan key:generate --no-interaction
else
    echo "✅ Klucz aplikacji już istnieje"
fi

# Czekanie na MySQL
echo "⏳ Oczekiwanie na bazę danych..."
for i in {1..30}; do
    if php artisan db:show 2>/dev/null | grep -q "MySQL"; then
        echo "✅ Baza danych dostępna"
        break
    fi
    echo "   Próba $i/30 - czekam 2s..."
    sleep 2
done

# Uruchamianie migracji
echo "🗄️  Uruchamianie migracji..."
php artisan migrate --force --no-interaction

# Utworzenie storage link
echo "🔗 Tworzenie linku storage..."
php artisan storage:link --force

# Sprawdzenie czy trzeba uruchomić seedery
USER_COUNT=$(php artisan tinker --execute="echo \\App\\Models\\User::count();" 2>/dev/null | tail -1 | tr -d '=>' || echo "0")
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "📊 Wypełnianie bazy danymi testowymi..."
    php artisan db:seed --force --no-interaction
else
    echo "✅ Baza danych już zawiera dane ($USER_COUNT użytkowników)"
fi

# Nadawanie uprawnień
echo "🔐 Nadawanie uprawnień..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache

echo "✨ Projekt RentalVOD gotowy!"
echo "📍 Aplikacja dostępna na: http://localhost:8000"

# Uruchomienie supervisord
echo "🚀 Uruchamianie serwera..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
