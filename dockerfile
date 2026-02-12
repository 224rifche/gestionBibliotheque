# 1️⃣ Image PHP + Apache
FROM php:8.2-apache

# 2️⃣ Dépendances système pour PostgreSQL
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    git unzip curl \
    && docker-php-ext-install pdo pdo_pgsql zip || true

# 3️⃣ Activer mod_rewrite Apache
RUN a2enmod rewrite

# 4️⃣ Configurer Apache pour /public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# 5️⃣ Copier composer.json et composer.lock (cache Docker)
COPY composer.json composer.lock ./

# 6️⃣ Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7️⃣ Copier tout le code source
COPY . .

# 8️⃣ Nettoyer les caches Laravel
RUN rm -rf bootstrap/cache/*.php bootstrap/cache/tmp/*

# 9️⃣ Installer dépendances PHP (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 10️⃣ Installer Node.js et build les assets
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && npm install \
    && npm run build

# 11️⃣ Variables d'environnement Laravel
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_KEY=base64:dVt+awiXOXyIEgIHVcrlHHF30m/ky1K1Ip5WB1pO0IQ=

# 12️⃣ Permissions Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 13️⃣ Port Render
EXPOSE 10000

# 14️⃣ Lancer Apache
CMD ["apache2-foreground"]
