<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['app_id']) != true)
{
	header("location: whitelist.php");
}

include_once '../class/assets/dbconfig.php';
include_once '../core/supporterarea.php';

$account = $_COOKIE['account'];
$app = $_REQUEST['app_id'];

$db = getDB();
$stmt = $db->prepare("UPDATE character_applications SET handler_id=:account, handled_at=NOW() WHERE id=:id");
$stmt->bindParam("account", $account, PDO::PARAM_INT);
$stmt->bindParam("id", $app, PDO::PARAM_INT);
$stmt->execute();

echo $takeButton = ("

	<hr><textarea id='reason' class='form-control' rows='5' placeholder='Yorum'></textarea>
	<br><span type='button'><center>
	<button type='button' class='btn btn-danger' style='height: 30px;' onclick='evaluateApplication($app, 0)'>Reddet</button>
	<button type='submit' class='btn btn-success' style='height: 30px;' onclick='evaluateApplication($app, 1)'>Kabul Et</button>
	</span></center>");

$admin = new User($_COOKIE['account']);
$admin->playerLog("take_application", $account, $app);

?>
