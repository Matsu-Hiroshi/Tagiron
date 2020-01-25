<?php

	namespace MyApp;

	$array = array();

	for($i = 0; $i < 4; $i++) {
		$array += [ $i => array() ];
		echo $i."\n";
	}

	var_dump($array);
	echo "\n".$array[0]."\n";
	
?>