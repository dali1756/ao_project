<?php 
	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');
	
	if($user_sn) {
?>
<section id="main" class="wrapper">
	<div class="rwd-box"></div><br>
	<div class="rwd-box"></div><br>        
	<div class="inner">  
		<div class="row">
			<div class="col-12"> 
				<div class="card"> 
				  <div class="member_header card-header"><?php echo $lang->line("index.student_info"); ?></div>  
				  <ul class="list-group list-group-flush">    
				    <li class="list-group-item"><b> 
						<?php echo $lang->line("index.name_username"); ?>：</b><br><?php echo $_SESSION['user']['cname']; ?> / <?php echo $_SESSION['user']['id']; ?>
						</li>
							<li class="list-group-item"><b>	
								<?php echo $lang->line("index.dormitory_number"); ?>：</b>  
								<?php 
									if($_SESSION['user']['room_id'] == 'C000'){ 
										echo '非住宿生'; 
									} else {
										echo $_SESSION['user']['room_id']; 
									}           
								?>  
						</li>
				    <li class="list-group-item"><b>
						<?php echo $lang->line("index.system_balance"); ?>：</b>
						<?php
							if($user_sn){
								echo round($rs['balance'])."/ NTD"; 
							} elseif($public_user_sn) {
								echo round($rs3['balance'])."/ NTD";
							}
						?>
					 </li>	
				  </ul>
				</div><!-- card end -->
			</div><!-- col-12 end --> 
		</div>  
    </div>
    <!-- 圖示智慧操作系統 -->
    <section class="ao-main">
        <div class="ao-container">  
            <div class="ao-box-main">
                <div class="ao-box"><a href="MemberEZCardRecordPublicList.php?betton_color=primary"><img src="images/Stored_icon.png" alt=""><h5><?php echo $lang->line("index.stored_value_query"); ?></h5></a></div>
                <div class="ao-box"><a href="MemberPowerRecordPublicList.php?betton_color=primary"><img src="images/Record_icon.png" alt=""><h5><?php echo $lang->line("index.electricity_bill_query"); ?></h5></a></div>
                <div class="ao-box"><a href="change_passowrd.php"><img src="images/Password_icon.png" alt=""><h5><?php echo $lang->line("index.password_change"); ?></h5></a></div>
                <div class="ao-box-after-box"></div>
            </div>
        </div>   
    </section> 
	<!-- End 圖示智慧操作系統 -->
	<div class="rwd-box"></div><br>
	<div class="rwd-box"></div><br>     
	<div class="rwd-box"></div><br>     
</section>
<?php } elseif($admin_id) { ?>
<section id="main" class="wrapper">
	<div class='col-12 btn-back'><a href='new-member2.php' ><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>

	<div class="rwd-box"></div><br>
	<div class="container" style="text-align: center;">
	<h1 class="jumbotron-heading">宿舍名單群組管理</h1>
	</div>
	<div class="inner">       
		<div class="row"> 
			<!--管理資訊 TABLE
			<div class="col-12">
				<div class="card">
				  <div class="member_header card-header">            
				  <?php if($_SESSION['admin_user']['id'] == 'andy'){ ?>
 					<?php echo '超級管理員'; ?>           
				  <?php } else { ?> <?php echo $lang->line("index.instructor_info"); ?> <?php } ?>
				  </div>
				  <ul class="list-group list-group-flush">    
				    <li class="list-group-item">                    
				    	<?php echo $lang->line("index.instructor_account"); ?>：<?php echo $_SESSION['admin_user']['id']; ?>
				    	<a href="admin_edit.php?id=<?php echo $_SESSION['admin_user']['sn']; ?>"><i class="fas fa-edit"></i></a>
				    </li>
				    <li class="list-group-item">
				    	<?php echo $lang->line("index.instructor_name"); ?>：<?php echo $_SESSION['admin_user']['cname']; ?>
				    </li>                      
					<?php if($_SESSION['admin_user']['id'] == 'andy'){ ?>
				    <li class="list-group-item"> 
				    	<?php echo $lang->line("index.data_backup"); ?>
				    	<br><a href="dump_db.php?act=all">SQL ALL Download</a> 
				    </li>
				     <li class="list-group-item"> 
				    	<?php echo '清空更新資料'; ?>
				    	<br><a href="truncate_data.php">data_updates_changes</a>
				    </li>        
					<?php } ?>
				  </ul>
				</div>
			</div> 
			-->
			
			<?php
			// 名單管理 --
			if(check_access_icon(1)) {
			?>	
				<div class="col-lg-6 col-6 p-4 text-center ">
					<a  href="roomlist-manager.php">
						<img class="mb-3" src="img/05名單管理.png">
						<h4>名單管理</h4>
					</a>
				</div>				
			<?php
			} else {
			?>
				<div class="col-lg-6 col-6 p-4 text-center"> 
					<a  href="#"  onclick="return confirm('無權限！');">
						<img class="mb-3" src="img/05名單管理.png">
					<h4>名單管理</h4>
					</a>
				</div>			
			<?php
			}
			?>
			
			<?php
			// 名單群組管理 --
			if(check_access_icon(2)) {
			?>	
				<!--
				<div class="col-lg-6 col-6 p-4 text-center ">
					<a  href="roomgroup.php">
						<img class="mb-3" src="img/06名單群組管理.png">
						<h4>名單群組管理</h4>
					</a>
				</div>
				-->		
			<?php
			} else {
			?>
				<!--
				<div class="col-lg-6 col-6 p-4 text-center"> 
					<a  href="#" onclick="return confirm('無權限！');">
						<img class="mb-3" src="img/06名單群組管理.png">
						<h4>名單群組管理</h4>
					</a>
				</div>
				-->		
			<?php
			}
			?>

</section>
<?php } ?>

<?php include('footer_layout.php'); ?>