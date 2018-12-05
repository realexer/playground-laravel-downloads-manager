FROM php:7.2-cli


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
