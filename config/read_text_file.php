<?php
	$filename = "db_info.txt";
	$lines = array();
	$fp = fopen($filename, "r");

	if(filesize($filename) > 0){
		$content = fread($fp, filesize($filename));
		$lines = explode("\n", $content);
		fclose($fp);
	}
	print_r($lines);
	foreach($lines as $k=>$newline){
		
		echo '<h3 style="color:#453288">'.$newline.'</h3><br>';
		echo $k;

	}
	
?>