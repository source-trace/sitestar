<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModWizard extends Module {

	protected $_filters = array(
        'check_admin' => '{index}{dologin}'
    );
	
    public function admin_index() {
        $this->_layout = 'wizard';

		$direct = 'guid';
		$tag = ParamHolder::get('_t', 0);
		if ( $tag ) $direct .= $tag;
		
		$this->assign( 'tag', $tag );
		return $direct;
    }
    
    public function admin_wizard() {
    	$action = ParamHolder::get('action', array());
    	$uid = ParamHolder::get('uid', array());
    	if($action == 'wizard') {
    		$o_user = new User($uid);
    		try{
	    		if($o_user->wizard == 1) {
	    			$o_user->set(array('wizard' => '0'));
	    		} elseif($o_user->wizard == 0) {
	    			$o_user->set(array('wizard' => '1'));
	    		}
	    		$o_user->save();
    		} catch(Exception $ex) {
    			die("Processing Error!");
    		}
    	} else {
    		die("Missing Information!");
    	}
    }
}
?>