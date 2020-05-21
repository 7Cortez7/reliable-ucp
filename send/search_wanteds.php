<?php

include_once '../core/userarea.php';
include_once '../core/pdarea.php';

include_once '../class/assets/dbconfig.php';

$stmt = $db->prepare("SELECT * FROM wanteds ORDER BY level DESC;");
$stmt->execute();
$count = $stmt->rowCount();

if(!$count) echo $apb = ("Veritabanında bulunamadı.");
if($count) while($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$id = $data['id'];
	$level = $data['level'];
	$target = $data['target'];
	echo $apb = ("<a onclick='showAPB($id)'>#<b>$id</b> - $target (Aciliyet Seviyesi: <b>$level</b>)</a><br>");
}

?>
