<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['account_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/User.class.php';
include_once '../class/assets/dbconfig.php';
include_once '../core/staffarea.php';

$account = $_REQUEST['account_id'];
$level = $_REQUEST['level'];

$db = getDB();
$stmt = $db->prepare("UPDATE accounts SET admin_level=:level WHERE id=:id");
$stmt->bindParam("level", $level, PDO::PARAM_INT);
$stmt->bindParam("id", $account, PDO::PARAM_INT);
$stmt->execute();	

$user = new User($account);		
$role = $user->getRole();

echo $txtLevel = ("<p class='text-center' style='font-size:17px'>Ruolo: <b>$role</b></p>");

$admin = new User($_COOKIE['account']);
$admin->playerLog("admin_account", $account, $level);

?>
