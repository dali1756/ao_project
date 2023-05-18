<?php
require('db_config.php');	
require('pdodb_connection.php');	

$pdo_service = new PdoService();
$data = $pdo_service->query("SELECT * FROM member limit 10", 'All' ,false ,$pdo_service );
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
print_r(get_all($pdo_service));
echo "<br/>";
$table = "member";
$fields = getFields($pdo_service,$table);
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