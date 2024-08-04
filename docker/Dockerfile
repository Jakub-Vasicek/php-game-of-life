FROM php:8.1-cli

COPY docker /usr/src/gol
WORKDIR /usr/src/gol

RUN apt-get -y update

#add composer
RUN apt-get -y install git
COPY --from=composer /usr/bin/composer /usr/bin/composer

#php-ext zip
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip
