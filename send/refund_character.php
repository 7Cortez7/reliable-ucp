<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['character_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/User.class.php';
include_once '../class/assets/dbconfig.php';
include_once '../core/staffarea.php';

$user = $_COOKIE['account'];
$character = $_REQUEST['character_id'];
$item = $_REQUEST['item'];
$quantity = $_REQUEST['quantity'];
$quality = $_REQUEST['quality'];
$thread = $_REQUEST['thread'];

if($character < 1 or $item == 0 or $quantity < 1)
{
	echo $txtJail = ("Geçersiz veri."); return false;
}

$db = getDB();
$stmt = $db->prepare("INSERT INTO refunds (character_id, item, amount, extra, admin_id, created_at, thread) VALUES (:character, :item, :quantity, :quality, :user, NOW(), :thread)");
$stmt->bindParam("character", $character, PDO::PARAM_INT);
$stmt->bindParam("item", $item, PDO::PARAM_INT);
$stmt->bindParam("quantity", $quantity, PDO::PARAM_INT);
$stmt->bindParam("quality", $quality, PDO::PARAM_INT);
$stmt->bindParam("user", $user, PDO::PARAM_INT);
$stmt->bindParam("thread", $thread, PDO::PARAM_INT);
$stmt->execute();

echo $txtJail = ("Ödeme yapıldı.");

$admin = new User($user);
$admin->playerLog("refund_character", $character, $item);

?>
