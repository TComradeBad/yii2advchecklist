FROM php:fpm

RUN bash -c "docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql && docker-php-ext-install mbstring && docker-php-ext-install zip && docker-php-ext-install gd"