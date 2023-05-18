<!--客服中心、手機版的登入介面-->
<?php 

$message   = '';
$error_arr = array('1' => '帳號密碼錯誤，請再確認',
				   '2' => '帳號密碼錯誤，請再確認');

if(isset($_GET['error'])) {
	$message = $error_arr[$_GET['error']];
}

$rs = $PDOLink->query("SELECT contact FROM system_info WHERE id = '1' ");
$row = $rs->fetch();
$SchoolContact=$row['contact'];
define("SITE_KEY", "6LdfXCklAAAAACuqgdBBsz9lpfkgCZqd-335vyz7");
define("SECRET_KEY", "6LdfXCklAAAAAPdlEtagQRtvfbX560Gneg-w2GFo");
?>

	<section id='banner' class='col-lg-12 '>
		
		<!--管理員登入介面-->
		<div id='identity' class='col-lg-12' >
			<form id='adminlogin' action='model/chk_adminlogin.php' method='post' class='col-lg-12 '>
				<!--<div class='login form title mb-5'></div>-->
				<h1 class='h4 mb-4'>管理員登入</h1>
				<div class='login form'>
					<div class='div_column'>
						<span class='user col-lg-12'></span>
					</div>
					<div class='div_column' >帳號&nbsp;</div>
					<div class='div_column' style='width:300px;'>
						<input class='form-control' type='text' name='id' placeholder='帳號' id='example-text-input'>
					</div>
				</div>
				<div class='login form'>
					<div class='div_column'>
						<span class='lock col-lg-12'></span>
					</div>
					<div class='div_column'>密碼&nbsp;</div>
					<div class='div_column' style='width:300px;'>
						<input class='form-control' type='password' name='pwd' placeholder='密碼' id='example-search-input'>
					</div>
				</div>
				<div class='login form notice'><?php echo $message; ?></div>
				<div class='login form  col-lg-12'>
					<button id='btn_login' class='btn btn-loginfont btn-primary2 btn-user col-lg-12'>登入</button>
				</div>
		
			</form>
		</div>
		
		<!--學生登入介面-->
		<div id='identity2' class='col-lg-12 ' >
			<form id='adminlogin2' action='model/chk_adminlogin.php' method='post' class='col-lg-12 '>
				<!--<div class='login form title mb-5'></div>-->
				<h1 class='h4 mb-4'>學生登入</h1>
				
				<div class='login form'>
					<div class='div_column'>
						<span class='user col-lg-12'></span>
					</div>
					<div class='div_column' >帳號&nbsp;</div>
					<div class='div_column' style='width:300px;'>
						<input class='form-control' type='text' name='id' placeholder='帳號' id='example-text-input'>
					</div>
				</div>
				<div class='login form'>
					<div class='div_column'>
						<span class='lock col-lg-12'></span>
					</div>
					<div class='div_column'>密碼&nbsp;</div>
					<div class='div_column' style='width:300px;'>
						<input class='form-control' type='password' name='pwd' placeholder='密碼' id='example-search-input'>
					</div>
				</div>
				<div class='login form notice'><?php echo $message; ?></div>
				<!-- Google reCAPTCHA widget
				<div class="form-row">
					<div class="g-recaptcha"
						</div>
					<input type="hidden" id="captcha-response" name="captcha-response" />
				</div> -->
				<div class='login form  col-lg-12'>
					<button id='btn_login' class='btn btn-loginfont btn-primary2 btn-user col-lg-12'>登入</button>
				</div>
		
			</form>
		</div>

		<!-- Footer-->
		<?php include('footer_layout.php'); ?>
	</section>
	<!--系統公告
	<section>
		<div class='contain-fulid wrapper style1 special' >  
			<div id='two' class=' '>
				<h2 class='text-center'><?php echo $lang->line("index.system_title") ?></h2>
				<figure>
					<blockquote style='text-shadow: 0px 1px 0px #000;'><?php echo strip_tags($SchoolContact) ?></blockquote>
				</figure>
			</div>
		</div>
	</section>
	-->


<!--置換背景效果-->
<script>
	
	var counter = 0;
	var chk_msg = "<?php echo $message; ?>";
  
	if(chk_msg != '') {
		alert(chk_msg);
	}
  
	$('#btn_login').click(function() {
		$('#adminlogin').submit();
	});
		
	$(document).ready(function() {
		
		// 底圖 -- 20200227
		$('#banner').hide();

		$('.button.alt').click(function() {
			
			$('#identity2').hide();
			$('#main').hide();
			$('#footer').hide();
			$('#banner').show();
			
			if($('#identity').css('display') == 'block') {
				$('#banner').css("background-image", "url(img/bk2.jpg)");
			} else {
				$('#banner').css("background-image", "url(img/bk2.jpg)");
			}
		});
		
		$('.button.alt2').click(function() {
			
			$('#identity').hide();
			$('#main').hide();
			$('#footer').hide();
			$('#banner').show();

			$('#banner').css("background-image", "url(img/bk2.jpg)");
			if($('#identity2').css('display') == 'block') {
				$('#banner').css("background-image", "url(img/bk2.jpg)");
			} else {
				$('#banner').css("background-image", "url(img/bk2.jpg)");
			}
			
		});
	});
</script>

<script src="assets/js/jquery.scrolly.min.js"></script>
<script src="assets/js/skel.min.js"></script>
<script src="assets/js/util.js"></script>
<script src="assets/js/main.js"></script>
