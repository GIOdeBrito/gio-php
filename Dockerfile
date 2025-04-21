# Use the official PHP 8.1 image with Apache
FROM php:8.1-apache

COPY . /var/www/html/

# Install composer
RUN curl -sS https://getcomposer.org/installer | php

# Installs Composer after downloading the PHAR
RUN php composer.phar install

# Enables the rewrite module for .htaccess
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80