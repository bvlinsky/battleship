FROM php:8.3-cli-bullseye

# docker build -t test ./
# docker run -it -v ${PWD}:/battleship -w /battleship test bash

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer