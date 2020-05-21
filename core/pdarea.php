<?php

include_once '../class/User.class.php';

$account = new User($_COOKIE['account']);
$officer = $account->getOfficer();

if($officer == false)
{
	header("location: index.php"); die();
}

?>