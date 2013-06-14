#!/bin/sh

#install mpc
wget http://www.multiprecision.org/mpc/download/mpc-1.0.tar.gz
tar xvf mpc-1.0.tar.gz
cd mpc-1.0
./configure
make
make install
cd ..
rm -rf mpc-1.0.tar.gz
rm -rf mpc-1.0

