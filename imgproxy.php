<?php
include 'classes/api.php';
$api = new ShotpicAPI();
$token = $_GET['token'];
if(isset($token) && file_exists($api->getFilePath($token))) {
	header("Content-Type: image/png");
	readfile( $api->getFilePath($token) );
}
else
{
	header("HTTP/1.0 404 Not Found");
}