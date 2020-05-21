<?php

if(isset($_COOKIE['account']) == true && isset($_COOKIE['code']) == true)
{
	header("location: user/index.php"); die();
}

?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Reliable Roleplay Karakter Kontrol Paneli">
    <meta name="author" content="Cortez">

    <title>Reliable Roleplay UCP</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<script>

	function toggleTxtError()
	{
		$("#txtError").fadeOut();
	}
	function checkLogin()
	{
		var xmlhttp = new XMLHttpRequest();
		var account = $('#account').val();
		var password = $('#password').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				var status = this.responseText;

				switch(status)
				{
					case 'fail': status = "Geçersiz veri."; break;
					case 'unconfirmed': status = "E-Posta onay bekliyor."; break;
					case 'banned': status = "Hesabınız yasaklandı."; break;
					case 'success': window.location.href = "user/index.php"; return true;
				}

				$("#txtError").fadeIn();
				document.getElementById("txtError").innerHTML = status;
				setTimeout(toggleTxtError, 3000);
			}
		};
		xmlhttp.open("GET", "send/login.php?account=" + account + "&password=" + password, true);
		xmlhttp.send();
	}

	function recoveryMode()
	{
		var xmlhttp = new XMLHttpRequest();
		var account = $('#account').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#txtError").fadeIn();
				document.getElementById("txtError").innerHTML = this.responseText;
				setTimeout(toggleTxtError, 3000);
			}
		};
		xmlhttp.open("GET", "send/recovery.php?account=" + account, true);
		xmlhttp.send();
	}

</script>

<body>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-panel">

					<center><img style="width: 320px; height: 120px;" src="core/images/logo_rlrp.png"></img></center>

					<div class="panel panel-default">
						<div class="panel-body">
						<form>

							<div id="txtError" class="alert alert-danger" hidden></div>

							<div class="alert alert-info">
								Hesabınız yok mu? <a href="register.php" class="alert-link">Kayıt ol</a>.<br>
								Şifrenizi mi unuttunuz? <a onclick="recoveryMode()" class="alert-link">Tıkla</a>.
							</div>

							<div class="form-group input-group">
								<span class="input-group-addon"> <i class="fa fa-user fa-fw"></i> </span>
								<input id="account" class="form-control" placeholder="Kullanıcı Adı" name="account" type="text" required>
							</div>

							<div class="form-group input-group">
								<span class="input-group-addon"> <i class="fa fa-key fa-fw"></i> </span>
								 <input id="password" class="form-control" placeholder="Şifre" name="password" type="password" value="" required>
							</div>

							<center><button type="button" style="width: 150; height: 35;" class="btn btn-outline btn-default btn-block" onclick="checkLogin(this);">Giriş</button><center>

						</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>
