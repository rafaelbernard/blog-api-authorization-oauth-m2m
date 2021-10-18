FROM php:8.0-apache

WORKDIR /var/www

RUN apt-get update && apt-get install -y wget git zip unzip

# install php extensions
RUN apt-get update && apt-get install -y libmemcached-dev zlib1g-dev \
    && pecl install memcached-3.1.5 \
    && docker-php-ext-enable memcached

RUN wget https://getcomposer.org/installer && php ./installer && rm installer
RUN mv composer.phar /usr/local/bin/composer

COPY composer.json composer.lock ./

RUN composer install

EXPOSE 80
