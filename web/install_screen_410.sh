#!/bin/sh

yum -y install git

#install autogen
yum -y install autoconf

#install ncurses-devel
yum -y install ncurses-devel

#install make
yum -y install make

#install screen
git clone git://git.savannah.gnu.org/screen.git
cd screen/src
autoconf
autoheader
./configure --enable-pam --enable-colors256 --enable-rxvt_osc --enable-use-locale --enable-telnet
make
make install
cd ../../
rm -rf screen
