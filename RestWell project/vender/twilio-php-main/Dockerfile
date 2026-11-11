
FROM php:7.4

RUN apt-get update --fix-missing \
    && apt-get install -y --no-install-recommends zip unzip \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir /twilio
WORKDIR /twilio

ENV PATH="vendor/bin:$PATH"

COPY src src
COPY tests tests
COPY composer* ./

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN composer install --prefer-dist