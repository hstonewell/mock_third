FROM php:8.2-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip libicu-dev \ 
  && docker-php-ext-install pdo_mysql zip intl

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

RUN docker-php-ext-install bcmath

WORKDIR /var/www
