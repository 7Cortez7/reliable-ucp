<?php

if(isset($_GET['account_id']) != true)
{
	header("location: index.php");
}

include_once '../core/userarea.php';
include_once '../class/assets/dbconfig.php';

$id = $_GET['account_id'];

$db = getDB();
$stmt = $db->prepare("SELECT * FROM accounts WHERE id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();
$count = $stmt->rowCount();

if($count < 1)
{
	header("location: index.php"); die();
}

include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/User.class.php';
include_once '../class/Character.class.php';

$account = new User($_COOKIE['account']);
$account_id = $account->getID();
$account_level = $account->getStaffLevel();

$user = new User($_GET['account_id']);
$admin_level = $user->getStaffLevel();
$id = $user->getID();

if($account_id != $id && $account_level < 2)
{
	header("location: index.php"); die();
}

$name = $user->getUsername();
$role = $user->getRole();
$banned = $user->getBanned();
$ban_time = $user->getBanTime();
$unban_date = ($banned && $ban_time) ? date("Y-m-d, H:i:s", $ban_time) : "Perma";
$premium = $user->getPremium();
$premium_expires = $user->getPremiumExpires();
$premium_expires_date = date("Y-m-d, H:i:s", $premium_expires);
$premium_name = ($premium == 1) ? "Bronz" : ($premium == 2) ? "Gümüş" : "Altın";
$coyns = $user->getCoynPoints();
$email = $user->getEmail();
$ip = $user->getIP();
if(empty($ip) == true)$ip = "Kimse";

?>

<script>

	function closeEditPanel()
	{
		$("#editPanel").fadeOut();
	}

	function togglePanelError()
	{
		$("#panelError").fadeOut();
	}
	function saveData(id)
	{
		var xmlhttp = new XMLHttpRequest();
		var password = $('#password').val();
		var new_password = $('#new_password').val();
		var confirm_new_password = $('#confirm_new_password').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#panelError").fadeIn();
				document.getElementById("panelError").innerHTML = this.responseText;
				setTimeout(togglePanelError, 3000);
			}
		};
		xmlhttp.open("GET", "../send/save_account.php?account_id=" + id + "&password=" + password + "&new_password=" + new_password + "&confirm=" + confirm_new_password, true);
		xmlhttp.send();
	}

	function showEditPanel(id)
	{
		var visible = $("#editPanel").is(":visible");

		if(visible)return $("#editPanel").fadeOut();

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#editPanel").fadeIn();
				document.getElementById("editPanel").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/edit_account.php?account_id=" + id, true);
		xmlhttp.send();
	}

	function updateLevel(id, level)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("txtLevel").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/admin_account.php?account_id=" + id + "&level=" + level, true);
		xmlhttp.send();
		window.location.reload();
	}

	function setCoyn(id, amount)
	{
		var xmlhttp = new XMLHttpRequest();
		var amount = $('#coyn_amount').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("txtCoyn").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/coyn_account.php?account_id=" + id + "&coyn=" + amount, true);
		xmlhttp.send();
	}

	function updateBanStatus(id, status)
	{
		var xmlhttp = new XMLHttpRequest();
		var time = $('#time_ban').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("txtBanned").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/ban_account.php?account_id=" + id + "&banned=" + status + "&time=" + time, true);
		xmlhttp.send();
	}

