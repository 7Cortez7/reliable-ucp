<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['character_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/User.class.php';
include_once '../class/assets/dbconfig.php';

$spawnpoint = $_REQUEST['spawn'];
$id = $_REQUEST['character_id'];

$db = getDB();
$stmt = $db->prepare("UPDATE characters SET spawn_point=:spawnpoint WHERE id=:id");
$stmt->bindParam("spawnpoint", $spawnpoint, PDO::PARAM_INT);
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();

echo $txtSpawn = ("Spawn konumu güncellendi.");

$user = new User($_COOKIE['account']);
$user->playerLog("spawn_character", $id, $spawnpoint);

?>
