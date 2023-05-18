<?php 

	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');

 	$pagesize = 10;
	
	$date_format = 'Y/m/d';
	$time_format = 'H:i:s';

	$sql_kw      = "";
	
 	$cname       = trim($_GET['cname']);
 	$id_card    = trim($_GET['id_card']);
 	$room_num    = trim($_GET['room_strings']);
	$start_date  = $_GET['start_date'];
	$end_date    = $_GET['end_date'];
	$search      = $_GET['search'];
	
	if($id_card) { $sql_kw .= " AND m.`id_card` like '%".trim($id_card)."%' "; }
	if($cname)    { $sql_kw .= " AND m.`cname` like '%".trim($cname)."%' "; }
	if($room_num) { $sql_kw .= " AND r.name LIKE '%".trim($room_num)."%' "; }
	
	if($start_date) { 
		$s_time  = date('Y-m-d', strtotime($start_date));
		$sql_kw .= " AND e.add_date > '{$s_time}' "; 
	}
	
	if($end_date)   { 
		$e_time  = date('Y-m-d', strtotime($end_date." +1 day"));
		$sql_kw .= " AND e.add_date < '{$e_time}' "; 
	}
	
	$sql = "
		SELECT count(*) as 'count' FROM `ezcard_record` e
		LEFT JOIN member m ON e.member_id = m.id
		LEFT JOIN room r ON r.id = e.room_id WHERE 1 AND r.Title = '研習室'".$sql_kw;
	$rs  = $PDOLink->prepare($sql);
	$rs->execute();
	$tmp = $rs->fetch();
	
	$rownum = $tmp['count'];
	
	if(isset($_GET['page'])) {
		$page = $_GET['page'];  
	} else {
		$page = 1;                                 
	}
	
	$pageurl  = '';
	$pagenum  = (int) ceil($rownum / $pagesize);  
	$prepage  = $page - 1;                        
	$nextpage = $page + 1;

	if($page == 1) {                         
		$pageurl .= " ".$lang->line("index.home")." | ".$lang->line("index.previous_page")." | ";
	} else {
		$pageurl .= "<a href='?page=1&cname={$cname}&id_card={$id_card}&room_strings={$room_num}&start_date={$start_date}&end_date={$end_date}&search={$search}'>".$lang->line("index.home").
					"</a> | <a href='?page={$prepage}&cname={$cname}&id_card={$id_card}&room_strings={$room_num}&start_date={$start_date}&end_date={$end_date}&search={$search}'>".$lang->line("index.previous_page")."</a> | ";
	}

	if($page == $pagenum || $pagenum == 0) {     
		$pageurl .= " ".$lang->line("index.next_page")." | ".$lang->line("index.last_page")." ";
	} else {
		$pageurl .= "<a href='?page={$nextpage}&cname={$cname}&id_card={$id_card}&room_strings={$room_num}&start_date={$start_date}&end_date={$end_date}&search={$search}'>".$lang->line("index.next_page").
					"</a> | <a href='?page={$pagenum}&cname={$cname}&id_card={$id_card}&room_strings={$room_num}&start_date={$start_date}&end_date={$end_date}&search={$search}'>".$lang->line("index.last_page")."</a>";
	}
	
	$sql = "
		SELECT e.*, m.username, m.cname, r.`name`, r.name, m.berth_number ,m.id_card 
		FROM `ezcard_record` e
		LEFT JOIN member m ON e.member_id = m.id
		LEFT JOIN room r ON r.id = e.room_id
		WHERE 1 ".$sql_kw." AND r.Title = '研習室' ORDER BY e.add_date DESC LIMIT ".($page-1)* $pagesize . ",".$pagesize;
	$rs  = $PDOLink->prepare($sql);
	$rs->execute();
	$data = $rs->fetchAll();
	
	$sort_map = array("Stored" => "付款", "Refund" => "退費");
?>

