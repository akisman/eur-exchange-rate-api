FROM php:8.2-fpm-bookworm

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

# Create a group and user for Laravel
RUN groupadd -g ${GID} laravel \
    && useradd -g laravel -s /bin/sh -u ${UID} -m laravel

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    ca-certificates \
    gnupg \
    libpng-dev \
    libwebp-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmagickwand-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    sendmail

# Node
RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
ENV NODE_MAJOR=20
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update && apt-get install -y nodejs
# Moving the cache to somewhere you have permission to write
ENV NPM_CONFIG_CACHE=/tmp/.npm

# Composer
COPY --from=composer:2.6.6 /usr/bin/composer /usr/local/bin/composer

# PHP extensions
RUN pecl install redis xdebug sodium imagick \
    && docker-php-ext-enable redis xdebug sodium imagick \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache

RUN docker-php-ext-install intl
