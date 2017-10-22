<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModThirdAccount extends Module {
    protected $_filters = array(
        'check_admin' => ''
    );
    
	
    public function admin_list(){
		$this->_layout = 'content';
		$prefix=  Config::$tbl_prefix;

		$third_accounts= ThirdAccount::findAll2();
		$keyaccounts=array();
		foreach ($third_accounts as $value) {
			 $accounttype=$value['account_type'];
			 $keyaccounts[$accounttype]=$value;
		}
		$support_accounts=ThirdAccount::support_accounts();
		$othirdaccs=array();
		foreach ($support_accounts as $key=>$value) {
			 if(!empty( $keyaccounts[$key])){
				 $othirdaccs[]=array_merge($keyaccounts[$key],array('constant'=>$value));
			 }else{
				 $accountinfo=array('account_type'=>$key,'active'=>'0');
				 $othirdaccs[]=array_merge($accountinfo,array('constant'=>$value));
				 $this->edit_third_account($accountinfo);
			 }
		}
		

		 $this->assign('third_accounts', $othirdaccs);
		
	}
    
	public function admin_edit() {
         $this->_layout = 'content';

        $accounttype = ParamHolder::get('type', '');
	   $support_accounts=ThirdAccount::support_accounts();
        if (empty($accounttype)||empty($support_accounts[$accounttype])) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
       	
		 $account=ThirdAccount::findAll2(" account_type='".mysql_escape_string($accounttype)."' ");
          if(empty($account)){
			$account=array('account_type'=>$accounttype,'active'=>'0');
		 }else{
			 $account=$account[0];
		 }
		 $account=array_merge($account,array('constant'=>$support_accounts[$accounttype]));
            $this->assign('curr_account', $account);

		  return '_form';

    }	
	
	public function admin_save() {
			 $this->_layout =NO_LAYOUT;
			$third_account=& ParamHolder::get('account', array());
			if (empty($third_account)|| empty($third_account['account_type'])) {
					$this->assign('json', Toolkit::jsonERR(__('Missing Account information!')));
					return '_result';
			}
			 if ($third_account['active'] == '1') {
					$third_account['active'] = '1';
			} else {
					$third_account['active'] = '0';
			}
			$this->edit_third_account($third_account);
		     $this->assign('json', Toolkit::jsonOK(array()));
			return '_result';
	}	
	
	public function admin_pic(){
		$this->_layout=NO_LAYOUT;
		
		$resultval = 0;
		$accounttype = trim(ParamHolder::get('type', ''));
		if(!empty($accounttype))
		{
			$ret=ThirdAccount::findAll2(" account_type='".mysql_escape_string($accounttype)."' ");
			if(count($ret)>0) $obj=$ret[0];
			
			if($obj['active'] == 1)
			{
				$resultval = 0;
			}
			elseif($obj['active']== 0)
			{
				if(empty($obj['appid']) || empty($obj['appsecret'])){
					$this->assign('json', Toolkit::jsonERR(__('Not fill in the app information to activate.')));
					return '_result';
				}
				$resultval = 1;
			}
			$o_field = new ThirdAccount($obj['id']);
			$o_field->set(array('active'=>$resultval));
			$o_field->save();
//			$this->db->update('third_accounts',array('active'=>$resultval)," account_type='".mysql_escape_string($accounttype)."' ");
			$this->assign('json', Toolkit::jsonOK(array('activeval'=>$resultval)));
			return '_result';
		}
		$this->assign('json', Toolkit::jsonERR(__('Missing Account information!')));
		return '_result';
	}
	
	private function edit_third_account($accountinfo){
		$accounttype=$accountinfo['account_type'];
		$account=ThirdAccount::findAll2(" account_type='".mysql_escape_string($accounttype)."' ");
		if(empty($account)){
			unset($accountinfo['id']);
			$o_field = new ThirdAccount();
			$o_field->set($accountinfo);
			$o_field->save();
		}else{
			$o_field = new ThirdAccount($account[0]['id']);
			unset($accountinfo['id']);
			unset($accountinfo['account_type']);
			$o_field->set($accountinfo);
			$o_field->save();
		}
		
	}
}
?>
