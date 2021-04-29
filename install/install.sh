#!/bin/bash

INSTALL_DIR=${1:-"/install"}

if [ ! -d ${INSTALL_DIR} ]; then
	mkdir ${INSTALL_DIR}
fi;

if [ ! -f ${INSTALL_DIR}/libjpeg62-turbo.deb ]; then
	echo "Download libjpeg62-turbo"
	wget -O ${INSTALL_DIR}/libjpeg62-turbo.deb http://ftp.br.debian.org/debian/pool/main/libj/libjpeg-turbo/libjpeg62-turbo_1.5.2-2+deb10u1_amd64.deb
fi;

if [ ! -f ${INSTALL_DIR}/wkhtmltox.deb ]; then
	echo "Download wkhtmltox"
	wget -O ${INSTALL_DIR}/wkhtmltox.deb  https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.buster_amd64.deb
fi;

echo "Installation de libjpeg62-turbo.deb"
dpkg -i ${INSTALL_DIR}/libjpeg62-turbo.deb
echo "Installation de wkhtmltox.deb"
dpkg -i ${INSTALL_DIR}/wkhtmltox.deb

