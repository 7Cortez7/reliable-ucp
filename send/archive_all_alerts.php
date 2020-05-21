<?php

include_once '../core/userarea.php';
include_once '../class/assets/dbconfig.php';

$account = $_COOKIE['account'];

$db = getDB();
$stmt = $db->prepare("UPDATE ucp_alerts SET archived = '1' WHERE account=:account");
$stmt->bindParam("account", $account, PDO::PARAM_INT);
$stmt->execute();	

?>
