FROM php:8.4-fpm-alpine

LABEL maintainer="WinCase CRM <wincasetop@gmail.com>"

# System dependencies
RUN apk add --no-cache \
    bash curl git zip unzip libzip-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    icu-dev oniguruma-dev libxml2-dev \
    mysql-client redis nodejs npm

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
        xml

# Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# PHP configuration
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
