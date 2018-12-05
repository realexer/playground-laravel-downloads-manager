FROM php:7.2-apache

#
# update packages
# rabbitmq
# libssh
#
RUN apt-get update \
    && apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
        libpq-dev

# standard extensions
RUN docker-php-ext-install \
        bcmath \
        sockets \
        pgsql \
        pdo_pgsql \
        zip

# pecl extensions
RUN pecl install amqp \
    && pecl install mongodb \
    && pecl install xdebug \
    && docker-php-ext-enable amqp mongodb xdebug


# node and npm
RUN apt-get install -y gnupg
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get install -y nodejs

# composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#
# set document root to /public
#
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

#
# enable mod_rewrite
#
RUN a2enmod rewrite
