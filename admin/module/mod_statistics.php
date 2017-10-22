<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModStatistics extends Module {
    protected $_filters = array(
        'check_admin' => ''
    );
    
    public function admin_list() {
    	$this->_layout = 'content';
    }
    
    public function admin_update() {
        
        $site_param =& ParamHolder::get('sparam', array());
        if (sizeof($site_param) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing site parameters!')));
            return '_result';
        }
		if (!isset($site_param['SITE_COUNTER'])) {
            $site_param['SITE_COUNTER'] = '0';
        }
        
        try {
        	$o_param = new Parameter();
        	foreach ($site_param as $key => $val) {
        	    $param =& $o_param->find('`key`=?', array($key));
        	    if ($param) {
        	        $param->val = $val;
        	        $param->save();
        	    }
        	}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
}
?>
