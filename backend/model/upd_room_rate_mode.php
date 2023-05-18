<?php 
include_once('../../config/db.php');
$admin = $_SESSION['admin_user']['username'];
if(!$admin) func::alertMsg('請登入', '../session.php', true);

$ip = func::getUserIP(); 
$room_id  = trim($_POST["room_id"]);
$room_num = trim($_POST["room_num"]);
$mode = trim($_POST["mode"]);
$rate = trim($_POST["price_degree"]);

if($mode =='' || $rate =='') die(header("Location: ../power-ratecharge.php?room_numbers_kw={$room_num}&error=1"));
$sql = "SELECT * FROM `room` WHERE  id = ? ";
$data = func::excSQLwithParam('select', $sql, array($room_id), false, $PDOLink);

if($data['id'])
{
	$room_id = $data['id'];
	$room_name = $data['name'];
	$sql = "UPDATE `room` SET `mode`= ?, price_degree= ?, update_date=NOW() WHERE `id` = ? "; 
	$updated = func::excSQLwithParam('update', $sql, array($mode, $rate, $room_id), false, $PDOLink);
	if($updated)
	{
		$hw_cmd = array('op' => 'update', 'table' => 'room', 'id' => $room_id, 'field' => array('mode', 'price_degree'), 'mode' => 'single');
		insert_system_setting($hw_cmd);
		
		$hw_cmd = array('op' => 'Single_ChangeMode', 'table' => 'room', 'id' => $room_id, 'field' => array('mode', 'price_degree'));
		insert_system_setting($hw_cmd);		
		$mode_chn = func:: powerMode($mode);
		$content = "更新費率及模式: room_id : {$room_id}; 房間名稱 : {$room_name}; 用電度數: {$rate};收費設定 :{$mode_chn }; 管理員:{$admin}; ip : {$ip}";
		func::toLog('後台', $content, $PDOLink);	
		die(header("Location: ../power-ratecharge.php?room_numbers_kw={$room_num}&success=1"));		
	}else{
		die(header("Location: ../power-ratecharge.php?room_numbers_kw={$room_num}&error=1"));
	}
}else{
	die(header("Location: ../power-ratecharge.php?room_numbers_kw={$room_num}&error=1"));
}
 

?>       