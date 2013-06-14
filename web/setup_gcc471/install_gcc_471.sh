#!/bin/sh
: << '#_comment_out'

mkdir install_gcc_471_work_dir
cd install_gcc_471_work_dir

#pre install
yum -y install gcc
yum -y install gcc-c++
yum -y install glibc-devel
yum -y install kernel-devel
yum -y install glibc-devel.i686

#install gmp
../install_gmp505.sh 2>&1 install_gmp505.log

#install mpfr
../install_mpfr311.sh 2>&1 install_mpfr311.log

#install mpc
../install_mpc_10.sh 2>&1 install_mpc_10.log

#install ppl
../install_ppl_0112.sh 2>&1 install_ppl_0112.log

#install binutils
../install_binutils_2211.sh 2>&1 install_binutils_2211.log

#_comment_out

#install gcc-4.7.1
wget http://ftp.gnu.org/gnu/gcc/gcc-4.7.1/gcc-4.7.1.tar.bz2
bzip2 -dc gcc-4.7.1.tar.bz2 | tar xvf -
cd gcc-4.7.1
export LD_LIBRARY_PATH=/usr/local/lib
./configure 
make
make install
cd ..
rm -rf gcc-4.7.1.tar.bz2
rm -rf gcc-4.7.1

# add path
echo '/usr/lib' >> /etc/ld.so.conf
echo '/usr/local/lib' >> /etc/ld.so.conf
echo '/usr/local/lib64' >> /etc/ld.so.conf
/sbin/ldconfig

