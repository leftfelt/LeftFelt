#!/bin/sh

#install gmp
yum -y install gmp-devel
wget http://ftp.gnu.org/gnu/gmp/gmp-5.0.5.tar.xz
tar xvf gmp-5.0.5.tar.xz
cd gmp-5.0.5
./configure --enable-cxx
make
make check
make install
cd ..
rm -rf gmp-5.0.5
rm -rf gmp-5.0.5.tar.xz
