<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModEmail extends Module {
    
    public function full_list() {
    	global $db;
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_bulletin/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_bulletin/_LOCALE', $lang_sw);
        
        $where = " user_id=?";
        $params = array(SessionHolder::get("user/id",0));
        $bulletin_data = &Pager::pageByObject('Email', $where, $params,' order by id desc');
	                						  
	    $this->assign('bulletins', $bulletin_data['data']);
	    $this->assign('pager', $bulletin_data['pager']);
	    $this->assign('page_mod', $bulletin_data['mod']);
		$this->assign('page_act', $bulletin_data['act']);
		$this->assign('page_extUrl', $bulletin_data['extUrl']);
	    $this->assign('langs', Toolkit::loadAllLangs());
	    $this->assign('lang_sw', $lang_sw);
    }
	
	public function detail() {
		
		$id = trim(ParamHolder::get('id', '0'));
    	if (intval($id) == 0) die(__('Invalid ID!'));
		$note = new Email($id);
		$note->is_read=1;
		$note->save();
		$this->assign('note', $note);
		
	}
	public function send_mail($title,$msg,$address){
		include_once(P_LIB.'/phpmailer/class.phpmailer.php');
		$user_name = 'www123';
		$mail = new PHPMailer();
		$mail->SetLanguage("en", P_LIB.'/phpmailer/language/');
		$mail->IsSMTP();
		//$mail->SMTPSecure = "ssl";
		$mail->Host = SMTP_SERVER;
		$mail->IsHTML(true);
		$mail->SMTPAuth = true;
		$mail->Username = SMTP_USER;
		$mail->Password = Toolkit::baseDecode(SMTP_PASS);
		$mail->CharSet = "UTF-8";
		$mail->Encoding = "8bit";
		$mail->From = utf8_decode(SMTP_USER);
		$mail->FromName = utf8_decode(SMTP_USER);
		$mail->AddAddress($address, $user_name);
		$mail->WordWrap = 80;
		$mail->Subject = $title;
		$mail->Body = $msg;
		if(!$mail->Send()){
			$error = $mail->ErrorInfo."\n";
			return false;
		}else{
			return true;
		}
	}

   public function do_mail(){
   		global $db;
    	$title = ParamHolder::get("title");
    	$msg = ParamHolder::get("email_s");
    	$msg .= ParamHolder::get("email_m");
    	$roles = ParamHolder::get("role");
    	$type = ParamHolder::get("type");
    	$user_email = ParamHolder::get('users'); 
    	$send_id = SessionHolder::get("user/id");
    	$time = time();
    	$ok = 0;
    	if (strstr($msg,'<img')) {
    		$str = $_SERVER['HTTP_HOST'].":".$_SERVER['SERVER_PORT'];
			
			$msg = str_replace('<img src="','<img src="http://'.$str,$msg);
    	}
    	if (empty($title)) {
    		echo "<script>alert('".__("Title not empty")."');history.go(-1);</script>";
    		exit;
    	}
    	if (empty($msg)) {
    		echo "<script>alert('".__("Send conten not empty")."');history.go(-1);</script>";
    		exit;
    	}
    	
		if (!empty($type) && $type=='single') {//单个邮件发送
			if (empty($user_email)) {
				 echo "<script>alert('".__("Enter Username please")."');history.go(-1);</script>";
                exit;
			}
			if (strstr($user_email,'|')) {//如果是多个用户
				$users = explode('|',$user_email);
				foreach ($users as $u){
					$sql = "select id,email,login from ".Config::$tbl_prefix."users where login='".$u."'";
			    	$res = $db->query($sql);
			    	$eml = $res->fetchRow();
			    	if (!empty($eml)) {
			    		if ($this->send_mail($title,$msg,$eml['email'])) {
			    			$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}','{$eml['id']}','{$eml['login']}',1,{$send_id},0,1,'{$time}')";
	    					$db->query($sql);
			    			$ok++;
			    		}else{
			    			$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}','{$eml['id']}','{$u}',1,{$send_id},0,0,'{$time}')";
	    					$db->query($sql);
					    	$s_err[] = $u;
					    }
			    	}else{
			    		if(empty($u)) continue;
			    		$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}',0,'{$u}',1,{$send_id},0,0,'{$time}')";
    					$db->query($sql);
			    		$s_err[] = $u;
			    	}
	    		}
	    		$err_count = count($s_err);
	    		$e_eml = implode(',',$s_err);
				if ($err_count<=0) {//对出錯的进行处理
					echo "<script>alert('".__("Mail sended!")."');history.go(-1);</script>";
		    		exit;
				}
			}else{//单个用户发送
				$sql = "select id,login,email from ".Config::$tbl_prefix."users where login='".$user_email."'";
			    $res = $db->query($sql);
			    $eml = $res->fetchRow();
			    if (!empty($eml)) {
				    if($this->send_mail($title,$msg,$eml['email'])){
				    	$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}','{$eml['id']}','{$eml['login']}',1,{$send_id},0,1,'{$time}')";
	    				$db->query($sql);
				    	 echo "<script>alert('".__("Mail sended!")."');history.go(-1);</script>";
	    				exit;
				    }else{
				    	$s_err[] = $eml['login'];
				    	$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}',0,'{$user_email}',1,{$send_id},0,0,'{$time}')";
	    				$db->query($sql);
				    }
			    }else{
			    	echo "<script>alert('".__("User does not exist!")."');history.go(-1);</script>";
			    	exit;
			    }
			    $err_count = count($s_err);
				$e_eml = implode(',',$s_err);
			}
		}else{//邮件群发
			foreach ($roles as $k=>$row){
				$sql = "select id,login,email from ".Config::$tbl_prefix."users where s_role='{".$row."}'";
			    $res = $db->query($sql);
			    $emails = $res->fetchRows();
				if (!empty($emails)) {
	    			foreach ($emails as $eml){
				        if($this->send_mail($title,$msg,$eml['email'])){
				        	$ok++;
				        	$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}','{$eml['id']}','{$eml['login']}',1,{$send_id},0,1,'{$time}')";
	    					$db->query($sql);
				        }else{
				        	$sql = "insert into ".Config::$tbl_prefix."emails(`title`,`content`,user_id,user_name,is_mail,send_id,is_read,is_ok,create_time) values('{$title}','{$msg}','{$eml['id']}','{$eml['login']}',1,{$send_id},0,0,'{$time}')";
	    					$db->query($sql);
				        	$s_err[] = $eml['login'];
				        }
	    			}
	    		}else{
	    			
	    			$sql = "select `desc` from ".Config::$tbl_prefix."roles where `name`='".$row."'";
				    $res = $db->query($sql);
				    $desc = $res->fetchRow();
	    			$s_err[] = $desc['desc'];
	    			$e_str = __("Currently no register");
	    		}
	    	}
	    	$err_count = count($s_err);
			$e_eml = implode(',',$s_err);
			if ($err_count<=0) {//对出錯的进行处理
				echo "<script>alert('".__("Mail sended!")."');history.go(-1);</script>";
	    		exit;
			}
		}
		$this->assign("ok",$ok);
		$this->assign("err_count",$err_count);
		$this->assign("e_eml",$e_eml);
		$this->assign("e_str",$e_str);
    }
}
?>