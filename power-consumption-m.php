<?php 
	ob_start();
	include('header_layout.php');
	include('nav.php');
	// include('chk_log_in.php');  
	ini_set('max_execution_time', 0); // 最大連線逾時時間 (在php安全模式下無法使用)	 
	set_time_limit(0);
	// ini_set("memory_limit","2048M");	
	
	$pagesize = 10;
	$sql_kw   = "";
	$now_time = date('Y-m-d H:i:s');
	$search   = $_GET['search'];
	$room_total_ele = 0;
	$room_ele_110 = 0;
	$room_ele_220 = 0;

	$dong_html = "";
	$sql = "SELECT * FROM dongname ORDER BY id";
	$dong_data = func::excSQL($sql, $PDOLink, true);
	$dong = $_GET['m_dong'];
	$year_st = $_GET['m_year_st'];
	$year_end = $_GET['m_year_st'];
	$month_st = $_GET['m_month_st'];

	if($_GET['search']){
		$dong_name = "";
		if(count($dong) > 0) {
			$$dong_html = "";			
			foreach($dong_data as $v) {
				$selected = "";
				for($i=0;$i<count($dong[$i]);$i++) {
					$selected = ($dong[$i] == $v['dong']) ? 'selected':'';
					if($selected == 'selected') {
						$dong_name = $v['dong_name'];
						break;
					}
				}
				$dong_html .= "<option value='".$v['dong']."'} {$selected}>{$v['dong_name']}</option>";
			}
		}
		// $month_end = $_GET['m_month_end'];
		$inner_html = "";
		$now_year = date('Y');
	
		# 結數月取隔月一號的第一筆資料
		$st_time = $year_st.'-'.$month_st.'-01 00:00:00';
		// $end_time = $year_end.'-'.$month_end.'-01 00:00:00';
		
		if(!$dong || !$year_st  || !$month_st )  die(header('Location: power-consumption-m.php?error=3'));  
		// if((date($st_time) > date($end_time)) || ( $year_end < $year_st) ) die(header('Location: power-consumption.php?error=4')); 
	
		$month_end = date( "m", strtotime( $st_time." +1 month" )); 
		if($month_st == '12')// 如果12月 ,結束年月為隔年1月的第一筆資料
		{
			$year_end = $year_st + 1;
		}

		// echo  date( "Y-m-d", strtotime( $end_time." +1 month" ) ); 
		// print '<br>'.$month_end.'<br>'.$year_end;
		// exit;
		// echo $year_st.$year_end.$month_st .$month_end; 
		if(isset($_GET['m_year_st']) && isset($_GET['m_month_st']) ) {
			$param_st = array(
			":year_st"=> $year_st,
			":month_st"=> $month_st."-01 00:00:00",
			":year_end"=> $year_end,
			":month_end1"=> $month_end."-01 00:00:59",
			":year_st_220"=> $year_st_220,
			":month_st_220"=> $month_st_220."-01 00:00:00",
			":year_end_220"=> $year_end_220,
			":month_end1_220"=> $month_end_220."-01 00:00:59",
			":dong" => $dong
			);
			$sql = "select (select name from room where id = a.room_id) as name,
			a.amonut as start_amonut,
			b.amonut as end_amonut,
			ROUND((b.amonut-a.amonut),2) as use_amonut,
			a.amonut_220 as start_amonut_220,
			b.amonut_220 as end_amonut_220,
			ROUND((b.amonut_220 - a.amonut_220), 2) as use_amonut_220,
			DATE_FORMAT(a.update_date,'%Y-%m-%d %H:%i:%s') as start_time,
			DATE_FORMAT(b.update_date,'%Y-%m-%d %H:%i:%s') as end_time, 
			DATE_FORMAT(a.update_date,'%Y-%m-%d %H:%i:%s') as start_time_220,
			DATE_FORMAT(b.update_date,'%Y-%m-%d %H:%i:%s') as end_time_220
			from  room_amonut_log as a
			INNER JOIN (
				select room_id,max(b.amonut) as amonut, max(b.amonut_220) as amonut_220,max(b.update_date) as update_date 
				from  room_amonut_log as b 
				where b.update_date >= '".$param_st[':year_st']."-".$param_st[':month_st']."' and b.update_date <=  '".$param_st[':year_end']."-".$param_st[':month_end1']."' 
				group by b.room_id) as b on a.room_id = b.room_id
			where a.update_date >= '".$param_st[':year_st']."-".$param_st[':month_st']."' and a.update_date <= '".$param_st[':year_end']."-".$param_st[':month_end1']."' and a.room_id in (SELECT id FROM room where dong = '".$param_st[':dong']."') group by a.room_id order by a.room_id ";  
			
			$room_data = func::excSQL($sql, $PDOLink, true);
			// echo $sql ;
			if(count($room_data) > 0 )
			{
				$room_total_ele = 0;
				foreach($room_data as $r)
				{ 
					$room_ele = $r['use_amonut']; // 單間房的總度數
					$room_ele_220 = $r["use_amonut_220"];
					// echo "房間 : {$r['name']} 使用度數 : {$room_ele} <br>";

					$room_total_ele += $room_ele; // 整棟加總
					$room_total_ele_220 += $room_ele_220; // 220加總
					$room_total_ele_110 = $room_total_ele - $room_total_ele_220; // 110加總

					$update_st = $r['start_time'];
					$update_ed = $r['end_time'];
					$update_st_220 = $r['start_time_220'];
					$update_ed_220 = $r['end_time_220'];
					// print_r($update_st);//印出陣列

					$inner_html .=" 
					<div class='col-lg-4 card-group'>
						<div class='card mb-4 card-green text-green fz-18 h-auto'>
							<div class='py-2 nowsystem'>
								<ul class='px-1'>
									<li >房號：<span id='dong'>{$r['name']}</span></li>
									<li >開始時間：<span id ='st_time'>{$update_st}</span></li>
									<li >結束時間：<span id ='ed_time'>{$update_ed}</span> </li>
									<li class='total-meter' >用電總計(110V):{$room_ele} 度</li>
									<!--新増220V使用度數資料 -->
									<li >開始時間：<span id ='st_time'>{$update_st_220}</span></li>
									<li >結束時間：<span id ='ed_time'>{$update_ed_220}</span> </li>
									<li class='total-meter'>用電總計(220V):{$room_ele_220} 度</li>
								</ul>
							</div>
						</div>
					</div>	 
					";
				}  	
			// print '總計'.$room_total_ele.'<br>';
			// print $update_st .'<br>';
			// print $update_ed .'<br>';
			}else{
				die(header("Location: power-consumption-m.php?error=5&m_dong={$_GET['m_dong']}&m_year_st={$_GET['m_year_st']}&m_month_st={$_GET['m_month_st']}"));  
			}

		}else{
			die(header("Location: power-consumption.php-m?error=3&m_dong={$_GET['m_dong']}&m_year_st={$_GET['m_year_st']}&m_month_st={$_GET['m_month_st']}"));  
		}
	} else {
		if($dong_data && is_array($dong_data)) {
			foreach($dong_data as $v) {
				$selected = "";
				for($i=0;$i<count($dong[$i]);$i++) {
					$selected = ($dong[$i] == $v['dong']) ? 'selected':'';
					if($selected == 'selected') {
						$dong_name = $v['dong_name'];
						break;
					}
				}
				$dong_html .= "<option value='".$v['dong']."'} {$selected}>{$v['dong_name']}</option>";
			}
		}				
	}

