<?php

	require "phpmailer/PHPMailerAutoload.php";
	$mail = new PHPmailer();

	$mail->IsSMTP();
	$mail->IsHTML(true);
	$mail->Host       = "mail.reliable-roleplay.com";
	$mail->SMTPAuth = true;
	$mail->Port=465;
	$mail->Username   = "no-reply@reliable-roleplay.com";
	$mail->Password   = "FKft41S8";

	$mail->SetFrom('no-reply@reliable-roleplay.com', 'Reliable Roleplay');
	$mail->AddAddress($mail_receiver);
	$mail->AddReplyTo('ahmete474@gmail.com');
	$mail->Subject = $mail_object;

	$mail->Body = $mail_body;

	$send = $mail->Send();
	if(!$send) echo $mail->ErrorInfo;

	$mail->SmtpClose();
	unset($mail);

?>
