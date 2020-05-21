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
$coyn = $_REQUEST['coyn'];

if($coyn < 0)die();

$db = getDB();
$stmt = $db->prepare("UPDATE accounts SET coyn_points=:coyn WHERE id=:id");
$stmt->bindParam("coyn", $coyn, PDO::PARAM_INT);
$stmt->bindParam("id", $account, PDO::PARAM_INT);
$stmt->execute();

echo $txtCoyn = ("<p class='text-center' style='font-size:17px'><i class = 'fa fa-support'></i> rCoin: <b>$coyn</b></p>");

$admin = new User($_COOKIE['account']);
$admin->playerLog("coyn_account", $account, $coyn);

?>
