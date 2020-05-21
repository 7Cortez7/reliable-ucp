<?php

include_once '../core/userarea.php';

if(isset($_POST['character_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/Character.class.php';
include_once '../class/User.class.php';

include_once '../class/assets/dbconfig.php';

$character = new Character($_POST['character_id']);
$account = $character->getAccountID();
$id = $character->getID();

$db = getDB();
$stmt = $db->prepare("UPDATE characters SET deleted = '1', deleted_at = NOW() WHERE id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();	

$user = new User($account);
$user->playerLog("delete_character", $id, -1);

echo ("<script>location.href = '../user/import.php';</script>");
	
?>

