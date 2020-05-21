<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['account_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/User.class.php';
include_once '../class/assets/dbconfig.php';

$user = new User($_REQUEST['account_id']);
$id = $user->getID();
$email = $user->getEmail();

echo $editPanel = ("

	<div class ='row'>
	<div class='col-lg-12 col-lg-offset-5'>
	<form>
		<label>Mevcut Şifre</label>
		<input id='password' type='password' style='height: 30px; width:180px;' class='form-control' placeholder='********'><br>

		<label>Yeni Şifre</label>
		<div class='form-group input-group'>
		<span class='input-group-addon'> <i class='fa fa-key fa-fw'></i> </span>
		<input id='new_password' type='password' style='height: 30px; width:180px;' class='form-control' placeholder='********'>
		</div>

		<label>Şifreyi Onayla</label>
		<div class='form-group input-group'>
		<span class='input-group-addon'> <i class='fa fa-check fa-fw'></i> </span>
		<input id='confirm_new_password' type='password' style='height: 30px; width:180px;' class='form-control' placeholder='********'>
		</div>

		<input type='button' class='btn btn-success' style='float: center;' onclick='saveData($id)' value='Kaydet'>
		<input type='reset' class='btn btn-danger' style='float: center;' value='Sıfırla'>
	</form>
	</div>
	</div>");

?>
