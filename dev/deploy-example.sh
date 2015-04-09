#!/bin/bash

tar -cvf all.tar src/* vendor/*
scp all.tar root@127.0.0.1:all.tar

ssh root@127.0.0.1 <<'ENDSSH'
rm -rf src/
tar xvf all.tar
sudo rm -rf /opt/wikipedia-analyse
sudo mkdir /opt/wikipedia-analyse
sudo mv src /opt/wikipedia-analyse/src
sudo mv vendor /opt/wikipedia-analyse/vendor
sudo mkdir /opt/wikipedia-analyse/temp
sudo chmod -R a+rw /opt/wikipedia-analyse/temp
rm all.tar
ENDSSH

rm all.tar
