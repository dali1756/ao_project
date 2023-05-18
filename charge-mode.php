<?php 

	include('header_layout.php');
	include('nav.php');          
	// include('chk_log_in.php');   

	$room_id  = '';
	$mode     = '';
	$room_num = $_GET["room_numbers_kw"];
	
	if($room_num) {
		
		$sql  = "SELECT * FROM `room` WHERE `name` = '{$room_num}'";
		$rs   = $PDOLink->prepare($sql);
		$rs->execute();
		$tmp  = $rs->fetch();
		
		$room_id = $tmp['id'];
		$mode    = $tmp['mode'];
	}
	
	$sql = "SELECT * FROM `custom_variables` WHERE `custom_catgory` = 'mode'";
	$rs  = $PDOLink->prepare($sql);
	$rs->execute();
	$mode_arr = $rs->fetchAll();
?>

<section id="main" class="wrapper">
	<div class='col-12 btn-back'><a href='powersetting.php' ><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	<div class="rwd-box"></div><br><br>
	<div class="container" style="text-align: center;">
		<h1 class="jumbotron-heading text-center">收費模式設定</h1>
	</div>
 <!-- OLD建立新學生 修改學生
		<div class="container" style="text-align: center;">
          <p>
            <a href="newMember.php" class="btn btn-success my-2"><?php echo $lang->line("index.create_member");?></a>	 
           <a href="newPublicMember.php" class="btn btn-success my-2"><?php echo $lang->line("index.new_public_idcard"); ?></a> 
            <a href="MemberStudent.php?betton_color=primary" class="btn btn-info my-2"><?php echo $lang->line("index.edit_member");?></a>
          </p> 
    </div>
 -->
	<div class="row container-fluid mar-bot50 mar-center2">
		<?php if($_GET['success'] == 1){ ?>
			<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
			<strong><?php echo $lang->line("index.success_room_settings"); ?>!!</strong>
			</div>
		<?php } elseif($_GET['success'] == 2) { ?>
			<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
			<strong><?php echo $lang->line("index.success_room_settings_all"); ?>!!</strong>
			</div>
		<?php } elseif($_GET['error'] == 1) { ?> 
			<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
			<strong>Error</strong>資料輸入錯誤或不存在
			</div>	
		<?php } elseif($_GET['error'] == 2) { ?> 
			<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
			請選擇模式
			</div>				
		<?php } ?>
	</div>   


