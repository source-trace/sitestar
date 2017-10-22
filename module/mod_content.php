<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModContent extends Module {

	protected $_filters = array(
        'check_login' => '{content}'
    );
    
	public function content()
	{
		
	}
}
?>