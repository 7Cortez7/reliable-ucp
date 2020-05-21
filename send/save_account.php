<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['account_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/User.class.php';
include_once '../class/assets/dbconfig.php';

$id = $_REQUEST['account_id'];
$password = $_REQUEST['password'];
$hash_actual_password = hash('whirlpool', $password); 

$user = new User($id);
$user_password = $user->getPassword();

if($hash_actual_password != $user_password)
{
	echo $panelError = ("Password errata."); return false;
}

$tmp_password = $_REQUEST['new_password'];
$tmp_confirm_password = $_REQUEST['confirm'];

if($tmp_password != $tmp_confirm_password)
{
	echo $panelError = ("Password diverse."); return false;
}

if(strlen($tmp_confirm_password) < 8 or strlen($tmp_confirm_password) > 32)
{
	echo $panelError = ("Lunghezza invalida (8 - 32).");  return false;
}

$hash_new_password = hash('whirlpool', $tmp_confirm_password);

$db = getDB();
$stmt = $db->prepare("UPDATE accounts SET password=:password WHERE id=:id");
$stmt->bindParam("password", $hash_new_password, PDO::PARAM_STR);
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();

echo $panelError = ("Password aggiornata.");