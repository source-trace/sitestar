<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModUser extends Module {
    protected $_filters = array(
        'check_login' => '{reg_form}{chk_login_name}{do_reg}{auth_adduser}{req_rstpwd_form}{send_rstpwd_req}{rstpwd_form}{rstpwd}'
    );

    public function req_rstpwd_form() {
        $this->_layout = 'content';
    }

    public function send_rstpwd_req() {
        $user_info =& ParamHolder::get('user', array());
        if (sizeof($user_info) != 2) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid Input!')));
            return '_result';
        }
        if (!isset($user_info['login']) || !isset($user_info['email'])) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid Input!')));
            return '_result';
        }

        try {
            $o_user = new User();
            $curr_user =& $o_user->find("login=? AND email=?",
                array($user_info['login'], $user_info['email']));
            if (!$curr_user) {
                $this->assign('json', Toolkit::jsonERR(__('User does not exist!')));
                return '_result';
            }
            /* Alter user switches */
            $curr_user->rstpwdreq_rkey = Toolkit::randomStr(128);
            $curr_user->rstpwdreq_time = time();
            $curr_user->active = 0;
            $curr_user->save();
            /* Generate return sign */
            $sign = sha1($curr_user->login.$curr_user->passwd.$curr_user->email.$curr_user->rstpwdreq_rkey);
            /* Send mail */
            include_once(P_LIB.'/phpmailer/class.phpmailer.php');
			include_once(P_LIB.'/mailer.php');
			
            Mailer::send($curr_user->email,
                'rstpwdreq.'.SessionHolder::get('_LOCALE', DEFAULT_LOCALE),
                array('site_name' => SessionHolder::get('_SITE')->site_name,
                    'rstpwd_url' => $this->_genRstpwdURL($curr_user->login,
                        $curr_user->email, $sign)));
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }

    public function rstpwd_form() {
        // TODO: There must be a simple layout for single function page
        $this->_layout = 'content';

        $login = trim(ParamHolder::get('login', ''));
        $email = trim(ParamHolder::get('email', ''));
        $sign = trim(ParamHolder::get('sign', ''));

        $this->assign('v_login', $login);
        $this->assign('v_email', $email);
        $this->assign('v_sign', $sign);
    }

    public function rstpwd() {
        $valid_info =& ParamHolder::get('v', array());
        if (sizeof($valid_info) != 3) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid Input!')));
            return '_result';
        }
        if (strlen(trim($valid_info['login'])) == 0 ||
            strlen(trim($valid_info['email'])) == 0 ||
            strlen(trim($valid_info['sign'])) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid Input!')));
            return '_result';
        }

        $user_info =@ ParamHolder::get('user', array());
        if (sizeof($user_info) != 2) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid user information!')));
            return '_result';
        }
        if ($user_info['passwd'] != $user_info['re_passwd']) {
            $this->assign('json', Toolkit::jsonERR(__('Password Mismatch!')));
            return '_result';
        }

        try {
            $o_user = new User();
            $curr_user =& $o_user->find("login=? AND email=?",
                array($valid_info['login'], $valid_info['email']));
            if (!$curr_user) {
                $this->assign('json', Toolkit::jsonERR(__('User does not exist!')));
                return '_result';
            }
            /* Check timeout */
            if (time() - intval($curr_user->rstpwdreq_time) > 3600 * 24) {
                $this->assign('json', Toolkit::jsonERR(__('Request timeout! Please re-send a request from reset password page!')));
                return '_result';
            }
            /* Generate local sign and check return sign */
            $l_sign = sha1($curr_user->login.$curr_user->passwd.$curr_user->email.$curr_user->rstpwdreq_rkey);
            if (trim($sign) != $l_sign) {
                $this->assign('json', Toolkit::jsonERR(__('Invalid sign!')));
                return '_result';
            }
            /* Alter user switches */
            $curr_user->passwd = sha1($user_info['passwd']);
            $curr_user->rstpwdreq_rkey = '';
            $curr_user->rstpwdreq_time = 0;
            $curr_user->active = 1;
            $curr_user->save();
        } catch (Exceiption $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }

    public function edit_profile() {
        $this->_layout = 'content';

        $curr_user_id = SessionHolder::get('user/id');
        try {
            $curr_user = new User($curr_user_id);
		  $fields=  UserField::findAll2(" showinlist='1' "," order by i_order"); 	
            $this->assign('curr_user', $curr_user);
		  $this->assign('user_fields', $fields);
        } catch (Exception $ex) {
            $this->assign('json', $ex->getMessage());
            return ('_error');
        }
    }

    public function save_profile() {
       $user_info =@ ParamHolder::get('user', array());
       unset($user_info['s_role']);
	   $extend_info=& ParamHolder::get('extends', array());
        if (sizeof($user_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing user information!')));
            return '_result';
        }
        $user_id = SessionHolder::get('user/id',0);
        if (empty($user_id)){
        	$this->assign('json', Toolkit::jsonERR(__('Login first!')));
        	return '_result';
        }
        $passwd_changed = false;
        try {
            $o_user = new User($user_id);
			if($o_user->s_role=='{admin}'){
				$this->assign('json', Toolkit::jsonERR(__('Role Error!')));
				return '_result';
			}
            if ($user_info['email'] != $o_user->email) {
                /* Check duplicates */
                if ($o_user->count("email=?", array($user_info['email'])) > 0) {
                    $this->assign('json', Toolkit::jsonERR(__('User E-mail address exists!')));
                    return '_result';
                }
            }
		
		 $custom_fields=array();
		 $fields=  UserField::findAll2(" showinlist='1' "," order by i_order");
	    foreach($fields as $fieldinfo){
			$fieldname="field".$fieldinfo['id'];
			$fieldtype=$fieldinfo['field_type'];
			$propname=$fieldinfo['label'];
			
			$isrequired=$fieldinfo['required'];
			if($isrequired=='1' && ($fieldtype == 0 && (!isset($user_info[$propname]) || UserField::trim($user_info[$propname])=='')
							||	 $fieldtype != 0 &&(!isset($extend_info[$fieldname]) ||UserField::trim($extend_info[$fieldname])==''))){
				$label=UserField::getUserDefineLabel($fieldinfo); 
				$this->assign('json', Toolkit::jsonERR(__('The field cannot be empty!').":{$label}"));
				return '_result';
			}else if($fieldtype != 0){
				if(isset($extend_info[$fieldname]) && UserField::trim($extend_info[$fieldname])!=''){
					 $custom_fields[$fieldname] =$extend_info[$fieldname];
				}
			}
		}
		$user_info['params'] =json_encode($custom_fields);				
						
            $o_user->set($user_info);

            /* Check password */
            $passwd_info =@ ParamHolder::get('passwd', array());
            if (sizeof($passwd_info) != 2) {
                $this->assign('json', Toolkit::jsonERR(__('Invalid Password!')));
                return '_result';
            }
            if (strlen(trim($passwd_info['passwd'])) > 0 ||
                strlen(trim($passwd_info['re_passwd'])) > 0) {
                if ($passwd_info['passwd'] == $passwd_info['re_passwd']) {
                    $o_user->passwd = sha1($passwd_info['passwd']);
                    $passwd_changed = true;
                }
            }

            $o_user->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        if ($passwd_changed) {
        	SessionHolder::destroy();
            $this->assign('json', Toolkit::jsonOK(array('forward' => 'index.php')));
        } else {
        	$forward_url = Html::uriquery('mod_user', 'edit_profile');
            $this->assign('json', Toolkit::jsonOK(array('forward' => $forward_url)));
        }
        return '_result';
    }

    public function reg_form() {
			
        $this->_layout = 'content';
	   $regtype =@ trim(ParamHolder::get('regtype', ''));
		 $auth_type=SessionHolder::get('open_auth_type');
		if($regtype == 'accbinding' && !empty($auth_type)){
			$title=__('Account binding');
			$regtitle=__('Submit');
			$className=UserOauth::auth_lib($auth_type);
			if(empty($className)) die('Failed!');
			$authclass=new $className();
			$userparams=$authclass->generateUserField();
			$this->assign('userparams', $userparams);
			$this->assign('auth_type', $auth_type);
		}else{
			$title=__('Register Member');
			$regtitle=__('Register');
		}	
				
		 $fields=  UserField::findAll2(" showinlist='1' "," order by i_order");
		 $this->assign('user_fields', $fields);
		 $this->assign('mod_title', $title);
		 $this->assign('regtitle', $regtitle);
    }

    public function chk_login_name() {
        $login =@ trim(ParamHolder::get('login', ''));
        if (strlen($login) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing login name!')));
            return '_result';
        }

        try {
            $o_user = new User();
            if ($o_user->count("login=?", array($login)) > 0) {
                $this->assign('json', Toolkit::jsonERR(__('User login name exists!')));
            } else {
                $this->assign('json', Toolkit::jsonOK(array('msg' => __('User login name available!'))));
            }
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
        }
        return '_result';
    }
	
	public function auth_adduser(){
		           $this->_layout = NO_LAYOUT;
				 $auth_type=& ParamHolder::get('auth_type', '');

				 if(!empty($auth_type) && $auth_type==SessionHolder::get('open_auth_type')){
			     $className=UserOauth::auth_lib($auth_type);
				if(empty($className)) die('Failed!');
				$authclass=new $className();
				$userparams=$authclass->generateUserField();
				$loginname=$userparams['login'];
				try {
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
						
					 UserOauth::oauth_bind_user($auth_type, $o_user->id);
				}catch (Exception $ex) {
//						$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
//						return '_result';
				}
				if(MEMBER_VERIFY != '1'){
					@ACL::loginWithUserObj($o_user);
				}
				Content::redirect('index.php');
		 }
	}	
		
    public function do_reg() {
        $user_info =@ ParamHolder::get('user', array());
	   $extend_info=& ParamHolder::get('extends', array());
        if (sizeof($user_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing user information!')));
            return '_result';
        }
        /** Check password format **/
        if (!preg_match('/^[^\s]{6,20}$/', $user_info['passwd'])) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid Password!')));
            return '_result';
        }

        if ($user_info['passwd'] != $user_info['re_passwd']) {
            $this->assign('json', Toolkit::jsonERR(__('Password Mismatch!')));
            return '_result';
        }
        if (!preg_match ("/^[a-z0-9_\.@]*$/i",  $user_info['login'])) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid user!')));
            return '_result';
	}
        $user_info['login']  = strip_tags($user_info['login']);
        $user_info['passwd'] = sha1($user_info['passwd']);
        try {
            $o_user = new User();

            /* Check duplicates */
            if ($o_user->count("login=?", array($user_info['login'])) > 0) {
                $this->assign('json', Toolkit::jsonERR(__('User login name exists!')));
                return '_result';
            }
            if ($o_user->count("email=?", array($user_info['email'])) > 0) {
                $this->assign('json', Toolkit::jsonERR(__('User E-mail address exists!')));
                return '_result';
            }
		
		   $custom_fields=array();
		 $fields=  UserField::findAll2(" showinlist='1' "," order by i_order");
	    foreach($fields as $fieldinfo){
			$fieldname="field".$fieldinfo['id'];
			$fieldtype=$fieldinfo['field_type'];
			$propname=$fieldinfo['label'];
			
			$isrequired=$fieldinfo['required'];
			if($isrequired=='1' && ($fieldtype == 0 && (!isset($user_info[$propname]) || UserField::trim($user_info[$propname])=='')
							||	 $fieldtype != 0 &&(!isset($extend_info[$fieldname]) ||UserField::trim($extend_info[$fieldname])==''))){
				$label=UserField::getUserDefineLabel($fieldinfo); 
				$this->assign('json', Toolkit::jsonERR(__('The field cannot be empty!').":{$label}"));
				return '_result';
			}else if($fieldtype != 0){
				if(isset($extend_info[$fieldname]) && UserField::trim($extend_info[$fieldname])!=''){
					 $custom_fields[$fieldname] =$extend_info[$fieldname];
				}
			}
		}
		$user_info['params'] =json_encode($custom_fields);
            $o_user->set($user_info);
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
 //
 //日志写入
$reg_time = date("Y-m-d H:i:s",time());
$url = $_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
$file = '#log#.log';
$str = <<<LOGSTR
注册时间:$reg_time\r\n
注册IP:$_SERVER[REMOTE_ADDR]\r\n
操作时间:$reg_time\r\n
操作类型:注册\r\n
用户源IP地址:$_SERVER[REMOTE_ADDR]\r\n
用户源端口号:$_SERVER[REMOTE_PORT]\r\n
用户名:$user_info[login]\r\n
URL:$url\r\n
======================================================   \r\n
LOGSTR;
ParamParser::writeFile($file,$str);
		 $auth_type=& ParamHolder::get('auth_type', '');
		 if(!empty($auth_type) && $auth_type==SessionHolder::get('open_auth_type')){
				 UserOauth::oauth_bind_user($auth_type, $o_user->id);
		 }
            // TODO: send registration mail
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        

        $forward_url = ParamHolder::get('_f', '');
        if (strlen(trim($forward_url)) == 0) {
            $forward_url = 'index.php';
        }
		/* Login after registered */
		if(MEMBER_VERIFY=='1'){
			$this->assign('json', Toolkit::jsonOK(array('verify' => '1','forward' => $forward_url)));
			return '_result';
		}else{
			@ACL::loginUser($user_info['login'], $user_info['re_passwd']);
		}

        $this->assign('json', Toolkit::jsonOK(array('forward' => $forward_url)));
        return '_result';
    }


    // Delivery address functions
    public function adddeliveryaddr() {
        $this->_layout = 'content';
        $this->assign('success_action', 'close');
        $this->assign('content_title', __('New Delivery Address'));
        $this->assign('next_action', 'createdeliveryaddr');

        return '_delivery_addr_form';
    }

    public function createdeliveryaddr() {
        $addr_info =& ParamHolder::get('addrinfo', array());
        if (sizeof($addr_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing address information!')));
            return '_result';
        }
        try {
            $o_delivery_addr = new DeliveryAddress();

            $o_delivery_addr->set($addr_info);
            $o_delivery_addr->user_id = SessionHolder::get('user/id');
            $o_delivery_addr->save();

            // TODO: send registration mail
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_user', 'deliveryaddrlst'))));
        return '_result';
    }

    public function editdeliveryaddr() {
        $this->_layout = 'content';
        $this->assign('success_action', 'close');
        $this->assign('content_title', __('Edit Delivery Address'));
        $this->assign('next_action', 'updatedeliveryaddr');

        $curr_user_id = SessionHolder::get('user/id');
        $curr_addr_id = trim(ParamHolder::get('da_id', '0'));
        if (intval($curr_addr_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        try {
            $o_addr = new DeliveryAddress();
            $curr_addr =& $o_addr->find("id=? AND user_id=?", array($curr_addr_id, $curr_user_id));
            $this->assign('curr_addr', $curr_addr);
            return '_delivery_addr_form';
        } catch (Exception $ex) {
            $this->assign('json', $ex->getMessage());
            return ('_error');
        }
    }

    public function updatedeliveryaddr() {
        $curr_user_id = SessionHolder::get('user/id');
        $addr_info =& ParamHolder::get('addrinfo', array());
        if (sizeof($addr_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing address information!')));
            return '_result';
        }
        try {
            $o_addr = new DeliveryAddress();
            $o_delivery_addr =& $o_addr->find("id=? AND user_id=?", array($addr_info['id'], $curr_user_id));
            if (!$o_delivery_addr) {
                $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                return '_error';
            }

            $o_delivery_addr->set($addr_info);
            $o_delivery_addr->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_user', 'deliveryaddrlst'))));
        return '_result';
    }

    public function deldeliveryaddr() {
        $curr_user_id = SessionHolder::get('user/id');
        $curr_addr_id = trim(ParamHolder::get('da_id', '0'));
        if (intval($curr_addr_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
            $o_addr = new DeliveryAddress();
            $curr_addr =& $o_addr->find("id=? AND user_id=?", array($curr_addr_id, $curr_user_id));
            if ($curr_addr) {
                $curr_addr->delete();
            }
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }

    private function _genRstpwdURL($login, $email, $sign) {
        $host = $_SERVER['HTTP_HOST'];
        if (intval($_SERVER['SERVER_POST']) != 80) {
            $host = $host.':'.$_SERVER['SERVER_POST'];
        }
        return 'http://'.$host.$_SERVER['REQUEST_URI']
            .'?_m=mod_user&_a=rstpwd_form&_r=_page'
            .'&login='.urlencode($login)
            .'&email='.urlencode($email)
            .'&sign='.urlencode($sign);
    }
}
?>