<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

if(isset($_REQUEST['name']) != true)
{
	header("location: ../user/mdc.php");
}

include_once '../class/Character.class.php';
include_once '../class/assets/dbconfig.php';

include_once '../core/itemconfig.php';

$name = $_REQUEST['name'];
$db = getDB();

$stmt = $db->prepare("SELECT id FROM characters WHERE char_name LIKE CONCAT ('%', :name, '%')");
$stmt->bindParam("name", $name, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();

if(!$count)
{
	echo $players = ("Veritabanında bulunamadı."); die();
}

$data = $stmt->fetch(PDO::FETCH_ASSOC);
$id = $data['id'];

$citizen = new Character($id);
$name = $citizen->getCharacterName();
$boat = $citizen->getBoatLicense();
$fly = $citizen->getFlyLicense();
$house = $citizen->getHouse();
$drive = $citizen->getDriveLicense();
$warns = $citizen->getDriveWarns();
$skin = $citizen->getSkin();

$boat = ($boat) ? "Evet" : "Hayır";
$fly = ($fly) ? "Evet" : "Hayır";
$drive = ($drive) ? "Evet ($warns uyarı)" : "Hayır";

$house_string = "";
$biz_string = "";
$phone_string = "";
$vehicle_string = "";

$stmt = $db->prepare("SELECT h.id, h.owner_id, COUNT(h.id) AS num FROM houses h JOIN houses c ON h.id > c.id WHERE h.owner_id=:id GROUP BY h.id;");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();
$houses = $stmt->rowCount();

if(!$houses && $house == -1) $house_string = "Ev bulunamadı.<br>";
if($house != -1) $house_string = "Kiralık Ev (ID: <b>$house</b>)<br>";
if($houses)while($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$house = $data['num'];
	$house_string .= "Ev (ID: <b>$house</b>)<br>";
}

$stmt = $db->prepare("SELECT b.name, b.id, b.owner_id, COUNT(b.id) AS num FROM buildings b JOIN buildings c ON b.id > c.id WHERE b.owner_id=:id GROUP BY b.id;");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();
$buildings = $stmt->rowCount();

if(!$buildings) $biz_string = "İşletme bulunamadı.";
if($buildings)while($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$biz = $data['num'];
	$bname = $data['name'];
	$biz_string .= "$bname (ID: <b>$biz</b>)<br>";
}

$stmt = $db->prepare("SELECT number FROM phones WHERE owner=:name");
$stmt->bindParam("name", $name, PDO::PARAM_INT);
$stmt->execute();
$phones = $stmt->rowCount();

if(!$phones) $phone_string = "Numara bulunamadı.";
if($phones)while($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$number = $data['number'];
	$phone_string .= "$number<br>";
}

$stmt = $db->prepare("SELECT model, plate FROM vehicles WHERE owner_id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();
$vehicles = $stmt->rowCount();

if(!$vehicles) $vehicle_string = "Araç bulunamadı.";
if($vehicles)while($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$model = $data['model'];
	$plate = $data['plate'];
	$model_name = $models[$model - 400];
	$vehicle_string .= "$model_name (<b>$plate</b>)<br>";
}

$name = str_replace("_", " ", $name);

echo $players = ("

	<hr>
	<ul class='nav nav-tabs'>

	<li class='active'><a href='#info' data-toggle='tab'>Bilgi</a>
    </li>

	<li><a href='#properties' data-toggle='tab'>Mülkler</a>
	</li>

	<li><a href='#vehicles' data-toggle='tab'>Araçlar</a>
	</li>

	<li><a href='#numbers' data-toggle='tab'>Numaralar</a>
	</li>

	</ul>

	<div class='tab-content'>

		<div class='tab-pane fade in active' id='info'>
		<br>
		<p>Ad Soyad: <b>$name</b>
		<img style='width: 60px; height: 100px;' src='../core/images/accounts/small_skins/Skin_$skin.png' align='right'></p>
		<p>Sürücü Lisansı: <b>$drive</b></p>
		<p>Uçuş Lisansı: <b>$fly</b></p>
		<p>Deniz Aracı Lisansı: <b>$boat</b></p>
		</div>

		<div class='tab-pane fade' id='properties'>
		<br>
		<p>$house_string $biz_string</p>
		</div>

		<div class='tab-pane fade' id='vehicles'>
		<br>
		<p>$vehicle_string</p>
		</div>

		<div class='tab-pane fade' id='numbers'>
		<br>
		<p>$phone_string</p>
		</div>

	</div>

	<center><button type='button' class='btn btn-default' onclick='hideCitizen()'>Kapat</button></center>

	");

die();

?>
