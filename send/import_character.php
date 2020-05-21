<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['character_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/assets/dbconfig.php';

$id = $_REQUEST['character_id'];

$db = getDB();
$stmt = $db->prepare("UPDATE characters SET deleted = '0', deleted_at = NULL WHERE id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();
