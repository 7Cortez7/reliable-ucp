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
$banned = $_REQUEST['banned'];

$db = getDB();
$stmt = $db->prepare("UPDATE characters SET banned=:banned WHERE id=:id");
$stmt->bindParam("banned", $banned, PDO::PARAM_INT);
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();

echo $txtBanned = ($banned) ? ("Karakter yasaklandı.") : ("Karakterin yasağı açıldı.");

$admin = new User($_COOKIE['account']);
$admin->playerLog("ban_character", $id, $banned);

?>
