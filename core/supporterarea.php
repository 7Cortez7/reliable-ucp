<?php
	
	include_once '../class/User.class.php';
	
	$user = new User($_COOKIE['account']);
	$admin_level = $user->getStaffLevel();

	if($admin_level < 1)
	{
		header("location: ../user/index.php"); die();
	}
?>