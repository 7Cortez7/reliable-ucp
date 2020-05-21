<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['app_id']) != true)
{
	header("location: whitelist.php");
}

include_once '../class/assets/dbconfig.php';
include_once '../core/supporterarea.php';

include_once '../class/Application.class.php';
include_once '../class/User.class.php';

$admin = $_COOKIE['account'];

$app = $_REQUEST['app_id'];
$result = $_REQUEST['result'];
$reason = $_REQUEST['reason'];

$application = new Application($app);
$account = $application->getAccountId();
$name = $application->getName();
$skin = $application->getSkin();
$sex = $application->getSex();
$description = $application->getBackground();
$license = $application->getLicense();
$telephone = $application->getTelephone();
$status = $application->getStatus();

if(strlen($status) > 1)die();

$db = getDB();

$stmt = $db->prepare("UPDATE character_applications SET is_accepted=:result WHERE id=:id");
$stmt->bindParam("result", $result, PDO::PARAM_INT);
$stmt->bindParam("id", $app, PDO::PARAM_INT);
$stmt->execute();

if($result)
{
	$stmt = $db->prepare("INSERT INTO characters (account_id, char_name, sex, skin, spawn_point, description, drive_license, telephone, first_login) VALUES (:account, :name, :sex, :skin, 1, :description, :license, :telephone, 1)");
	$stmt->bindParam("account", $account, PDO::PARAM_INT);
	$stmt->bindParam("name", $name, PDO::PARAM_INT);
	$stmt->bindParam("sex", $sex, PDO::PARAM_INT);
	$stmt->bindParam("skin", $skin, PDO::PARAM_INT);
	$stmt->bindParam("description", $description, PDO::PARAM_INT);
	$stmt->bindParam("license", $license, PDO::PARAM_INT);
	$stmt->bindParam("telephone", $telephone, PDO::PARAM_INT);
	$stmt->execute();

	$char_id = $db->lastInsertId();

	$stmt = $db->prepare("UPDATE character_applications SET character_id=:char_id WHERE id=:id");
	$stmt->bindParam("char_id", $char_id, PDO::PARAM_INT);
	$stmt->bindParam("id", $app, PDO::PARAM_INT);
	$stmt->execute();
}
else
{
	$stmt = $db->prepare("UPDATE character_applications SET reason=:reason WHERE id=:id");
	$stmt->bindParam("reason", $reason, PDO::PARAM_STR);
	$stmt->bindParam("id", $app, PDO::PARAM_INT);
	$stmt->execute();
}

$name = str_replace("_", " ", $name);
$result_text = ($result) ? "onayladi" : "reddetti";

$object = "$name sonuçlandi.";
$text = "
			Los Santos Stories ekibi karakterin <b>$name</b>'i inceledi ve <b>$result_text</b>.
			<br><hr><br>
			<center>Yorum:</center><br>
			$reason
		";

$user = new User($account);
$user->playerAlert($object, $text);

?>
