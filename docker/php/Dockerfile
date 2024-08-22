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
    libonig-dev

RUN docker-php-ext-install zip pdo_mysql mbstring exif
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install gd

RUN mkdir -p /usr/src/php/ext/redis; \
	curl -fsSL https://pecl.php.net/get/redis --ipv4 | tar xvz -C "/usr/src/php/ext/redis" --strip 1; \
	docker-php-ext-install redis;

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG user=innoviclinic
ARG uid=1000

#RUN useradd -G www-data,root -u $uid -d /home/$user $user
#RUN mkdir -p /home/$user/.composer && \
#    chown -R $user:$user /home/$user

#RUN groupadd -g 1000 laravel
#RUN useradd -u 1000 -ms /bin/bash -g laravel user

#COPY --chown=$user:www-data ./innoviclinic/ /var/www
#COPY ./innoviclinic/ /var/www

#USER $user

EXPOSE 9000

#CMD ["php-fpm"]

#docker build -t 193.203.183.228:32768/innovclinic-backend -f ./docker/php/Dockerfile .
