<?php
	date_default_timezone_set('Asia/Taipei');
	require("PHPMailer_5.2.0/class.phpmailer.php");
//	require_once('./application/libraries/PHPMailer/src/PHPMailer.php');
//	require_once('./application/libraries/PHPMailer/src/SMTP.php');
	//發送郵件、通知對帳單

	$mail = new PHPMailer();
	$mail->CharSet = "utf-8"; //设置字符集编码
$cnt = 100;
	$mail->IsSMTP();  // set mailer to use SMTP
	if($cnt < 10){
	//	$mail->Host = $res['emailhost'];  // specify main and backup server 
	}else{
	//	$mail->Host = "smtp.gmail.com";  // specify main and backup server
		$mail->Host = "pollux3.url.com.tw";  // specify main and backup server
	//	$mail->Host = $res['emailhost'];  // specify main and backup server
	}
	$mail->SMTPAuth = true;  // turn on SMTP authentication
	if($cnt < 10){
	//	$mail->Username = $res['sendemail'];  // SMTP username 
	}else{
	//	$mail->Username = "aibooks009@gmail.com";  // SMTP username
		$mail->Username = "sale@popdiy.com.tw";  // SMTP username 
	//	$mail->Username = $res['sendemail'];  // SMTP username 
	}
	if($cnt < 10){
	//	$mail->Password = $res['sendpassword']; // SMTP password
	}else{
	//	$mail->Password = "worker@12345";  // SMTP password 
		$mail->Password = "ibanner2516";  // SMTP password 
	//	$mail->Password = "a0975382327";  // SMTP password 
	}


	if($cnt < 10){
	//	$mail->From = $res['sendemail']; 
	}else{
	//	$mail->From = "aibooks009@gmail.com";  
		$mail->From = "sale@popdiy.com.tw";  
	//	$mail->From = $res['sendemail'];  
	}
	if($cnt < 20){$cnt++;}else{$cnt=0;}
	$mail->FromName = "鯨動智能科技";
	$mail->AddAddress("rsun329@hotmail.com", "客服人員");
	$mail->AddReplyTo("sale@popdiy.com.tw", "Information");
/*
	$mail->FromName = $res['title'];
	$mail->AddAddress($agent['mailbox'], $agent['name']);
	$mail->AddReplyTo($res['sendemail'], "Information");
*/
	$mail->WordWrap = 1500;                                 // set word wrap to 1500 characters
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->IsHTML(true);   // set email format to HTML
	$mail->Subject = "鯨動智能科技-電子報";	//信件主旨
	$mail->Body    = "淡淡三月天";	//信件內文
//	$mail->Subject = $title;	//信件主旨
//	$mail->Body    = $content;	//信件內文
?>