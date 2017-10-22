<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModQq extends Module {
    
	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
    	$this->_layout = 'content';
    			 
		$where = "s_locale = '".SessionHolder::get('_LOCALE')."' order by i_order desc";

        $o_qq = new OnlineQq();
        $qqs =& $o_qq->findAll($where);
        $this->assign('qqs', $qqs);

        $this->assign('content_title', __('service'));
        $this->assign('next_action', 'admin_service_create');
        $this->assign('roles', Toolkit::loadAllRoles());
        $o_param = new Parameter();
        $s_param =& $o_param->find("`key`='SERVICE53'");
        $this->assign('service', $s_param->val);
      
		if(QQ_ONLINE_TITLE){
			$QQ_ONLINE_TITLE_temp=unserialize(QQ_ONLINE_TITLE);
		}else{
			$QQ_ONLINE_TITLE_temp=array();
		}
		if (isset($QQ_ONLINE_TITLE_temp[SessionHolder::get('_LOCALE')]) ){
			$this->assign('qqstitle', $QQ_ONLINE_TITLE_temp[SessionHolder::get('_LOCALE')]);
		 }else{
			$this->assign('qqstitle', '');
		 }
        
        // for sitestarv1.3 online customer service
        $site_param =& ParamHolder::get('sparam', array());
        $str_temp = trim(ParamHolder::get('hidqq_online', ''));
		 $tjok = trim(ParamHolder::get('tjok', ''));
		 if($tjok=='1'){
			if (isset($site_param['QQ_ONLINE']) || !empty($str_temp)) {
				$site_param['QQ_ONLINE'] = !empty($str_temp) ? '0' : $site_param['QQ_ONLINE'];
			}
			if (!isset($site_param['QQ_ONLINE_SHOW'])) {
				$site_param['QQ_ONLINE_SHOW'] = 0;
			}
				$o_param = new Parameter();				
				foreach ($site_param as $key => $val) {
					
					$param =& $o_param->find('`key`=?', array($key));
					if ($param) {
						if($key=='QQ_ONLINE_TITLE'){
							$arrtemp=array();
							$arrtemp=$QQ_ONLINE_TITLE_temp;
							$arrtemp[SessionHolder::get('_LOCALE')]=$val;
							$val=serialize($arrtemp); 
						}
						if ($key=='QQ_ONLINE_SHOW') {
							$show_tag = 1;
						}
						$param->val = $val;
						$param->save();
					}
				}
				if (!$show_tag) {//第一次插入浮动窗口总是显示值
					$o_param->key = 'QQ_ONLINE_SHOW';
					$o_param->val = $site_param['QQ_ONLINE_SHOW'];
					$o_param->save();
				}
				@header('Location: '.Html::uriquery('mod_qq','admin_list'));
		
		}
    }
    
    public function admin_add() {
    	$this->_layout = 'content';
    	
		$select_categories = array('QQ','MSN', __('WangWang'),'Skype','ICQ','Yahoo!');
        $this->assign('select_categories', $select_categories);
        $this->assign('content_title', __('New IM Account'));
        $this->assign('next_action', 'admin_create');
        
        return '_form';
    }
    
    public function admin_create() {
        
        $qq_info =& ParamHolder::get('qq', array());
        if (sizeof($qq_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing Account information!')));
            return '_result';
        }
        try {

            $qq_info['published'] = '1';
            // Data operation
			$currentlanguage=SessionHolder::get('_LOCALE');
			$qq_info['s_locale'] = trim($currentlanguage);
	
            $o_qq = new OnlineQq();
            $o_qq->set($qq_info);
            $o_qq->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_qq', 'admin_list'))));
        return '_result';
    }
    
    public function admin_edit() {
    	$this->_layout = 'content';
    	
        $q_id = ParamHolder::get('q_id', '0');
		$q_id = intval($q_id);
        if ($q_id == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        
        try {
			$currentlanguage=SessionHolder::get('_LOCALE');
            $curr_qq = new OnlineQq($q_id);
		

			
            $this->assign('curr_qq', $curr_qq);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
        $select_categories = array('QQ','MSN', __('WangWang'),'Skype','ICQ','Yahoo!');
        $this->assign('select_categories', $select_categories);
        $this->assign('content_title', __('Edit IM Account'));
        $this->assign('next_action', 'admin_update');
        
        return '_form';
    }
    
    public function admin_update() {
        
        $qq_info =& ParamHolder::get('qq', array());
        if (sizeof($qq_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing Account information!')));
            return '_result';
        }
        try {
        	// Re-arrange publish status
//            if ($qq_info['published'] == '1') {
//                $qq_info['published'] = '1';
//            } else {
//                $qq_info['published'] = '0';
//            }
            
            // Data operation
            $o_qq = new OnlineQq($qq_info['id']);
			
			$currentlanguage=SessionHolder::get('_LOCALE');
			if($currentlanguage){ $qq_info['s_locale'] = $currentlanguage;}
			
            $o_qq->set($qq_info);
            $o_qq->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_qq', 'admin_list'))));
        return '_result';
    }
    
    public function admin_delete() {
        
        $q_id = trim(ParamHolder::get('q_id', '0'));
        if (intval($q_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			$tmp_arr = explode('_', $q_id);
			$len = sizeof($tmp_arr);
			for ($i = 0; $i< $len; $i++){
				$curr_qq = new OnlineQq($tmp_arr[$i]);
				$curr_qq->delete();
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_order() {

		$order_info =& ParamHolder::get('i_order', array());
		if (!is_array($order_info)) {
            $this->assign('json', Toolkit::jsonERR(__('Missing product order information!')));
            return '_result';
        }
		try {
			foreach($order_info as $key => $val) {
				$qq['i_order'] = $val;
				$o_qq = new OnlineQq($key);
				$o_qq->set($qq);
				$o_qq->save();
			}
		} catch (Exception $ex) {
			$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		Content::redirect(Html::uriquery('mod_qq', 'admin_list'));
	}
    
	public function admin_pic()
    {
    	$qq_info = array();
    	$qq_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($qq_id))
    	{
    		$o_qq = new OnlineQq($qq_id);
            if($o_qq->published == 1)
            {
            	$qq_info['published'] = '0';
            	$o_qq->set($qq_info);
            	$o_qq->save();
				die('0');
            }
            elseif($o_qq->published == 0)
            {
            	$qq_info['published'] = '1';
            	$o_qq->set($qq_info);
            	$o_qq->save();
				die('1');
            }
    	}
    }
	/*
	public function admin_order() {
		$order_info =& ParamHolder::get('i_order', array());
		if (!is_array($order_info)) {
            $this->assign('json', Toolkit::jsonERR(__('Missing article order information!')));
            return '_result';
        }
		try {
			foreach($order_info as $key => $val) {
				$article_info['i_order'] = $val;
				$o_article = new OnlineQq($key);
				$o_article->set($article_info);
				$o_article->save();
			}
		} catch (Exception $ex) {
			$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		$this->assign('json', Toolkit::jsonOK());
        return '_result';
	}
	*/
	
	
}
?>
