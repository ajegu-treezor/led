FROM php:7.3.12-fpm-alpine
RUN apk update
RUN apk add php7-dev gcc make g++ zlib-dev composer icu-dev bash git openssl nano

# PHP extensions
RUN docker-php-ext-install mbstring tokenizer intl
# For YAML
RUN apk add --update --no-cache \
          yaml && \
      # Build dependancy for YAML \
      apk add --update --no-cache --virtual .yaml-build \
          yaml-dev && \
      pecl install yaml && \
      docker-php-ext-enable yaml; \
      apk del .yaml-build
# For Xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.remote_enable=On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_port=9010" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey=idekey" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_autostart=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# Add group deploy
# Add user deploy
# User and group used for launching composer
RUN addgroup -S deploy && adduser -S deploy -G deploy
