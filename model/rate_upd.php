<?php 

include_once('../config/db.php');

include('../chk_log_in.php');
//ini_set('display_errors', 1);

//error_reporting(E_ALL ^ E_NOTICE);

//error_reporting(E_ALL ^ E_WARNING);

$admin = $_SESSION['admin_user']['username'];
$admin_id = $_SESSION['admin_user']['id'];
$nowtime  = date('Y-m-d H:i:s');
$ip = func::getUserIP();
// $room_id  = $_POST["room_id"];
$room_num = trim($_POST["room_num"]);
$price = trim($_POST["price_elec_degree"]); 
$price_220 = trim($_POST["price_elec_degree_220"]); 
if($price == '' || $price_220 == '') die(header("Location: ../rate.php?error=2"));
 
$sql = "SELECT id FROM `room` WHERE name = ? ";
$room_id = func::excSQLwithParam('select', $sql, array($room_num), false, $PDOLink);
 
if($room_id['id'])
{	
	$sql = "UPDATE `room` SET `price_degree` = ?,`price_degree_220` = ? ,update_date=NOW() WHERE `id` = ? ";
	$updated = func::excSQLwithParam('update', $sql, array($price, $price_220, $room_id['id']), false, $PDOLink);
	if( $updated) 
	{
		$hw_cmd = array('op' => 'update', 'table' => 'room', 'id' =>$room_id['id'], 'field' => array('price_degree','price_degree_220'), 'mode' => 'single');
		insert_system_setting($hw_cmd);
 
		$hw_cmd = array('op' => 'Single_ChangeMode', 'table' => 'room', 'id' =>$room_id['id'], 'field' => array('price_degree','price_degree_220'));
		insert_system_setting($hw_cmd);			
	}
	
	$content = "個別房間設定[費率更新] :id:{$room_id['id']}; 房間 : {$room_num}; 110費率:{$price}; 220費率:{$price_220}; ip: {$ip}; 管理員:{$admin_id}/{$admin}";
	func::toLog('前台', $content, $PDOLink);
		
	die(header("Location: ../rate.php?room_numbers_kw={$room_num}&price_elec_degree={$price}&success=1"));
	
} else { 
	die(header("Location: ../rate.php?room_numbers_kw={$room_num}&price_elec_degree={$price}&error=1"));
	
} 

?>       