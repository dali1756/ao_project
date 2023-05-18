<?php
require('db_config.php');	
require('php_pdo.php');	

$pdo_service = new PdoService();
	
$dbh = $pdo_service->PdoNewConnect($db_host, $db_name, $db_user, $db_pass);
//$dbh = $pdo_service->PDOConnect($host, $dbname, $user, $pass);

$data = $pdo_service->query("SELECT * FROM member limit 10", 'All' ,false, $dbh );
print_r( $data );
echo "<br/>";

function get_all($dbh) {
    $sql  = "SELECT * FROM member";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt;
}
 function getFields($dbh,$table)
    {
        $fields = array();
        $recordset = $dbh->query("SHOW COLUMNS FROM $table");
    //    $this->getPDOError();
        $recordset->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $recordset->fetchAll();
        foreach ($result as $rows) {
            $fields[] = $rows['Field'];
        }
        return $fields;
    }
echo "<br/>";
print_r(get_all($dbh));
echo "<br/>";
$table = "member";
$fields = getFields($dbh,$table);
print_r( $fields );
echo "<br/>";
/*
		$fields = array();
        $recordset = $dbh->query("SHOW COLUMNS FROM $table");
    //    $this->getPDOError();
        $recordset->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $recordset->fetchAll();
        foreach ($result as $rows) {
            $fields[] = $rows['Field'];
        }
		print_r( $fields );
*/
/*
<?php

	$host       = 'localhost';
	$dbname     = 'ntunhs_db_new';
	$user     	= 'barry';
	$pass		= '1qaz!@#$W';
	try { 
		$dbHandle = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		$dbHandle->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// always disable emulated prepared statement when using the MySQL driver
		$dbHandle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	} catch(PDOException $e) {
		print "連線失敗" . $e->getMessage(); 
	}
	
function get_all($dbHandle) {
    $sql = "SELECT * FROM member";
    $stmt = $dbHandle->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    return $stmt;
}

get_all($dbHandle);
//echo get_all($dbHandle);
echo "<br/>";
print_r(get_all($dbHandle));
*/