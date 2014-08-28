<?php
include 'classes/api.php';
$api = new ShotpicAPI();
$token = $_POST['token'];
$drawings = $_POST['drawings'];
if(!isset($drawings) && isset($_POST['boxCount']) && $_POST['boxCount'] == 0) {
	$drawings = array();
}
if(isset($token, $drawings) && file_exists($api->getFilePath($token))) {
	$metadata = $api->getFileMetadata($token);
	if($metadata == null)
	{
		$metadata = array();
	}
	$metadata['drawings'] = $drawings;
	$api->setFileMetadata($token, $metadata);
}
else
{
	header("HTTP/1.0 404 Not Found");
}