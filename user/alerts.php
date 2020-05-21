<?php

include_once '../core/userarea.php';
include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/User.class.php';
include_once '../class/Alert.class.php';

$user = new User($_COOKIE['account']);
$id = $user->getID();
$alerts = $user->getAlertsList();

?>

<script>

	function archiveAll()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				window.location.reload();
			}
		};
		xmlhttp.open("GET", "../send/archive_all_alerts.php", true);
		xmlhttp.send();
	}

	function openedAll()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				window.location.reload();
			}
		};
		xmlhttp.open("GET", "../send/open_all_alerts.php", true);
		xmlhttp.send();
	}

	function setOpened(id)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("alertIcon_" + id).innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/open_alert.php?alert_id=" + id, true);
		xmlhttp.send();
	}

	function setArchived(id)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				window.location.reload();
			}
		};
		xmlhttp.open("GET", "../send/archive_alert.php?alert_id=" + id, true);
		xmlhttp.send();
	}

</script>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class='page-header'>Bildirimler</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<div class="row">
                <div class="col-lg-6">
                    <div class="panel-body">
                        <div class="panel-group" id="accordion">

							<blockquote>
								<li><a onclick="archiveAll()">Tümünü sil</a><br></li>
								<li><a onclick="openedAll()">Tümünü okundu olarak işaretle</a></li>
							</blockquote>

							<?php

								foreach($alerts as $idAlert) {

									$alert = new Alert($idAlert);

									$object = $alert->getObject();
									$date = $alert->getDate();
									$text = $alert->getText();
									$opened = $alert->getOpened();

									print ("<div class='panel panel-default'>");
										print ("<div class='panel-heading'>");
											print ("<h4 class='panel-title'>");
												print ("<a data-toggle='collapse' data-parent='#accordion' href='#$idAlert' onclick='setOpened($idAlert)'>");
													print ($opened) ? ("<span id='alertIcon_$idAlert'><i class = 'fa fa-circle-o'></i> (<b>$date</b>) $object</span>") : ("<span id='alertIcon_$idAlert'><i class = 'fa fa-circle'></i> (<b>$date</b>) $object</span>");
												print ("</a>");
											print ("</h4>");
										print ("</div>");

										print ("<div id='$idAlert' class='panel-collapse collapse'>");
											print ("<div class='panel-body'>");
												print ("$text<br>");
												print ("<button style='float: right;' type='button' class='btn btn-primary' onclick='setArchived($idAlert)'><i class = 'fa fa-archive'></i></button>");
											print ("</div>");

										print ("</div>");
									print ("</div><br>");
								}
							?>

						</div>
                    </div>
                </div>
                <!-- /.col-lg-6 -->
            </div>
            <!-- /.row -->
		</div>
	</div>

	<?php include_once '../core/footer.php'; ?>

</body>
