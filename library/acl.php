<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Access control toolkit class
 * 
 * @package acl
 */
class ACL {
    /**
     * Normal user login
     *
     * @access public
     * @static
     * @param string $username The login name
     * @param string $password The password in plain text
     * @return boolean
     */
     // 26/04/2010 Edit >>
    //public static function loginUser($username, $password) {
    public static function loginUser($username, $password, $tag = '') {
    // 26/04/2010 Edit <<
        if (strlen(trim($username)) == 0 ||
            strlen(trim($password)) == 0) {
            return false;
        }
        
        $password = sha1($password);
        
        $o_user = new User();
        $n_user = $o_user->count("login=? AND passwd=?", 
            array($username, $password));
        if ($n_user == 1) {
            $curr_user =& $o_user->find("login=? AND passwd=?", 
                array($username, $password));
            // 26/04/2010 Add >>
		 self::loginWithUserObj($curr_user, $tag);
	        return true;
        } else {
            return false;
        }
    }
    
	public static function loginWithUserObj($curr_user,$tag=''){
		if (ACL::isRoleAdmin($curr_user->s_role) && !empty($tag) ) {
        		SessionHolder::set('role', $curr_user->s_role);
        	} else {// 26/04/2010 Add <<
	            SessionHolder::set('user/id', $curr_user->id);
	            SessionHolder::set('user/login', $curr_user->login);
	            SessionHolder::set('user/passwd', $curr_user->passwd);
	            SessionHolder::set('user/s_role', $curr_user->s_role);
	            SessionHolder::set('user/email', $curr_user->email);
	            SessionHolder::set('user/lastlog_time', $curr_user->lastlog_time);
	            SessionHolder::set('user/lastlog_ip', $curr_user->lastlog_ip);
	            SessionHolder::set('user/member_verify', $curr_user->member_verify);
	            SessionHolder::set('user/active', $curr_user->active);
	            // Set page status
	            if (ACL::isRoleAdmin($curr_user->s_role)) {
	                SessionHolder::set('page/status', 'edit');
	            } else {
	                SessionHolder::set('page/status', 'view');
	            }
	            
	            $curr_user->lastlog_time = time();
	            $curr_user->lastlog_ip = $_SERVER['REMOTE_ADDR'];
	            @$curr_user->save();
	        }
	            	 //日志写入
$reg_time = date("Y-m-d H:i:s",time());
$url = $_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
$lastlog_time = date("Y-m-d H:i:s",SessionHolder::get('user/lastlog_time'));
$lastlog_ip = SessionHolder::get('user/lastlog_ip');
$user = SessionHolder::get('user/login');
$file = '#log#.log';
$str = <<<LOGSTR
注册时间:$lastlog_time\r\n
注册IP:$lastlog_ip\r\n
操作时间:$reg_time\r\n
操作类型:登陆\r\n
用户源IP地址:$_SERVER[REMOTE_ADDR]\r\n
用户源端口号:$_SERVER[REMOTE_PORT]\r\n
用户名:$user\r\n
URL:$url\r\n
======================================================   \r\n
LOGSTR;
ParamParser::writeFile($file,$str);
	        //如果登陆前购物车有订单，则更新到登录后的用户名下
	        $prds = $_COOKIE['prds1'];
	        $n_prd= $_COOKIE['n_prd1'];
	        if (!empty($prds)&&!empty($n_prd)) {
	        	foreach ($prds as $k=>$v){
	        		foreach ($n_prd as $k2=>$v2){
	        			ShoppingCart::addProduct($v, $v2);
	        		}
	        	}
	        }
	}	
		
    /**
     * Login default guest account
     *
     * @access public
     * @static
     */
    public static function loginGuest() {
        if (!SessionHolder::has('user')) {
            SessionHolder::set('user/id', 0);
            SessionHolder::set('user/login', 'guest');
            SessionHolder::set('user/passwd', '305e67fb4048f3119c8a9136a14b56ebc51465ff');
            SessionHolder::set('user/s_role', '{guest}');
            SessionHolder::set('user/lastlog_time', '0');
            SessionHolder::set('user/lastlog_ip', '0.0.0.0');
            // Set page status
            SessionHolder::set('page/status', 'view');
        }
    }
    
