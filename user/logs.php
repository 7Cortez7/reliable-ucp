<?php

if(isset($_POST['character_id']) != true or isset($_POST['filter']) != true)
{
	header("location: index.php");
}

include_once '../core/userarea.php';
include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/User.class.php';
include_once '../class/Character.class.php';

include_once '../class/assets/dbconfig.php';

$character_id = $_POST['character_id'];
$filter = $_POST['filter'];
$character = new Character($character_id);
$char_name = $character->getCharacterName();
$account_id = $character->getAccountID();

$account = new User($account_id);
$account_name = $account->getUsername();

$char_name = str_replace("_", " ", $char_name);		
				
?>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <?php print ("<h1 class='page-header'>Logs di $char_name</h1>"); ?>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>	
									<th>Mittente</th>
									<th>Destinatario</th>
									<th>Comando</th>
									<th>Valore</th>
									<th>Valore</th>
									<th>Data</th>
									<th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php
									
								$db = getDB();
								$stmt = $db->prepare("SELECT * FROM transfer_logs WHERE (giver_character_id=:id OR receiver_character_id=:same_id) AND command=:filter ORDER BY created_at DESC;");
								$stmt->bindParam("id", $character_id, PDO::PARAM_INT);
								$stmt->bindParam("same_id", $character_id, PDO::PARAM_INT);
								$stmt->bindParam("filter", $filter, PDO::PARAM_STR);
								$stmt->execute();
										
								while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
										
									print ("<tr class='gradeA'>");
											
									$command = $data['command'];
									$extra_var = $data['extra_var'];
									$second_extra_var = $data['second_extra_var'];
									$date = $data['created_at'];
									$receiver = $data['receiver_character_id'];
									$giver = $data['giver_character_id'];
									$ip = $data['ip'];
											
									$receiver_character = new Character($receiver);
									$giver_character = new Character($giver);
											
									$receiver_name = $receiver_character->getCharacterName();
									$giver_name = $giver_character->getCharacterName();
									$receiver_id = $receiver_character->getID();
									$giver_id = $giver_character->getID();
										
									$receiver_name = str_replace("_", " ", $receiver_name);	
									$giver_name = str_replace("_", " ", $giver_name);
											
									if($extra_var == -1) $extra_var = "";
									if($second_extra_var == -1) $second_extra_var = "";
											
									print ("<td><a href='character.php?character_id=$giver_id'>$giver_name</a></td>");
									print ("<td><a href='character.php?character_id=$receiver_id'>$receiver_name</a></td>");
									print ("<td>$command</td>");
									print ("<td class='center'>$extra_var</td>");
									print ("<td class='center'>$second_extra_var</td>");
									print ("<td class ='center'>$date</td>");
									print ("<td>$ip</td>");
											
									print ("</tr>");
								}
							?>
                            </tbody>
							</table>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>

	<?php include_once '../core/footer.php'; ?>
	
</body>		