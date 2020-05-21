<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['character_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/User.class.php';
include_once '../class/assets/dbconfig.php';
include_once '../core/skinconfig.php';

$id = $_REQUEST['character_id'];
$skin = $_REQUEST['skin'];
$sex = $_REQUEST['sex'];

if(in_array($skin, $unavailable_skins) == true or in_array($skin, $female_skins) != $sex)
{
	die();
}

$db = getDB();
$stmt = $db->prepare("UPDATE characters SET skin=:skin WHERE id=:id");
$stmt->bindParam("skin", $skin, PDO::PARAM_INT);
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();	

echo $skinImg = ("<img style='width: 700px; height: 500px;' img src='../core/images/accounts/skins/$skin.png' align='right'>");

$user = new User($_COOKIE['account']);
$user->playerLog("skin_character", $id, $skin);
		
?>

