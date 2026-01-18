# 1️⃣ Base PHP avec Apache
FROM php:8.2-apache

# 2️⃣ Installer extensions PHP pour Laravel
RUN docker-php-ext-install pdo pdo_mysql

# 3️⃣ Activer mod_rewrite
RUN a2enmod rewrite

# 4️⃣ Copier le projet
COPY . /var/www/html
WORKDIR /var/www/html

# 5️⃣ Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# 6️⃣ Modifier Apache pour pointer vers /public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

# 7️⃣ Installer Node.js pour Vite
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# 8️⃣ Installer les dépendances front-end et builder Vite
RUN npm install
RUN npm run build

# 9️⃣ Installer Composer et dépendances PHP
COPY composer.json composer.lock ./
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# 10️⃣ Exposer le port Render
EXPOSE 10000

# 11️⃣ Lancer Apache
CMD ["apache2-foreground"]
