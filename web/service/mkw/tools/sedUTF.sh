#!/bin/sh
#入力された正規表現に当てはまるファイルの文字コードをUTF-8に変換する
for read in $*
do
cp $read $read.bak
sed -e "s/Shift_JIS/UTF-8/g" $read.bak > $read
rm $read.bak
done
