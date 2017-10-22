<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * ThirdAccount object
 * 
 */
class ThirdAccount extends RecordObject {
    public $has_many = array();
    public $belong_to = array();
    public $has = array();
    
    protected $no_validate = array(

    );
    
    protected $yes_validate = array(

    );
		
		public static function findAll2($where='',$orderby=''){
				$db=MysqlConnection::get();
				$return = $row = array();
				$table_name= Config::$tbl_prefix.'third_accounts';

			     $find_keys = '*';

				$_sql = "SELECT $find_keys FROM {$table_name}";
				$_sql = empty($where) ? $_sql : $_sql." WHERE $where";
				$_sql = empty($orderby) ? $_sql : $_sql." $orderby";
				$res = $db->query($_sql);

				if(empty($res))
				{
					die('Select is failed.'.$_sql);
					return false;
				}

				$return=$res->fetchRows();
				//释放数据库结果集
				$res->free();
				return $return;
		}
		
		public static function support_accounts(){
			return array(
					'qq'=>array('name'=>__('QQ'),'dev_url'=>'http://connect.qq.com/manage/?apptype=web'),
			);
		}
}
?>
