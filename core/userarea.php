<?php

if(isset($_COOKIE['account']) != true or isset($_COOKIE['code']) != true)
{
	setcookie("code", null, time() - 1, "/");
	setcookie("account", null, time() - 1, "/");
	header("location: ../index.php");
}

include_once '../class/assets/dbconfig.php';

$id = $_COOKIE['account'];
$token = $_COOKIE['code'];

$db = getDB();
$stmt = $db->prepare("SELECT id, banned FROM accounts WHERE remember_token=:token");
$stmt->bindParam("token", $token, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if($count < 1 or $data['id'] != $id or $data['banned'] > 0)
{
	setcookie("code", null, time() - 1, "/");
	setcookie("account", null, time() - 1, "/");
	header("location: ../index.php");
}

?>