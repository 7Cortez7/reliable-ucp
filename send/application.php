<?php

include_once '../core/userarea.php';

include_once '../class/User.class.php';
include_once '../class/Question.class.php';
include_once '../class/assets/dbconfig.php';

include_once '../core/skinconfig.php';

if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) == true)
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

$user = new User($_COOKIE['account']);
$char_count = $user->getCharactersCount();
$app_count = $user->getApplicationsWaiting();

if($app_count)
{
	echo ("Zaten başvurunuz bulunuyor."); die();
}

$db = getDB();

$name = $_POST['name'];
$surname = $_POST['surname'];
$sex = $_POST['sex'];
$skin = $_POST['skin'];
$telephone = (isset($_POST['telephone']) == true) ? $_POST['telephone'] : 0;
$drive_license = (isset($_POST['drive_license']) == true) ? $_POST['drive_license'] : 0;
$background = $_POST['background'];

if($name == "" || $surname == "" || $background == "")
{
	echo ("Tüm alanları doldurun."); die();
}

$character = $name . "_" . $surname;
$character = strtolower($character);
$character = ucwords($character, "_");

if(strlen($character) > 19)
{
	echo ("Ad ve Soyad 20 karakteri aşamaz."); die();
}

if(preg_match('/[^A-Za-z0-9_]/', $character) == true)
{
	echo ("Karakter adınızda geçersiz karakterler bulunuyor."); die();
}

$stmt = $db->prepare("SELECT (SELECT COUNT(id) FROM characters WHERE char_name = '$character') AS c_count, (SELECT COUNT(id) FROM character_applications WHERE char_name = '$character' AND is_accepted != 0) AS a_count FROM dual");
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
$result = $data['c_count'] + $data['a_count'];

if($result > 0)
{
	echo ("Personaggio già esistente."); die();
}

if(in_array($skin, $unavailable_skins) == true or in_array($skin, $female_skins) != $sex)
{
	echo ("Bu skin'i kullanamazsın.."); die();
}

$stmt = $db->prepare("INSERT INTO character_applications (skin, essay, account_id, ip_address, char_name, created_at, sex, wants_phone, wants_license) VALUES (:skin, :bg, :id, :ip, :name, NOW(), :sex, :phone, :license)");
$stmt->bindParam("skin", $skin, PDO::PARAM_INT);
$stmt->bindParam("bg", $background, PDO::PARAM_STR);
$stmt->bindParam("id", $_COOKIE['account'], PDO::PARAM_INT);
$stmt->bindParam("ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
$stmt->bindParam("name", $character, PDO::PARAM_STR);
$stmt->bindParam("sex", $sex, PDO::PARAM_INT);
$stmt->bindParam("phone", $telephone, PDO::PARAM_INT);
$stmt->bindParam("license", $drive_license, PDO::PARAM_INT);
$stmt->execute();

if($char_count < 1)
{
	$app_id = $db->lastInsertId();

	$answers = array();

	foreach($_POST['dm'] as $ind => $ans)
	{
		$answers[$ind] = ['q' => $ind, 'a' => $ans];
	}

	foreach($answers as $ans)
	{
		$q = $ans['q'];
		$a = $ans['a'];

		$stmt = $db->prepare("INSERT INTO character_application_answers (application_id, question_id, created_at, answer) VALUES (:app_id, :question_id, NOW(), :answer)");
		$stmt->bindParam("app_id", $app_id, PDO::PARAM_INT);
		$stmt->bindParam("question_id", $q, PDO::PARAM_INT);
		$stmt->bindParam("answer", $a, PDO::PARAM_STR);
		$stmt->execute();
	}
}

$character = str_replace("_", " ", $character);
$sex = ($sex) ? "Kadın" : "Erkek";
$drive_license = ($drive_license) ? "Var" : "Yok";
$telephone = ($telephone) ? "Var" : "Yok";

$object = "$character basvurusu olusturuldu.";
$text = "
			Asagidaki bildirim karakter basvurunuzu basariyla gönderdiginizi belirtmek içindir..<br>
			Karakter detaylari asagidadir. Sonuç belirtilince size bildirim gönderilecektir.
			<br><hr><br>
			<center><img src='../core/images/accounts/small_skins/Skin_$skin.png'></center><br><br>
			<b>Isim Soyisim:</b> $character<br>
			<b>Cinsiyet:</b> $sex<br>
			<b>Ehliyet:</b> $drive_license<br>
			<b>Cep Telefonu:</b> $telephone
			<br><hr><br>
			$background
		";

$user->playerAlert($object, $text);

echo ("Başvuru gönderildi."); die();

?>
