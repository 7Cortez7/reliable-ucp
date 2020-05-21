<?php

include_once '../core/userarea.php';

include_once '../core/header.php';
include_once '../core/dashboard.php';

include_once '../class/User.class.php';
include_once '../class/Character.class.php';

$user = new User($_COOKIE['account']);
$deleted_count = $user->getDeletedCount();
$char_count = $user->getCharactersCount();
$slot_count = $user->getCharactersSlot();
$free_slot = $slot_count - $char_count;

?>

<script>

	function importCharacter(id)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			window.location.reload();
		};
		xmlhttp.open("GET", "../send/import_character.php?character_id=" + id, true);
		xmlhttp.send();
	}

</script>

<body>
	<div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class='page-header'>Importa personaggio</h1>
                </div>
			</div>

			<?php

				if($deleted_count < 1)
				{
					print ("<div class='alert alert-danger' style='height: 50px; width: 280px;'>Non hai personaggi eliminati.</div>"); die();
				}

				print ("<div id='txtAlert' class='alert alert-danger' style='height: 50px; width: 280px;' hidden></div>");

				foreach($user->getDeletedList() as $char_id) {
					print ("<div class='col-lg-5'>");
					print ("<div class='well well-lg'>");

					$character = new Character($char_id);
					$charName = $character->getCharacterName();
					$skin = $character->getSkin();
					$banned = $character->getBanned();
					$jail = $character->getJail();
					$date = $character->getDeleteDate();
					$cash = $character->getCash();
					$banned = $character->getBanned();

					$jail = number_format($jail/60);
					$charName = str_replace("_", " ", $charName);

					print ("<h4><p class='text-center'>$charName</h4></p><br>");
					print ("<img style='width: 60px; height: 100px;' src='../core/images/accounts/small_skins/Skin_$skin.png' align='right'>");
					print ("<p>Esportato il: <b>$date</b></p>");

					print ($banned) ? ("<p>Bannato: <b>Sì</b></p>") : ("<p>Bannato: <b>No</b></p>");
					print ($jail) ? ("<p>Jailato: <b>Sì</b> ($jail min.)</p>") : ("<p>Jailato: <b>No</b></p>");
					print ("<p>Denaro: $<b>$cash</b></p>");

					if(!$banned && $free_slot) print ("<br><center><button type='button' class='btn btn-default' onclick='importCharacter($char_id)'>Importa</button></center>");
					print ("</div>");
					print ("</div>");
				}
			?>

        </div>
	</div>

	<?php include_once '../core/footer.php'; ?>

</body>