    /**
     * Check whether current logged-in user is valid
     *
     * @access public
     * @static
     * @return boolean
     */
    public static function checkLogin() {
        if (!SessionHolder::has('user')) {
            return false;
        }
        if (SessionHolder::get('user/login', 'guest') == 'guest' || 
            SessionHolder::get('user/passwd', 'tseug') == 'tseug') {
            return false;
        }
        
        $o_user = new User();
        $n_user = $o_user->count("login=? AND passwd=? AND active='1'", 
            array(SessionHolder::get('user/login'), 
                SessionHolder::get('user/passwd')));
        if ($n_user == 1) {
            $curr_user =& $o_user->find("login=? AND passwd=? AND active='1'", 
                array(SessionHolder::get('user/login'), 
                SessionHolder::get('user/passwd')));
            
            SessionHolder::set('user/id', $curr_user->id);
            SessionHolder::set('user/login', $curr_user->login);
            SessionHolder::set('user/passwd', $curr_user->passwd);
            SessionHolder::set('user/s_role', $curr_user->s_role);
            
            return true;
        } else {
            SessionHolder::destroy();
            return false;
        }
    }
    
    /**
     * Check whether the current logged-in user has specific roles
     *
     * @access public
     * @static
     * @param array $arr_roles Required roles
     * @return boolean
     */
    public static function requireRoles($arr_roles = array('guest')) {
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        foreach ($arr_roles as $role) {
	 if($role=='admin'){
		if(self::isRoleAdmin($user_role)) return true;
	 }
            elseif ($user_role == '{'.$role.'}') {
                return true;
            }
        }
        return false;
    }
    
    
	public static function isRoleSuperAdmin($rolename=null) {
		 if(!isset($rolename)){
                    $rolename=trim(SessionHolder::get('user/s_role', '{guest}'));
            }
            if ($rolename == '{admin}') {
				return true;
           }
           return false;
	 }
		
    public static function isRoleAdmin($rolename=null) {
            if(!isset($rolename)){
                    $rolename=trim(SessionHolder::get('user/s_role', '{guest}'));
            }
            if ($rolename != '{guest}'&&$rolename != '{member}') {
		return true;
           }
           return false;
    }

    /**
     * Translate is_member_only selection into literal roles
     * 
     * @access public
     * @static
     * @param boolean $is_member_only Whether the content is member accessible only
     * @return string
     */
    public static function explainAccess($is_member_only) {
    	$accessible_roles = '{member}{admin}';
        if (!$is_member_only) {
            $accessible_roles .= '{guest}';
        }
        
        return $accessible_roles;
    }
    
    /**
     * Check whether the accessible roles string is a member only roles set
     * 
     * @access public
     * @static
     * @param string $accessible_roles The string contain roles who has accessibility of specific contents
     * @return int
     */
    public static function isMemOnly($accessible_roles) {
    	if (!$accessible_roles) {
    	    return 0;
    	} else if (strpos($accessible_roles, '{guest}') === false) {
            return 1;
        } else {
            return 0;
        }
    }
	
	
    public static function isAdminActionHasPermission($module=R_MOD,$action=R_ACT) {
		$user_role = trim(SessionHolder::get('user/s_role', '{guest}')); 
		if($user_role=='{admin}') return true;
		if($user_role=='{guest}'||$user_role=='{member}') return false;
		$permissions=self::getUserPermission();
		return Role::isActionPermission($module, $action, $permissions);
		
     }
	 
     public static function getUserPermission() {
	          if(!isset($GLOBALS['_user_permissions'])){
			$user_role = trim(SessionHolder::get('user/s_role', '{guest}')); 
			$permissions=Role::getRolePermission($user_role);
			$GLOBALS['_user_permissions']=$permissions;
		 }
		
		 return  $GLOBALS['_user_permissions'];
		
     }

	 /*
    public static function exitNoPerm($acl_id, $no_perm_msg = "No Permission!") {
        if (intval(SessionHolder::get('user/acl_id', -1)) < intval($acl_id)) {
            Notice::set('auth/error', $no_perm_msg);
            Content::redirect('index.php');
        }
    }
    
    public static function su($acl_id) {
        SessionHolder::set('user/acl_id', $acl_id);
        SessionHolder::set('acl_su', '1');
    }
    
    public static function id($acl_id) {
        if (intval(SessionHolder::get('user/acl_id', -1)) == intval($acl_id)) {
            return true;
        } else {
            return false;
        }
    }
    */
}
?>