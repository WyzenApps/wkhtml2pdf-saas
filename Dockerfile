#FROM php:7.4-cli-buster
FROM wyzenrepo/php-fpm74:latest

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive
ARG PHP_RELEASE=7.4
ARG APP_DIR=/www
ARG TIMEZONE="Europe/Paris"
ARG LOCALE="fr_FR.UTF-8"
ARG LC_ALL="fr_FR.UTF-8"
ENV LOCALE="fr_FR.UTF-8"
ENV LC_ALL="fr_FR.UTF-8"

ENV APP_DIR=/www
ENV DATA_DIR=/data
ENV WK_PDF=/usr/local/bin/wkhtmltopdf
ENV WK_IMAGE=/usr/local/bin/wkhtmltoimage

EXPOSE 80

COPY config/system/locale.gen /etc/locale.gen
COPY config/system/export_locale.sh /etc/profile.d/05-export_locale.sh

RUN apt update \
	&& apt -y --no-install-recommends dist-upgrade \
	&& mkdir -p ${APP_DIR} ${DATA_DIR} \
	&& usermod -u 33 -g 33 -d ${APP_DIR} www-data \
	&& chown -R www-data:www-data ${APP_DIR} ${DATA_DIR} \
	&& apt -y --no-install-recommends install curl wget git sudo locales vim \
	&& locale-gen $LOCALE && update-locale LANGUAGE=${LOCALE} LC_ALL=${LOCALE} LANG=${LOCALE} LC_CTYPE=${LOCALE} \
	&& ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
	&& . /etc/default/locale

# COMPOSER, PHP
RUN update-alternatives --set php /usr/bin/php${PHP_RELEASE} \
	&& update-alternatives --set phar /usr/bin/phar${PHP_RELEASE} \
	&& update-alternatives --set phar.phar /usr/bin/phar.phar${PHP_RELEASE} \
	&& cd /tmp && \
	curl -fsS https://getcomposer.org/installer -o composer-setup.php && \
	php composer-setup.php --quiet && mv composer.phar /usr/local/bin/composer && rm composer-setup.php

# PHP Packages
RUN apt -y --no-install-recommends install \
	php${PHP_RELEASE}-gd \
	php${PHP_RELEASE}-intl \
	php${PHP_RELEASE}-mbstring \
	php${PHP_RELEASE}-curl \
	php${PHP_RELEASE}-yaml \
	php${PHP_RELEASE}-zip \
	php-json

# Dependencies for wk
RUN apt -y install \
	fontconfig fontconfig-config fonts-dejavu-core libfontconfig1 libfontenc1 libfreetype6 \
	libpng16-16 libx11-6 libx11-data libxau6 libxcb1 libxdmcp6 libxext6 libxrender1 \
	sensible-utils ucf x11-common xfonts-75dpi xfonts-base xfonts-encodings xfonts-utils

# ADDITIONALS CONFIG
COPY ./config/php/php-ini-overrides.ini /etc/php/${PHP_RELEASE}/fpm/conf.d/99-overrides.ini
COPY ./config/php/php-ini-overrides.ini /etc/php/${PHP_RELEASE}/cli/conf.d/99-overrides.ini
COPY ./config/system/alias.sh /etc/profile.d/01-alias.sh
RUN cat /etc/profile.d/01-alias.sh > /etc/bash.bashrc

# INSTALL WK
COPY install /install
COPY --chown=www-data:www-data application ${APP_DIR}
RUN chmod +x /install/install.sh && /install/install.sh \
	&& cd ${APP_DIR} \
	&& composer install --no-dev \
	&& chown -R www-data:www-data ${APP_DIR}

# CLEAN
RUN apt -y purge php8.0* \
	&& apt -y autoremove \
	&& apt -y clean \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* /install/*

USER www-data
WORKDIR ${APP_DIR}
VOLUME [ "/data" ]
# Initializing Redis server and Gunicorn server from supervisord
CMD ["php","-S","0.0.0.0:80","-t", "/www/public"]
