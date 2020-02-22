FROM php:7.3-apache AS ospos
MAINTAINER jekkos

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
    libicu-dev \
    libgd-dev \
    openssl

RUN a2enmod rewrite
RUN docker-php-ext-install mysqli bcmath intl gd
RUN echo "date.timezone = \"\${PHP_TIMEZONE}\"" > /usr/local/etc/php/conf.d/timezone.ini
RUN echo -e “$(hostname -i)\t$(hostname) $(hostname).localhost” >> /etc/hosts

WORKDIR /app
COPY . /app
RUN ln -s /app/*[^public] /var/www && rm -rf /var/www/html && ln -nsf /app/public /var/www/html
RUN chmod 755 /app/public/uploads && chown -R www-data:www-data /app/public /app/application

FROM ospos AS ospos_test
 
COPY --from=composer /usr/bin/composer /usr/bin/composer
 
RUN composer install -d/app 
RUN php /app/vendor/kenjis/ci-phpunit-test/install.php -a /app/application -p /app/vendor/codeigniter/framework
 
WORKDIR /app/application/tests
 
CMD ["/app/vendor/phpunit/phpunit/phpunit"]

FROM ospos AS ospos_dev

RUN mkdir -p /app/bower_components && ln -s /app/bower_components /var/www/html/bower_components
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

