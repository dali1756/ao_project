<?php
class DBConnection extends PDO
{
	public function __construct()
    {
		$db_host      	 = 'localhost';
		$db_name    	 = 'ndhu_db_new';
		$db_user    	 = 'barry';
		$db_pass		 = '1qaz!@#$W';
	    parent::__construct("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
	    $this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // always disable emulated prepared statement when using the MySQL driver
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}
