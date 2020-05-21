<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

if(isset($_POST['target']) != true)
{
	header("location: ../user/mdc.php");
}

include_once '../class/assets/dbconfig.php';
include_once '../class/Character.class.php';

$target = $_POST['target'];
$db = getDB();

$character = new Character($_POST['officer']);
$officer = $character->getCharacterName();
$officer = str_replace("_", " ", $officer);

$stmt = $db->prepare("SELECT id FROM wanteds WHERE target LIKE CONCAT ('%', :target, '%')");
$stmt->bindParam("target", $target, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();

if($count)
{
	$data = $stmt->fetch(PDO::FETCH_ASSOC);
	$id = $data['id'];

	echo $apb = ("APB veritabanına eklendi.<br><a onclick='showAPB($id)'>Buraya tıklayarak</a> görebilirsiniz."); die();
}

$stmt = $db->prepare("INSERT INTO wanteds (created_at, target, officer) VALUES (NOW(), :target, :officer)");
$stmt->bindParam("target", $target, PDO::PARAM_STR);
$stmt->bindParam("officer", $officer, PDO::PARAM_STR);
$stmt->execute();

$apb_id = $db->lastInsertId();

echo $apb = ("APB veritabanına eklendi.<br><a onclick='showAPB($apb_id)'>Buraya tıklayarak</a> bitirebilirsiniz."); die();

?>
