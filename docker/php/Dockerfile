FROM php:7.4-fpm

COPY php.ini /usr/local/etc/php/

RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

RUN apt-get update \
  && apt-get install -y \
  git \
  zip \
  unzip \
  vim \
  libmagickwand-dev --no-install-recommends \
  && pecl install imagick \
  && docker-php-ext-enable imagick

RUN apt-get update \
  && apt-get install -y libpq-dev \
  && docker-php-ext-install pdo_mysql pdo_pgsql

WORKDIR /var/www