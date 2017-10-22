<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

class ModAuth extends Module {
    protected $_filters = array(
        'check_login' => '{loginform}{loginregform}{dologin}{dologout}{open_auth}{auth_callback}'
    );
    
    public function loginform() {
    	$this->_layout = 'frontpage';
    	$this->assign('page_title', __('Login'));
        if (SessionHolder::get('user/s_role', '{guest}') != '{guest}') {
            // Do simply action override
            $this->userinfo();	
            return 'userinfo';
        } else {
            $forward_url = ParamHolder::get('_f', '');
            if (strlen(trim($forward_url)) == 0) {
                $forward_url = 'index.php';
            }
			$accounts=ThirdAccount::findAll2(" active=1 ");
	
			$theaccounts=array();
			foreach($accounts as $theacc){
				$theaccounts[$theacc['account_type']]=$theacc;
			}
			
			
			$this->assign('accounts',$theaccounts);
            $this->setVar('forward_url', $forward_url);
        }
    }
    
	public function open_auth(){
		$this->_layout = NO_LAYOUT;
		
		$type=ParamHolder::get('type');
		try{
			$className=$this->auth_lib($type);
			if(empty($className)) die('Failed!');
			$authclass=new $className();
			Content::redirect($authclass->getAuthorizeURL());
		}catch(OAuthException $e){
			die($e->errormsg);
		}catch(Exception $e){
			die('Failed!');
		}
		exit();
	}	
	
	public function auth_callback(){
		$this->_layout = NO_LAYOUT;
		$type=$_REQUEST['type'];
		$code=$_REQUEST['code'];
		$db=MysqlConnection::get();
		$db->query("SET sql_mode = ''");

		try{
			$className=$this->auth_lib($type);
			if(empty($className)) die('Failed!');
			$authclass=new $className();
			$authclass->processCallback();
			if($authclass->return_val==2){
				$userparams=$authclass->generateUserField();
				$loginname=$userparams['login'];
				$o_user = new User();
				 if ($o_user->count("login=?", array($loginname)) > 0) {
					$userparams['login']=$authclass->autoGenLoginName();
				}
				$o_user->set($userparams);
				$o_user->lastlog_time = time();
				$o_user->lastlog_ip = '0.0.0.0';
				$o_user->rstpwdreq_time = 0;
				$o_user->rstpwdreq_rkey = '';
				$o_user->active = 1;
				$o_user->wizard = 0;
				if(defined('MEMBER_VERIFY')&&MEMBER_VERIFY=='1'){
					$o_user->member_verify = '0';
				}else{
					$o_user->member_verify = '1';
				}
				$o_user->s_role = '{member}';  
				$o_user->save();

				// Initialize user extend info
				$o_user_extend = new UserExtend();
				$o_user_extend->total_saving = '0.00';
				$o_user_extend->total_payment = '0.00';
				$o_user_extend->balance = '0.00';
				$o_user_extend->user_id = $o_user->id;
				$o_user_extend->save();	

				 UserOauth::oauth_bind_user($type, $o_user->id);
				
				if(MEMBER_VERIFY != '1'){
					@ACL::loginWithUserObj($o_user);
				}
				SessionHolder::set ('open_auth_type','');
				SessionHolder::set ('open_auth_user','');
			}
			$this->assign('return_type', $authclass->return_val);
		}catch(OAuthException $e){
			$this->assign('error_code', $e->error);
			$this->assign('error_message', $e->errormsg);
			$this->assign('return_type', 3);
		}catch(LocalAuthException $e){
			$this->assign('error_message', $e->getMessage());
			$this->assign('return_type', 4);
		}
	}
	
    public function loginregform() {
    	$this->_layout = 'frontpage';
    	$this->assign('page_title', __('Login'));
        $forward_url = ParamHolder::get('_f', '');

        $goto =& SessionHolder::get('goto');
        if ((MOD_REWRITE == 2) && !empty($goto)) {
        	$forward_url = $goto;
        	// destroy session
        	SessionHolder::set('goto', '');
        }
        /**
         * for bugfree 350 14:38 2010-7-23 Add end
         */
        if (strlen(trim($forward_url)) == 0) {
            $forward_url = 'index.php';
        }
       $fields=  UserField::findAll2(" showinlist='1' "," order by i_order");
		 $this->assign('user_fields', $fields);
        $this->setVar('forward_url', $forward_url);
        
    }
    
    public function userinfo() {
    	global $db;
    	$user_id = SessionHolder::get('user/id', '0');
    	$sql = "select * from ".Config::$tbl_prefix."emails where user_id=".$user_id." and is_read=0";
    	$res = $db->query($sql);
    	$rows = $res->fetchRows();
        $curr_user = new User($user_id);
        $this->setVar('curr_user', $curr_user);
        $this->assign("read",count($rows));
    }

    public function dologin() {
    	$captcha = ParamHolder::get('rand_rs') ? ParamHolder::get('rand_rs') : ParamHolder::get('rand_rs_reglogn');
        if (!RandMath::checkResult($captcha)) {
            $this->setVar('json', Toolkit::jsonERR(__('Sorry! Please have another try with the math!')));
            return '_result';
        }

        if (ACL::loginUser(ParamHolder::get('login_user', ''), 
            ParamHolder::get('login_pwd', ''),'client')) {
            // 26/04/2010 Add <<
//            if (SessionHolder::get('role', '{guest}') == '{admin}') {
            if (ACL::isRoleAdmin()) {
            	$this->setVar('json', Toolkit::jsonERR(__('Administrator prohibit login!')));
            } else if(MEMBER_VERIFY=='1' && SessionHolder::get('user/member_verify')!='1'){
				SessionHolder::destroy();
				$this->setVar('json', Toolkit::jsonERR(__('being reviewed')));
			}else if(SessionHolder::get('user/active')!='1'){
				SessionHolder::destroy();
				$this->setVar('json', Toolkit::jsonERR(__('This account was prohibited from login, please contact the administrator.')));
			}else{// 26/04/2010 Add <<
            	$forward_url = ParamHolder::get('_f', '');
	            if (strlen(trim($forward_url)) == 0) {
	                $forward_url = 'index.php';
	            }
	            $this->setVar('json', Toolkit::jsonOK(array('forward' => $forward_url)));
            }
            
        } else {
            $this->setVar('json', Toolkit::jsonERR(__('Username and password mismatch!')));
        }
        
        return '_result';
    }
    
    public function dologout() {
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
操作类型:退出\r\n
用户源IP地址:$_SERVER[REMOTE_ADDR]\r\n
用户源端口号:$_SERVER[REMOTE_PORT]\r\n
用户名:$user\r\n
URL:$url\r\n
======================================================   \r\n
LOGSTR;
		ParamParser::writeFile($file,$str);
        SessionHolder::destroy();
        // TODO: We need a logged out page and countdown redirecting to index.php
//        Content::redirect(Html::uriquery('mod_auth', 'loginform'));
        Content::redirect(Html::uriquery('frontpage', 'index'));
    }
		
	private function auth_lib($type){
		return UserOauth::auth_lib($type);
	}	
}
?>