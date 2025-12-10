FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add \
    curl \
    git \
    postgresql-libs \
    postgresql-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-install pdo_pgsql \
    && apk del postgresql-dev $PHPIZE_DEPS

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /srv/app

# Copy entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]
