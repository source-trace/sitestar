<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModBulletin extends Module {

	protected $_filters = array(
        'check_admin' => ''
    );
    
    public function admin_list() {
        $this->_layout = 'content';
        
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_bulletin/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_bulletin/_LOCALE', $lang_sw);
        
        $where = "s_locale=?";
        $params = array($lang_sw);
        
        $bulletin_data = &Pager::pageByObject('Bulletin', $where, $params,
	                						  'ORDER BY `create_time` DESC');
	    $this->assign('bulletins', $bulletin_data['data']);
	    $this->assign('pager', $bulletin_data['pager']);
	    $this->assign('page_mod', $bulletin_data['mod']);
		$this->assign('page_act', $bulletin_data['act']);
		$this->assign('page_extUrl', $bulletin_data['extUrl']);
	    $this->assign('langs', Toolkit::loadAllLangs());
	    $this->assign('lang_sw', $lang_sw);
    }
	
	public function admin_detail() {
		$this->_layout = 'content';
		
		$bulletin_id = trim(ParamHolder::get('bulletin_id', '0'));
    	if (intval($bulletin_id) == 0) die(__('Invalid ID!'));
		$curr_bulletin = new Bulletin($bulletin_id);
		$this->assign('curr_bulletin', $curr_bulletin);
		
		return '_detail';
	}

    public function admin_add() {
        $this->_layout = 'content';
        
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_bulletin/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        SessionHolder::set('mod_bulletin/_LOCALE', $lang_sw);

		$bulletin_id = ParamHolder::get('bulletin_id', '0');
        if (!empty($bulletin_id)) {
        	$next_action = 'admin_update';
        	
        	try {
        		$curr_bulletin = new Bulletin($bulletin_id);
        	} catch (Exception $ex) {
	            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
	            return '_error';
	        }
        } else {
        	$curr_bulletin = array();
        	$curr_bulletin = (object)$curr_bulletin;
        	$curr_bulletin->s_locale = $lang_sw;
        	$next_action = 'admin_create';
        }

        $this->assign('curr_bulletin', $curr_bulletin);
        $this->assign('next_action', $next_action);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        
        return '_form';
    }
    
    public function admin_edit() {
        $this->_layout = 'content';
        
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_bulletin/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        SessionHolder::set('mod_bulletin/_LOCALE', $lang_sw);

		$bulletin_id = ParamHolder::get('bulletin_id', '0');
        if (!empty($bulletin_id)) {
        	$next_action = 'admin_update';
        	
        	try {
        		$curr_bulletin = new Bulletin($bulletin_id);
        	} catch (Exception $ex) {
	            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
	            return '_error';
	        }
        } else {
        	$curr_bulletin = array();
        	$curr_bulletin = (object)$curr_bulletin;
        	$curr_bulletin->s_locale = $lang_sw;
        	$next_action = 'admin_create';
        }

        $this->assign('curr_bulletin', $curr_bulletin);
        $this->assign('next_action', $next_action);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('language_info',$mod_locale);
        
        return '_form';
    }
    
    public function admin_create() {

        $bulletin_info =& ParamHolder::get('bulletin', array());
        if (sizeof($bulletin_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing bulletin information!')));
            return '_result';
        }

        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
            $bulletin_info['pub_start_time'] = -1;
            $bulletin_info['pub_end_time'] = -1;

			$bulletin_info['published'] = '1';
            $bulletin_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            $bulletin_info['title'] = $bulletin_info['title'];
            $bulletin_info['content'] = $bulletin_info['content'];
            $bulletin_info['create_time'] = $_SERVER['REQUEST_TIME'];

            // Data operation
            $o_bulletin = new Bulletin();
            $o_bulletin->set($bulletin_info);
            $o_bulletin->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_bulletin', 'admin_list'))));
        return '_result';
    }
        
    public function admin_update() {

        $bulletin_info =& ParamHolder::get('bulletin', array());
        if (sizeof($bulletin_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing bulletin information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
            $bulletin_info['pub_start_time'] = -1;
			$bulletin_info['pub_end_time'] = -1;

            $bulletin_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            $bulletin_info['title'] = $bulletin_info['title'];
            $bulletin_info['content'] = $bulletin_info['content'];
			$bulletin_info['create_time'] = $_SERVER['REQUEST_TIME'];

            // Data operation
            $o_bulletin = new Bulletin($bulletin_info['id']);
            $pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
			$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
			$bulletin_info['content'] = str_replace($path,"",$bulletin_info['content']);
            $o_bulletin->set($bulletin_info);
            $o_bulletin->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_bulletin', 'admin_list'))));
        return '_result';
    }
    
    public function admin_pic()
    {
    	$bulletin_info = array();
    	$bulletin_id = trim(ParamHolder::get('_id', ''));
    	if (!empty($bulletin_id)) {
    		$o_bulletin = new Bulletin($bulletin_id);
            if($o_bulletin->published == 1) {
            	$bulletin_info['published'] = '0';
            	$o_bulletin->set($bulletin_info);
            	$o_bulletin->save();
				die('0');
            } else if ($o_bulletin->published == 0) {
            	$bulletin_info['published'] = '1';
            	$o_bulletin->set($bulletin_info);
            	$o_bulletin->save();
				die('1');
            }
    	}
    }
    
    /*public function admin_published() {
    	$bulletin_id = trim(ParamHolder::get('bulletin_id', ''));
    	if (intval($bulletin_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        // 
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_bulletin/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        SessionHolder::set('mod_bulletin/_LOCALE', $lang_sw);
        
        $where = "s_locale=?";
        $params = array($lang_sw);
        
        $o_bulletin = new Bulletin();
        $bulletin_data = $o_bulletin->findAll($where, $params);
        if (sizeof($bulletin_data)) {
        	try {
	        	foreach($bulletin_data as $bulletin) {
	        		$o_temp = new Bulletin($bulletin->id);
	        		$bulletin_info['published'] = ($bulletin->id == $bulletin_id) ? '1' : '0';
	        		$o_temp->set($bulletin_info);
	            	$o_temp->save();
	        	}
	        	
	        	$this->assign('json', Toolkit::jsonOK());
        		return '_result';
        	} catch (Exception $ex) {
	            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
	            return '_result';
	        }
        }
    }*/
    
    public function admin_delete() {

        $bulletin_id = trim(ParamHolder::get('bulletin_id', '0'));
        if (intval($bulletin_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			if (strpos($bulletin_id, '_') > 0) {
				$tmp_arr = explode('_', $bulletin_id);
				$len = sizeof($tmp_arr) - 1;
				for ($i = 0; $i< $len; $i++){
					$curr_bulletin = new Bulletin($tmp_arr[$i]);
					$curr_bulletin->delete();
				}
			} else {
				$curr_bulletin = new Bulletin($bulletin_id);
				$curr_bulletin->delete();
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
}
?>