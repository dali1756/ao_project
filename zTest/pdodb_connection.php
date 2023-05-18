<?php
class PdoService extends PDO
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
	/**
     * 防止克隆
     *
     */
    private function __clone() {}
    /**
     * getPDOError 捕获PDO错误信息
     */
    private function getPDOError()
    {
        if ($this->dbh->errorCode() != '00000') {
            $arrayError = $this->dbh->errorInfo();
            $this->outputError('pod error:'.json_encode($arrayError));
        }
    }
    /**
     * 输出错误信息
     *
     * @param String $strErrMsg
     */
    private function outputError($strErrMsg)
    {
        //throw new \Exception('MySQL Error: '.$strErrMsg);
      //  LogService::getLogger(['log_file' => $this->logFile])->error(' MySQL Error:' . $strErrMsg);

    }
    /**
     * Query 查询
     *
     * @param String $strSql SQL语句
     * @param String $queryMode 查询方式(All or Row)
     * @param Boolean $debug
     * @return Array
     */
    public function query($strSql, $queryMode = 'All', $debug = false,$db)
    {
        if ($debug === true) $this->debug($strSql);
	//	$db = self::__construct(); // 連線
		
        $recordset = $db->query($strSql);
    //    $this->getPDOError();
        if ($recordset) {
            $recordset->setFetchMode(\PDO::FETCH_ASSOC);
            if ($queryMode == 'All') {
                $result = $recordset->fetchAll();
            } elseif ($queryMode == 'Row') {
                $result = $recordset->fetch();
            }
        } else {
            $result = null;
        }
        return $result;
    }
    /**
     * getFields 获取指定数据表中的全部字段名
     *
     * @param String $table 表名
     * @return array
     */
    public function getFields($table)
    {
        $fields = array();
        $recordset = $this->dbh->query("SHOW COLUMNS FROM $table");
        $this->getPDOError();
        $recordset->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $recordset->fetchAll();
        foreach ($result as $rows) {
            $fields[] = $rows['Field'];
        }
        return $fields;
    }

}
