FROM php:8.4-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

