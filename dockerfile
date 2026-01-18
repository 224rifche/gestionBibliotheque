# Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Installer les extensions PHP nécessaires pour Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Copier le projet dans le conteneur
COPY . /var/www/html

# Définir le dossier public comme root
WORKDIR /var/www/html

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Modifier Apache pour pointer vers /public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf

# Installer Node.js pour le build Vite
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Installer les dépendances frontend
RUN npm install
RUN npm run build

# Exposer le port Render
EXPOSE 10000

# Lancer Apache
CMD ["apache2-foreground"]