?>  
 <section id="main" class="wrapper">
	<div class='col-12 btn-back'><a href='powersearch.php' ><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>

	<div class="rwd-box"></div><br><br>
	<div class="container" style="text-align: center;">
		<h1 class="jumbotron-heading text-center">用電查詢</h1>
    </div>

	<div class="row mar-center2 mb-4" style="margin: 0 auto;">
		<?php if($_GET['error'] == 1){ ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>開始月份無資料</strong>
			</div>
		<?php } elseif ($_GET['error'] == 2) { ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>結束月份無資料</strong>
			</div>			
		<?php } elseif ($_GET['error'] == 3) { ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>請勿空白</strong>
			</div>
		<?php } elseif ($_GET['error'] == 4) { ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>年/月份錯誤</strong>
			</div>
		<?php } elseif ($_GET['error'] == 5) { ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>查無資料</strong>
			</div>	
		<?php } elseif ($_GET['error'] == 6) { ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>無資料匯出</strong>
			</div>			
		<?php } elseif ($_GET['success']) { ?>
			<div style="margin: 0 auto; text-align: center;" class="alert alert-success col-lg-9" role="alert">
			<strong>Success 成功設置！！</strong>
			</div>
		<?php } ?>
	</div>
	<!--標籤切換頁-->
	<div class="container">
		<ul class='nav nav-tabs nav-border'>
			<li class='nav-item'> 
				<!-- <a class='nav-link card card-border' data-toggle='tab' id='tab_day' href='#day' role='tab' aria-selected='false'> -->
				<a class='nav-link card card-border' id='tab_day' href='power-consumption-d.php' role='tab' aria-selected='false'>
					<span class='h5 mb-0 font-weight-bold '>每日用電</span>
				</a>    
			</li>
			<li class='nav-item'>
				<!-- <a class='nav-link card card-border active' data-toggle='tab' id='tab_month' href='#month' role='tab' aria-selected='false'> -->
				<a class='nav-link card card-border active' id='tab_month' href='power-consumption-m.php' role='button' aria-selected='false'>
					<span class='h5 mb-0 font-weight-bold'>每月用電</span>
				</a>           
			</li>
			<li class='nav-item'> 
				<!-- <a class='nav-link card card-border' data-toggle='tab' id='tab_year' href='#year' role='tab' aria-selected='false'> -->
				<a class='nav-link card card-border' id='tab_year' href='power-consumption-y.php' role='button' aria-selected='false'>
					<span class='h5 mb-0 font-weight-bold '>每年用電</span>
				</a>    
			</li>
		</ul>
		<hr class='view'>
	</div>
	<!--標籤切換頁 END-->

	<!-- 查詢&顯示結果 -->
	<div class="container">
			<form id='mform2' method="get" class='col-12'>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label label-right">棟別</label>	
						<div class="col-sm-9 form-inline">
							<select class="col form-control selectpicker show-tick" title='請選擇'  size="1" name="m_dong"  >
								<?php echo $dong_html;?>
							</select>
						</div>
					</div>
					<div class="form-group row select-mar4">
						<label class="col-sm-2 col-form-label label-right btn-martop20">年份</label>	
						<div class="col-sm-9 form-inline ">
							<select class="col form-control selectpicker show-tick" title='請選擇'  size="1" id="m_year_st" name="m_year_st" > 
								<?php func::yearOption($PDOLink, false, $year_st) ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label label-right btn-martop20">月份</label>	
						<div class="col-sm-9 form-inline">
							<select class="col form-control selectpicker show-tick" title='請選擇'  size="1" name="m_month_st" data-size="5">
							
							<?php
								$month_arr = array('01','02','03','04','05','06','07','08','09','10','11','12');
								foreach($month_arr as $v)
								{
									$selected = ($_GET['m_month_st']==$v)?'selected':'';
									print "<option value={$v} {$selected}>{$v}</option>";
								}
							?>
							</select>
						</div>
					</div>	
					<br>
					<input type='hidden' name='search' value='1'>
					<input type='hidden' id='room_total_ele' name='room_total_ele'  value='<?php echo $room_total_ele ?>'>
					<input type='hidden' id='room_total_ele_110' name='room_total_ele_110'  value='<?php echo $room_total_ele_110 ?>'>
					<input type='hidden' id='room_total_ele_220' name='room_total_ele_220'  value='<?php echo $room_total_ele_220 ?>'>
					<input type='hidden' id='dong_name' name='dong_name'  value='<?php echo $dong_name ?>'>
					<div class='col-12'>
						<button type='submit' id="search-btn" class='btn  btn-loginfont btn-primary2  col-4 offset-4'><?php echo $lang->line("index.confirm_query") ?></button>
					</div>
			</form>
			<?php 
				if($_GET['search'] == 1)
				{
					print 	"<div class='col-12 mt-4'>";
				}else{
					print 	"<div class='col-12 mt-4' style='display:none'>";
				}
			?>
	
			<h1 class="jumbotron-heading text-center h1-mar">查詢結果</h1>
			<div class=" text-right ">
				<button type="button" class="btn btn-loginfont btn-primary2 col-sm-2 offset-sm-10 mb-3" onclick='export2excel1()'><?php echo $lang->line("index.export") ?></button>
			</div>
			<h5 class="text-gray-900 font-weight-bold">更新時間:<?php echo $now_time ?></h5>
			<div id="power-total" class="col-12 alert alert-info text-green">
				<?php echo $dong_name?>總計用電：<?php echo $room_total_ele?><br>
				<!--新増使用度數資料 -->
				<?php echo $dong_name?>小計用電(110V)：<?php echo $room_total_ele_110 ?><br>
				<?php echo $dong_name?>小計用電(220V)：<?php echo $room_total_ele_220 ?><br>
			</div>
			<div class="row">
				<?php echo $inner_html; ?>
			</div>
	</div>
	<!-- 查詢&顯示結果 END-->

</section>

<script>
	$('#search-btn').click(function() {
			$('body').loading({
		stoppable: false,
		message: '資料較龐大加總計算中，請耐心等候勿關閉本頁...',
		theme: 'dark'
		}); 
	});

	function export2excel1() 
	{	
		var dong=$('[name="m_dong"]').val();
		var year_st=$('[name="m_year_st"]').val();
		var month_st=$('[name="m_month_st"]').val();
		//var total=$('#power-total').text();
		var dong_name=$('#dong_name').val();
		var total=$('#room_total_ele').val();
		var total_1=$('#room_total_ele_110').val();
		var total_2=$('#room_total_ele_220').val();
		location.replace("model/power-consumption-m_report.php?dong="+dong+"&year_st="+year_st+"&month_st="+month_st+"+&dong_name="+dong_name+"&total="+total+"&total_1="+total_1+"&total_2="+total_2);
	}
</script>

<?php include('footer_layout.php'); ?>