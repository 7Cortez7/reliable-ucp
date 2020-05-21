<?php

include_once '../core/userarea.php';

if(isset($_REQUEST['alert_id']) != true)
{
	header("location: ../user/index.php");
}

include_once '../class/Alert.class.php';
include_once '../class/assets/dbconfig.php';

$id = $_REQUEST['alert_id'];

$alert = new Alert($id);
$opened = $alert->getOpened();
$date = $alert->getDate();
$object = $alert->getObject();

echo("<i class = 'fa fa-circle-o'></i> (<b>$date</b>) $object");

if($opened == true) return false;

$db = getDB();
$stmt = $db->prepare("UPDATE ucp_alerts SET opened = '1' WHERE id=:id");
$stmt->bindParam("id", $id, PDO::PARAM_INT);
$stmt->execute();	

?>

