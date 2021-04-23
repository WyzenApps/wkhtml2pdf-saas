#!/bin/sh

TAG=${1:-"wkhtml2pdf-saas"}

if [ ! -f config/wkhtmltox.deb ]; then
	wget -O wkhtmltox.deb  https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.buster_amd64.deb
fi;

docker build -f Dockerfile --tag ${TAG}:latest .

