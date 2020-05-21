<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

if(isset($_REQUEST['number']) != true)
{
	header("location: ../user/mdc.php");
}

include_once '../class/Character.class.php';
include_once '../class/assets/dbconfig.php';

include_once '../core/itemconfig.php';

$number = $_REQUEST['number'];
$db = getDB();

$stmt = $db->prepare("SELECT * FROM phones WHERE number=:number");
$stmt->bindParam("number", $number, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();

if(!$count)
{
	echo $phones = ("Veritabanıda bulunamadı."); die();
}

$data = $stmt->fetch(PDO::FETCH_ASSOC);
$owner = $data['owner'];
$date = $data['created_at'];
$biz = $data['biz'];
$number = $data['number'];

$owner = str_replace("_", " ", $owner);
$biz_string = "";
$log_string = "";

$stmt = $db->prepare("SELECT b.name, b.id, COUNT(b.id) AS num FROM buildings b JOIN buildings c ON b.id > c.id WHERE b.id=:id GROUP BY b.id;");
$stmt->bindParam("id", $biz, PDO::PARAM_INT);
$stmt->execute();
$buildings = $stmt->rowCount();

if(!$buildings) $biz_string = "Kimse";
if($buildings)
{
	$data = $stmt->fetch(PDO::FETCH_ASSOC);
	$biz = $data['num'];
	$bname = $data['name'];
	$biz_string .= "$bname (ID: <b>$biz</b>)<br>";
}

$stmt = $db->prepare("SELECT * FROM phone_logs WHERE caller_number='$number' OR receiver_number='$number' ORDER BY created_at DESC LIMIT 1;");
$stmt->execute();
$logs = $stmt->rowCount();

if(!$logs) $log_string = "<b>Yok</b>";
if($logs) while($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$caller = $data['caller_number'];
	$receiver = $data['receiver_number'];
	$type = $data['type'];
	$date = $data['created_at'];
	$log_string =

		($type == 1 && $receiver == $number) ? "<b>SMS</b> da <b>$caller</b> il <b>$date</b><br>" :
		($type == 1 && $caller == $number) ? "<b>SMS</b> a <b>$receiver</b> il <b>$date</b><br>" :
		($type == 2 && $receiver == $number) ? "<b>Giden Arama</b> da <b>$caller</b> il <b>$date</b><br>" :
											 "<b>Gelen Arama</b> <b>$receiver</b> il <b>$date</b><br>";
}

echo $phones = ("

	<hr>

	<p>Numara: <b>$number</b></p>
	<p>Sahip: <b>$owner</b></p>
	<p>Satın alınma tarihi: <b>$date</b></p>
	<p>Şirket: <b>$biz_string</b></p>
	<p>Son yapılan işlem: $log_string</p>

	<center><button type='button' class='btn btn-default' onclick='hidePhones()'>Kapat</button></center>

	");

die();

?>
