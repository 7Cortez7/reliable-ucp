<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

if(isset($_REQUEST['plate']) != true)
{
	header("location: ../user/mdc.php");
}

include_once '../class/Character.class.php';
include_once '../class/assets/dbconfig.php';

include_once '../core/itemconfig.php';

$plate = $_REQUEST['plate'];
$db = getDB();

$stmt = $db->prepare("SELECT plate, model, id, owner_id FROM vehicles WHERE plate=:plate");
$stmt->bindParam("plate", $plate, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();

if(!$count)
{
	echo $plates = ("Veritabanında bulunamadı."); die();
}

$data = $stmt->fetch(PDO::FETCH_ASSOC);
$id = $data['id'];
$model = $data['model'];
$model_name = $models[$model - 400];
$owner_id = $data['owner_id'];
$plate = $data['plate'];

if($owner_id)
{
	$owner = new Character($owner_id);
	$name = $owner->getCharacterName();
	$name = str_replace("_", " ", $name);
}

echo $plates = ("

	<hr>

	<p><img style='width: 150px; height: 80px;' src='../core/images/accounts/vehicles/Vehicle_$model.jpg' align='right'></p>
	<p><p>Model: <b>$model_name</b></p>
	<p>Plaka: <b>$plate</b></p>
	<p>Sahip: <b>$name</b></p>

	<center><button type='button' class='btn btn-default' onclick='hidePlates()'>Kapat</button></center>

	");

die();

?>
