#!/usr/bin/env bash

rm -rf /tmp/wikimedia-download
mkdir /tmp/wikipedia-download

wget https://dumps.wikimedia.org/cswiki/latest/cswiki-latest-all-titles.gz -O /tmp/wikipedia-download/cswiki-latest-all-titles.gz
gzip -d /tmp/wikipedia-download/cswiki-latest-all-titles.gz
