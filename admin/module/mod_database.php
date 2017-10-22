<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModDatabase extends Module {
	
	protected $_filters = array(
        'check_admin' => ''
    );
    
    public function admin_list() {
    	$this->_layout = 'content';
    }
    
    public function backup() {
    	//back-up database
    	$site_param =& ParamHolder::get('sparam', array());
    	try{
	    	if(isset($site_param['SITE_BACKUP'])) {
	    		$user = Config::$db_user;
	    		$password = Config::$db_pass;
	    		$db_name = Config::$db_name;
	
	    		$current_time = date("YmdHis");
	    		$random = rand(100,999);
	
	    		$file_name = "../sql/backup/backup_$current_time"."_rand$random.sql";
	    		$command = ROOT."/sql/mysqldump -u$user -p$password --database $db_name>$file_name";
	    		system($command);
	    	} else {
	    		$this->assign('json', Toolkit::jsonERR(__('Missing site parameters!')));
            	return '_result';
	    	}
    	} catch(Exception $ex) {
    		$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
    	}
    	
    	$this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function import() {
    	$site_param =& ParamHolder::get('import', array());
    	if(isset($site_param['SITE_IMPORT'])) {
    		$file_name = '../sql/backup/backup_20100226172357_rand248.sql';
    		$command = "source $file_name";
    		system($command);
    	} else {
    		$this->assign('json', Toolkit::jsonERR(__('Missing site parameters!')));
            return '_result';
    	}
    	$this->assign('json', Toolkit::jsonOK());
    	return '_result';
    }
}
?>