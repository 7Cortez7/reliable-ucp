<?php

if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) == true)
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

include_once '../class/assets/dbconfig.php';

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$email = $_REQUEST['email'];
$confirm = $_REQUEST['confirm'];
$ip = $_SERVER['REMOTE_ADDR'];

if(strlen($username) < 3 or strlen($username) > 24)
{
	echo $txtError = ("Kullanıcı adınız 3 ile 24 karakter arasında olmalıdır."); return false;
}

if(preg_match('/[^A-Za-z0-9_]/', $username) == true)
{
	echo $txtError = ("Geçersiz kullanıcı adı."); return false;
}

if($password != $confirm)
{
	echo $txtError = ("Şifreler farklı."); return false;
}

if(strlen($confirm) < 8 or strlen($confirm) > 32)
{
	echo $txtError = ("Şifreniz 8 ile 32 karakter arasında olmalıdır.");  return false;
}

if(filter_var($email, FILTER_VALIDATE_EMAIL) != true)
{
	echo $txtError = ("Geçersiz E-Posta.");  return false;
}

$db = getDB();
$stmt = $db->prepare("SELECT email, username FROM accounts WHERE email=:email OR username=:username");
$stmt->bindParam("email", $email, PDO::PARAM_STR);
$stmt->bindParam("username", $username, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();

if($count)
{
	$data = $stmt->fetch(PDO::FETCH_ASSOC);

	if($data['email'] == $email) echo $txtError = ("E-Posta zaten kullanılıyor.<br>");
	if($data['username'] == $username) echo $txtError = ("Hesap zaten kayıtlı.");

	return false;
}

$tmp_token = random_bytes(8);
$token_register = bin2hex($tmp_token);
$hash_password = hash('whirlpool', $confirm);

$stmt = $db->prepare("INSERT INTO accounts (username, password, email, confirmed, created_at, register_token, ip) VALUES (:username, :hash_password, :email, '0', NOW(), :token_register, :ip)");
$stmt->bindParam("username", $username, PDO::PARAM_STR);
$stmt->bindParam("hash_password", $hash_password, PDO::PARAM_STR);
$stmt->bindParam("email", $email, PDO::PARAM_STR);
$stmt->bindParam("token_register", $token_register, PDO::PARAM_STR);
$stmt->bindParam("ip", $ip, PDO::PARAM_STR);
$stmt->execute();

$mail_receiver = $email;
$mail_object = "Kayıt - Reliable RP";
$mail_body = "
				<body>
				<font size='3'>Reliable Roleplay'a hoşgeldin, <b>$username</b>.<br>
				<br>Hesabınızın onaylanması için aşağıdaki linke butona veya linke tıklayabilirsiniz:
				<a href='http://ucp.xxx/send/account_confirm.php?key=$token_register'>Aktivasyon Tuşu</a>.<br>
				<br>Link: http://ucp.xxx/send/account_confirm.php?key=$token_register<br>
				Bağlantıya tıkladıktan sonra giriş sayfasına yönlendireleceksiniz.<br>
				<br>Saygılarımızla <b>Reliable Roleplay</b>.
				</body>
			";

include '../core/emailconfig.php';

echo $txtError = ("Hesabınız oluşturuldu.<br>Onaylamak için e-posta'nızı kontrol edin.<br> Giriş yapmak için buraya <a href='index.php'>tıkla</a>.");

?>
