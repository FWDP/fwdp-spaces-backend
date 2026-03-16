FROM php:8.5-apache

RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libpq-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev pkg-config \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    mbstring \
    bcmath \
    exif \
    pcntl \
    gd

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN a2enmod rewrite headers mpm_prefork

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]