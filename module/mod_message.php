<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModMessage extends Module {
    protected $_filters = array(
        'check_login' => '{form}{messInsert}'
    );
    
	public function form() {
    	$this->_layout = 'layout';
		
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
        $message = new MenuItem();         
        $message_info = $message->find(" `mi_category`=? and s_locale=? ",array("message",$curr_locale)," order by id limit 1");
        $page_cat = $message_info->name;   
        $this->assign('page_cat', $page_cat);
        $fields=  MsgField::findAll2(" showinlist='1' "," order by i_order");
		$this->assign('user_fields', $fields);
		$message_token=Toolkit::token();
		SessionHolder::set('token/message', $message_token);
		$this->assign('token', $message_token);
		$this->assign('curr_locale', $curr_locale);
    	return 'form';
    }
    
	public function messInsert() {
		$mess_info =& ParamHolder::get('mess', array());
		$extend_info=& ParamHolder::get('extends', array());
		$token = ParamHolder::get("token",'0');
		if(SessionHolder::get('token/message')!=$token){
			die("access violation error!");
		}
		SessionHolder::set('token/message', '');
		try {
			if (isset($mess_info['username'])) {
				if (!preg_match('/^(?!_|\s\')[A-Za-z0-9_\x80-\xff\s\']+$/', $mess_info['username'])) {
					$this->assign('json', Toolkit::jsonERR(__('Invalid nickname!')));
					return '_result';
				} 
			}
			if (isset($mess_info['email'])) {
				// 电子邮件
				 if (!preg_match('/^[ _a-z0-9- ]+(\.[a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',$mess_info['email']))
				{
					$this->assign('json', Toolkit::jsonERR(__('Invalid email address!')));
					return '_result';
				}
			}
			if (isset($mess_info['tele'])) {
				// 电话
				if (!preg_match('/^[0-9\-]+$/', $mess_info['tele']) && SITE_LOGIN_VCODE) {
					$this->assign('json', Toolkit::jsonERR(__('Invalid telephone number!')));
					return '_result';
				}
			}
			// 验证码
			if (isset($mess_info['rand_rs'])&&!RandMath::checkResult($mess_info['rand_rs'])) {
	            $this->setVar('json', Toolkit::jsonERR(__('Sorry! Please have another try with the math!')));
	            return '_result';
			}else {
				$o_mess = new Message();
				$custom_fields=array();
				$fields=  MsgField::findAll2(" showinlist='1' "," order by i_order");
			    foreach($fields as $fieldinfo){
					$fieldname="field".$fieldinfo['id'];
					$fieldtype=$fieldinfo['field_type'];
					$propname=$fieldinfo['label'];
					
					$isrequired=$fieldinfo['required'];
					if($isrequired=='1' && ($fieldtype == 0 && (!isset($mess_info[$propname]) || MsgField::trim($mess_info[$propname])=='')	||	 $fieldtype != 0 &&(!isset($extend_info[$fieldname]) ||MsgField::trim($extend_info[$fieldname])==''))){
						$label=MsgField::getUserDefineLabel($fieldinfo); 
						$this->assign('json', Toolkit::jsonERR(__('The field cannot be empty!').":{$label}"));
						return '_result';
					}else if($fieldtype != 0){
						if(isset($extend_info[$fieldname]) && MsgField::trim($extend_info[$fieldname])!=''){
							 $custom_fields[$fieldname] =$extend_info[$fieldname];
						}
					}
				}
				$mess_info['param'] =json_encode($custom_fields);
		
				$mess_info['create_time'] = time();
				$o_mess->set($mess_info);
				$o_mess->save();
				$this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_message', 'form'))));
		 		return '_result';
			}
		} catch (Exception $ex) {
			$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		
	}
}
?>
