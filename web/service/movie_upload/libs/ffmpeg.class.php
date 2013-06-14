<?php

class ffmpeg {
	//H264+AACで変換
	function convert($input,$output){
		
		exec("ffmpeg -i $input  -vcodec libx264 -vpre default -acodec libfaac -y $output");
		exec("rm $input");
		return file_exists($output);
	}
}

