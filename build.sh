#!/bin/sh

TAG=${1:-"wkhtml2pdf-saas"}

docker build -f Dockerfile --tag ${TAG}:latest .

