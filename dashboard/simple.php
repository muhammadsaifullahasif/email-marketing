<?php
include('smtp/PHPMailerAutoload.php');
//echo smtp_mailer('to','subject','msg');
function smtp_mailer($to,$subject, $msg){
	$mail = new PHPMailer(); 
	//$mail->SMTPDebug=3;
	$mail->IsSMTP(); 
	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = 'ssl'; 
	$mail->Host = "wirecoder.com";
	$mail->Port = "465"; 
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	$mail->Username = "contact@wirecoder.com";
	$mail->Password = '7S@!fullah';
	$mail->SetFrom("contact@wirecoder.com", "WireCoder");
	$mail->HeaderLine('X-Priority', 1);
	$mail->Subject = $subject;
	$mail->Body =$msg;
	$mail->AddAddress($to);
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
	));
	if(!$mail->Send()){
		echo $mail->ErrorInfo;
	}else{
		echo 'Sent';
	}
}
?>