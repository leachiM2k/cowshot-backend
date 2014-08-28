<?php
require_once "classes/api.php";
require_once "jsonRPCServer.php";

$api = new ShotpicAPI();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

$isBadRequest = false;

switch ($method) {
  case 'PUT':
	$token = $_REQUEST['token'];
	if(isset($token)) {
		$api->putFile($token);
	} else {
		$isBadRequest = true;
	}
	break;
  case 'POST':
	$isBadRequest = !jsonRPCServer::handle($api);
	break;
  default:
	$isBadRequest = true;
	break;
}

if($isBadRequest) :
header("HTTP/1.1 400 Bad request");
?>
<h1>Bad Request</h1>
<p>Not a jsonrpc request.</p>
<p>Known Methods:</p>
<?php echo join('<br>', $api->getPublicMethods()); ?>
<?php endif;
