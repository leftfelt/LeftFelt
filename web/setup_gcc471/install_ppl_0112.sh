#!/bin/sh

#install ppl
wget http://bugseng.com/external/ppl/download/ftp/releases/0.11.2/ppl-0.11.2.tar.gz
tar xvf ppl-0.11.2.tar.gz
cd ppl-0.11.2
./configure

make
make install
cd ..
rm -rf ppl-0.11.2.tar.gz
rm -rf ppl-0.11.2

