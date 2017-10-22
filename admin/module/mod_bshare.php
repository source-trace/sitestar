<?php
if(!defined('IN_CONTEXT')) die('access violation error!');
class ModBshare extends Module {
	protected $_filters = array(
        'check_admin' => ''
    );
    
    
	public function admin_list(){
   		$this->_layout = 'content';
   		if (file_exists("../data/bshare.php")) {
   			//读取配置文件
   			$c_str = file_get_contents("../data/bshare.php");
   			$tmparr = unserialize($c_str);
   			$codeOrder = 'qqmb%2csinaminiblog%2csohubai%2cbaiduhi%2crenren%2cbgoogle';
   			$this->assign('uuid', $tmparr['uuid']);
			$this->assign('account', $tmparr['account']);
			$this->assign('codeOrder', $codeOrder);
   			return "setting";
   		}else{
   			return "checklogn";
   		}
   }
   
   public function create_bshare(){
   		$domain =& ParamHolder::get('bshare_domain', '');
    	$account =& ParamHolder::get('bshare_mail', '');
    	$password =& ParamHolder::get('bshare_pwd', '');
    	// checkEasy
    	$tmpdomain = $tmpmail = $tmpswd = $errmsg = '';
    	$tmpdomain = trim($domain);
    	$tmpmail = trim($account);
    	$tmpswd = trim($password);
    	if (empty($tmpdomain) || empty($tmpmail) || empty($tmpswd)
    		 || !preg_match('/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/',$tmpdomain)) {
    		 	 echo "<script>alert('".__("invalid-param")."');history.go(-1);</script>";
    	} else {
			$account = urlencode($account);
			$password = urlencode($password);
//			$domain = $_SERVER['HTTP_HOST'];
			// bShare API
			$target = "http://api.bshare.cn/analytics/reguuid.json?email={$account}&password={$password}&domain={$domain}&source=sitestar
";
			$json = $this->mycurl($target);
			if (!empty($json)) {
				$tmpmixed = json_decode($json,true);
				if (is_array($tmpmixed)) {
					$tmpmixed['domain'] = $domain;
					$tmpmixed['account'] = urldecode($account);
					$tmpmixed['password'] = urldecode($password);
				} else {
					$tmpmixed['domain'] = $domain;
					$tmpmixed['account'] = urldecode($account);
					$tmpmixed['password'] = urldecode($password);
					
				}
				//写入配置
				$str = serialize($tmpmixed);
				try {
					$filename = "../data/bshare.php";
					$file = fopen($filename, "w");      //以写模式打开文件
					fwrite($file, $str);      //写入
					fclose($file);        
			    } catch (Exception $ex) {
			        $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			        return '_result';
			    }
			}else{
				echo "<script>alert('".__("Service failure")."');history.go(-1);</script>";
			}
    	} 
    	Content::redirect(Html::uriquery("mod_bshare","admin_list"));
   }
   public function setting(){
   		$this->_layout = 'content';	
   }
   public function save(){
   		$a_code = ParamHolder::get("a_code");//文章
   		$p_code = ParamHolder::get("p_code");//产品
   		$g_code = ParamHolder::get("g_code");//全局
   		$o_par = new Parameter();
   		$o_pars = $o_par->findAll();
   		
   		foreach ($o_pars as $k=>$v){
   			if ($v->key=="A_BSHARE") {
   				$o_p = new Parameter($v->id);
   				$o_p->val=$a_code;
   				$o_p->save();
   				$is_a =1;
   				continue;
   			}
   			if ($v->key=="P_BSHARE") {
   				$o_p = new Parameter($v->id);
   				$o_p->val=$p_code;
   				$o_p->save();
   				$is_p =1;
   				continue;
   			}
   			if ($v->key=="BSHARE") {
   				$o_p = new Parameter($v->id);
   				$o_p->val=$g_code;
   				$o_p->save();
   				$is_g =1;
   				continue;
   			}
   		}
   		if ($is_a!=1) {
   			$o_par = new Parameter();
   			$o_par->key='A_BSHARE';
	   		$o_par->val=$a_code;
	   		$o_par->save();
   		}
   		if ($is_p!=1) {
   			$o_par = new Parameter();
   			$o_par->key='P_BSHARE';
	   		$o_par->val=$p_code;
	   		$o_par->save();
   		}
   		if ($is_g!=1) {
   			$o_par = new Parameter();
   			$o_par->key='BSHARE';
	   		$o_par->val=$g_code;
	   		$o_par->save();
   		}
   		die('<script type="text/javascript">alert("'.__("Operate success").'");parent.window.location.reload();</script>');
   }
   private function mycurl($url, $basic_auth = ''){
		$ch = curl_init();
		if($basic_auth) curl_setopt($ch, CURLOPT_USERPWD, $basic_auth);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
		$json = curl_exec($ch);
		curl_close($ch);
		return $json;
    }
	
   
	
    

}
?>