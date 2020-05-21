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
$banned = $_REQUEST['banned'];
$time = $_REQUEST['time'];

$ban_time = ($banned && $time) ? time() + (60 * 60 * 24 * $_REQUEST['time']) : 0;
$unban_date = ($_REQUEST['time']) ? date("Y-m-d, H:i:s", $ban_time) : "Permanente";

$db = getDB();
$stmt = $db->prepare("UPDATE accounts SET banned=:banned, ban_time=:ban_time WHERE id=:id");
$stmt->bindParam("banned", $banned, PDO::PARAM_INT);
$stmt->bindParam("ban_time", $ban_time, PDO::PARAM_INT);
$stmt->bindParam("id", $account, PDO::PARAM_INT);
$stmt->execute();	

echo $txtBanned = ($banned) ? ("<p class='text-center' style='font-size:17px'><i class = 'fa fa-lock'></i> Bannato: <b>Sì</b><br><i class='fa fa-long-arrow-up'></i> Data di unban: <b>$unban_date</b></p>") : ("<p class='text-center' style='font-size:17px'><i class = 'fa fa-unlock'></i> Bannato: <b>No</b></p>");

$admin = new User($_COOKIE['account']);
$admin->playerLog("ban_account", $account, $banned);

?>
