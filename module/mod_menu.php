<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModMenu extends Module {
    protected $_filters = array(
        'check_login' => '{leftmenu}{topmenu}'
    );
    
    public function leftmenu() {
        $curr_locale = trim(SessionHolder::get('_LOCALE', DEFAULT_LOCALE));
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        
        $curr_menu = trim(ParamHolder::get('menuid'));
        
        if (ACL::requireRoles(array('admin'))) {
        	$all_menus =& MenuItem::listMenuItems(0, "published='1' AND s_locale=? and menu_id=?", 
        		array($curr_locale, 0));
        } else {
        	$all_menus =& MenuItem::listMenuItems(0, 
				"published='1' AND for_roles LIKE ? AND s_locale=? and menu_id=?", 
				array('%'.$user_role.'%', $curr_locale, 0));
        }
        $this->assign('menus', $all_menus);
    }
    
    public function topmenu() {
        $curr_locale = trim(SessionHolder::get('_LOCALE', DEFAULT_LOCALE));
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        
        $curr_menu = trim(ParamHolder::get('menuid'));
        
        if (ACL::requireRoles(array('admin'))) {
        	$all_menus =& MenuItem::listMenuItems(0, 
				"s_locale=? AND menu_id=?", 
				array($curr_locale, 0));
        } else {
        	$all_menus =& MenuItem::listMenuItems(0, 
				"for_roles LIKE ? AND s_locale=? AND menu_id=?", 
				array('%'.$user_role.'%', $curr_locale, 0));
        }

        $this->assign('menus', $all_menus);
    }
}
?>