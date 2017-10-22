<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class ModCounter extends Module {
	    protected $_filters = array(
        'check_login' => '{counter}'
    );

	public function counter() {
		$this->_layout = 'frontpage';
		$counter_title = trim(ParamHolder::get('counter_title', ''));
		$this->assign('counter_title', $counter_title);
	}
}
?>