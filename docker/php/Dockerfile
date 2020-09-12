FROM php:fpm

RUN apt-get update && apt-get install -y \
    libpq-dev

RUN docker-php-ext-install pdo_pgsql

COPY . /var/www/kt_test_task_backend
WORKDIR /var/www/kt_test_task_backend
