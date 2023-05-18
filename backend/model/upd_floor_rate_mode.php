<?php  
include_once('../../config/db.php');

$admin = $_SESSION['admin_user']['username'];
if(!$admin) func::alertMsg('請登入', '../session.php', true);

$center_id = array();
$param = array();
$where = "";
$dong = $_POST["dong"];
$floor = $_POST["floor"];
$mode = trim($_POST["price_floor_mode"]);
$rate = trim($_POST["price_floor_degree"]);
$ip = func::getUserIP();

if($dong == '' || $floor == '' ) die(header("Location: ../power-ratecharge.php?error=2"));
if($mode=='' && $rate == '') die(header("Location: ../power-ratecharge.php?error=3"));
if($dong) {
	$where .= " AND dong = :dong ";
	$param[':dong'] = $dong;
}

if($floor) {
	$where .= " AND floor = :floor ";
	$param[':floor'] = $floor;
}

if($mode) {
	$sql = "UPDATE `room` SET `mode`= :mode, update_date = NOW() WHERE 1 ".$where;
	$param[':mode'] = $mode;
	$update_mode = func::excSQLwithParam('update', $sql, $param, false, $PDOLink);	
	unset($param[':mode']);
}
if($rate) {
	$sql = "UPDATE `room` SET  price_degree=:rate ,  update_date = NOW() WHERE 1 ".$where;
	$param[':rate'] = $rate;
	$update_rate = func::excSQLwithParam('update', $sql, $param, false, $PDOLink);	
	unset($param[':rate']);
}

$sql  = "SELECT center_id FROM `room` WHERE 1 {$where} GROUP BY center_id";
$data = func::excSQLwithParam('select', $sql, $param, true, $PDOLink);
$mode_chn = func:: powerMode($mode);
foreach($data as $v) {
	$center_id[] = $v['center_id'];
}

if($update_mode || $update_rate ) {	
	$field = array();
	$change = '';
	if($update_mode) { 
		$field[] = 'mode';
		$change .=" 收費設定:{$mode_chn}; "; 		
	}		
	if($update_rate) {
		$field[] = 'price_degree'; 
		$change .=" 用電度數:{$rate}; ";
	}
	foreach($center_id as $v) { 
		//$sql = "SELECT id FROM room WHERE center_id = ".$v;
		$sql = "SELECT id FROM room WHERE center_id = ".$v." AND dong='".$dong."' ORDER BY dong,center_id LIMIT 1";
		echo 'sql:'.$sql.'<BR';
		$room_arr = func::excSQL($sql, $PDOLink, false);
		$room_id  = $room_arr['id'];
		 
		$hw_cmd = array('op' => 'update', 'table' => 'room', 'id' => $room_id, 'field' =>$field, 'mode' => 'layer'); // room_id
		insert_system_setting($hw_cmd);
		$hw_cmd = array('op' => 'Layer_ChangeMode', 'table' => 'room', 'id' => $v, 'field' =>$field);  // center_id
		//insert_system_setting($hw_cmd);
		insert_system_setting_for_dong($hw_cmd, $dong);
	}   
		$content = "整層樓房間設定: 棟別:{$dong}; 樓層:{$floor}; 管理員:{$admin}; ip: {$ip};".$change;
		func::toLog('後台', $content, $PDOLink);
		die(header("Location: ../power-ratecharge.php?success=1"));
	
} else { 
	die(header("Location: ../power-ratecharge.php?error=1")); 
} 

?>       