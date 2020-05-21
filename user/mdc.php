<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

include_once '../core/header.php';
include_once '../core/dashboard.php';

?>

<script>

	function hideCitizen()
	{
		$("#players").fadeOut();
		$("#citizen").val("");
	}
	function searchCitizen()
	{
		var xmlhttp = new XMLHttpRequest();
		var name = $("#citizen").val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#players").fadeIn();
				document.getElementById("players").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/search_citizen.php?name=" + name, true);
		xmlhttp.send();
	}

	function hidePlates()
	{
		$("#plates").fadeOut();
		$("#plate").val("");
	}
	function searchPlate()
	{
		var xmlhttp = new XMLHttpRequest();
		var plate = $("#plate").val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#plates").fadeIn();
				document.getElementById("plates").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/search_plate.php?plate=" + plate, true);
		xmlhttp.send();
	}

	function hideRecords()
	{
		$("#records").fadeOut();
		$("#record").val("");
	}
	function searchRecord()
	{
		var xmlhttp = new XMLHttpRequest();
		var name = $("#record").val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#records").fadeIn();
				document.getElementById("records").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/search_record.php?name=" + name, true);
		xmlhttp.send();
	}
	function showRecord(id)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("records").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/show_record.php?id=" + id, true);
		xmlhttp.send();
	}

	function hidePhones()
	{
		$("#phones").fadeOut();
		$("#number").val("");
	}
	function searchNumber()
	{
		var xmlhttp = new XMLHttpRequest();
		var number = $("#number").val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				$("#phones").fadeIn();
				document.getElementById("phones").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/search_number.php?number=" + number, true);
		xmlhttp.send();
	}

	function searchAPB()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("apb").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/search_wanteds.php", true);
		xmlhttp.send();
	}
	function sendAPB()
	{
		$.ajax
		({
			url: "../send/add_wanteds.php",
			type: 'POST',
			data: $('#create').serialize(),
			success: function(response)
			{
				document.getElementById("apb").innerHTML = response;
			}
		});
	}
	function createAPB()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("apb").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/create_wanteds.php", true);
		xmlhttp.send();
	}
	function deleteAPB(id)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				searchAPB();
			}
		};
		xmlhttp.open("GET", "../send/delete_wanteds.php?id=" + id, true);
		xmlhttp.send();
	}
	function showAPB(id)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("apb").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/show_wanteds.php?id=" + id, true);
		xmlhttp.send();
	}
	function editAPB(id)
	{
		$('#wanteds input').attr("readonly", false);
		document.getElementById("btn").innerHTML = "<button type='button' class='btn btn-success' onclick='updateAPB("+ id +")'>Kaydet</button>";
		document.getElementById("add").innerHTML = "<i><input name='crime' type='text' class='text-center' value='N/A' style='background-color: transparent; border: none; width: 120px;'></i><hr>";
	}
	function updateAPB(id)
	{
		$.ajax
		({
			url: "../send/update_wanteds.php",
			type: 'POST',
			data: $('#wanteds').serialize(),
			success: function(response)
			{
				showAPB(id);
			}
		});
	}

</script>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                   <h1 class='page-header'>Mobile Data Computer</h1>
                </div>

				<div class="col-lg-5">
					<div class="well well-lg">

					<h4><p class="text-center">İsim Arama</h4></p><br>

					<div class="form-group input-group">
						<span class="input-group-addon"> <i class="fa fa-user fa-fw"></i> </span>
						<input id="citizen" class="form-control" placeholder="İsim" type="text" onchange="searchCitizen()">
					</div>

					<div id="players" hidden>
					</div>

					</div>
				</div>

				<div class="col-lg-5">
					<div class="well well-lg">

					<h4><p class="text-center">Dosya No Arama</h4></p><br>

					<div class="form-group input-group">
						<span class="input-group-addon"> <i class="fa fa-archive fa-fw"></i> </span>
						<input id="record" class="form-control" placeholder="Dosya No" type="text" onchange="searchRecord()">
					</div>

					<div id="records" hidden>
					</div>

					</div>
				</div>

				<div class="col-lg-5">
					<div class="well well-lg">

					<h4><p class="text-center">Plaka Arama</h4></p><br>

					<div class="form-group input-group">
						<span class="input-group-addon"> <i class="fa fa-car fa-fw"></i> </span>
						<input id="plate" class="form-control" placeholder="Plaka" type="text" onchange="searchPlate()">
					</div>

					<div id="plates" hidden>
					</div>

					</div>
				</div>

				<div class="col-lg-5">
					<div class="well well-lg">

					<h4><p class="text-c
						enter">Numara Arama</h4></p><br>

					<div class="form-group input-group">
						<span class="input-group-addon"> <i class="fa fa-phone fa-fw"></i> </span>
						<input id="number" class="form-control" placeholder="Numara" type="text" onchange="searchNumber()">
					</div>

					<div id="phones" hidden>
					</div>

					</div>
				</div>

				<div class="col-lg-5">
					<div class="well well-lg">

					<h4><p class="text-center">APB - All-points Bulletin <a onClick="createAPB()"><i class="fa fa-plus"></i></a></h4></p><br>

					<?php

					print ("<div id='apb'>");

						echo ("<script>searchAPB()</script>");

					print ("</div>");

					?>

					</div>
				</div>

			</div>
        </div>
	</div>

	<?php include_once '../core/footer.php'; ?>

</body>