<section id="main" class="wrapper">
	<div class='col-12 btn-back'><a href='studysearch.php' ><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>

	<div class="rwd-box"></div><br><br>
	<div class="container" style="text-align: center;">
		<h1 class="jumbotron-heading text-center">付款紀錄查詢</h1>
    </div>
	
	<div class="row container-fluid mar-bot50 mar-center2">
		<?php if($_GET['error'] == 1){ ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>沒有可匯出的資料！！</strong>
			</div>
		<?php } elseif ($_GET['error'] == 2) { ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>Error Error！！</strong>
			</div>
		<?php } elseif ($_GET['success']) { ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-success col-lg-9" role="alert">
			<strong>Success 成功設置！！</strong>
			</div>
		<?php } ?>
	</div>
	<!-- SEARCH -->
	<div class="inner">
		<div class="row">
				<!--div class="alert alert-orange col-12">
					<p>【製作說明】</p>
					<p>1.目的：查詢研習室的儲值付款紀錄</p>
					<p>2.協助檢查前端串資料庫後端PHP寫法、功能有無問題，有請協助修正</p>
					<p>3.查詢條件、表格欄位：卡號改成學號</p>
				</div>-->
				<form id='mform1' action="" method="get" class='col-12'>
					<div class='col-12'>
						<section class='panel panel-noshadow'>
	             			<div class='panel-body'>
							 
							 <div class='form-group row '>
							 <label for='exampleFormControlInput1' class='col-sm-2 col-form-label label-right'><?php echo $lang->line("index.room_number")?></label>
							 <div class='col-sm-9'> 
								 <input  type='text' class='form-control' name='room_strings' placeholder='全部' value='<?php echo $room_num ?>'>
							 </div>
						 	</div>



							 <div class='form-group row '>
							 <label for='exampleFormControlInput1' class='col-sm-2 col-form-label label-right'>姓名</label>
							 <div class='col-sm-9'>
								   <input   type='text'  class='form-control' name='cname' placeholder='全部' value='<?php echo $cname ?>'>  
							 </div>
							 </div>
							 							 

							 <div class='form-group row '>
							 <label for='exampleFormControlInput1'  class='col-sm-2 col-form-label label-right'>卡號</label>
							 <div class='col-sm-9'> 
								 <input  type='text' maxlength='10' class='form-control  col' name='id_card' placeholder='全部' value='<?php echo $id_card ?>'>  
							 </div>
							 </div>
							 
						 	<div class='form-group row'>
							 <label for='exampleFormControlInput1' class='col-sm-2 col-form-label label-right'>開始時間</label>
							 <div class='col-sm-9'> 
								 <input  type='date' class='form-control date-pd' name='start_date' value='<?php echo $start_date ?>'>
							 </div>
							</div>
							 

							<div class='form-group row'>
							 <label for='exampleFormControlInput1' class='col-sm-2 col-form-label label-right'>結束時間</label>
							 <div class='col-sm-9'> 
								 <input  type='date'  class='form-control date-pd' name='end_date' value='<?php echo $end_date ?>'>
							 </div>
							</div>
 
							<br><br>
							<input type='hidden' name='search' value='1'>
							<button type='button' onclick='export_list()' class='btn  btn-loginfont btn-primary2  col-4 offset-4'><?php echo $lang->line("index.confirm_query") ?></button>
	             			</div>
	             		</section>
	             	</div>
				</form>
		</div>
	</div>
	<!-- SEARCH END-->

	<!--表格 -->
	<div class='inner' style="display:<?php echo ($search != '') ? 'block' : 'none'?>">
		<div class="col-12">
					<h1 class="jumbotron-heading text-center h1-mar"><?php echo ($search != '') ? $lang->line("serach_results") : "" ?></h1>
						<div class=" text-right ">
							<button type="button" onclick='export_file()' class="btn btn-loginfont btn-primary2 col-sm-2 offset-sm-10 mb-3"><?php echo $lang->line("index.export") ?></button>
						</div>
						<br>
						<div class="table-responsive">
							<table class="table  text-center font-weight-bold">
							<thead class="thead-green">
								<tr class="text-center">
								<th scope="col">#</th>
								<th scope="col">付款日期</th>
								<th scope="col"><?php echo $lang->line("index.room_number")?></th>
								<th scope="col">卡號/姓名</th>
								<th scope="col">付款金額</th> 
								<th scope="col">備註</th>

								</tr>
	
							</thead>
							<tbody>
								<?php 
									foreach($data as $row) 
									{
										$row_count = ($prepage * $pagesize) + ++$j;
										$showtime  = date($date_format.' '.$time_format, strtotime($row["add_date"]));
										$showname  = $row['id_card'];
										$showname .= " / ";
										$showname .= $row['cname'];
										$showfee   = $row["PayValue"];
										$showsort  = $sort_map[$row["Sort"]];
										$room_num  = $row["name"];
								?>
									<tr>
										<td scope='row'><?php echo $row_count ?></td>
										<td scope='row'><?php echo $showtime  ?></td>
										<td scope='row'><?php echo $room_num  ?></td>
										<td scope='row'><?php echo $showname  ?></td>
										<td scope='row'><?php echo $showfee   ?></td>
										<td scope='row'><?php echo $showsort  ?></td>
									</tr>
								<?php
									}
								?>
							</tbody>
							</table>
						</div>

							<!-- 跳頁 上下頁-->
							<div class="row ">
								<div class="container-fluid">
									<div class="text-center" id="dataTable_paginate">
									<?php  
									if($rownum > $pagesize) {
										echo $pageurl;
										echo " ".$lang->line("index.current_page")." $page | ".$lang->line("index.a_few_pages")." $pagenum ".$lang->line("index.page")."";
									}
									?> 
									</div>
								</div>
							</div>
							<!-- 跳頁 上下頁 END -->
		</div>
	</div>
	<!--表格 END-->

</section>


<script>

function export_list() {
	
	$('#mform1').prop('action', 'study-stored.php');
	$('#mform1').prop('method', 'get');
	
	$('#mform1').submit();
}

function export_file() {

	$('#mform1').prop('action', 'model/study_stored_download.php');
	$('#mform1').prop('method', 'get');
	
	$('#mform1').submit();
}

</script>

<?php include('footer_layout.php'); ?>