<?php
require_once("PHPMailer_5.2.0/class.phpmailer.php");

date_default_timezone_set('Asia/Taipei');


$mail= new PHPMailer();

// Server 資訊
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;
$mail->CharSet = "utf-8";

// 登入
$mail->Username = "rsun329@gmail.com"; //帳號
$mail->Password = "ocdiaqdsnufjbtbm"; //密碼

// 寄件者
$mail->From = "rsun329@hotmail.com"; //寄件者信箱
$mail->FromName = "Steve Lin"; //寄件者姓名
//$mail->ConfirmReadingTo = "your_gmail_account@gmail.com"; // 讀取回條 (對 Gmail 沒啥用)

// 郵件資訊
$mail->Subject = "淡淡三月份"; //設定郵件標題
$mail->IsHTML(true); //設定郵件內容為HTML
/*
function send_mail($mail_address, $name, $body)
{
	global $mail;
	$mail->Body = $body;
	$mail->ClearAddresses();
	$mail->AddAddress($mail_address,$name); //新稱收件者 (郵件及名稱)
	//$mail->AddCC("some_other one@gmail.com", "Someone"); // 新稱副本收件者

	if(!$mail->Send()) {
		echo "Error: " . $mail->ErrorInfo . "\n";
	} else {
		echo "Send To: " . $mail_address . "\n";
	}
}

function msg()
{
	return <<<EOF
<p>Hi $%name%,
<p>Here's the HTML template with $%pattern_strings%.
EOF;
}

function fill_template($content, $data)
{
	foreach($data as $key => $value){
		$content = str_replace('$%'.$key.'%', $value, $content);
	}
	return $content;
}

function parse_name_list_tsv($filename)
{
	$namelist = array();
	$file = fopen($filename, 'r');
	while($line = fgets($file)){
		$line = str_replace("\n", "", $line);
		list($name, $mail_address, $vip_code) = explode("\t", $line);
		$namelist[] = array('name'=>$name, 'mail_address'=>$mail_address, 'vip_code'=>$vip_code);
	}
	return $namelist;
}

$namelist = parse_name_list_tsv('list.tsv');

//var_dump($namelist); die(); // convenient for show the name list

// Send email to all persons in name list
foreach($namelist as $data){
	$body = fill_template(msg(), $data);
	send_mail($data['mail_address'], $data['name'], $body);
}
*/
?>