<?php

include_once '../core/userarea.php';

if(isset($_COOKIE['account']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/assets/dbconfig.php';

$account = $_COOKIE['account'];

$db = getDB();
$stmt = $db->prepare("UPDATE ucp_alerts SET opened = '1' WHERE account=:account");
$stmt->bindParam("account", $account, PDO::PARAM_INT);
$stmt->execute();	

?>
