<?php

if (!defined('IN_CONTEXT')) die('access violation error!');

class CheckAdmin
{
	
    public function execute() 
    {
//    	 if (!ACL::requireRoles(array('admin'))) 
//    	 {	
//			Content::redirect(Html::uriquery('frontpage', 'index'));
//            die('No Permission!');
//         }
	if(!ACL::isAdminActionHasPermission()){
	      if (!ACL::requireRoles(array('admin'))) {
		Content::redirect(Html::uriquery('frontpage', 'index'));
	       }
                 die('No Permission!');	
	}
        return true;
    }
}
?>
