FROM php:7.4-apache

RUN   adduser   --group  --shell /bin/sh --disabled-password laravel   


ADD ./visoft/ /var/visoft/

RUN mv /var/www/html /var/www/html.old && chown -R www-data:www-data /var/visoft/ && ln -s /var/visoft/public/ /var/www/html

WORKDIR /var/visoft/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf &&\
    a2enmod rewrite &&\
    a2dissite 000-default &&\
    service apache2 restart


RUN apt-get update && apt-get install -y \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install pdo pdo_mysql zip

