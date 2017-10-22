<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModUser extends Module {

	protected $_filters = array(
        'check_admin' => '{adddeliveryaddr}'
    );
	
    public function admin_list() {
        $this->_layout = 'content';
        $s_role = SessionHolder::get('user/s_role');
        $u_id = SessionHolder::get('user/id');
        $a_user = new User($u_id);
 		if ($s_role!='{admin}') {//过滤角色用户显示的用户列表，只能看到普通用户
	    	$user_data =& Pager::pageByObject('User','s_role="{member}" or id in('.$u_id.')');
 		}else{
 			$user_data =& Pager::pageByObject('User');
 		}
		$usersdata=$user_data['data'];
		$useriddatarel=array();
		$userids=array();
		foreach ($usersdata as $u) {
			$userids[]=$u->id;
			$useriddatarel[$u->id]=$u;
		}
		if (!empty($userids)) {
			$useridstr=implode(',', $userids);
			$oauthinfo=UserOauth::findAll2(" user_id in ($useridstr) ");
		}
		
		if(!empty($oauthinfo)){
			foreach($oauthinfo as $oinfo){
				if(!empty($useriddatarel[$oinfo['user_id']])){
					if(empty($useriddatarel[$oinfo['user_id']]->oauth)) $useriddatarel[$oinfo['user_id']]->oauth=array();
					$useroauth=&$useriddatarel[$oinfo['user_id']]->oauth;
					$useroauth[$oinfo['auth_type']] = $oinfo;
				}
			}
		}

        $this->assign('users', $user_data['data']);
        $this->assign('pager', $user_data['pager']);
        $this->assign('page_mod', $user_data['mod']);
		$this->assign('page_act', $user_data['act']);
		$this->assign('page_extUrl', $user_data['extUrl']);
    }

    public function admin_add() {
        $this->_layout = 'content';
        
	   $fields=  UserField::findAll2(" showinlist='1' "," order by i_order"); 	
				
       $this->assign('roles', Toolkit::loadAllRoles(array('guest','admin')));
	   $this->assign('user_fields', $fields);
    }
    
    public function admin_search() {
        $this->_layout = 'content';
        
        $this->assign('roles', Toolkit::loadAllRoles(array('guest','admin')));
    }
    
    public function search_list() {
        $this->_layout = 'content';
        extract($_REQUEST);
        $u = new User();
        $more_sql = '';
        $where = ' 1=1 ';
        $uri_arr = array();
        if (!empty($user_name)) {
        	if ($name_c) {
        		$where .= " and login='".$user_name."'";
        	}else{
        		$where .= " and login like '%".$user_name."%'";
        	}
        }
        if (!is_null($active)) {
        	$where .= " and active=?";
        	$params[] = $active;
        }
        if (!empty($email)) {
        	$where .= " and email=?";
        	$params[] = $email;
        }
        if (!empty($role)) {
        	$where .= " and s_role=?";
        	$params[] = "{".$role."}";
        }
//        echo $where;
        $users =& $u->findAll($where, $params, $more_sql);
        $this->assign('users', $users);
//		var_dump($users);
        $this->assign('roles', Toolkit::loadAllRoles(array('guest','admin')));
    }

    public function admin_create() {
		$this->_layout=NO_LAYOUT;
        $user_info =@ ParamHolder::get('user', array());
		$extend_info=& ParamHolder::get('extends', array());
        if (sizeof($user_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing user information!')));
            return '_result';
        }
        if ($user_info['passwd'] != $user_info['re_passwd']) {
            $this->assign('json', Toolkit::jsonERR(__('Password Mismatch!')));
            return '_result';
        }
        $user_info['passwd'] = sha1($user_info['passwd']);
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
		//2013/4/27 zhangjc
		$roles=Toolkit::loadAllRoles(array('guest','admin'));
		$role_arr=array();
		foreach($roles as $role){
			array_push($role_arr,$role->name);
		}
		if (!in_array($user_info['s_role'],$role_arr)) {
            $this->assign('json', Toolkit::jsonERR(__('Role Error!')));
            return '_result';
        }
        try {
           
            if ($user_info['active'] == '1') {
                $user_info['active'] = '1';
            } else {
                $user_info['active'] = '0';
            }
			if (isset($user_info['wizard']) && $user_info['wizard'] == '1') {
                $user_info['wizard'] = '1';
            } else {
                $user_info['wizard'] = '0';
            }

			$user_info['member_verify'] = '1';
            $user_info['s_role'] = '{'.$user_info['s_role'].'}';
						
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
            $o_user->lastlog_time = 0;
            $o_user->lastlog_ip = '0.0.0.0';
            $o_user->rstpwdreq_time = 0;
            $o_user->rstpwdreq_rkey = '';
            $o_user->save();

            // Initialize user extend info
            $o_user_extend = new UserExtend();
            $o_user_extend->total_saving = '0.00';
            $o_user_extend->total_payment = '0.00';
            $o_user_extend->balance = '0.00';
            $o_user_extend->user_id = $o_user->id;
            $o_user_extend->save();

            // TODO: send registration mail
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_user', 'admin_list'))));
        return '_result';
    }

    public function admin_edit() {
        $this->_layout = 'content';
	  $issuperadmin=ACL::isRoleSuperAdmin();
	   $curUserid= SessionHolder::get('user/id', 0);
	   $ismyself=false;
	  
        $curr_user_id = trim(ParamHolder::get('u_id', '0'));
				
	    if($curr_user_id==$curUserid){
			 $ismyself=true;
	   }
        if (intval($curr_user_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        try {
            $curr_user = new User($curr_user_id);
						
		  if(!ACL::isRoleSuperAdmin()&&ACL::isRoleSuperAdmin($curr_user->s_role)){
				 $this->assign('json', Toolkit::jsonERR(__('No Permission')));
				  return '_error';
		   }
			 
		 $fields=  UserField::findAll2(" showinlist='1' "," order by i_order"); 	 
            $this->assign('curr_user', $curr_user);
		$this->assign('ismyself', $ismyself);
		$this->assign('issuperadmin', $issuperadmin);
        	$this->assign('roles', Toolkit::loadAllRoles(array('guest','admin')));
		  $this->assign('user_fields', $fields);
		
        } catch (Exception $ex) {
            $this->assign('json', $ex->getMessage());
            return ('_error');
        }
    }

    public function admin_update() {
		$this->_layout=NO_LAYOUT;
        $user_info =@ ParamHolder::get('user', array());
		$extend_info=& ParamHolder::get('extends', array());
        if (sizeof($user_info) <= 0) {
            $this->assign('json', Toolkit::jsonOK(__('Missing user information!')));
            return '_result';
        }
	   $issuperadmin=ACL::isRoleSuperAdmin();
	   $curUserid= SessionHolder::get('user/id', 0);
	   $ismyself=false;
	   if($user_info['id']==$curUserid){
			 $ismyself=true;
	   }
        $passwd_changed = false;
		$o_user = new User($user_info['id']);	
		if ($user_info[s_role]=='admin') {
			if($o_user->s_role!='{admin}'){
				$this->assign('json', Toolkit::jsonERR(__('Role Error!')));
				return '_result';
			}
        }else{
        	$issuperadmin=ACL::isRoleSuperAdmin();
			$roles=Toolkit::loadAllRoles(array('guest','admin'));
			$role_arr=array();
			foreach($roles as $role){
				array_push($role_arr,$role->name);
			}
			if ($issuperadmin) {
				if (!in_array($user_info['s_role'],$role_arr)) {
					$this->assign('json', Toolkit::jsonERR(__('Role Error!')));
					return '_result';
				}
			}else{
				if (!empty($user_info['s_role'])) {
					$this->assign('json', Toolkit::jsonERR(__('Role Error!')));
					return '_result';
				}
			}
			
		}
		  
		  if(!ACL::isRoleSuperAdmin()&&ACL::isRoleSuperAdmin($o_user->s_role)){
				 $this->assign('json', Toolkit::jsonERR(__('No Permission')));
				  return '_result';
		   }
						
            if ($user_info['email'] != $o_user->email) {
                /* Check duplicates */
                if ($o_user->count("email=?", array($user_info['email'])) > 0) {
                    $this->assign('json', Toolkit::jsonERR(__('User E-mail address exists!')));
                    return '_result';
                }
            }
		 if(ACL::isRoleSuperAdmin()){
				if ((isset($user_info['active'])&&$user_info['active'] == '1'||ACL::isRoleSuperAdmin($o_user->s_role))) {
						$user_info['active'] = '1';
				} else {
						$user_info['active'] = '0';
				}
		 }else{
			  unset($user_info['active']);
		 }
		 if($issuperadmin){
			$user_info['s_role'] = '{'.$user_info['s_role'].'}';
		 }else{
			 unset($user_info['s_role']);
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
						
//		  if($ismyself ||$issuperadmin){	
			
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
//		}
            $o_user->save();
        

        if ($passwd_changed && $o_user->login == SessionHolder::get('user/login')) {
        	SessionHolder::destroy();
        	$this->assign('json', Toolkit::jsonOK(array('forward' => 'index.php', 'selfpwd' => '1')));
        } else {
        	$this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_user', 'admin_list'))));
        }
        return '_result';
    }

    public function admin_delete() {

        $curr_user_id = trim(ParamHolder::get('u_id', '0'));
        if (intval($curr_user_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
            $curr_user = new User($curr_user_id);
            if ($curr_user->login == SessionHolder::get('user/login')) {
                $this->assign('json', Toolkit::jsonERR(__('Cannot delete yourself!')));
                return '_result';
            } elseif($curr_user->s_role=='{admin}') {
                $this->assign('json', Toolkit::jsonERR(__('Cannot delete Administrator!')));
                return '_result';
               // $db = MysqlConnection::get();
            	//$db->query('DELETE FROM es_user_extends WHERE user_id = ?',array($curr_user_id));
            }else {
                $curr_user->delete();
			 $oauthtable=Config::$tbl_prefix."user_oauths";
			 $db = MysqlConnection::get();
			 $db->query("DELETE FROM {$oauthtable} WHERE user_id = ?",array($curr_user_id));
               // $db = MysqlConnection::get();
            	//$db->query('DELETE FROM es_user_extends WHERE user_id = ?',array($curr_user_id));
            }
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
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
    
    public function admin_toggle_active() {

        $curr_user_id = trim(ParamHolder::get('u_id', '0'));
        if (intval($curr_user_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        $active = trim(ParamHolder::get('u_acti', '0'));
        if ($active != '1') {
            $active = '0';
        }
        try {
            $curr_user = new User($curr_user_id);
            if ($curr_user->login == SessionHolder::get('user/login') && $active == '0') {
                $this->assign('json', Toolkit::jsonERR(__('Cannot deactive yourself!')));
                return '_result';
            }elseif($curr_user->s_role=='{admin}') {
                $this->assign('json', Toolkit::jsonERR(__('Cannot deactive Administrator!')));
                return '_result';
               // $db = MysqlConnection::get();
            	//$db->query('DELETE FROM es_user_extends WHERE user_id = ?',array($curr_user_id));
            } else {
                $curr_user->active = $active;
                $curr_user->save();
            }
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK(array('u_id' => $curr_user_id, 'u_acti' => $active)));
        return '_result';
    }

    public function admin_finance() {
        $this->_layout = 'content';

        $curr_user_id = trim(ParamHolder::get('u_id', '0'));
        if (intval($curr_user_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        $o_user_ext = new UserExtend();
        $curr_user_ext =& $o_user_ext->find("user_id=?", array($curr_user_id));
        if (!$curr_user_ext) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $this->assign('curr_user_ext', $curr_user_ext);

        $user_transactions =& Pager::pageByObject('Transaction', "user_id=?", array($curr_user_id),
                "ORDER BY `action_time` DESC");
        $this->assign('transactions', $user_transactions['data']);
        $this->assign('pager', $user_transactions['pager']);
        $this->assign('page_mod', $user_transactions['mod']);
		$this->assign('page_act', $user_transactions['act']);
		$this->assign('page_extUrl', $user_transactions['extUrl']);
    }

    public function admin_financialop() {

        $trans_info =& ParamHolder::get('usermoney', array());
        if (sizeof($trans_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing money information!')));
            return '_result';
        }

        $curr_user_id = trim(ParamHolder::get('u_id', '0'));
        if (intval($curr_user_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }

        try {
            $o_transaction = new Transaction();
            $o_transaction->set($trans_info);
            $o_transaction->action_time = time();
            $o_transaction->user_id = $curr_user_id;
            $o_transaction->save();

            $o_user_ext = new UserExtend();
            $curr_user_ext =& $o_user_ext->find("user_id=?", array($curr_user_id));
            if (!$curr_user_ext) {
                $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                return '_result';
            }
            if (intval($trans_info['type']) == 1) {
                $curr_user_ext->total_saving = floatval($curr_user_ext->total_saving) + floatval($trans_info['amount']);
            } else if (intval($trans_info['type']) == 2) {
                $curr_user_ext->total_payment = floatval($curr_user_ext->total_payment) + floatval($trans_info['amount']);
            }
            $curr_user_ext->balance = floatval($curr_user_ext->total_saving) - floatval($curr_user_ext->total_payment);
            $curr_user_ext->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK());
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
    
    public function admin_dashboard() {
   		$this->_layout = 'default';
    }
	
	//zhangjc 2012-3-16
	public function admin_pic()
    {
    	$article_info = array();
    	$article_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($article_id))
    	{
    		$o_article = new User($article_id);
            if($o_article->member_verify == 1)
            {
            	$article_info['member_verify'] = '0';
            	$o_article->set($article_info);
            	$o_article->save();
				die('0');
            }
            elseif($o_article->member_verify == 0)
            {
            	$article_info['member_verify'] = '1';
            	$o_article->set($article_info);
            	$o_article->save();
				die('1');
            }
    	}
    }
}
?>