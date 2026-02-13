#!/bin/bash
set -e

echo "🚀 Inicjalizacja projektu RentalVOD..."

# Kopiowanie .env jeśli nie istnieje
if [ ! -f "/var/www/html/.env" ]; then
    echo "📋 Kopiowanie pliku .env..."
    cp /var/www/html/.tools/docker/.env.docker /var/www/html/.env
fi

# Instalacja zależności Composer
if [ ! -d "/var/www/html/vendor" ]; then
    echo "📦 Instalacja zależności PHP (Composer)..."
    composer install --no-interaction --optimize-autoloader
else
    echo "✅ Zależności PHP już zainstalowane"
fi

# Instalacja zależności NPM i budowanie assets
if [ ! -d "/var/www/html/node_modules" ]; then
    echo "📦 Instalacja zależności Node.js..."
    npm install
    echo "🔨 Budowanie assets..."
    npm run build
else
    echo "✅ Zależności Node.js już zainstalowane"
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

# Uruchamianie seederów (tylko jeśli są nowe migracje lub pusta baza)
TABLE_COUNT=$(php artisan db:show 2>/dev/null | grep -oP 'Tables\s+\K\d+' || echo "0")
if [ "$TABLE_COUNT" -gt 0 ]; then
    USER_COUNT=$(php artisan tinker --execute="echo \DB::table('users')->count();" 2>/dev/null || echo "0")
    if [ "$USER_COUNT" = "0" ]; then
        echo "📊 Wypełnianie bazy danymi testowymi..."
        php artisan db:seed --force --no-interaction
    else
        echo "✅ Baza danych już zawiera dane"
    fi
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
