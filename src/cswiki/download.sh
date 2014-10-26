#!/usr/bin/env bash

TEMP_DIR="/tmp/wikimedia-download/"
DUMPS_URL="https://dumps.wikimedia.org/cswiki/latest/"

rm -rf $TEMP_DIR
mkdir $TEMP_DIR

FILE="cswiki-latest-all-titles.gz"
wget ${DUMPS_URL}${FILE} -O ${TEMP_DIR}${FILE}
gzip -d ${TEMP_DIR}${FILE}
