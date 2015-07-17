#!/bin/bash

tar -cvf all.tar src/* vendor/*
scp all.tar root@127.0.0.1:all.tar

ssh root@127.0.0.1 <<'ENDSSH'
rm -rf src/
tar xvf all.tar
rm -rf /opt/wikipedia-analyse
mkdir /opt/wikipedia-analyse
mv src /opt/wikipedia-analyse/src
mv vendor /opt/wikipedia-analyse/vendor
mkdir /opt/wikipedia-analyse/temp
chmod -R a+rw /opt/wikipedia-analyse/temp
rm all.tar
ENDSSH

rm all.tar
