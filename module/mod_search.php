<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModSearch extends Module {
	protected $_filters = array(
        'check_login' => '{show_search}'
    );
    
    public function show_search(){
    	
    }
}