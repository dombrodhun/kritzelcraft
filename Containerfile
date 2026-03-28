FROM php:8.4-apache

# Aktiviere mod_rewrite für den Apache
RUN a2enmod rewrite

# Installiere die PDO MySQL Erweiterung
RUN docker-php-ext-install pdo_mysql

# Setze das Arbeitsverzeichnis
WORKDIR /var/www/html

# Erstelle das Upload-Verzeichnis und setze die Berechtigungen
RUN mkdir -p /var/www/html/public/uploads && chown -R www-data:www-data /var/www/html/public/uploads

# Kopiere die virtuelle Host-Konfiguration
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
