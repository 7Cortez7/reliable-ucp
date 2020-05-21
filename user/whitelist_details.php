<?php

if(isset($_GET['app_id']) == false)
{
	header("location: whitelist.php");
}

include_once '../class/assets/dbconfig.php';

$db = getDB();
$stmt = $db->prepare("SELECT * FROM character_applications WHERE id=:id");
$stmt->bindParam("id", $_GET['app_id'], PDO::PARAM_INT);
$stmt->execute();
$count = $stmt->rowCount();

if($count < 1)
{
	header("location: whitelist.php");
}

include_once '../core/userarea.php';
include_once '../core/supporterarea.php';
include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/User.class.php';
include_once '../class/Application.class.php';
include_once '../class/Question.class.php';

$app = new Application($_GET['app_id']);
$id = $app->getID();
$name = $app->getName();
$background = $app->getBackground();
$account_name = $app->getAccountName();
$ip = $app->getIP();
$status = $app->getStatus();
$handler = $app->getHandlerId();
$handler_name = $app->getHandlerName();

$name = str_replace("_", " ", $name);

?>

<script>

	function evaluateApplication(app_id, result)
	{
		var xmlhttp = new XMLHttpRequest();
		var reason = $('#reason').val();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				window.location.href = "whitelist.php";
			}
		};
		xmlhttp.open("GET", "../send/evaluate_application.php?app_id=" + app_id + "&result=" + result + "&reason=" + reason, true);
		xmlhttp.send();
	}

	function takeApplication(app_id)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("takeButton").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "../send/take_application.php?app_id=" + app_id, true);
		xmlhttp.send();
	}

</script>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">

				<?php

					print ("<div class='col-lg-12'>");
						print ("<h1 class='page-header'>Başvuru: $name</h1>");
					print ("</div>");

					print ("<div class='col-lg-8'>");

					if($handler != -1)print ("<blockquote><p>İnceleyen: <b><a href='profile.php?account_id=$handler'>$handler_name</a></b></blockquote>");

					print ("<label>Karakter</label>");
					print ("<div class='form-group input-group'>");
                        print ("<span class='input-group-addon'><i class='fa fa-user fa-fw'></i></span>");
						print ("<input type='text' class='form-control' placeholder='$name ($account_name)' style='width: 200;' readonly>");
                    print ("</div><hr>");

					print ("<div class='form-group'>");
                        print ("<label>Karakter hikayesi</label>");
                        print ("<textarea class='form-control' rows='5' readonly>$background</textarea>");
                    print ("</div>");

					$questions = array();
					$questions = Application::getApplicationQuestions($id);

					if(empty($questions) == false)foreach($questions as $question_id)
					{
						$question = Question::getQuestion($question_id);
						$answer = Question::getQuestionAnswer($id, $question_id);

						print ("<hr><div class='form-group'>");
							print ("<label>$question</label>");
							print ("<textarea class='form-control' rows='5' readonly>$answer</textarea>");
						print ("</div>");
					}

					if($handler != -1 && $handler == $_COOKIE['account'] && strlen($status) < 1)
					{
						print ("<hr><textarea id='reason' class='form-control' rows='5' placeholder='Yorum'></textarea>");
						print ("<br><span type='button'><center>");
						print ("<button type='button' class='btn btn-danger' style='height: 30px;' onclick='evaluateApplication($id, 0)'>Reddet</button> ");
						print ("<button type='button' class='btn btn-success' style='height: 30px;' onclick='evaluateApplication($id, 1)'>Kabul et</button>");
						print ("</span></center><br>");
					}
					if($handler == -1) print ("<span id='takeButton'><hr><center><button type='button' style='width: 150; height: 35;' class='btn btn-primary btn-block' onclick='takeApplication($id)'>Yanıtla</button></center></span><br>");
				?>

				</form>
				</div>
			</div>
		</div>
	</div>

	<?php  include_once '../core/footer.php'; ?>

</body>
