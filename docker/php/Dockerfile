FROM php:8.1-fpm

WORKDIR /var/www

RUN rm -rf /var/www/html

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    apt-utils \
    libonig-dev && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath  zip

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

RUN mkdir -p /usr/src/php/ext/redis; \
	curl -fsSL https://pecl.php.net/get/redis --ipv4 | tar xvz -C "/usr/src/php/ext/redis" --strip 1; \
	docker-php-ext-install redis;

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG user=innoviclinic
ARG uid=1000

COPY ./innoviclinic/composer.json ./innoviclinic/composer.lock ./
RUN composer install --no-scripts --no-dev --optimize-autoloader
COPY ./innoviclinic/ .

#USER $user

# Certifique-se de que os diretórios existam antes de ajustar as permissões
RUN mkdir -p /var/www/storage/logs /var/www/bootstrap/cache

# Adiciona permissões ao diretório storage e bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000


#docker build --no-cache -t 213.199.34.152:32768/innovclinic-backend -f ./docker/php/Dockerfile .
