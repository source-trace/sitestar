<?php

class qq_auth extends oauth_class {
	public $redirect_url;
	public $open_id;
	
	public function __construct() {
		 $this->type='qq';
		 $this->nickcolname='nickname';
		 $this->redirect_url=$this->uriquery('mod_auth', 'auth_callback',array('type'=>$this->type));
		 $this->host="https://graph.qq.com/";
		 $this->getAccountAppInfo();
	}
	
	public function getAuthorizeURL(){
		$params = array();
		$redirecturl=$this->redirect_url;
		$params['client_id'] = $this->account['appid'];
		$params['redirect_uri'] = $redirecturl;
		$params['response_type'] = 'code';
		$params['state'] = 'website';
		return 'https://graph.qq.com/oauth2.0/authorize?' . http_build_query($params);
	}
	
	public function processCallback(){
		$code=$_REQUEST['code'];
		if(empty($code)){
			$this->return_val=0;
			return;
		}
		$token=$this->getAccessCode();
		if(!$token){
			return;
		}
		$this->processUserInfo();
	}
	
	public function getAccessCode(){
		$code=$_REQUEST['code'];
		$params = array();
		$params['client_id'] =$this->account['appid'];
		$params['client_secret'] = $this->account['appsecret'];
		$params['grant_type'] = 'authorization_code';
		$params['code'] = $code;
		$params['redirect_uri'] = $this->redirect_url;
		$response = $this->oAuthRequest('https://graph.qq.com/oauth2.0/token', 'POST', $params);
		if (strpos($response, "callback") !== false){
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error))
            {
				throw new OAuthException($msg->error_code,$msg->error_description);
            }
        }
		parse_str($response,$token);
		
		if ( is_array($token) && !isset($token['code']) ) {
			$this->access_token = $token['access_token'];
		} else {
			return false;
		}
		return $token;
		
	}
	
	public function oauth_set_parameter($parameters){
		if ( isset($this->access_token) && $this->access_token )
			$parameters = array_merge(array('access_token'=>$this->access_token),$parameters);
		$parameters = array_merge(array('oauth_consumer_key'=>$this->account['appid']),$parameters);
		return $parameters;
	}
	
	public function addURLFormat(&$url,&$parameters){
			if(!empty($this->format)) $parameters=array_merge(array('format'=>$this->format),$parameters);
	}
	
	public function processUserInfo(){
		$access_token=$this->access_token;
		$this->format='';
		$resp=$this->get( 'https://graph.qq.com/oauth2.0/me',array('access_token'=>$access_token));
		$this->format='json';
		if (strpos($resp, "callback") !== false)
		{
				$lpos = strpos($resp, "(");
				$rpos = strrpos($resp, ")");
				$resp  = json_decode(substr($resp, $lpos + 1, $rpos - $lpos -1),true);
		}
		if(isset($resp['openid'])){
			$auth_key=$resp['openid'];
			$isexists=$this->checkUserExist($this->type,$auth_key);
			if($isexists){
				$this->return_val=1;
				return;
			}
			$userinfo=$this->get( 'user/get_user_info',array('openid'=>$auth_key));
//			$userinfo=array();
			SessionHolder::set ('open_auth_type',$this->type);
			SessionHolder::set ('open_auth_user',array_merge($userinfo,array('the_auth_key'=>$auth_key)));
			$this->return_val=2;
		}
	}
	
	public function autoGenLoginName(){
		$randstr=substr("".time(),-4).Toolkit::randomStr();
		$login="QQ_".$randstr;
		return $login;
	}
	
	public function generateUserField(){
		$auth_user=SessionHolder::get ('open_auth_user');
		$userrelation=array('nickname'=>'nickname');
		$userinfo=array();
		$randstr=substr("".time(),-4).Toolkit::randomStr();
		$userinfo['login']=$this->autoGenLoginName();
		foreach($userrelation as $key=>$val){
			if(!empty($auth_user[$key])){
				$userinfo[$val]=$auth_user[$key];
			}
		}
		return $userinfo;
	}
}

?>
