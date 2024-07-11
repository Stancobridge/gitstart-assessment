#docker/php
FROM php:8.2-fpm-buster

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-configure gd --with-jpeg --with-freetype 

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY . /var/www/html

COPY ./composer.json ./composer.lock ./

# Install Symfony dependencies
RUN composer install --no-scripts --no-autoloader --no-dev

COPY --chown=www-data:www-data . /var/www/html

EXPOSE 9000

CMD ["php-fpm"]