<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Menu object
 * 
 */
class Menu extends RecordObject {
    protected $no_validate = array(
        'isEmpty' => array(
            array('name', 'Missing menu name!'), 
            array('s_locale', 'Missing locale!'),
            array('for_roles', 'Missing access property!')
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^(\{\w+\})+$/', 'for_roles', 'Invalid access property!')
        )
    );

    public function &getMenu() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        
        $o_menu = new Menu();
        if (ACL::requireRoles(array('admin'))) {
        	$all_menu = $o_menu->findAll("published='1' AND s_locale=?", array($curr_locale));
        } else {
        	$all_menu = $o_menu->findAll("published='1' AND for_roles LIKE ? AND s_locale=?", 
        		array('%'.$user_role.'%', $curr_locale));
        }
        $select_array = array();
        if(sizeof($all_menu) > 0) {
            for($i = 0; $i < sizeof($all_menu); $i++) {
                $select_array[$all_menu[$i]->id] = $all_menu[$i]->name;
            }
        }
        return $select_array;
    }
}
?>