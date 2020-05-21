<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

if(isset($_POST['id']) != true)
{
	header("location: ../user/mdc.php");
}

include_once '../class/assets/dbconfig.php';
include_once '../class/User.class.php';

$id = $_POST['id'];
$target = $_POST['target'];
$location = $_POST['location'];
$level = $_POST['level'];
$crime = $_POST['crime'];
$db = getDB();

$stmt = $db->prepare("UPDATE wanteds SET level=:level, location=:location, target=:target WHERE id=:id");
$stmt->bindParam("level", $level, PDO::PARAM_INT);
$stmt->bindParam("location", $location, PDO::PARAM_STR);
$stmt->bindParam("target", $target, PDO::PARAM_STR);
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();

if(isset($_POST['crime']) == true && strlen($crime) > 3)
{
	$stmt = $db->prepare("INSERT INTO police_crime_records (crime, wanted_id) VALUES (:crime, :id)");
	$stmt->bindParam("crime", $crime, PDO::PARAM_INT);
	$stmt->bindParam("id", $id, PDO::PARAM_INT);
	$stmt->execute();
}

$user = new User($_COOKIE['account']);
$user->playerLog("apb_update", $id, -1);

die();
	
?>