</script>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <?php print ($account_id == $id) ? ("<h1 class='page-header'>$name <button type='button' class='btn btn-default' onclick='showEditPanel($id)'>Ayarlar</button><br></h1>") : ("<h1 class='page-header'>$name</h1>"); ?>
                </div>
                <!-- /.col-lg-12 -->

			<?php

				print ("<hr><div id='panelError' class='col-lg-12 col-lg-offset-5 alert alert-danger' style='height: 50px; width: 220px;' hidden></div>");
				print ("<br><div id='editPanel' hidden></div>");

				print ("<hr><span id='txtLevel'><p class='text-center' style='font-size:17px'>Rol: <b>$role</b></p></span>");
				print ("<p class='text-center' style='font-size:17px'><i class = 'fa fa-server'></i> IP: <b>$ip</b></p>");
				print ("<p class='text-center' style='font-size:17px'><i class = 'fa fa-envelope'></i> E-Posta: <b>$email</b></p>");
				print ($banned or $ban_time) ? ("<span id='txtBanned'><p class='text-center' style='font-size:17px'><i class = 'fa fa-lock'></i> Yasaklı: <b>Sì</b><br><i class='fa fa-long-arrow-up'></i> Yasaklama tarihi: <b>$unban_date</b></p></span>") : ("<span id='txtBanned'><p class='text-center' style='font-size:17px'><i class = 'fa fa-unlock'></i> Yasaklı: <b>Hayır</b></p></span>");
				print ($premium) ? ("<p class='text-center' style='font-size:17px'><i class = 'fa fa-star'></i> Bağışcı: <b>Evet</b> (Seviye: <b>$premium_name</b>)</b><br></p>") : ("<p class='text-center' style='font-size:17px'><i class = 'fa fa-star-o'></i> Bağışcı: <b>Hayır</b></p>");
				print ("<span id='txtCoyn'><p class='text-center' style='font-size:17px'><i class = 'fa fa-support'></i> rCoin: <b>$coyns</b></p></span>");

				print ("<hr><div class ='row'>");

				if($account_level > 1)
				{
					$staff_levels_name = array ("Kullanıcı", "Destek", "Moderatör", "Geliştirici", "Yönetici", "Management", "Owner");

					print ("<center>");
					if($account_level > $admin_level)
					{
						print ("<select id='level' class='form-control' style='height: 30px; width:180px;' onchange='updateLevel($id, this.value)'>");
							print ("<option value='' disabled selected>$role</option>");

							for($i = 0; $i < 6; $i++)
								if($admin_level != $i && $i < $account_level)print ("<option value='$i'>$staff_levels_name[$i]</option>");

						print ("</select><br>");
					}
					print ("<div class='form-group input-group'>");
					print ("<span class='buttom' style='float: left;'>");
					print ("<button type='button' class='btn btn-success' style='height: 30px;' onclick='updateBanStatus($id, 0)'><i class = 'fa fa-unlock'></i></button>");
					print ("<button type='submit' class='btn btn-danger' style='height: 30px;' onclick='updateBanStatus($id, 1)'><i class = 'fa fa-lock'></i></button>");
					print ("</span>");
					print ("<input id='time_ban' type='text' class='form-control' style='height: 30px; width: 100px;' placeholder='Ban(Gün)' name='time'>");
					print ("</div>");
					if($account_level > 3)
					{
						print ("<div class='form-group input-group'>");
						print ("<br><span type='button' style='float: left;'>");
						print ("<button type='submit' class='btn btn-primary' style='height: 30px;' onclick='setCoyn($id)'><i class = 'fa fa-support'></i></button>");
						print ("</span>");
						print ("<input id='coyn_amount' type='text' class='form-control' style='height: 30px; width: 100px;' placeholder='rCoin' name='Bağış'>");
						print ("</div>");
					}
					print ("</center>");
					print ("</div>");
					print ("<hr>");
				}

				foreach($user->getCharactersList() as $char_id) {
					print ("<div class='col-lg-5'>");
					print ("<div class='well well-lg'>");

					$character = new Character($char_id);
					$charName = $character->getCharacterName();
					$skin = $character->getSkin();
					$banned = $character->getBanned();
					$jail = $character->getJail();
					$last_login = $character->getLastLogin();

					$jail = number_format($jail/60);
					$charName = str_replace("_", " ", $charName);

					print ("<h4><p class='text-center'>$charName</h4></p><br>");
					print ("<img style='width: 60px; height: 100px;' src='../core/images/accounts/small_skins/Skin_$skin.png' align='right'>");
					print ("<p>Son giriş: <b>$last_login</b></p>");

					print ($banned) ? ("<p>Yasaklı: <b>Evet</b></p>") : ("<p>Yasaklı: <b>Hayır</b></p>");
					print ($jail) ? ("<p>Hapis: <b>Evet</b> ($jail min.)</p>") : ("<p>Hapis: <b>Hayır</b></p>");

					print ("<br><a href='character.php?character_id=$char_id'><p>Ayrıntılar <i class = 'fa fa-angle-right'></i></p></a>");
					print ("</div>");
					print ("</div>");
				}
			?>

        </div>
	</div>

	<?php include_once '../core/footer.php'; ?>

</body>
