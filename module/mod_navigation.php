<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class ModNavigation extends Module {
	protected $_filters = array(
        'check_login' => '{index}'
    );
    
	public function index() {
		$this->setVar('nav_path', DEFAULT_NAV);
	}

}
?>