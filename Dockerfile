FROM php:8.1-cli

# Instalacja rozszerzeń PHP
RUN apt-get update \
    && apt-get install -y --no-install-recomsdsadsadsadasdsadasdasdmends \
        git zip unzip libzip-dev zlib1g-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Instalacja zależności
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Kopiowanie całej aplikacji
COPY . /app

# Wystawienie portu
ENV PORT=8080
EXPOSE 8080

# Uruchamianie serwera PHP z katalogiem publicznym jako root
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t public"]

