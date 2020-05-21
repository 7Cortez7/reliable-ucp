<?php

include_once '../core/userarea.php';
include_once '../class/assets/dbconfig.php';

$account = $_COOKIE['account'];
$alert = $_REQUEST['alert_id'];

$db = getDB();
$stmt = $db->prepare("UPDATE ucp_alerts SET archived = '1' WHERE id=:alert");
$stmt->bindParam("alert", $alert, PDO::PARAM_INT);
$stmt->execute();	

?>
