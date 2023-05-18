<?php
/** PDO連線資料庫的通用
 * @version 0.0.3 2020/07/16 新增init(PDO)使$db可以選擇直接外部帶入
 * @version 0.0.2 加入資料庫DB操作 查詢、新增、刪除，連線直接使用全域$db，或許未來可以改名為PdoHelper或DbHelper
 * @version 0.0.1 新增BindValueWithDataTypes，方便PDO插入含有null的參數
 */
class PdoService
{
	private static $db = null;
	/** 設定PDO */
	public static function init(PDO $pdo){
		self::$db = $pdo;
	}
	public static function isInit(){
		return self::$db != null;
	}
	/** 建立PDO */
	public static function PdoNewConnect($HOST='',$DBNAME='',$USER='',$PASSWD='')
	{
			global $host,$db_name,$name,$pass;
			if(empty($HOST) && empty($DBNAME) && empty($USER) && empty($PASSWD)){
				$HOST = $host;
				$DBNAME = $db_name;
				$USER = $name;
				$PASSWD = $pass;
			}

			$db = new PDO("mysql:dbname=$DBNAME;host=$HOST;", $USER, $PASSWD,
				array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
			$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // 增加安全性與速度
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // 方便測試 ERRMODE_EXCEPTION 
			return $db;
	}
	/** 建立/取得PDO */
	public static function PDOConnect($HOST='', $DBNAME='', $USER='', $PASSWD=''){
			global $db; // PDO
			self::$db = $db ?: self::PdoNewConnect($HOST, $DBNAME, $USER, $PASSWD);
			return self::$db;
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
	//	$db = self::PdoNewConnect(); // 連線
		
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

	public static function SqlQuery($sql, $params = [], $PDO_FETCH = PDO::FETCH_OBJ){
		$db = self::PDOConnect(); // 連線
		$query = $db->prepare($sql);
		self::BindValueWithDataTypes($query,$params);    // 設定參數
		$query->execute();  // 執行
		$data = $query->fetchAll($PDO_FETCH) ?: array(); // 撈取全部內容
		return $data;
	}
	/** PDO插入含有null的參數 
	 * 格式1 $param=[ ['name'=>':pid','value'=>null], ... ];
	 * 格式2 $param=[ ':key'=>'value',':key'=>null, ... ];
	 */
	public static function BindValueWithDataTypes(PDOStatement $sth, array $params)
	{
			//可能格式 $param=[ ['name'=>':pid','value'=>null], ... ]
			//可能格式 $param=[ ':key'=>'value','key'=>null, ... ]

			foreach ($params as $key => $param) {
				//格式判斷
				//格式 $param=[ ['name'=>':pid','value'=>null], ... ]
				if (is_array($param)) {
					if (!is_null($param['value']))
						$sth->bindValue($param['name'], $param['value']);
					else
						$sth->bindValue($param['name'], null, PDO::PARAM_NULL);
				} else {
					//格式 $param=[ 'key'=>'value','key'=>null, ... ]
					if (!is_null($param))
						$sth->bindValue($key, $param);
					else
						$sth->bindValue($key, null, PDO::PARAM_NULL);
				}
			}
	}
	public static function Insert($table,array $data){
		$db = self::PDOConnect();
		$sqlFields=[];
		$sqlValues=[];
		$sqlParams=[];
		foreach ($data as $key => $value) {
			if($key=='') continue;
			$sqlFields[]=$key;
			// 判斷該欄位值是一般欄位還是sql陳述句
			if(strtoupper(substr($value,0,7))!='(SELECT'){
				// 一般欄位
				$sqlValues[]=":$key";
				$sqlParams[":$key"]=$value;
			}else{
				// sql陳述句
				$sqlValues[]=$value;
			}
		}
		$sql='insert into '.$table.'('.join(',',$sqlFields).')values('.join(',',$sqlValues).')';
		$query = $db->prepare($sql);
		PdoService::BindValueWithDataTypes($query,$sqlParams);
		return $query->execute();
	}

	public static function Delete($table,array $where){
			$db = self::PDOConnect();
			$sqlValues=[];
			$sqlParams=[];

			foreach ($where as $key => $value) {

				if($key=='') continue;

				// 判斷該欄位值是一般欄位還是sql陳述句
				if(strtoupper(substr($value,0,7))!='(SELECT'){
					// 一般欄位
					$sqlValues[]="$key = :$key";
					$sqlParams[":$key"]=$value;
				}else{
					// sql陳述句
					$sqlValues[]=$value;
				}

			}

			$sql = "DELETE FROM $table ";
			if(count($sqlValues))
				$sql .=' where '.join(' and ',$sqlValues);
			$sth = $db->prepare($sql);
			return $sth->execute($sqlParams);
	}
	public static function Update($table,array $data, $where=[]){
			$db = self::PDOConnect();
			
			$sqlData=[];
			$sqlWhere=[];
			$sqlParams=[];

			foreach ($data as $key => $value) {
				if($key=='') continue;

				// 判斷該欄位值是一般欄位還是sql陳述句
				if(strtoupper(substr($value,0,7))!='(SELECT'){
					// 一般欄位
					$sqlData[]="`$key`=:$key";
					$sqlParams[":$key"]=$value;
				}else{
					// sql陳述句
					$sqlData[]="`$key`=$value";
				}
			}

			foreach ($where as $key => $value) {
				if($key=='') continue;

				// 判斷該欄位值是一般欄位還是sql陳述句
				// TODO:可改用正則改寫
				if (empty($value) && !empty($key)) {
					$sqlWhere[] = $key;
				} else if(is_array($value)) {
					$sqlWhere[] = "`$key`IN('" . join("','", $value) . "')";
				} else if(in_array(strtoupper(substr($value,0,1)), ['(', '<', '>', '=']) 
					|| strtoupper(substr($value,0,4)) =='LIKE '){
					// 一般欄位
					$sqlWhere[] = "`$key`=:$key";
					$sqlParams[":$key"] = $value;
				}else{
					// sql陳述句
					$sqlWhere[] = "`$key`=$value";
				}
			}

			$sql="UPDATE $table SET " . join(' , ',$sqlData) . (count($sqlWhere)>0 ? " WHERE ". join(' AND ',$sqlWhere) : '' ) ;
			$sth = $db->prepare($sql);
			return $sth->execute($sqlParams);
	}
}
?>