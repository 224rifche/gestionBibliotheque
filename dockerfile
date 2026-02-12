# 1️⃣ Image PHP + Apache
FROM php:8.2-apache

# 2️⃣ Dépendances système + PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev \
    gcc make autoconf pkg-config \
    && docker-php-ext-install pdo pdo_pgsql zip

# 3️⃣ Activer mod_rewrite
RUN a2enmod rewrite

# 4️⃣ Apache vers /public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# 5️⃣ Copier fichiers dépendances d’abord (cache Docker)
COPY composer.json composer.lock ./

# 6️⃣ Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7️⃣ Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# 8️⃣ Node + npm
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# 9️⃣ Copier package.json + build front
COPY package*.json ./
RUN npm install
RUN npm run build

# 🔟 Copier tout le projet
COPY . .

# 1️⃣1️⃣ Variables d'environnement Laravel
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_KEY=base64:dVt+awiXOXyIEgIHVcrlHHF30m/ky1K1Ip5WB1pO0IQ=

# 1️⃣2️⃣ Permissions Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 1️⃣2️⃣ Port Render
EXPOSE 10000

# 1️⃣3️⃣ Lancer Apache
CMD ["apache2-foreground"]
