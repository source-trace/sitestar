<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModEmail extends Module {

	protected $_filters = array(
        'check_admin' => ''
    );
    
    public function admin_list() {
        $this->_layout = 'content';
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_bulletin/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_bulletin/_LOCALE', $lang_sw);
        $roles = Toolkit::loadAllRoles(array('guest','admin'));


        $this->assign('roles',$roles);
    }
    
    public function email_list() {
        $this->_layout = 'content';
        $t = ParamHolder::get('t');
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_bulletin/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_bulletin/_LOCALE', $lang_sw);
        $roles = Toolkit::loadAllRoles(array('guest','admin'));
        
        $this->assign('roles',$roles);
        $this->assign('t',$t);
    }
    
    public function note_list(){
    	global $db;
    	$this->_layout = 'content';
    	$where = " is_mail=?";
    	$param = array(0);
    	$sql = "select * from ".Config::$tbl_prefix."emails where is_mail=0 group by title";
    	$res = $db->query($sql);
    	$n = $res->fetchRows();
    	$count = count($n);
    	$sql = "select * from ".Config::$tbl_prefix."emails where is_mail=0";
    	$res = $db->query($sql);
    	$u = $res->fetchRows();

    	$notes_data = &Pager::pageByObject('Email', $where, $param, 'group by title ORDER BY `id` DESC','p','',$count);

	    $this->assign('notes', $notes_data['data']);
	    $this->assign('users', $u);
	    $this->assign('pager', $notes_data['pager']);
	    $this->assign('page_mod', $notes_data['mod']);
		$this->assign('page_act', $notes_data['act']);
		$this->assign('page_extUrl', $notes_data['extUrl']);
    }
    
    public function send_list(){
    	global $db;
    	$this->_layout = 'content';
    	$where = " is_mail=?";
    	$param = array(1);
    	$sql = "select * from ".Config::$tbl_prefix."emails where is_mail=1 group by title";
    	$res = $db->query($sql);
    	$n = $res->fetchRows();
    	$count = count($n);
    	$sql = "select * from ".Config::$tbl_prefix."emails where is_mail=1";
    	$res = $db->query($sql);
    	$u = $res->fetchRows();
    	$notes_data = &Pager::pageByObject('Email', $where, $param, 'group by title ORDER BY `id` DESC','p','',$count);

	    $this->assign('notes', $notes_data['data']);
	     $this->assign('users', $u);
	    $this->assign('pager', $notes_data['pager']);
	    $this->assign('page_mod', $notes_data['mod']);
		$this->assign('page_act', $notes_data['act']);
		$this->assign('page_extUrl', $notes_data['extUrl']);
    }
    
    public function detail() {
		$this->_layout = 'content';
		$id = trim(ParamHolder::get('id', '0'));
		$type_get = ParamHolder::get('type');
    	if (intval($id) == 0) die(__('Invalid ID!'));
		$note = new Email($id);
		$this->assign('note', $note);
		$this->assign('type_get', $type_get);
	}
	
    public function do_note(){
    	global $db;
    	$send_id = SessionHolder::get("user/id");
    	$roles = ParamHolder::get("role");
    	$title = ParamHolder::get("title");
    	$msg = ParamHolder::get("message");
    	$time = time();
    	if (empty($roles)) {
    		echo "<script>alert('".__("Choose you want sent member,please")."');history.go(-1);</script>";
    		exit;
    	}
    	if (empty($title)) {
    		echo "<script>alert('".__("Title not empty")."');history.go(-1);</script>";
    		exit;
    	}
    	if (empty($msg)) {
    		echo "<script>alert('".__("Send conten not empty")."');history.go(-1);</script>";
    		exit;
    	}
    	foreach ($roles as $k=>$row){
    		$sql = "select id,login from ".Config::$tbl_prefix."users where s_role='{".$row."}'";
    		$res = $db->query($sql);
    		$ids = $res->fetchRows();
    		if (!empty($ids)) {
    			foreach ($ids as $id){
    				$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}','{$id['id']}','{$id['login']}',0,{$send_id},0,1,'{$time}')";
    				$db->query($sql);
    			}
    		}
    	}
    	echo "<script>alert('".__("Sended")."');history.go(-1);</script>";
    	exit;
    }
    
    public function do_note_single(){
    	global $db;
    	$this->_layout = 'content';
    	$send_id = SessionHolder::get("user/id");
    	$user = ParamHolder::get("user");
    	$title = ParamHolder::get("title");
    	$msg = ParamHolder::get("message2");
    	$time = time();
    	$ok = 0;
    	if (empty($title)) {
    		echo "<script>alert('".__("Title not empty")."');history.go(-1);</script>";
    		exit;
    	}
    	if (empty($msg)) {
    		echo "<script>alert('".__("Send conten not empty")."');history.go(-1);</script>";
    		exit;
    	}
    	if (strstr($user,'|')) {//如果是多个用户
    		$users = explode('|',$user);
    		foreach ($users as $u){
    			$sql = "select id,login from ".Config::$tbl_prefix."users where login='".$u."'";
		    	$res = $db->query($sql);
		    	$id = $res->fetchRow();
		    	if (!empty($id)) {
		    		$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}','{$id['id']}','{$id['login']}',0,{$send_id},0,1,'{$time}')";
		    		$db->query($sql);
		    		$ok++;
		    		
		    	}else{
		    		if(empty($u)) continue;
		    		$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}',0,'{$u}',0,{$send_id},0,0,'{$time}')";
	    			$db->query($sql);
		    		$s_err[] = $u;
		    	}
		    	
    		}
    		$err_count = count($s_err);
		    	if ($err_count>0) {
		    		$e_eml = implode(',',$s_err);
		    		$this->assign("ok",$ok);
					$this->assign("err_count",$err_count);
					$this->assign("e_eml",$e_eml);
		    		
		    	}else{
		    		echo "<script>alert('".__("Sended")."');history.go(-1);</script>";
		    		exit;
		    	}
    	}else{
	    	$sql = "select id,login from ".Config::$tbl_prefix."users where login='".$user."'";
	    	$res = $db->query($sql);
	    	$id = $res->fetchRow();
	    	if (!empty($id)) {
	    		$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}','{$id['id']}','{$id['login']}',0,{$send_id},0,1,'{$time}')";
	    		$db->query($sql);
	    		echo "<script>alert('".__("Sended")."');history.go(-1);</script>";
	    		exit;
	    	}else{
	    		$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}',0,'{$user}',0,{$send_id},0,0,'{$time}')";
	    		$db->query($sql);
	    		echo "<script>alert('".__("User does not exist!")."');history.go(-1);</script>";
	    		exit;
	    	}
    	}
    }
}
?>