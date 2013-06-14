!/bin/sh
#入力された正規表現に当てはまるファイルの文字コードをUTF-8に変換する
for read in $*
do
cp $read $read.bak
nkf -w $read.bak > $read
rm $read.bak
done
