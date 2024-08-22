FROM php:8.3-cli

COPY . /var/www
WORKDIR /var/www

RUN apt-get -y update

#add composer
RUN apt-get -y install git
COPY --from=composer /usr/bin/composer /usr/bin/composer

#php-ext zip
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip