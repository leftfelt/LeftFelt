<?php

require_once('../libs/ffmpeg.class.php');

$filename = @$_GET['filename'];

if(!isset($filename)) exit;

$movie_path = '../uploads/' . $filename;
$output_path = '../videos/output.mp4';
$movie = new ffmpeg_movie($movie_path);


$file_name = h($movie->getFileName());
$bitrate = h($movie->getFrameRate());
$videocodec = h($movie->getVideoCodec());
$audiocodec = h($movie->getAudioCodec());

echo "Filename : {$file_name}<br />\n";
echo "BitRate : {$bitrate}<br />\n";
echo "VideoCodec : {$videocodec}<br />\n";
echo "AudioCodec : {$audiocodec}<br />\n";
convert_test($movie_path,$output_path);
exit();

function h($str){
	return htmlspecialchars($str,ENT_QUOTES);
}

function convert_test($input,$output){
	$ffmpeg = new ffmpeg();
	$ffmpeg->convert($input,$output);
	echo 'complete!<br>';
}
