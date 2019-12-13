FROM php:7-apache

RUN apt-get update \
  && docker-php-ext-install mysqli
#
#RUN curl -sS https://getcomposer.org/installer | php \
#        && mv composer.phar /usr/local/bin/ \
#        && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer
#
#WORKDIR /var/www/html
#
#ADD composer.json /var/www/html/composer.json
#ADD composer.lock /var/www/html/composer.lock
#
#RUN composer install
