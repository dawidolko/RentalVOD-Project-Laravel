#!/bin/bash
set -e

echo "🚀 Initialising project RentalVOD..."

# Copy .env if it does not exist
if [ ! -f "/var/www/html/.env" ]; then
    echo "📋 Copying the .env file..."
    cp /var/www/html/.tools/docker/.env.docker /var/www/html/.env
fi

# Install Composer dependencies.
# Check `autoload.php`, not the directory itself: an interrupted install leaves
# an empty `vendor/` that used to pass this check as "ready", after which
# artisan crashed on the missing autoloader and the container restart-looped.
if [ ! -f "/var/www/html/vendor/autoload.php" ]; then
    echo "📦 Installing PHP dependencies (Composer)..."
    # `install`, not `update`: the lockfile pins versions that work with this app.
    # `composer update` ignored it and tried to fetch the newest packages, which
    # are blocked by Packagist security advisories, so the container fell into
    # a restart loop and the app never came up.
    composer install --no-interaction --optimize-autoloader \
        || composer install --no-interaction --optimize-autoloader --no-scripts
else
    echo "✅ PHP dependencies already installed"
fi

# Install NPM dependencies
if [ ! -d "/var/www/html/node_modules/vite" ]; then
    echo "📦 Installing Node.js dependencies..."
    npm install
else
    echo "✅ Node.js dependencies already installed"
fi

# Build assets (always)
if [ ! -d "/var/www/html/public/build" ]; then
    echo "🔨 Building assets..."
    npm run build
else
    echo "✅ Assets already built"
fi

# Generate the application key if it does not exist
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "🔑 Generating the application key..."
    php artisan key:generate --no-interaction
else
    echo "✅ Application key already exists"
fi

# Wait for MySQL
echo "⏳ Waiting for the database..."
for i in {1..30}; do
    if php artisan db:show 2>/dev/null | grep -q "MySQL"; then
        echo "✅ Database available"
        break
    fi
    echo "   Attempt $i/30 - waiting 2s..."
    sleep 2
done

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force --no-interaction

# Utworzenie storage link
echo "🔗 Creating the storage link..."
php artisan storage:link --force

# Check whether seeders need to run
USER_COUNT=$(php artisan tinker --execute="echo \\App\\Models\\User::count();" 2>/dev/null | tail -1 | tr -d '=>' || echo "0")
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "📊 Seeding the database with test data..."
    php artisan db:seed --force --no-interaction
else
    echo "✅ Database already has data ($USER_COUNT users)"
fi

# Set permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache

echo "✨ Projekt RentalVOD ready!"
echo "📍 Application available at: http://localhost:8000"

# Start supervisord
echo "🚀 Starting the server..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
