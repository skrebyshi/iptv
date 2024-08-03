<?php

$url = "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$url = substr($url, 0, strrpos($url, "/") + 1); 

$videoLabel = isset($_GET['videoLabel']) ? $_GET['videoLabel'] : "Video ";
$dir = (isset($_GET['dir']) ? urldecode($_GET['dir']) : null);
$decodedeURL = urldecode($dir);
$decodedeURL = ltrim($decodedeURL, "/");
$decodedeURL = rtrim($decodedeURL, "/");
$decodedeURL = $decodedeURL . "/";
$url .= $decodedeURL;

$dirHandle = opendir($dir); 
$imlBody = '{"folder":[';
$main_ar = array();
$all_ar = array();
$ar = array();
$i = 0;

while ($file = readdir($dirHandle)) { 
      if(!is_dir($file) && strpos($file, '.mp4') || strpos($file, '.mp3')){
	    $i++; 
		if(strrpos($file, "-mobile") === false) array_push($main_ar, $file);
		$all_ar[$i] = $file;
     } 
} 
sort($main_ar);

for($i=0;$i<count($main_ar);$i++){
	$videoPath = $main_ar[$i];
	
	if(strrpos($videoPath, "http") === false){
		$ar[$i] = $url.$videoPath;
	}else{
		$ar[$i] = $videoPath;
	}
}

for($i=0;$i<count($ar);$i++){
	$imlBody .= '{"@attributes":{';
	$file = $ar[$i];
	$rawPath = $url.$main_ar[$i];
	$trackTitle;
	
	if(strrpos($file, ".") !== false){
		$rawPath =  substr($rawPath, 0, strrpos($rawPath, "."));
	}
	
	$trackTitle = substr($rawPath, strrpos($rawPath, "/") + 1);
	$imlBody .='"data-video-path":"' . $file . '",';
	$imlBody .='"data-thumb-path":"' . $rawPath . '-thumbnail.jpg",';
	$imlBody .='"data-poster-path":"' . $rawPath . '-poster.jpg",';
	if(strpos($file, '.mp4')){
		$imlBody .='"download-path":"' . $rawPath . '.mp4",';
	}else{
		$imlBody .='"download-path":"' . $rawPath . '.mp3",';
	}
	$imlBody .='"data-title":"' . $trackTitle . '"';
	
	if($i != count($ar) - 1){
		$imlBody .= "}},";
	}else{
		$imlBody .= "}}";
	}
 };
closedir($dirHandle);
$imlBody .= ']}';
echo $imlBody; 
?>