<!--收費模式設定-->
<div class="inner">   
	<div class="row justify-content-center">
		<!--收費設定 110V單間設定-->
		<div class="col-lg-6">
			<div class="card shadow mb-4">

                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-center">
						<?php echo $lang->line('110V_meter').'<br>'.
									$lang->line('rate_light_socket')
						?>
				  </h6>
                </div>
                <div class="card-body">
						<div class="form-group">
							<form id="myForm1" action="#" method="get">
								<label for="exampleInputEmail1" class="label-center col btn-marbot20">輸入房號</label>  
								<input type="search" name="room_numbers_kw" value="<?php echo $room_num ?>" class=" form-control  col-8 offset-2" placeholder="Ex. T201" required>
								<button class="btn  btn-h-auto text-white btnfont-30   btn-martop20 font-weight-bold  btn-primary2 col-6 offset-3">查詢</button> 
							</form>
						</div>

				 
						<div class="form-group  btn-martop30">
							<form id="myForm1" action="model/mode_upd.php" method="post">
								<input type='hidden' name='room_id'  value='<?php echo $room_id ?>'>
								<input type='hidden' name='room_num' value='<?php echo $room_num ?>'>
								<label for="exampleInputPassword1"  class="label-center col btn-marbot20">收費設定</label>
								<select class='form-control col-8 offset-2 input-lg2' size='1' name='mode'>
								<?php
									foreach($mode_arr as $v) {
										$opt_key = $v['custom_id'];
										$opt_val = $v['custom_var'];
										$select  = ($opt_key == $mode) ? 'selected' : '';
										echo "<option value='{$opt_key}' {$select}>{$opt_val}</option>";
									}
								?>
								</select>
								<button type="submit"  class="btn  btn-h-auto text-white btnfont-30  btn-martop20 font-weight-bold  btn-primary2 col-6 offset-3"
								onclick="return confirm('確認更新?')">確認更新
								</button> 
							</form>
						</div>
						<!--收費設定
								<div class="form-group">

									<label for="exampleInputEmail1"  class="label-center col">收費設定</label>
									<?php
									//	$sel_q="select * from var_list where var_type='收費設定' order by var_value desc";
									//	$sel_r= $PDOLink->Query($sel_q); 
									//	if($sel_r)
									//	{
									//		print "<select class='form-control col-8 offset-2' size='1' name='mode'>";
									//		while($rs=$sel_r->Fetch())
									//		{
									//				$v_name=$rs[var_name];
									//				$v_value=$rs[var_value1];
									//				print "<option value='".$v_value."'";if($mode==$v_value)print " selected "; print ">".$v_name."</option>";
									//		}
									//		print "</select>";
									//	}
									?>
								</div> 
								-->

                </div>
			</div>
		</div>
		<!--收費設定 110V單間設定 END-->

			<!--收費設定 220V單間設定-->
				<div class="col-lg-6">
			<div class="card shadow mb-4">

                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-center">
						<?php echo $lang->line('220V_meter').'<br>'.
									$lang->line('rate_air_conditioner')
						?>
				  </h6>
                </div>
                <div class="card-body">
						<div class="form-group">
							<form id="myForm1" action="#" method="get">
								<label for="exampleInputEmail1" class="label-center col btn-marbot20">輸入房號</label>  
								<input type="search" name="room_numbers_kw" value="<?php echo $room_num ?>" class=" form-control  col-8 offset-2" placeholder="Ex. T201" required>
								<button class="btn  btn-h-auto text-white btnfont-30   btn-martop20 font-weight-bold  btn-primary2 col-6 offset-3">查詢</button> 
							</form>
						</div>
						<div class="form-group  btn-martop30">
							<form id="myForm1" action="model/mode_upd.php" method="post">
								<input type='hidden' name='room_id'  value='<?php echo $room_id ?>'>
								<input type='hidden' name='room_num' value='<?php echo $room_num ?>'>
								<label for="exampleInputPassword1"  class="label-center col btn-marbot20">收費設定</label>
								<select class='form-control col-8 offset-2 input-lg2' size='1' name='mode'>
								<?php
									foreach($mode_arr as $v) {
										$opt_key = $v['custom_id'];
										$opt_val = $v['custom_var'];
										$select  = ($opt_key == $mode) ? 'selected' : '';
										echo "<option value='{$opt_key}' {$select}>{$opt_val}</option>";
									}
								?>
								</select>
								<button type="submit"  class="btn  btn-h-auto text-white btnfont-30  btn-martop20 font-weight-bold  btn-primary2 col-6 offset-3"
								onclick="return confirm('確認更新?')">確認更新
								</button> 
							</form>
						</div>
                </div>
			</div>
		</div>
		<!--收費設定 220V單間設定 END-->

		<!-- 全部房間設定_目前隱藏 -->
		<div class="col-lg-6 d-none">
			<div class="card shadow mb-4">

                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-center">全部房間設定</h6>
                </div>
                <div class="card-body">					
					<form id="myForm2" action="model/mode_all_upd.php" method="post">
						<div class="form-group">
							<label for="exampleInputPassword1"  class="label-center col btn-marbot20">收費設定</label>
							<select class='form-control col-8 offset-2 input-lg2' size='1' name='mode'>
							<option value=''>請選擇</option>
							<?php
								$sql = "SELECT * FROM `room` ";
								$tmp = func::excSQL($sql, $PDOLink, false);
								$all_mode = $tmp['mode'];
							
								foreach($mode_arr as $v) {
									$opt_key = $v['custom_id'];
									$opt_val = $v['custom_var'];
									// $select  = ($opt_key == $all_mode) ? 'selected' : '';
									echo "<option value='{$opt_key}' {$select}>{$opt_val}</option>";
								}	
							?>
							</select>
							<button type="submit"  class="btn  btn-h-auto text-white btnfont-30  btn-martop20 font-weight-bold  btn-primary2 col-6 offset-3" 
							onclick="return confirm('確認更新?')">確認更新
							</button> 
						</div>
				
						
					</form>
                </div>

			</div>
		</div>
		<!-- 全部房間設定_目前隱藏 END-->

	</div>
</div>


<!--NEW 費率設定 END-->


<!--OLD
		<div class="inner">     
			<div class="row">
					<div class="col-8">	
							 Search Form
							<form id="myForm" action='#' method='get' style="width: 40%; margin: 0 auto;">
									<div class="form-group">
										<label for="exampleInputEmail1">輸入房號</label>  
										<input style="width: 100%;" type="search" name="room_numbers_kw" value="<?php echo $room_numbers_kw; ?>" class="RoomSearch form-control" placeholder="Ex. A101">
										<button style="width: 100%;height: 30px;">查詢</button> 
									</div>
									 <button type="submit" class="btn btn-primary">搜尋</button> 
								</form>
									 End Search Form 
					</div>
					<div class="col-4">
							  <label for="exampleInputEmail1">全部房間設定</label>  
								<div class="form-check">
										<input type="checkbox" class="form-check-input userroomBoxChangeAll" name="status" value="update_all" id="exampleCheck1">
										<label class="form-check-label" for="exampleCheck1">請先勾選</label><br><br>

										<input type="radio" class="BoxPriceElecDegree form-check-input userroomBoxChangePriceElecDegree" name="status" value="update_price_elec_degree" id="exampleCheck2">
										<label class="BoxPriceElecDegree form-radio-label" for="exampleCheck2">更新費率</label>

										<input type="radio" class="BoxMode form-check-input userroomBoxChangeMode" name="status" value="update_mode" id="exampleCheck3">
										<label class="BoxMode form-radio-label" for="exampleCheck3">更新模式</label>
								</div>
							
					</div>
			</div>
		</div>
-->		
</section>
<script>

	$(document).ready(function(){
		
		var mode = '<?php echo $mode ?>';
		
		$('.btn-martop30').hide();
		
		if(mode != '') {
			$('.btn-martop30').show();
		}
		
	});
</script>
<?php include('footer_layout.php'); ?>