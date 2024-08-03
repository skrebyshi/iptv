<?php

// Get info, set here your API info.
$client_id = "bfa67fcff0c409e5f0dd169a32453ed7b1ba0825"; 
$vimeo_secret = "Dk4i4XuBZMNWiRFK3frYf/tpJ6LzDCkDlvmG7j5p9pggKU31sVtKEYVMc/g5VazeZ0kDAqUIo6NU2zNCJbs2HBpQMLAZSDqKewFOa7IE7iZt3ELjE/Husv43/NazOTur";
$vimeo_token = "f0e000c5a57980b346ac4c3382322d57";

// Check if values are set.
if(isset($_REQUEST['client_id'])){
	$client_id = $_REQUEST['client_id'];
} 

if(isset($_REQUEST['vimeo_secret'])){
	$vimeo_secret = $_REQUEST['vimeo_secret'];
} 

if(isset($_REQUEST['vimeo_token'])){
	$vimeo_token = $_REQUEST['vimeo_token'];
}

if(!isset($_REQUEST['type']) || !isset($_REQUEST['page']) || !isset($_REQUEST['per_page'])){
	 exit("Vimeo access info missing!");
}


// Reques data.
require("autoload.php");

$type 		= $_REQUEST['type'];
$page 		= $_REQUEST['page'];
$per_page 	= $_REQUEST['per_page'];
$album_id 	= $_REQUEST['album_id'];
$user 		= $_REQUEST['user'];
$sort 		= isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'default';

$lib = new Vimeo\Vimeo($client_id, $vimeo_secret, $vimeo_token);

if($type == 'vimeo_channel'){
	$path 		= $_REQUEST['path'];
	
	// Vimeo channel
	$r = $lib->request("/channels/$path/videos", array(
		'page'		=> $page,
		'per_page' 	=> $per_page,
		'fields' 	=> 'uri,name,description,privacy,pictures.sizes',
		'sort' 		=> $sort
	));
}else if($type == 'vimeo_user_album'){
	// Vimeo album.
	$r = $lib->request("/users/$user/albums/$album_id/videos", array(
		'page'		=> $page,
		'per_page' 	=> $per_page,
		'fields' 	=> 'uri,name,description,privacy,pictures.sizes',
		'sort' 		=> $sort
	));										
}

// Return JSON result.
echo json_encode($r);

?>