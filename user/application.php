<?php

include_once '../class/User.class.php';

$user = new User($_COOKIE['account']);
$char_count = $user->getCharactersCount();
$slot_count = $user->getCharactersSlot();
$free_slot = $slot_count - $char_count;

include_once '../core/userarea.php';
include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/Question.class.php';

include_once '../class/assets/dbconfig.php';

?>

<script>

	function toggleTxtAlert()
	{
		$("#txtAlert").fadeOut();
	}

	function checkApplication()
	{
		$.ajax
		({
			url: "../send/application.php",
			type: 'POST',
			data: $('#application').serialize(),
			success: function(response)
			{
				if(response == "Başvuru gönderildi.")
				{
					$("#application").get(0).reset();
					window.location.href = "alerts.php"; return true;
				}
				$("#txtAlert").fadeIn();
				document.getElementById("txtAlert").innerHTML = response;
				setTimeout(toggleTxtAlert, 3000);
				window.scrollTo(0, 0);
			}
		});
	}

</script>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
					<h1 class='page-header'>Başvurular</h1>
				</div>

				<div class="col-lg-8">

					<form id="application" action="../send/application.php" method="POST">

					<?php

					if($free_slot < 1)
					{
						print ("<div class='alert alert-danger'>Tüm karakter slotlarınız dolu.</div>"); die();
					}
					else print("<div id='txtAlert' class='alert alert-danger' hidden></div>");

					?>

					<fieldset>

					<label>İsim</label>
					<div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                        <input name="name" type="text" class="form-control" placeholder="Ad" style="width: 150;">
						<input name="surname" type="text" class="form-control" placeholder="Soyad" style="width: 150;">
                    </div>

					<label>Skin</label>
					<div class="form-group input-group">
						<span class='buttom' style='float: left;'><a href = 'http://wiki.sa-mp.com/wiki/Skins:All' target="_blank"><button type ='button' class='btn btn-primary'>Liste</button></a></span>
						<input name='skin' type='number' min="1" max="311" class='form-control' style='height: 35px; width: 100px;' placeholder='ID'>
					</div>

					<label>Cinsiyet</label>
					<div class="form-group input-group">
						<span class="input-group-addon"><i class="fa fa-male fa-fw"></i></span>
						<select name="sex" class="form-control" style="width: 150;">
						<option value='0'>Erkek</option>
						<option value='1'>Kadın</option>
						</select>
                    </div><hr>

					<div class="checkbox">
                        <label>
                            <input name="telephone" type="checkbox" value="0" checked>Cep Telefonu
                        </label>
                    </div>

					<div class="checkbox">
                        <label>
                            <input name="drive_license" type="checkbox" value="0" checked>Sürücü Lisansı
                        </label>
                    </div><hr>

					<div class="form-group">
                        <label>Karakter Hikayesi</label>
                        <textarea name="background" class="form-control" rows="5"></textarea>
                    </div><hr>

					<?php

					if($char_count < 1)
					{
						$questionsList = array();
						$questionsList = Question::getQuestionsList();

						$questions = array(51, 52, 53);
						$random_limit = max($questionsList);

						while(count($questions) < 5)
						{
							$random_question = rand(53, $random_limit);

							if(in_array($random_question, $questions) == false)
								array_push($questions, $random_question);
						}

						foreach($questions as $i)
						{
							$name = Question::getQuestion($i);

							print ("<div class='form-group'>");
								print ("<label>$name</label>");
								print ("<textarea name='dm[$i]' class='form-control alert' rows='5'></textarea>");
							print ("</div>");
						}
					}

					?>

					</fieldset>
					<center><button type="button" style="width: 150; height: 35;" class="btn btn-primary btn-block" onclick="checkApplication()">Gönder</button></center><br>

				</form>
				</div>
			</div>
		</div>
	</div>

	<?php  include_once '../core/footer.php'; ?>

</body>
