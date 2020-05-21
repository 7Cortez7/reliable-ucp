<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

if(isset($_REQUEST['name']) != true)
{
	header("location: ../user/mdc.php");
}

include_once '../class/assets/dbconfig.php';

$name = $_REQUEST['name'];
$db = getDB();

$stmt = $db->prepare("SELECT id, created_at FROM police_arrest_records WHERE target=:name");
$stmt->bindParam("name", $name, PDO::PARAM_STR);
$stmt->execute();
$record = $stmt->rowCount();

if(!$record)
{
	echo $records = ("Veritabanında bulunamadı."); die();
}

$record_string = "";

while($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$id = $data['id'];
	$date = $data['created_at'];
	$record_string .= "<a onclick='showRecord($id)'>#<b>$id</b> - $date<br></a>";
}

echo $records = ("

	<hr>

	<p>$record_string</p>

	<center><button type='button' class='btn btn-default' onclick='hideRecords()'>Kapat</button></center>

	");

die();

?>
