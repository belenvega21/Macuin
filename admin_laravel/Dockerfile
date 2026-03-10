FROM php:8.4-apache

WORKDIR /var/www/html
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/html
RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache