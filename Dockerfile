FROM composer as vendor
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --ignore-platform-reqs

FROM node:alpine as node
WORKDIR /app
COPY package.json webpack.mix.js package-lock.json tailwind.config.js ./
RUN npm install
COPY resources/js ./resources/js
COPY resources/css ./resources/css
COPY resources/views ./resources/views
RUN npm run production

FROM php:apache
WORKDIR /var/www/homework
## create PHP ini file
ENV DEPLOY_ENVIRONMENT "production"
RUN cp ${PHP_INI_DIR}/php.ini-${DEPLOY_ENVIRONMENT} ${PHP_INI_DIR}/php.ini
## Run the update and install commands together to avoid caching issues when creating the docker container
RUN apt-get update -q && apt-get install -qq -y zip unzip libpng-dev zlib1g-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev

## Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# configure apache
ADD docker/apache/homework.conf /etc/apache2/sites-available/01-homework.conf
RUN a2ensite 01-homework.conf
RUN a2dissite 000-default.conf
RUN a2enmod rewrite

#Copy the source code
COPY --chown=www-data:www-data . /var/www/homework/
COPY --from=vendor --chown=www-data:www-data /app/vendor/ /var/www/homework/vendor/
COPY --from=node --chown=www-data:www-data /app/public /var/www/homework/

#Port
EXPOSE 80

#Change directory ownership
RUN mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views
RUN chmod -R 755 storage && chown -R www-data:www-data storage

# security configuration
RUN sed -E -i 's/expose_php = On/expose_php = Off/' ${PHP_INI_DIR}/php.ini
RUN sed -E -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-enabled/security.conf
RUN sed -E -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-enabled/security.conf

# Run the tests to make sure the app is working fine
RUN php artisan test
