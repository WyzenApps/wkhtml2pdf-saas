#FROM php:7.4-cli-buster
#FROM phpdockerio/php73-cli
FROM wyzenrepo/php-fpm74:latest

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive
ARG PHP_RELEASE=7.4
ARG APPDIR=/application
ARG TIMEZONE="Europe/Paris"
ARG LOCALE="fr_FR.UTF-8"
ARG LC_ALL="fr_FR.UTF-8"
ENV LOCALE="fr_FR.UTF-8"
ENV LC_ALL="fr_FR.UTF-8"

ENV APPDIR=/application
ENV DATA_DIR=/data
ENV WK_PDF=/usr/local/bin/wkhtmltopdf
ENV WK_IMAGE=/usr/local/bin/wkhtmltoimage

EXPOSE 80

COPY config/system/locale.gen /etc/locale.gen
COPY config/system/export_locale.sh /etc/profile.d/05-export_locale.sh

RUN apt update \
	&& apt -y --no-install-recommends dist-upgrade \
	&& mkdir -p ${APPDIR} ${DATA_DIR} \
	&& usermod -u 33 -g 33 -d ${APPDIR} www-data \
	&& chown -R www-data:www-data ${APPDIR} ${DATA_DIR} \
	&& apt-get -y --no-install-recommends install curl wget git sudo locales vim \
	&& locale-gen $LOCALE && update-locale LANGUAGE=${LOCALE} LC_ALL=${LOCALE} LANG=${LOCALE} LC_CTYPE=${LOCALE} \
	&& ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
	&& . /etc/default/locale

RUN apt-get -y install \
	#&& apt-get -y install fontconfig fontconfig-config fonts-dejavu-core libfontconfig1 libfontenc1 libfreetype6 libjpeg62-turbo libpng16-16 libx11-6 libx11-data libxau6 libxcb1 libxdmcp6 libxext6 libxrender1 sensible-utils ucf x11-common xfonts-75dpi xfonts-base xfonts-encodings xfonts-utils \
	&& apt-get -y install fontconfig fontconfig-config fonts-dejavu-core libfontconfig1 libfontenc1 libfreetype6 libpng16-16 libx11-6 libx11-data libxau6 libxcb1 libxdmcp6 libxext6 libxrender1 sensible-utils ucf x11-common xfonts-75dpi xfonts-base xfonts-encodings xfonts-utils \
	&& wget -O /install/wkhtmltox.deb  https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.buster_amd64.deb \
	&& wget -O /install/libjpeg62-turbo.deb http://ftp.br.debian.org/debian/pool/main/libj/libjpeg-turbo/libjpeg62-turbo_1.5.2-2+deb10u1_amd64.deb \
	&& dpkg -i /install/libjpeg62-turbo.deb && rm /install/libjpeg62-turbo.deb \
	&& dpkg -i /install/wkhtmltox.deb && rm /install/wkhtmltox.deb

# COMPOSER
RUN update-alternatives --set php /usr/bin/php${PHP_RELEASE} \
	&& update-alternatives --set phar /usr/bin/phar${PHP_RELEASE} \
	&& update-alternatives --set phar.phar /usr/bin/phar.phar${PHP_RELEASE} \
	&& cd /tmp && \
	curl -fsS https://getcomposer.org/installer -o composer-setup.php && \
	php composer-setup.php --quiet && mv composer.phar /usr/local/bin/composer && rm composer-setup.php

# PHP Packages
RUN apt-get -y --no-install-recommends install \
	php${PHP_RELEASE}-gd \
	php${PHP_RELEASE}-intl \
	php${PHP_RELEASE}-mbstring \
	php${PHP_RELEASE}-curl \
	php${PHP_RELEASE}-yaml \
	php${PHP_RELEASE}-zip \
	php-json

# CLEAN
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* /install/*

# ADDITIONALS CONFIG
COPY ./config/php/php-ini-overrides.ini /etc/php/${PHP_RELEASE}/fpm/conf.d/99-overrides.ini
COPY ./config/php/php-ini-overrides.ini /etc/php/${PHP_RELEASE}/cli/conf.d/99-overrides.ini
COPY ./config/system/alias.sh /etc/profile.d/01-alias.sh

RUN cat /etc/profile.d/01-alias.sh > /etc/bash.bashrc

WORKDIR ${APPDIR}

USER www-data:www-data

COPY application ${APPDIR}
RUN cd ${APPDIR} && composer install --no-dev

VOLUME [ "/data" ]

# Initializing Redis server and Gunicorn server from supervisord
CMD ["php","-S","0.0.0.0:80","-t", "/application/public"]
