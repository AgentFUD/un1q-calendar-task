FROM php:8.2-fpm-alpine

# Update app
RUN apk update && apk add --no-cache tzdata
# Set timezone
ENV TZ="Europe/Malta"

RUN apk add --update --no-cache autoconf g++ make openssl-dev
RUN apk add libpng-dev libzip-dev zip unzip php-zip
RUN apk add --update linux-headers
RUN apk add git

RUN docker-php-ext-install gd
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install sockets
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
### End Init install

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

# Install Redis
# RUN pecl install redis
# RUN docker-php-ext-enable redis

# Install Mongodb
# RUN pecl install mongodb
# RUN docker-php-ext-enable mongodb



#install xdebug
# RUN pecl install xdebug
# COPY 90-xdebug.ini "${PHP_INI_DIR}"/conf.d
RUN chown -R laravel:laravel /var/www/html 
USER laravel
