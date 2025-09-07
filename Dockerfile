FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libicu-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl

RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --optimize-autoloader --no-scripts
RUN php bin/console cache:clear --env=prod

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

EXPOSE 80
