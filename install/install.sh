#!/bin/bash

INSTALL_DIR=${1:-"/install"}

if [ ! -d ${INSTALL_DIR} ]; then
	mkdir ${INSTALL_DIR}
fi;

