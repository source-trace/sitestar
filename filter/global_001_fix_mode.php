<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class FixMode {
    public function execute() {
        if (ACL::isRoleAdmin()) {
        	$per = Role::getRolePermission(SessionHolder::get("user/s_role"));
        	if (SessionHolder::get("user/s_role")=='{admin}') {
        		SessionHolder::set('page/status', 'edit');
        	}elseif(Role::isActionPermission("mod_all_web","web",$per)){
        		SessionHolder::set('page/status', 'edit');
        	}else{
        		SessionHolder::set('page/status', 'view');
        	}
            
        } else {
            SessionHolder::set('page/status', 'view');
        }
    }
}
?>
