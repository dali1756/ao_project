<?php 
date_default_timezone_set('Asia/Taipei');
require("PHPMailer_5.2.0/class.phpmailer.php");
$mail = new PHPMailer();
$mail->SMTPDebug = 0; //3:error display; 0:error non-display
$mail->CharSet = "utf-8"; //设置字符集编码

$mail->IsSMTP();  // set mailer to use SMTP

$mail->Host = "mail.lgood94.com.tw";  // specify main and backup server

$mail->SMTPAuth = true;  // turn on SMTP authentication
$mail->Username = "greengold@lgood94.com.tw";  // SMTP username 
$mail->Password = "a0975382327";  // SMTP password 
$mail->From = "greengold@lgood94.com.tw"; 

$mail->FromName = "EDM測試";
$mail->AddAddress("rsun329@gmail.com", "Steve Lin");
$mail->AddAddress("leo@aotech.com.tw", "LEO");
$mail->AddAddress("irene@aotech.com.tw", "Irene");
$mail->AddReplyTo("greengold@lgood94.com.tw", "Information");

$title="from pr8_EDM_EDM_Lgood94測試TITLE";
$content="from pr8_EDM_Lgood94測試內容";
$mail->WordWrap = 1500;                                 // set word wrap to 1500 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);   // set email format to HTML
$mail->Subject = $title;	//信件主旨
$mail->Body    = $content;	//信件內文
$mail->Send();
				
?>