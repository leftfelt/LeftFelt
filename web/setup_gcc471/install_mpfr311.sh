#!/bin/sh

#install mpfr
wget http://www.mpfr.org/mpfr-3.1.1/mpfr-3.1.1.tar.xz
tar xvf mpfr-3.1.1.tar.xz
cd mpfr-3.1.1
./configure 
make
make install
cd ..
rm -rf mpfr-3.1.1
rm -rf mpfr-3.1.1.tar.xz

