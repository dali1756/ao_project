<?php include_once("../config/db.php"); ?>
<?php require_once '../vendor/autoload.php'; ?>
<?php
    set_time_limit(0);
	
$admin = $_SESSION['admin_user']['id'];
if($admin)
{  
    $dong = $_GET['dong'];
    $dong_name = $_GET["dong_name"];
    $search   = $_GET['search'];
    // 每日用電:輸入日期，開始結束都抓同天
	$st_date = $_GET['st_date'];
	$end_date = date('Y-m-d',strtotime($_GET['st_date']."+1 day"));
    # 結束月取隔月一號的第一筆資料
    $st_time = $year_st.'-'.$month_st.'-01 00:00:00';

    $total = $_GET['total']; //棟別名稱+度數
    $total_1 = $_GET['total_1'];
    $total_2 = $_GET['total_2'];
    // $total_110 = $_GET['total'];
    // $total_220 = $_GET['total'];

if ($dong && $st_date && $end_date) 
{
        $filename  = "棟別日用電總計.csv";
        $body_date = "列印日期:" . date("Ymd");   
        $body_head = array("房號", "開始時間", "結束時間", "(110V)開始度數 ~ 結束度數", "(110V)用電總計", "(220V)開始度數 ~ 結束度數", "(220V)用電總計");
        $body_tail = array("經手人", "", "主辦出納", "", "主辦會計", "", "機關長官", "");
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $xls = $spreadsheet->getActiveSheet();
        $param_st = array(
            // 每日用電:抓開始~結束日的時間
            ":st_date"=> $st_date." 00:00:00",
            ":end_date"=> $end_date." 00:00:59",
            ":dong" => $dong 
        ); 
        $sql = "select 
        (select name from room where id = a.room_id) as name,
        a.amonut as start_amonut,
        b.amonut as end_amonut,
        ROUND((b.amonut-a.amonut),2) as use_amonut,
        a.amonut_220 as start_amonut_220,
        b.amonut_220 as end_amonut_220,
        ROUND((b.amonut_220 - a.amonut_220), 2) as use_amonut_220,
        DATE_FORMAT(a.update_date,'%Y-%m-%d %H:%i:%s') as start_time,
        DATE_FORMAT(b.update_date,'%Y-%m-%d %H:%i:%s') as end_time
        from  room_amonut_log as a
        INNER JOIN (
            select room_id,max(b.amonut) as amonut, max(b.amonut_220) as amonut_220, max(b.update_date) as update_date 
            from  room_amonut_log as b where b.update_date >= '".$param_st[':st_date']."' and b.update_date <=  '".$param_st[':end_date']."' group by b.room_id) as b on a.room_id = b.room_id
        where a.update_date >= '".$param_st[':st_date']."' and a.update_date <= '".$param_st[':end_date']."' and a.room_id in (SELECT id FROM room where dong = '".$param_st[':dong']."')
        group by a.room_id order by a.room_id ";  
        $room_data  = func::excSQL($sql, $PDOLink, true);
  
        foreach ($room_data as $k => $row) { 
            $room_name = $row['name']; // 房號
            $room_ele = $row['use_amonut']; // 單間房的總度數
            $room_ele_220 = $row['use_amonut_220']; // 單間房的總度數
            $st_time = $row['start_time'];
            $ed_time = $row['end_time'];
            $st_amonut = $row["start_amonut"];
            $end_amonut = $row["end_amonut"];
            $st_amonut_220 = $row["start_amonut_220"];
            $end_amonut_220 = $row["end_amonut_220"];
            if ($k == 0) 
            {
                // $xls->setCellValueByColumnAndRow(1, $k + 1, $body_date); 
                // $xls->setCellValueByColumnAndRow(1, $k + 2, $total);
                // test
                $xls->setCellValueByColumnAndRow(1, $k + 1, $body_date);
                $xls->setCellValueByColumnAndRow(1, $k + 2, $dong_name);  
                $xls->setCellValueByColumnAndRow(2, $k + 2, "棟別日用電總計"); 
                
                $xls->setCellValueByColumnAndRow(1, $k + 3, "總計用電：");
                $xls->setCellValueByColumnAndRow(2, $k + 3, $total);  
                $xls->setCellValueByColumnAndRow(1, $k + 4, "小計用電(110V)：");   
                $xls->setCellValueByColumnAndRow(2, $k + 4, $total_1); 
                $xls->setCellValueByColumnAndRow(1, $k + 5, "小計用電(220V)：");   
                $xls->setCellValueByColumnAndRow(2, $k + 5, $total_2); 
                
                foreach ($body_head as $i => $v) {
                    $xls->setCellValueByColumnAndRow($i + 1, $k + 7, $v);
                    
                }
            } 
            $i = 1; 
            $xls->setCellValueByColumnAndRow($i++, $k + 8, $room_name);				
            $xls->setCellValueByColumnAndRow($i++, $k + 8, $st_time ); 
            $xls->setCellValueByColumnAndRow($i++, $k + 8, $ed_time); 
            $xls->setCellValueByColumnAndRow($i++, $k + 8, $st_amonut. " ~ ". $end_amonut);
            $xls->setCellValueByColumnAndRow($i++, $k + 8, $room_ele);
            $xls->setCellValueByColumnAndRow($i++, $k + 8, $st_amonut_220. " ~ ". $end_amonut_220);             
            $xls->setCellValueByColumnAndRow($i++, $k + 8, $room_ele_220);
        }

        foreach ($body_tail as $i => $v) {
            $xls->setCellValueByColumnAndRow($i + 1, $k + 10, $v);
        }

        header('Content-Type: text/csv;charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
        $objWriter->setUseBOM(true);
        $objWriter->save('php://output');
        $room_data = null;
        $body_tail = null;
        $body_head = null;
    }else {
        die(header("location: ../power-consumption-d.php?error=6"));
    }
 
}else{
	  func::alertMsg('請登入', '../index.php' , true);
	  exit();
}			
?>