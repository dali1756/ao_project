<?php

		include("adodb5/adodb.inc.php");
		$conn = ADONewConnection('mysql'); 
		$conn->debug = true;
		$conn->Connect("localhost", "barry", "1qaz!@#$W", "ndhu_db_new");
		$conn->query("SET NAMES 'utf8'");
		if (!$conn) { echo "無法連接資料庫 db1"; exit; }