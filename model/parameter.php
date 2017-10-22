<?php

if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Database parameter object
 * 
 */
class Parameter extends RecordObject {
	 public  static function getParameters($params,$force=false){
		 	$ret=array();
			if(!empty($params)){
				if(!is_array($params)){
					$params=array($params);
				}
				foreach($params as $param){
					if(defined($param)) $ret[$param]=constant($param);
				}
			}
			return $ret;
	}
     
	public static function getOneParameter($param,$force=false){
			 $ret=self::getParameters(array($param), $force);
			 if(!empty($ret[$param])) return $ret[$param];
	}
	
    public static function updateParameters($params,$force=false){
			$db=&MysqlConnection::get();
			$paramkey=array_keys($params);
			$oriparams=self::getParameters($paramkey,$force);
			foreach($params as $key=>$value){
				$orival=$oriparams[$key];
				$paramtable=Config::$tbl_prefix."parameters";
				if(!array_key_exists($key, $oriparams)){
					//insert
					$sql="insert into {$paramtable}(`key` ,`val`) values ('".mysql_escape_string($key)."','".mysql_escape_string($value)."')";
					$db->query($sql);
				}elseif($orival != $value){
					$sql="UPDATE {$paramtable}  SET  `val` =  '".mysql_escape_string($value)."' WHERE  `key` ='".mysql_escape_string($key)."' ";
					$db->query($sql);
				}
			}
    }
	
}
?>