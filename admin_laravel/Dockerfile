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

# --- OPTIMIZACIÓN DE CACHÉ ---
# Copiar primero solo los archivos de configuración de dependencias
COPY composer.json composer.lock* ./
# Instalar dependencias sin autoloader para cachear la capa de Docker
RUN composer install --no-interaction --no-autoloader --no-scripts

# Luego copiar el resto del código
COPY . /var/www/html

# Finalmente generar el autoloader optimizado
RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache