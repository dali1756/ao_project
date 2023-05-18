<?php 
date_default_timezone_set('Asia/Taipei');
require("PHPMailer_5.2.0/class.phpmailer.php");
$mail = new PHPMailer();
$mail->CharSet = "utf-8"; //设置字符集编码

$mail->IsSMTP();  // set mailer to use SMTP

$mail->Host = "mail.rentzu.com.tw";  // specify main and backup server

$mail->SMTPAuth = true;  // turn on SMTP authentication
$mail->Username = "ginunion@rentzu.com.tw";  // SMTP username 
$mail->Password = "a0975382327";  // SMTP password 
$mail->From = "ginunion@rentzu.com.tw"; 

$mail->FromName = "EDM測試";
$mail->AddAddress("rsun329@hotmail.com", "Steve Lin");
$mail->AddReplyTo("ginunion@rentzu.com.tw", "Information");

$title="from ginunion測試TITLE";
$content="from ginunion測試TITLE";
$mail->WordWrap = 1500;                                 // set word wrap to 1500 characters
$mail->AddAttachment("/edm.lgood94.com.tw/usage.png");
//$mail->AddAttachment("/var/www/usage/usage.png");         // add attachments
//$mail->AddAttachment("/tmp/yum.log", "new.jpg");    // optional name
$mail->IsHTML(true);   // set email format to HTML
$mail->Subject = $title;	//信件主旨
$mail->Body    = $content;	//信件內文
$mail->Send();
				
?>