#!/bin/bash

ENV_FILE=./.env

chown -R www-data:www-data .

composer install

if [ -f "$ENV_FILE" ]; then
    echo "$ENV_FILE" exists;
else
    cp .env.example .env
fi

php artisan key:generate

php artisan migrate

php-fpm
