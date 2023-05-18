<?php 
date_default_timezone_set('Asia/Taipei');
require("PHPMailer_5.2.0/class.phpmailer.php");
$mail = new PHPMailer();
$mail->CharSet = "utf-8"; //设置字符集编码
$cnt = 0;
$mail->IsSMTP();  // set mailer to use SMTP

$mail->Host = "pollux3.url.com.tw";  // specify main and backup server
/*
if($cnt < 10){
	$mail->Host = $res['emailhost'];  // specify main and backup server 
}else{
	$mail->Host = "pollux3.url.com.tw";  // specify main and backup server
//	$mail->Host = $res['emailhost'];  // specify main and backup server
}*/
$mail->SMTPAuth = true;  // turn on SMTP authentication

	$mail->Username = "sale@popdiy.com.tw";  // SMTP username 
	
	$mail->Password = "ibanner2516";  // SMTP password 
	
	$mail->From = "sale@popdiy.com.tw"; 
/*	
if($cnt < 10){
	$mail->Username = $res['sendemail'];  // SMTP username 
}else{
	$mail->Username = "sale@popdiy.com.tw";  // SMTP username 
//	$mail->Username = $res['sendemail'];  // SMTP username 
}
if($cnt < 10){
	$mail->Password = $res['sendpassword']; // SMTP password
}else{
	$mail->Password = "ibanner2516";  // SMTP password 
//	$mail->Password = "a0975382327";  // SMTP password 
}


if($cnt < 10){
	$mail->From = $res['sendemail']; 
}else{
	$mail->From = "sale@popdiy.com.tw";  
//	$mail->From = $res['sendemail'];  
}
if($cnt < 20){$cnt++;}else{$cnt=0;}
*/
$mail->FromName = "EDM測試";
$mail->AddAddress("rsun329@hotmail.com", "Steve Lin");
$mail->AddReplyTo("sale@popdiy.com.tw", "Information");
//$mail->AddAddress($agent['mailbox'], $agent['name']);
//$mail->AddReplyTo($res['sendemail'], "Information");
$title="EDM測試TITLE";
$content="EDM測試內容";
$mail->WordWrap = 1500;                                 // set word wrap to 1500 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);   // set email format to HTML
$mail->Subject = $title;	//信件主旨
$mail->Body    = $content;	//信件內文
$mail->Send();
				//获取邮件发送状态
		//		if($mail->Send()){
?>