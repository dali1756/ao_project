<?php 
include_once('../../config/db.php');

$admin = $_SESSION['admin_user']['username'];
if(!$admin) func::alertMsg('請登入', '../session.php', true);

$ip = func::getUserIP();

$mode  = trim($_POST["price_all_mode"]);
$rate = trim($_POST["price_all_degree"]);
if($mode == '' && $rate == '') die(header("Location: ../power-ratecharge.php?error=3"));

if($mode)
{
	$sql = "UPDATE `room` SET `mode`=? , update_date=NOW() ;"; 
	$updated_mode =  func::excSQLwithParam('update', $sql, array($mode), false, $PDOLink);	 
}
if($rate)
{
	$sql = "UPDATE `room` SET price_degree=? , update_date=NOW() ;"; 
	$updated_rate =  func::excSQLwithParam('update', $sql, array($rate), false, $PDOLink);		 
} 
$field = array(); 
$change = '';
if($updated_mode || $updated_rate) 
{	  
	if($updated_mode)
	{
		$field[] = 'mode';
		$mode_chn = func::powerMode($mode); 
		$change .= " 收費設定 : {$mode_chn};";  
	}
	if($updated_rate)
	{
		$field[] = 'price_degree';
		$change .= " 用電度數 : {$rate};";  
	}
	$hw_cmd = array('op' => 'update', 'table' => 'room', 'id' => '1', 'field' => $field, 'mode' => 'all');
	insert_system_setting($hw_cmd); 
	$hw_cmd = array('op' => 'ALL_ChangeMode', 'table' => 'room', 'id' => '1' ,'field' =>$field);
	insert_system_setting($hw_cmd);
	
	$content = "全部房間設定: 管理員:{$admin}; ip:{$ip}; ".$change; 
	func::toLog('後台', $content, $PDOLink);	 			
	die(header("Location: ../power-ratecharge.php?success=1"));
	
} else {
	die(header("Location: ../power-ratecharge.php?error=1"));
}

?>       