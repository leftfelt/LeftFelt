#!/bin/sh

#install binutils
wget http://ftp.gnu.org/gnu/binutils/binutils-2.21.1.tar.bz2
tar xjf binutils-2.21.1.tar.bz2
cd binutils-2.21.1
./configure 

make
make install
cd ..
rm -rf binutils-2.21.1.tar.bz2
rm -rf binutils-2.21.1

