<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['character_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/User.class.php';
include_once '../class/assets/dbconfig.php';
include_once '../core/staffarea.php';

$id = $_REQUEST['character_id'];
$time = $_REQUEST['time'] * 60;

$db = getDB();
$stmt = $db->prepare("UPDATE characters SET jail_time=:time WHERE id=:id");
$stmt->bindParam("time", $time, PDO::PARAM_INT);
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();

echo $txtJail = ($time) ? ("Hapise gönderildi.") : ("Hapisten çıkarıldı.");

$admin = new User($_COOKIE['account']);
$admin->playerLog("jail_character", $id, $time);

?>
