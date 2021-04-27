#!/bin/sh

TAG=${1:-"wkhtml2pdf-saas"}

if [ ! -f config/wkhtmltox.deb ]; then
	wget -O config/wkhtmltox.deb  https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.buster_amd64.deb
fi;

if [ ! -f config/libjpeg62-turbo.deb ]; then
	wget -O config/libjpeg62-turbo.deb http://ftp.br.debian.org/debian/pool/main/libj/libjpeg-turbo/libjpeg62-turbo_1.5.2-2+deb10u1_amd64.deb
fi;

docker build -f Dockerfile --tag ${TAG}:latest .

