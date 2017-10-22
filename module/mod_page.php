<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModPage extends Module {

	protected $_filters = array(
        'check_login' => '{show_page}'
    );
	public function show_page(){
		$this->_layout = 'frontpage';
		
	}
}
?>