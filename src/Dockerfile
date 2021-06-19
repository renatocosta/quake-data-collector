FROM php:7.4-cli
COPY . /var/www
WORKDIR /var/www

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN chmod -R 775 framework/storage

RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/xdebug.ini 

RUN chmod +x framework/artisan
RUN chmod +x framework/vendor/bin/phpunit