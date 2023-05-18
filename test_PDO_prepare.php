<?php
ini_set('display_errors', 1);

error_reporting(E_ALL ^ E_NOTICE);

error_reporting(E_ALL ^ E_WARNING);

/************************ YOUR DATABASE CONNECTION START HERE   ****************************/
		$DB_Server = "localhost"; // MySQL Server
		$DB_Username = "barry"; // MySQL Username
	//	$DB_Password = "su631811";
		$DB_Password = '1qaz!@#$W'; // MySQL Password
		$DB_DBName = "rapidminer_server"; // MySQL Database Name
		 
		/***** DO NOT EDIT BELOW LINES *****/
		// Create MySQL connection
		$Connect = mysqli_connect($DB_Server, $DB_Username, $DB_Password) or die("Failed to connect to MySQL:<br />" . mysqli_error() . "<br />" . mysqli_errno());
		mysqli_query("SET NAMES 'big5'");
		// Select database
	//	$Db = mysqli_select_db($DB_DBName, $Connect) or die("Failed to select database:<br />" . mysqli_error(). "<br />" . mysqli_errno());
		$Db = mysqli_select_db($Connect, $DB_DBName) or die("Failed to select database:<br />" . mysqli_error(). "<br />" . mysqli_errno());

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$link = mysqli_connect("localhost", "barry", '1qaz!@#$W', "rapidminer_server");

/* get the name of the current default database */
$result = mysqli_query($link, "SELECT DATABASE()");
$row = mysqli_fetch_row($result);
printf("Default database is %s.\n", $row[0]);
echo "<br/>";
/* change default database to "bulletinboard" */
mysqli_select_db($link, "bulletinboard");

/* get the name of the current default database */
$result = mysqli_query($link, "SELECT DATABASE()");
$row = mysqli_fetch_row($result);
printf("Default database is %s.\n", $row[0]);

/* change default database to "rapidminer_server" */
mysqli_select_db($link, "rapidminer_server");

/* get the name of the current default database */
$result = mysqli_query($link, "SELECT DATABASE()");
$row = mysqli_fetch_row($result);
printf("Default database is %s.\n", $row[0]);

echo "<br/>";
/*你还可以进行一次搜索操作
    foreach ($dbh->query('SELECT * from FOO') as $row) {
        print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
    }
    */
/* Execute a prepared statement by passing an array of values */
$dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=rapidminer_server;charset=utf8mb4', 
        'barry', 
        '1qaz!@#$W'
    );
	foreach ($dbh->query('SELECT * from FOO') as $row) {
        print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
		echo($GLOBAL);
    }
$sql = 'SELECT name, colour, calories
    FROM fruit
    WHERE calories < :calories AND colour = :colour';
$sth = $dbh->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
$sth->execute(['calories' => 150, 'colour' => 'red']);
$red = $sth->fetchAll();
var_dump($red);
echo "<br/>";
/* Array keys can be prefixed with colons ":" too (optional) */
$sth->execute([':calories' => 175, ':colour' => 'yellow']);
$yellow = $sth->fetchAll();
var_dump($yellow);
?>