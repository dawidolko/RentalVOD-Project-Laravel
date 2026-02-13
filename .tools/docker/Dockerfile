FROM php:8.2-fpm

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nginx \
    supervisor \
    libicu-dev

# Czyszczenie cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalacja rozszerzeń PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Instalacja rozszerzenia Redis
RUN pecl install redis && docker-php-ext-enable redis

# Pobranie Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalacja Node.js i npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Ustawienie katalogu roboczego
WORKDIR /var/www/html

# Kopiowanie konfiguracji nginx
COPY .tools/docker/nginx.conf /etc/nginx/sites-available/default

# Kopiowanie konfiguracji supervisord
COPY .tools/docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Kopiowanie skryptu entrypoint
COPY .tools/docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
