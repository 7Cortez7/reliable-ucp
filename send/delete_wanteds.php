<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

if(isset($_REQUEST['id']) != true)
{
	header("location: ../user/mdc.php");
}

include_once '../class/assets/dbconfig.php';

$id = $_REQUEST['id'];
$db = getDB();

$stmt = $db->prepare("DELETE FROM wanteds WHERE id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM police_crime_records WHERE wanted_id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();

$user = new User($_COOKIE['account']);
$user->playerLog("apb_delete", $id, -1);

die();
	
?>
