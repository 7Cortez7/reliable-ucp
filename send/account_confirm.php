<?php 

include_once '../class/assets/dbconfig.php';
include_once '../class/User.class.php';

if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) == true) 
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

if(isset($_GET['key']) == false)
{
	header ("location: register.php"); die();
}

$key = $_GET['key'];
$db = getDB();

$stmt = $db->prepare("SELECT id, confirmed FROM accounts WHERE register_token=:key");
$stmt->bindParam("key", $key, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if($count > 0 && $data['confirmed'] < 1)
{
	$id = $data['id'];
	
	$stmt = $db->prepare("UPDATE accounts SET confirmed = '1', updated_at = 'NOW()' WHERE id=:id");
	$stmt->bindParam("id", $id, PDO::PARAM_INT);
	$stmt->execute();
	
	$object = "Registrazione";
	$text = "
				Lo staff ti dà il benvenuto e <b>ti ringrazia</b> per aver deciso di far parte di questa community.<br><br>
				<b>Link utili</b>:
				<ul>
					<li><a href='https://forum.ls-rp.it/' target='_blank'>Il nostro forum</a></li>
					<li><a href='https://forum.ls-rp.it/viewforum.php?f=19' target='_blank'>I nostri regolamenti</a></li>
				</ul>
				Distinti saluti, <b>lo staff</b>.
			";
				
	$user = new User($id);
	$user->playerAlert($object, $text);	

	header("location: ../index.php");	
}
else header("location: ../register.php");
 
?>