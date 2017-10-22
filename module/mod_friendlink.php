<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModFriendlink extends Module {
    protected $_filters = array(
        'check_login' => '{friendlink}{fullist}'
    );
    
    
    public function friendlink() {
        $this->_layout = 'frontpage';
        $list_size = trim(ParamHolder::get('friendlink_size'));
        $fl_type = trim(ParamHolder::get('fl_type'));
		if($fl_type != "1" && $fl_type != "2"){
			$fl_type = "1";
		}
        if (!is_numeric($list_size) || strlen($list_size) == 0) {
            $list_size = '5';
        }
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        $o_friendlink = new Friendlink();
        if (ACL::requireRoles(array('admin'))) {
	        $friendlinks = $o_friendlink->findAll("published='1' AND s_locale=? and fl_type=?", 
	        				array($curr_locale,$fl_type), 
	                        "ORDER BY i_order desc,`create_time` DESC LIMIT ".$list_size);
        } else {
	        $friendlinks = $o_friendlink->findAll("published='1' AND for_roles LIKE ? AND s_locale=? and fl_type=?", 
	        				array('%'.$user_role.'%', $curr_locale,$fl_type), 
	                        "ORDER BY i_order desc,`create_time` DESC LIMIT ".$list_size);
        }
        $this->assign('friendlinks', $friendlinks);
        $this->assign('fl_type', $fl_type);
    }
    
    public function fullist() {
        $this->_layout = 'frontpage';
        
        $this->assign('page_title', __('Friend Links'));
        
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        
        try {
            $o_friendlink = new Friendlink();
            if (ACL::requireRoles(array('admin'))) {
            	$fls = $o_friendlink->findAll("published='1' AND s_locale=?", 
            		array($curr_locale), 
            		"ORDER BY i_order desc,`create_time` DESC");
            } else {
            	$fls = $o_friendlink->findAll("published='1' AND for_roles LIKE ? AND s_locale=?", 
            		array('%'.$user_role.'%', $curr_locale), 
            		"ORDER BY i_order desc,`create_time` DESC");
            }
            
            $this->assign('fls', $fls);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

    }
    
}
?>