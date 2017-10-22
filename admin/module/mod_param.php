<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModParam extends Module {
    protected $_filters = array(
        'check_admin' => ''
    );
    
    public function admin_list() {
    	$this->_layout = 'content';
    }
    
    public function save_mail_server() {
        global $db;
    	$site_param = ParamHolder::get('sparam', array());
        if (sizeof($site_param) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing site parameters!')));
            return '_result';
        }
        if ($site_param['SMTP_SERVER']=='') {
        	echo "<script>alert('".__("SMTP server not empty")."');location.href='index.php?_m=mod_email&_a=email_list&t=s';</script>";
        	exit;
        }
        if ($site_param['SMTP_USER']=='') {
        	echo "<script>alert('".__("SMTP user not emtpy")."');location.href='index.php?_m=mod_email&_a=email_list&t=s';</script>";
        	exit;
        }
        if ($site_param['SMTP_PASS']=='') {
        	echo "<script>alert('".__("SMTP Password not emtpy")."');location.href='index.php?_m=mod_email&_a=email_list&t=s';</script>";
        	exit;
        }
        if (!strstr($site_param['SMTP_USER'],"@")) {
        	echo "<script>alert('".__("SMTP user Illegal")."');location.href='index.php?_m=mod_email&_a=email_list&t=s';</script>";
        	exit;
        }
        
	    $o_param = new Parameter();
	    foreach ($site_param as $key => $val) {
	    	
	        $param =& $o_param->find('`key`=?', array($key));
	        if ($key=='SMTP_PASS') {
	    		$val = Toolkit::baseEncode($val);
	    	}
	        if ($param) {
	            $param->val = $val;
	            $param->save();
	        }else{
	        	$sql = "insert into ".Config::$tbl_prefix."parameters(`key`,`val`) values('{$key}','{$val}')";
	        	$db->query($sql);
	        }
	    }
        echo "<script>alert('".__("Operate Success!")."');history.go(-1);</script>";
        exit;
    }
    
    public function server_info(){
    	$this->_layout = 'content';
    }
    public function save_param() {
        
        $site_param =& ParamHolder::get('sparam', array());
        if (sizeof($site_param) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing site parameters!')));
            return '_result';
        }
        if (!isset($site_param['AUTO_LOCALE'])) {
            $site_param['AUTO_LOCALE'] = '0';
        }
        if (!isset($site_param['SITE_OFFLINE'])) {
            $site_param['SITE_OFFLINE'] = '0';
        }
//		if (!isset($site_param['SITE_COUNTER'])) {
//            $site_param['SITE_COUNTER'] = '0';
//        }
		if (!isset($site_param['SITE_LOGIN_VCODE'])) {
            $site_param['SITE_LOGIN_VCODE'] = '0';
        }
    	if (!isset($site_param['USE_LANGUAGE'])) {
            $site_param['USE_LANGUAGE'] = SessionHolder::get('_LOCALE');
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
        	
        	//save language
        	//$curr_lang = new Language($site_param['USE_LANGUAGE']+1);
        	$curr_lang = new Language($site_param['USE_LANGUAGE']);
            $o_param = new Parameter();
            $locale_param =& $o_param->find("`key`='DEFAULT_LOCALE'");
            $locale_param->val = $curr_lang->locale;
            $locale_param->save();
            SessionHolder::set('_LOCALE', $curr_lang->locale);
            
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
}
?>
