<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

if(isset($_REQUEST['id']) != true)
{
	header("location: ../user/mdc.php");
}

include_once '../class/Character.class.php';
include_once '../class/assets/dbconfig.php';

$id = $_REQUEST['id'];
$db = getDB();

$stmt = $db->prepare("SELECT * FROM police_arrest_records WHERE id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
$target = $data['target'];
$officer = $data['officer'];
$record = $data['record'];
$date = $data['created_at'];

$crime_string = "";

$stmt = $db->prepare("SELECT crime, type FROM police_crime_records WHERE record_id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();
while($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$crime = $data['crime'];
	$type = ($data['type']) ? "AMM." : "PEN.";
	$crime_string .= "- (<b>$type</b>) $crime<br>";
}

echo $records = ("

	<hr>
	
	<p>Nome Cognome: <b>$target</b></p>
	<p>Agente: <b>$officer</b></p>
	<p>Data: <b>$date</b></p>
	<p>Rapporto: <b>$record</b></p>
	<p>Reati:<br></p>
	<p>$crime_string</p>
	
	<center><button type='button' class='btn btn-default' onclick='searchRecord()'>Indietro</button></center>
	
	");

die();
	
?>
