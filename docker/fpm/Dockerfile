FROM php:7.4-fpm

ARG environment=prod

RUN if [ $environment != "prod" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN pecl install timecop-beta && docker-php-ext-enable timecop;

RUN rm -f /usr/local/etc/www.conf.default && rm -f /usr/local/etc/docker.conf && rm -f /usr/local/etc/zz-docker.conf

COPY docker/fpm/config/php_dev.ini /usr/local/etc/php/php.ini
COPY docker/fpm/config/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY docker/fpm/config/www_dev.conf /usr/local/etc/php-fpm.d/www.conf


RUN mkdir ~/.ssh

RUN echo 'Host github.com\n\
            StrictHostKeyChecking no\n\
            IdentityFile ~/.ssh-local/id_rsa' > ~/.ssh/config


WORKDIR /usr/src/app

COPY . /usr/src/app

RUN if [ $environment = "prod" ]; then APP_ENV=prod \
     && echo "Fix permissions" \
     && chown -R www-data:www-data /usr/src/app; fi

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm"]
