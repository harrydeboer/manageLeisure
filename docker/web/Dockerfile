FROM php:8.1-apache

ARG APP_ENV

RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install \
    nano \
    libxml2-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    wget

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod headers rewrite ssl

RUN if [ "$APP_ENV" = "dev" ] ; then DEBIAN_FRONTEND=noninteractive apt-get -y install nodejs npm; fi

RUN docker-php-ext-install bcmath curl mbstring xml intl zip pdo_mysql opcache

RUN if [ "$APP_ENV" = "dev" ] ; then pecl install xdebug && docker-php-ext-enable xdebug; fi

COPY --from=composer:2.1.14 /usr/bin/composer /usr/bin/composer

RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

COPY . /var/www/html
COPY ./docker/web/apache2.conf /etc/apache2/apache2.conf
COPY ./docker/web/000-default.${APP_ENV}.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/web/php.ini /usr/local/etc/php/conf.d/php.ini

RUN if [ "$APP_ENV" = "dev" ] ; then cp ./docker/web/php-dev.ini /usr/local/etc/php/conf.d/php-dev.ini; fi

RUN if [ "$APP_ENV" = "dev" ] ; then \
    cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini;  else \
    cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini; fi

RUN if [ "$APP_ENV" = "dev" ] ;  \
    then cp /var/www/html/docker/web/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; fi

RUN chown www-data:www-data -R /var/www/html/

RUN mkdir /var/www/.cache
RUN chown www-data:www-data -R /var/www/.cache
