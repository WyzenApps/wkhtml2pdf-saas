FROM php:7.4-cli-buster

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive
ARG APPDIR=/application
ARG LOCALE="fr_FR.UTF-8"
ARG LC_ALL="fr_FR.UTF-8"
ENV LOCALE="fr_FR.UTF-8"
ENV LC_ALL="fr_FR.UTF-8"
ARG TIMEZONE="Europe/Paris"
ARG PHP_RELEASE=7.4

EXPOSE 80

COPY config/system/locale.gen /etc/locale.gen
COPY config/system/export_locale.sh /etc/profile.d/05-export_locale.sh

RUN apt update \
	&& apt -y --no-install-recommends dist-upgrade

RUN cd /tmp \
	&& mkdir -p $APPDIR \
	&& usermod -u 33 -g 33 -d $APPDIR www-data \
	&& chown -R www-data:www-data $APPDIR \
	&& apt-get -y --no-install-recommends install curl wget git sudo locales vim \
	&& apt-get -y install fontconfig fontconfig-config fonts-dejavu-core libfontconfig1 libfontenc1 libfreetype6 libjpeg62-turbo libpng16-16 libx11-6 libx11-data libxau6 libxcb1 libxdmcp6 libxext6 libxrender1 sensible-utils ucf x11-common xfonts-75dpi xfonts-base xfonts-encodings xfonts-utils \
	&& locale-gen $LOCALE && update-locale LANGUAGE=${LOCALE} LC_ALL=${LOCALE} LANG=${LOCALE} LC_CTYPE=${LOCALE} \
	&& ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
	&& . /etc/default/locale

COPY config/wkhtmltox.deb /install/
RUN dpkg -i /install/wkhtmltox.deb && rm /install/wkhtmltox.deb

# NODE, YARN, COMPOSER
RUN cd /tmp && \
	curl -fsS https://getcomposer.org/installer -o composer-setup.php && \
	php composer-setup.php --quiet && mv composer.phar /usr/local/bin/composer && rm composer-setup.php

# PHP Packages
#RUN apt-get -y --no-install-recommends install \
#	php${PHP_RELEASE}-memcached \
#	php${PHP_RELEASE}-gd \
#	php${PHP_RELEASE}-redis \
#	php${PHP_RELEASE}-pgsql \
#	php${PHP_RELEASE}-mysql \
#	php${PHP_RELEASE}-sqlite3 \
#	php${PHP_RELEASE}-intl \
#	php${PHP_RELEASE}-mbstring \
#	php${PHP_RELEASE}-xml \
#	php${PHP_RELEASE}-curl \
#	php${PHP_RELEASE}-zip \
#	php-json \
#	&& cd /etc/alternatives && ln -sf /usr/bin/php${PHP_RELEASE} php


# CLEAN
#RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# ADDITIONALS CONFIG
COPY ./config/php/php-ini-overrides.ini /etc/php/${PHP_RELEASE}/fpm/conf.d/99-overrides.ini
COPY ./config/php/php-ini-overrides.ini /etc/php/${PHP_RELEASE}/cli/conf.d/99-overrides.ini
COPY ./config/system/alias.sh /etc/profile.d/01-alias.sh

RUN cat /etc/profile.d/01-alias.sh > /etc/bash.bashrc

WORKDIR $APPDIR

COPY application $APPDIR
RUN chown -R www-data:www-data $APPDIR

VOLUME [ "/application/data" ]

# Initializing Redis server and Gunicorn server from supervisord
CMD ["php","-S","0.0.0.0:80","-t", "/application/public"]

