<?php
	require_once('../config/db.php');
	
	require '../vendor/autoload.php';
	
	include('../chk_log_in.php');
	
	set_time_limit(0);
	
	$date_format = 'Y/m/d';
	$time_format = 'H:i:s';
	
	$sql_kw      = "";
	
 	$cname       = $_GET['cname'];
 	$username    = $_GET['username'];
 	$room_num    = $_GET['room_strings'];
	$start_date  = $_GET['start_date'];
	$end_date    = $_GET['end_date'];
	
	if($username) { $sql_kw .= " AND m.`username` like '%".trim($username)."%' "; }
	if($cname)    { $sql_kw .= " AND m.`cname` like '%".trim($cname)."%' "; }
	if($room_num) { $sql_kw .= " AND concat(r.name, m.berth_number) LIKE '%".trim($room_num)."%' "; }
	
	if($start_date) { 
		$s_time  = date('Y-m-d', strtotime($start_date));
		$sql_kw .= " AND e.add_date > '{$s_time}' "; 
	}
	
	if($end_date)   { 
		$e_time  = date('Y-m-d', strtotime($end_date." +1 day"));
		$sql_kw .= " AND e.add_date < '{$e_time}' "; 
	}
	
	$sql = "
		SELECT e.*, m.username, m.cname, r.`name`, r.name, m.berth_number 
		FROM `ezcard_record` e
		LEFT JOIN member m ON e.member_id = m.id
		LEFT JOIN room r ON r.id = e.room_id
		WHERE 1 ".$sql_kw." ORDER BY e.add_date DESC";
	$rs  = $PDOLink->prepare($sql);
	$rs->execute();
	$data = $rs->fetchAll();
	
	$sort_map = array("Stored" => "付款", "Refund" => "退費");
	
	if($data) {
		
		$filename  = "付款紀錄匯出.csv";
		$body_date = "列印日期:".date("Ymd");
		$body_head = array("編號", "付款日期", "房號/床號", "學號/姓名", "付款金額", "備註");
		$body_tail = array("經手人", "", "主辦出納", "", "主辦會計", "", "機關長官", "");
		
		$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$xls = $spreadsheet->getActiveSheet();
		
		foreach($data as $k => $row) {
			
			$row_count = ($prepage * $pagesize) + ++$j;
			$showtime  = date($date_format.' '.$time_format, strtotime($row["add_date"]));
			$showname  = $row['username'];
			$showname .= " / ";
			$showname .= $row['cname'];
			$showfee   = $row["PayValue"];
			$showsort  = $sort_map[$row["Sort"]];
			$room_num  = $row["name"].'/'.$row["berth_number"];
			
			if($k == 0) {
				
				$xls->setCellValueByColumnAndRow(1, $k+1, $body_date);
				
				foreach($body_head as $i => $v) {				
					$xls->setCellValueByColumnAndRow($i+1, $k+2, $v);
				}
			}
						
			$i = 1;
			
			$xls->setCellValueByColumnAndRow($i++, $k+3, $k+1);
			$xls->setCellValueByColumnAndRow($i++, $k+3, $showtime);
			$xls->setCellValueByColumnAndRow($i++, $k+3, $room_num);
			$xls->setCellValueByColumnAndRow($i++, $k+3, $showname);
			$xls->setCellValueByColumnAndRow($i++, $k+3, $showfee);
			$xls->setCellValueByColumnAndRow($i++, $k+3, $showsort);
		}
		
		foreach($body_tail as $i => $v) {				
			$xls->setCellValueByColumnAndRow($i+1, $k+5, $v);
		}
		
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
		$writer->setUseBOM(true);
		$writer->save('php://output');
		
	} else {
		header('Location: ../power-stored.php?error=1');
	}
?>