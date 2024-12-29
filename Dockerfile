

FROM php:8.3-apache

# Installe l’extension pdo_mysql (et active-la automatiquement)
RUN docker-php-ext-install pdo_mysql
