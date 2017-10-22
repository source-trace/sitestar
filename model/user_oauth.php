<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * UserOauth object
 * 
 */
class UserOauth extends RecordObject {
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
				$table_name= Config::$tbl_prefix.'user_oauths';

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
		
		public static function auth_lib($type){
			$dirname=P_LIB.'/auth/';
			$classname=$type.'_auth';
			$libfilename=$dirname.$classname.'.php';
			if(is_file($libfilename)){
				require_once($dirname."oauth_class.php");
				require_once($libfilename);
				return $classname;
			}
		}
		
		public static function oauth_bind_user($type,$user_id){
			$className=UserOauth::auth_lib($type);
			if(empty($className)) die('Failed!');
			$authclass=new $className();
			$auth_user_info=SessionHolder::get ('open_auth_user');
			$authclass->oauth_bind_user($type,$auth_user_info,$user_id);
		}
}
?>
