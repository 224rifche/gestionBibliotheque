# Image PHP + Apache
FROM php:8.2-apache

# Dépendances système pour MySQL
RUN apt-get update && apt-get install -y --no-install-recommends \
    default-mysql-client \
    git unzip curl \
    && docker-php-ext-install pdo pdo_mysql zip || true

# Activer mod_rewrite Apache
RUN a2enmod rewrite

# Apache pointe vers public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copier composer.json et composer.lock
COPY composer.json composer.lock ./

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier tout le code source
COPY . .

# Nettoyer les caches Laravel
RUN rm -rf bootstrap/cache/*.php bootstrap/cache/tmp/*

# Installer dépendances PHP (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Installer Node.js et build assets
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && npm install \
    && npm run build

# Variables d'environnement Laravel
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_KEY=base64:dVt+awiXOXyIEgIHVcrlHHF30m/ky1K1Ip5WB1pO0IQ=

# Permissions Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Port correct pour Render
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
