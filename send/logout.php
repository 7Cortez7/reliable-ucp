<?php

	include_once '../class/User.class.php';
	
	$user = new User($_COOKIE['account']);
	$user->playerLog("logout", $user->getID(), -1);
 
	setcookie("code", null, time() - 1, "/");
	setcookie("account", null, time() - 1, "/");
	
	header("location: ../index.php");
	
?>		
