<?php
/**
 * memory cache strategy
 */
class Memorycache
{
	public static $table_name = 'atemporarise';
	public static $charset = 'utf8';
	public static $index_category = 'HASH';//HASH or BTREE
	public static $open_cache = true;
	public static $clear_flag = false;
	public static $cache_models = array("module_blocks","article_categories","languages");
	
	/**
	 * Create memory table
	 * @return bool
	 */
	public static function CreateMemTable()
	{
		if(!self::$open_cache) return false;
//		$exist_flag = self::hasMemTable();
		
//		if(!$exist_flag)
//		{
			$table_name = Config::$tbl_prefix.self::$table_name;
			$charset = self::$charset;
			$index_category = self::$index_category;
			$sql = <<<SQL
CREATE TABLE $table_name( 
`key` VARCHAR(32), INDEX USING $index_category (`key`),
`value` VARCHAR(200),
`extra` VARCHAR(50)
) ENGINE=MEMORY DEFAULT CHARSET=$charset;			
SQL;
			$db =& MysqlConnection::get();
			try{
//				mysql_select_db(Config::$db_name);
				$rs = $db->query($sql);
			}catch(Exception $ex) {
				self::$open_cache = false;
				return false;
			}
			return true;
//		} 
//		return true;
	}
	
	/**
	 * Insert data to memory cache table
	 * @param $key
	 * @param $value
	 * @param $extra
	 * @return bool or string
	 */
	public static function InsertMemTable($key,$value,$extra)
	{
		if(!self::$open_cache) return false;
		
		$table_name = Config::$tbl_prefix.self::$table_name;
		$db =& MysqlConnection::get();
		$sql = <<<SQL
INSERT INTO $table_name (`key`,`value`,`extra`) VALUES ('$key','$value','$extra')
SQL;
		try{
//			mysql_select_db(Config::$db_name);
			$rs = $db->query($sql);
		} catch(Exception $ex) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Find memory cache table
	 * @param $where
	 * @param $select_type
	 * @return null or array
	 */
	public static function FindMemTable($where = '1=1')
	{
		if(!self::$open_cache) return null;
		
		$rows = array();
		$table_name = Config::$tbl_prefix.self::$table_name;
		$db =& MysqlConnection::get();
		$sql = <<<SQL
SELECT * FROM $table_name WHERE $where
SQL;
		
//		mysql_select_db(Config::$db_name);
		$rs = $db->query($sql);
		
		$rows = & $rs->fetchRows(MYSQL_ASSOC);
		$rs->free();
		if(empty($rows))
		{
			return null;
		}
		return $rows;
	}
	
	/**
	 * Remove memory table
	 * @return bool
	 */
	public static function RemoveMemTable()
	{
		if(!self::$open_cache) return false;
		
		if(self::hasMemTable() == true)
		{
			$table_name = Config::$tbl_prefix.self::$table_name;
			$db =& MysqlConnection::get();
			$sql = "DROP TABLE $table_name";
			
			try{
//				mysql_select_db(Config::$db_name);
				$rs = $db->query($sql);
				
				if(file_exists(ROOT.'/cache.lock'))
				{
					chmod(ROOT.'/cache.lock',0755);
					unlink(ROOT.'/cache.lock');
				}
			} catch(Exception $ex) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Clear datas of memory table
	 * @param $sql
	 * @return bool
	 */
	public static function ClearMemTable($sql = '1=1')
	{
		if(!self::$open_cache) return false;
		
//		if(self::hasMemTable() == true)
//		{
			$table_name = Config::$tbl_prefix.self::$table_name;
			
			$db =& MysqlConnection::get();
			$sql = "DELETE FROM $table_name WHERE $sql";
			
			try{
//				mysql_select_db(Config::$db_name);
				$rs = $db->query($sql);
			} catch(Exception $ex) {
				return false;
			}
			
			return true;
//		}
		return true;
	}
	
	/**
	 * Memory table exists or not,now check it
	 * @return true or false
	 */
	public static function hasMemTable()
	{
		if(!self::$open_cache) return false;
		$db =& MysqlConnection::get();
		$sql = "SHOW TABLES";
		$rows = array();
		
		try{
//			mysql_select_db(Config::$db_name);
			$rs = $db->query($sql);
			$rows = & $rs->fetchRows(MYSQL_NUM);
			foreach($rows as $value)
			{
				if($value[0] == Config::$tbl_prefix.self::$table_name)
				{
					file_put_contents(ROOT.'/cache.lock','');
					return true;
				}
			}
		}catch(Exception $ex){
			return false;
		}
		
		return false;
	}
	
	public static function FetchMemory($sql,$extra,$select_type = 'all',$tableObject)
	{
		$table_name = Config::$tbl_prefix.self::$table_name;
		$key = md5($sql);
		if(Memorycache::hasMemTable())//exist memory cache
		{
			$res = self::FindMemTable("`key` = '$key'");
			
			if(empty($res))//insert memory cache
			{
//				mysql_select_db(Config::$db_name);
				$db =& MysqlConnection::get();
				$rs = $db->query($sql);
				$objects = array();
				if($select_type == 'all')
				{
					$objects =& $rs->fetchObjects($tableObject,array(false, false));
				}
				elseif($select_type == 'count')
				{
					$objects = & $rs->fetchRow(RSTYPE_NUM);
				}
				
				$result = serialize($objects);
				self::InsertMemTable($key,$result,$extra);
				$rs->free();
				return $objects;
			}
			else //hit cache
			{
				$result = unserialize($res[0]['value']);
				return $result;
			}
		}
		else
		{
			if(Memorycache::CreateMemTable())//create successfully
			{
//				mysql_select_db(Config::$db_name);
				$db =& MysqlConnection::get();
				$rs = $db->query($sql);
				$objects = array();
				if($select_type == 'all')
				{
					$objects =& $rs->fetchObjects($tableObject,array(false, false));
				}
				elseif($select_type == 'count')
				{
					$objects = & $rs->fetchRow(RSTYPE_NUM);
				}
				
				$result = serialize($objects);
				self::InsertMemTable($key,$result,$extra);
				$rs->free();
				return $objects;
			}
			else //create fail
			{
				return "notmatch";
			}
		}
	}
	
	/**
	 * Updata data of memory cache table
	 * @param $sql
	 * @return bool
	 */
	public static function UpdateMemTable($sql)
	{
		if(!self::$open_cache) return false;
		
		if(SessionHolder::get('page/status') == 'view') return;
		
		$arr = array();
		$table_name = '';
		$find_flag = false;
		
//		if(file_exists(ROOT.'/cache.lock') || Memorycache::hasMemTable())
//		{
			if(preg_match("/^SELECT/i",$sql) || (preg_match("/^SHOW/i",$sql)))
			{
				return;
			}
			else
			{
				if(preg_match("/^DELETE FROM/i",$sql))
				{
					$arr = explode(" ",$sql);
					$table_name = $arr[2];
					self::$clear_flag = true;
				}
				elseif(preg_match("/^UPDATE/i",$sql))
				{
					$arr = explode(" ",$sql);
					$table_name = $arr[1];
					self::$clear_flag = true;
				}
				elseif(preg_match("/^INSERT INTO/i",$sql))
				{
					$arr = explode(" ",$sql);
					$table_name = $arr[2];
					self::$clear_flag = true;
				}
				
				if(self::$clear_flag)
				{
					if((file_exists(ROOT.'/cache.lock') || Memorycache::hasMemTable()))
					{
						foreach(self::$cache_models as $v)
						{
							if(!(strpos($table_name,$v) === false))
							{
								$find_flag = true;
								break;
							}
						}
						if(!$find_flag) return;
						$table_name = str_replace('`','',$table_name);
						self::ClearMemTable("`extra` = '$table_name'");
					}
				}
			}
//		}
	}
}
?>