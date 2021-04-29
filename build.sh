#!/bin/sh

TAG=${1:-"latest"}

docker build --rm -f Dockerfile --tag wkhtml2pdf-saas:${TAG} .

