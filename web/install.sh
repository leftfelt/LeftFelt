#!/bin/bash

#リポジトリ追加
rpm --import http://dl.fedoraproject.org/pub/epel/RPM-GPG-KEY-EPEL-6
rpm --import http://rpms.famillecollet.com/RPM-GPG-KEY-remi
rpm --import http://apt.sw.be/RPM-GPG-KEY.dag.txt
rpm -ivh http://dl.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
rpm -ivh http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-0.5.2-2.el6.rf.x86_64.rpm
sed -e 's/enabled\=1/enabled\=0/' -i /etc/yum.repos.d/epel.repo
sed -e 's/enabled\=1/enabled\=0/' -i /etc/yum.repos.d/remi.repo
sed -e 's/enabled\=1/enabled\=0/' -i /etc/yum.repos.d/rpmforge.repo

yum -y install yum-priorities

#: << '#comment_out'
yum -y install php
yum -y install mysql
yum -y install mysql-server
yum -y install --enablerepo=remi,epel redis
yum -y install php-redis
yum -y install memcached
yum -y install php-pdo
yum -y install php-mysql
yum -y install php-devel
yum -y install mysql-devel
pecl -y install pdo_mysql
yum -y install php-pecl-memcache
yum -y install ftp

#comment_out

chkconfig mysqld on
chkconfig redis on
chkconfig memcached on

service mysqld start
service redis start
service memcached start
