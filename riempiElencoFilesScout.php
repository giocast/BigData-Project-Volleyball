<?php

$myfile = fopen("elenco_files_scout_def.js", "w") or die("Unable to open file!");

$fileList = glob('Scout_Files/*');

$i = 0;
$len = count($fileList);

echo $len;

foreach($fileList as $filename){
    if(is_file($filename)){
    	
        if ($i == $len - 1) {
        	echo $filename, '<br>';
        	$filename = $filename;
        	fwrite($myfile, $filename);
        
        } else {
    	
        	echo $filename, '<br>';
        	$filename = $filename.PHP_EOL;
        	fwrite($myfile, $filename);
    	}
        
         $i++;
    }   
}

fclose($myfile);



?>