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

$stmt = $db->prepare("SELECT * FROM wanteds WHERE id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
$target = $data['target'];
$officer = $data['officer'];
$level = $data['level'];
$location = $data['location'];
$date = $data['created_at'];

$crime_string = "";

$stmt = $db->prepare("SELECT crime FROM police_crime_records WHERE wanted_id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();
while($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$crime = $data['crime'];
	$crime_string .= "- $crime<br>";
}

echo $apb = ("

	<form id='wanteds' action='../send/update_wanteds.php' method='POST'>
	<input type='text' name='id' value='$id' hidden>

	<p>Aciliyet Seviyesi: <b><input name='level' type='number' class='text-left' min='1' max='3' readonly value='$level' style='background-color: transparent; border: none;'></b></p>
	<p>Başlık/Konu: <b><input name='target' type='text' class='text-left' readonly value='$target' style='background-color: transparent; border: none;'></b></p>
	<p>Bölge: <b><input name='location' type='text' class='text-left' readonly value='$location' style='background-color: transparent; border: none;'></b></p><br>

	<p>Oluşturan: <b>$officer</b></p>
	<p>Tarih: <b>$date</b></p>

	<br>Detay:<br></p>
	<p>$crime_string</p>
	<div id='add'></div>

	<center>
	<span id='btn'><button type='button' class='btn btn-success' onclick='editAPB($id)'>Düzenle</button></span>
	<button type='button' class='btn btn-danger' onclick='deleteAPB($id)'>Sil</button><br>
	<br><button type='button' class='btn btn-default' onclick='searchAPB()'>Kapat</button>
	</center>

	</form>

	");

die();

?>
