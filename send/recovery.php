<?php

if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) == true)
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

include_once '../class/assets/dbconfig.php';
include_once '../class/User.class.php';

$username = $_REQUEST['account'];

if(strlen($username) < 3 or strlen($username) > 24)
{
	echo $txtError = ("Geçerli bir kullanıcı adı girin."); die();
}

$db = getDB();
$stmt = $db->prepare("SELECT banned, id, email, recovery_password FROM accounts WHERE username=:username");
$stmt->bindParam("username", $username, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if(strlen($data['recovery_password']) > 1)
{
	echo $txtError = ("Kurtarma modu zaten etkinleştirildi.");  die();
}

if($count < 1)
{
	echo $txtError = ("Hesap bulunamadı.");  die();
}

if($data['banned'] > 0)
{
	echo $txtError = ("Hesabınız yasaklandı.");  die();
}

$email = $data['email'];
$id = $data['id'];

$tmp_string = random_bytes(8);
$tmp_password = bin2hex($tmp_string);

$stmt = $db->prepare("UPDATE accounts SET recovery_password=:password WHERE id=:id");
$stmt->bindParam("password", $tmp_password, PDO::PARAM_STR);
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();

$mail_receiver = $email;
$mail_object = "Şifre Kurtarma";
$mail_body = "
				<body>
				<font size='3'><b>$username</b> için hesap kurtarma işlemi başlatıldı.<br>
				<br>Bu hesapta şifre kurtarma etkinleştirildiğinden size bağlantıyı gönderdik. <a href='xx'>Kurtarma Sayfası</a>.<br>
				<br><b>Link:</b>xxx<br>
				<br>Sevgilerle, <b>Los Santos Stories Roleplay</b>.
				</body>
			";

include '../core/emailconfig.php';

echo $txtError = ("Kurtarma için size bir e-posta gönderildi.");

$user = new User($id);
$user->playerLog("recovery_password", $user->getID(), -1);

die();

?>
