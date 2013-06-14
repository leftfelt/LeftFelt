
message="Usage) $0 --prefix=[path]"

if [ $# -eq 0 ]
then
	echo $message
	exit
fi

#preinstall
yum install -y python openssl-devel gcc-c++

#install node.js
wget http://nodejs.org/dist/v0.8.17/node-v0.8.17.tar.gz
tar -zxf node-v0.8.17.tar.gz
cd node-v0.8.17
./configure $1
make
make install
rm -rf node-v0.8.17
rm -rf node-v0.8.17.tar.gz

echo "'export PATH=$PATH:$1'をbashrcとかに追記してください"
