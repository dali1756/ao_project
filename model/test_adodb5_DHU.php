<?php
ini_set('display_errors', 1);

error_reporting(E_ALL ^ E_NOTICE);

error_reporting(E_ALL ^ E_WARNING);

/************************ YOUR DATABASE CONNECTION START HERE   ****************************/
		$DB_Server = "localhost"; // MySQL Server
		$DB_Username = "barry"; // MySQL Username
	//	$DB_Password = "su631811";
		$DB_Password = '1qaz!@#$W'; // MySQL Password
		$DB_DBName = "ndhu_db_new"; // MySQL Database Name
		 
		/***** DO NOT EDIT BELOW LINES *****/
		// Create MySQL connection
		$Connect = mysqli_connect($DB_Server, $DB_Username, $DB_Password) or die("Failed to connect to MySQL:<br />" . mysqli_error() . "<br />" . mysqli_errno());
		mysqli_query("SET NAMES 'big5'");
		// Select database
	//	$Db = mysqli_select_db($DB_DBName, $Connect) or die("Failed to select database:<br />" . mysqli_error(). "<br />" . mysqli_errno());
		$Db = mysqli_select_db($Connect, $DB_DBName) or die("Failed to select database:<br />" . mysqli_error(). "<br />" . mysqli_errno());

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$link = mysqli_connect("localhost", "barry", '1qaz!@#$W', "ndhu_db_new");

/* get the name of the current default database */
$result = mysqli_query($link, "SELECT DATABASE()");
$row = mysqli_fetch_row($result);
printf("Default database is %s.\n", $row[0]);
echo "<br/>";
/* change default database to "world" */
mysqli_select_db($link, "bulletinboard");

/* get the name of the current default database */
$result = mysqli_query($link, "SELECT DATABASE()");
$row = mysqli_fetch_row($result);
printf("Default database is %s.\n", $row[0]);
/*
$db = adoNewConnection($driver); # eg. 'mysqli' or 'oci8'
$db->debug = true;
$db->connect($server, $user, $password, $database);
*/		
/*		include("adodb5/adodb.inc.php");
		$conn = ADONewConnection('mysql'); 
		$conn->debug = true;
		$conn->Connect("localhost", "barry", "1qaz!@#$W", "ndhu_db_new");
		$conn->query("SET NAMES 'utf8'");
		if (!$conn) { echo "無法連接資料庫 db1"; exit; }
*/
/*				
$sql="insert into twcms_courseitem (chr,post_year,post_month,sno,xtable,xtype,cu_seq,height,height_ref,width,width_ref,weight,label,orderno,xinner,insp,rough,stus,def,hrb,spec,quality,steel) values('".$chr."', '".$year."', '".$month."', '".$sno."', '".$xtable."', '".$xtype."', '".$cu_seq."', '".$height."', '".$height_ref."', '".$width."', '".$width_ref."', '".$weight."', '".$label."', '".$orderno."', '".$xinner."', '".$insp."', '".$rough."', '".$stus."', '".$def."', '".$hrb."', '".$spec."', '".$quality."', '".$steel."');";
			$insertTable=mysql_query($sql);
			if($insertTable==1){
				$cnt=$cnt+1;
			}
			echo mysql_error();
*/			
/************************ YOUR DATABASE CONNECTION END HERE  *******************************/
/*		include("adodb5/adodb.inc.php");
		$conn = ADONewConnection('mysql'); 
		$conn->debug = true;
		$conn->Connect("localhost", "barry", '1qaz!@#$W', "ndhu_db_new");
		$conn->query("SET NAMES 'utf8'");
		if (!$conn) { echo "無法連接資料庫 db1"; exit; }
*/
echo "<br/>";
echo "PDO connect...";
echo "<br/>";
			
	$PDOHostVar       = 'localhost';
	$PDODBnameVar     = 'ndhu_db_new';
	$PDODBuserVar     = 'barry';
	$PDODBpasswordVar = '1qaz!@#$W';
	
	try {   
		$PDOLink = new PDO("mysql:host={$PDOHostVar};dbname={$PDODBnameVar}",$PDODBuserVar,$PDODBpasswordVar);  
		$PDOLink->query("SET NAMES 'utf8'");
		$PDOLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		print "連線失敗" . $e->getMessage(); 
	}
			$id = 11;
			$mail_list = array();
			
			$sql = "SELECT * FROM content_us WHERE id = '{$id}'";
			$rs  = $PDOLink->prepare($sql);
			$rs->execute();
			$chk_data = $rs->fetch();

			$mail_list[] = $chk_data;
			foreach($mail_list as $v) {
				echo $v['email'].' '.$v['title'];     // Add a recipient
				echo "<br/>";
			}
			// log_list
			//	$nowtime = date('Y-m-d H:i:s');
				$Now = new DateTime('now', new DateTimeZone('Asia/Taipei'));

				$nowtime = $Now->format('Y-m-d H:i:s');
				$content = "客服中心回覆信件發送完成";
				$data_type = "測試PDO寫入insert";
				$log_ins_q = "INSERT INTO `log_list` (`content`, `data_type`, `add_date`) VALUES ('{$content}', '{$data_type}', '{$nowtime}'); ";
				$PDOLink->exec($log_ins_q);
		/*	try {
				// Server settings
				// $mail->SMTPDebug  = SMTP::DEBUG_SERVER;                  // Enable verbose debug output
				$mail->isSMTP();                                            // Send using SMTP
				$mail->Host 	  = MAIL_HOST;  	                    // Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				$mail->Username   = MAIL_ACCOUNT;
				$mail->Password   = MAIL_PWD;
				// $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = MAIL_PORT;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
				$mail->CharSet    = 'UTF-8';

				// Recipients
				$mail->setFrom(MAIL_ACCOUNT, 'mailer');
				
				foreach($mail_list as $v) {
					$mail->addAddress($v['email'], $v['title']);     // Add a recipient
				}
				
				$mail->addBCC(MAIL_ACCOUNT,"ao");
				$mail->addBCC("barry@aotech.com.tw",   "Barry");
				$mail->addBCC("emily@aotech.com.tw",   "Emily");
				//$mail->addBCC("ao.patty887@gmail.com", "Patty");
				$mail->addBCC("a120216363@gmail.com",  "浩軒");
				$mail->addBCC("vivi@aotech.com.tw",    "佳怡");

				// Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = $subject;
				$mail->Body    = $mail_body;

				if(!$mail->send()) {
					header('Location: ../contact_us_edit.php?error=4&id='.$rec_id);
					exit();
				}
				
				// log_list
				$content = "客服中心回覆信件發送完成";
				$log_ins_q = "INSERT INTO `log_list` (`content`, `data_type`, `add_date`) VALUES ('{$content}', 'remote_ip:{$remote_ip}', '{$nowtime}'); ";
				$PDOLink->exec($log_ins_q);
				
			} catch (Exception $e) {
				
				// log_list
				$content = "客服中心回覆信件發送失敗 ".$mail->ErrorInfo;
				$log_ins_q = "INSERT INTO `log_list` (`content`, `data_type`, `add_date`) VALUES ('{$content}, 'remote_ip:{$remote_ip}', '{$nowtime}'); ";
				$PDOLink->exec($log_ins_q);
				
				header('Location: ../contact_us_edit.php?error=4&id='.$rec_id);
				return;
			}
		*/		