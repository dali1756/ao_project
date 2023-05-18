<?php 
		require_once('PHPMailer/src/PHPMailer.php');
		require_once('PHPMailer/src/SMTP.php');
		$mail= new PHPMailer();                             //建立新物件
		$mail->SMTPDebug = 3;                        
		$mail->IsSMTP();                                    //設定使用SMTP方式寄信
		$mail->SMTPAuth = true;                        //設定SMTP需要驗證
	//	$mail->SMTPSecure = "ssl";                    // Gmail的SMTP主機需要使用SSL連線
	//	$mail->Host = "smtp.gmail.com";             //Gamil的SMTP主機
	//	$mail->Port = 465;                                 //Gamil的SMTP主機的埠號(Gmail為465)。
		$mail->Host = "mail.lgood94.com.tw";  // specify main and backup server
	//	$mail->Host = "mail.rentzu.com.tw";
	//	$mail->Port = 25;
		$mail->CharSet = "utf-8";                       //郵件編碼
		$mail->Username = "greengold@lgood94.com.tw";  // SMTP username 
		$mail->Password = "a0975382327";  // SMTP password 
		$mail->From = "greengold@lgood94.com.tw"; 
	//	$mail->Username = "ginunion@rentzu.com.tw";       //Gamil帳號
	//	$mail->Password = "a0975382327";                 //Gmail密碼
		$fromname="和創數位科技股份有限公司";
	//	$from="0006108@aibooks.tw";
		$title="和創數位科 MAIL測試";
		$body="和創數位科 MAIL測試內容";
	//	$email="ginunion@rentzu.com.tw";
		$mail->AddAddress("rsun329@hotmail.com", "Steve Lin");
	//	$mail->From = $from;        //寄件者信箱
		$mail->FromName = $fromname;                  //寄件者姓名
		$mail->Subject =$title; //郵件標題
		$mail->Body = $body; //郵件內容
		//$mail->addAttachment('../uploadfile/file/dirname.png','new.jpg'); //附件，改以新的檔名寄出
		$mail->IsHTML(true);                             //郵件內容為html
	//	$mail->AddAddress("$email");            //收件者郵件及名稱

		if(!$mail->Send())
		{
			return false;
		}
		else
		{
			return true;
		}
?>