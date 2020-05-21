<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

include_once '../class/User.class.php';
include_once '../class/Character.class.php';

$user = new User($_COOKIE['account']);
$officers = "";

foreach($user->getCharactersList() as $char_id)
{
	$character = new Character($char_id);
	$faction = $character->getFaction();
	$name = $character->getCharacterName();
	$name = str_replace("_", " ", $name);
	if(!$faction) $officers .= "<option value='$char_id'>$name</option>";
}

echo $apb = ("

	<form id='create' action='../send/add_wanteds.php' method='POST'>

	<div class='form-group input-group'>
		<span class='input-group-addon'> <i class='fa fa-user fa-fw'></i> </span>
		<input type='text' class='form-control' placeholder='Başlık/Konu' name='target'><br>
	</div>

	<div class='form-group input-group'>
		<span class='input-group-addon'> <i class='fa fa-cab fa-fw'></i> </span>
		<select name='officer' class='form-control'>
		$officers
		</select>
	</div>

	<br><center><button type='button' class='btn btn-success' onclick='sendAPB()'>Oluştur</button>
	<button type='button' class='btn btn-default' onclick='searchAPB()'>Vazgeç</button></center>

	</form>

	");
