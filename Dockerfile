FROM php:8.3-cli-bullseye

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer