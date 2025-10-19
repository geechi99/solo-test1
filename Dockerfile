FROM php:7.4-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    pkg-config \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    default-mysql-client \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j$(nproc) pdo_mysql mbstring zip gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* /var/www/html/
RUN if [ -f composer.json ]; then composer install --no-interaction --no-scripts --prefer-dist; fi

COPY . /var/www/html

RUN if [ -f composer.json ] && [ ! -d vendor ]; then composer install --no-interaction --prefer-dist; fi

RUN mkdir -p public/uploads && chown -R www-data:www-data /var/www/html || true

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8006

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:8006", "-t", "public"]
