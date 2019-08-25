ARG PHP_IMAGE=php:7.2-fpm

FROM ${PHP_IMAGE} as app_vendor

# Set the WORKDIR to /app so all following commands run in /app
WORKDIR /app

# Install Composer and make it available in the PATH
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

COPY . ./

## Install dependencies with Composer.
## --no-interaction makes sure composer can run fully automated
RUN composer install --no-interaction --prefer-dist --no-dev

# We don't need composer with cache inside image
FROM ${PHP_IMAGE}

RUN sed -i "s/\(user\|group\) = www-data/\1 = root/" /usr/local/etc/php-fpm.d/www.conf

# Set the WORKDIR to /app so all following commands run in /app
WORKDIR /app

COPY --from=app_vendor /app ./

CMD ["php-fpm", "-R"]
