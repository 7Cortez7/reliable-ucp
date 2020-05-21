<?php

if(isset($_COOKIE['account']) == true && isset($_COOKIE['code']) == true)
{
	header("location: user/index.php");
}

?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sublime</title>

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
	function checkRegister()
	{
		var xmlhttp = new XMLHttpRequest();
		var username = $('#username').val();
		var email = $('#email').val();
		var password = $('#password').val();
		var confirm = $('#confirm').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#txtError").fadeIn();
				document.getElementById("txtError").innerHTML = this.responseText;
				setTimeout(toggleTxtError, 5000);
			}
		};
		xmlhttp.open("GET", "send/register.php?username=" + username + "&email=" + email + "&password=" + password + "&confirm=" + confirm, true);
		xmlhttp.send();
	}

</script>

<body>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-panel">

					<center><img style="width: 320px; height: 120px;" src="core/images/logo_lsrp.png"></img></center>

					<div class="panel panel-default">
						<div class="panel-body">

							<div id="txtError" class="alert alert-danger" hidden></div>

							<div class="alert alert-info">
								Lütfen onay için <b>geçerli bir e-posta</b> girin.<br>
								Hesabınızın güvenliği için <b>hiç kullanılmamış bir şifre</b> kullanın.
							</div>

							<div class="form-group input-group">
								<span class="input-group-addon"> <i class="fa fa-user fa-fw"></i> </span>
								<input id="username" class="form-control" placeholder="Kullanıcı Adı" name="username" type="text">
							</div>

							<div class="form-group input-group">
								<span class="input-group-addon"> <i class="fa fa-envelope fa-fw"></i> </span>
								<input id="email" class="form-control" placeholder="E-Posta" name="email" type="text">
							</div>

							<div class="form-group input-group">
								<span class="input-group-addon"> <i class="fa fa-key fa-fw"></i> </span>
								<input id="password" class="form-control" placeholder="Şifre" name="password" type="password">
							</div>

							<div class="form-group input-group">
								<span class="input-group-addon"> <i class="fa fa-check fa-fw"></i> </span>
								<input id="confirm" class="form-control" placeholder="Parolayı Onayla" name="confirm" type="password">
							</div>

							<center><button type="submit" style="width: 150; height: 35;" class="btn btn-outline btn-default btn-block" onClick="checkRegister()">Kayıt Ol!</button><center>

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
