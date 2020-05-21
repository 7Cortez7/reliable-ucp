<?php 

if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) == true) 
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];

include '../class/User.class.php';

$status = User::playerLogin($_REQUEST['account'], $_REQUEST['password'], $_SERVER['REMOTE_ADDR']);

echo $status;

?>