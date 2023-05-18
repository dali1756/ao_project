<?php
	// 增加 ao 管理員 -- 20200829 
	if(!in_array($_SESSION['admin_user']['username'], array(WEBADMIN, 'barry', 'jack'))) {
		
		unset($_SESSION['user']);		
		unset($_SESSION['admin_user']);
		unset($_SESSION['MENU_ACCESS']);
		
		header("location: session.php"); // 先保留 -- warning headers already sent
		echo "<script>location.replace('session.php')</script>";
	}
?>