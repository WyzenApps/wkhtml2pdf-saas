#!/bin/bash

DATA_DIR="$(pwd)/data"

docker run -v $DATA_DIR:/application/data -p 8888:80 -it wkhtml2pdf-saas:latest
