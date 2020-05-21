<?php 

include_once '../class/assets/dbconfig.php';
include_once '../class/User.class.php';

if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) == true) 
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

if(isset($_GET['key']) == false)
{
	header ("location: index.php"); die();
}

$key = $_GET['key'];
$db = getDB();

$stmt = $db->prepare("SELECT id, username, email FROM accounts WHERE recovery_password=:key");
$stmt->bindParam("key", $key, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if($count > 0)
{
	$username = $data['username'];
	$email = $data['email'];
	$id = $data['id'];

	$tmp_string = random_bytes(8);
	$tmp_password = bin2hex($tmp_string);
	$tmp_hash_password = hash('whirlpool', $tmp_password);
	
	$stmt = $db->prepare("UPDATE accounts SET password=:password, recovery_password = NULL WHERE id=:id");
	$stmt->bindParam("password", $tmp_hash_password, PDO::PARAM_STR);
	$stmt->bindParam("id", $id, PDO::PARAM_INT);
	$stmt->execute();
	
	$mail_receiver = $email;	
	$mail_object = "Recupero password";
	$mail_body = "
				<body>
				<font size='3'>Recupero password dell'account <b>$username</b>.<br>
				<br>La tua nuova password è: <b>$tmp_password</b><br>
				Puoi decidere di cambiarla quando vuoi dal pannello.<br>
				<br>Distinti saluti, lo staff di <b>Los Santos Roleplay</b>.
				</body>
			";
	
	include '../core/emailconfig.php';
	
	User::playerLogin($username, $tmp_password, $_SERVER['REMOTE_ADDR']); 
	
	header("location: ../user/index.php"); die();	
}
else header("location: ../index.php");
 
?>