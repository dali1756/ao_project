<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
class Mail_test2 extends CI_Controller
{
	public function __construct()
	{
	}
	function index()
	{
		//echo "Hello World EDM...";
		$this->sendmail1("rsun329@hotmail.com","淡淡三月天aa","郊遊去bb!!",$fromname='鯨動智能科技',$from='0006108@aibooks.tw');
		
//echo "aaa";
	}
	public function sendmail1($email,$title,$body,$fromname='鯨動智能科技',$from='0006108@aibooks.tw')
	{
		require_once('./application/libraries/PHPMailer/src/PHPMailer.php');
		require_once('./application/libraries/PHPMailer/src/SMTP.php');
		$mail= new PHPMailer();                             //建立新物件
		$mail->SMTPDebug = 0;                        
		$mail->IsSMTP();                                    //設定使用SMTP方式寄信
		$mail->SMTPAuth = true;                        //設定SMTP需要驗證
		$mail->SMTPSecure = "ssl";                    // Gmail的SMTP主機需要使用SSL連線
		$mail->Host = "smtp.gmail.com";             //Gamil的SMTP主機
		$mail->Port = 465;                                 //Gamil的SMTP主機的埠號(Gmail為465)。
		$mail->CharSet = "utf-8";                       //郵件編碼
		$mail->Username = "@gmail.com";       //Gamil帳號
		$mail->Password = "";                 //Gmail密碼
		$mail->From = $from;        //寄件者信箱
		$mail->FromName = $fromname;                  //寄件者姓名
		$mail->Subject =$title; //郵件標題
		$mail->Body = $body; //郵件內容
		//$mail->addAttachment('../uploadfile/file/dirname.png','new.jpg'); //附件，改以新的檔名寄出
		$mail->IsHTML(true);                             //郵件內容為html
		$mail->AddAddress("$email");            //收件者郵件及名稱

		if(!$mail->Send())
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
?>