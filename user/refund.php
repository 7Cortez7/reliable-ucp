<?php

if(isset($_GET['character_id']) != true)
{
	header("location: index.php");
}

include_once '../core/userarea.php';
include_once '../core/staffarea.php';
include_once '../core/header.php';
include_once '../core/dashboard.php';
include_once '../core/itemconfig.php';

include_once '../class/User.class.php';
include_once '../class/Character.class.php';

include_once '../class/assets/dbconfig.php';

$character = new Character($_GET['character_id']);
$id = $character->getID();

?>

<script>

	function toggleTxtAlert()
	{
		$("#txtAlert").fadeOut();
	}
	function sendRefund(id)
	{
		var xmlhttp = new XMLHttpRequest();
		var quantity = $('#quantity').val();
		var quality = $('#quality').val();
		var item = $('#item').val();
		var thread = $('#thread').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#txtAlert").fadeIn();
				document.getElementById("txtAlert").innerHTML = this.responseText;
				setTimeout(toggleTxtAlert, 3000);
			}
		};
		xmlhttp.open("GET", "../send/refund_character.php?character_id=" + id + "&item=" + item + "&quantity=" + quantity + "&quality=" + quality + "&thread=" + thread, true);
		xmlhttp.send();
	}

</script>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
					<h1 class='page-header'>Ödeme</h1>
				</div>

				<div class="col-lg-6">

					<label>Konu</label>
					<div class="form-group">
						<select id="item" class="form-control">
						<?php
							for($i = 0; $i < count($items); $i++)
								print ("<option value='$i'>$items[$i]</option>");
						?>
						</select>
                    </div>

					<label>Miktar</label>
					<div class="form-group input-group">
						<span class="input-group-addon"><i class="fa fa-gbp fa-fw"></i></span>
                        <input id="quantity" type="text" class="form-control">
                    </div>

					<label>Kalite</label>
					<div class="form-group input-group">
                        <input id="quality" type="text" class="form-control">
                        <span class="input-group-addon">.00</span>
                    </div>

					<label>Kullanıcı ID</label>
					<div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-comments fa-fw"></i></span>
                        <input id="thread" type="text" class="form-control" placeholder="ID">
                    </div>

					<?php
						print ("<br><button type='submit' style='float: left;' class='btn btn-primary' onclick='sendRefund($id)'>Öde</button>");
						print ("<span class='group-addon'><div id='txtAlert' class='alert alert-danger' style='float: right; height: 50px; width: 220px;' hidden></div>");
					?>

				</div>
			</div>
		</div>
	</div>

	<?php include_once '../core/footer.php'; ?>

</body>
