<?php

class oauth_class {
	/**
	 * @ignore
	 */
	public $access_token;
	/**
	 * Contains the last HTTP status code returned. 
	 *
	 * @ignore
	 */
	public $http_code;
	/**
	 * Contains the last API call.
	 *
	 * @ignore
	 */
	public $url;
	/**
	 * Set up the API root URL.
	 *
	 * @ignore
	 */
	public $host = "";
	/**
	 * Set timeout default.
	 *
	 * @ignore
	 */
	public $timeout = 30;
	/**
	 * Set connect timeout.
	 *
	 * @ignore
	 */
	public $connecttimeout = 30;
	/**
	 * Verify SSL Cert.
	 *
	 * @ignore
	 */
	public $ssl_verifypeer = FALSE;
	/**
	 * Respons format.
	 *
	 * @ignore
	 */
	public $format = 'json';
	/**
	 * Decode returned json data.
	 *
	 * @ignore
	 */
	public $decode_json = TRUE;
	/**
	 * Contains the last HTTP headers returned.
	 *
	 * @ignore
	 */
	public $http_info;
	/**
	 * Set the useragnet.
	 *
	 * @ignore
	 */
	public $useragent = '';

	/**
	 * print the debug info
	 *
	 * @ignore
	 */
	public $debug = FALSE;
	
	/**
	 * 1:already binding  2: not binding 3:show error message    other: do nothing
	 *
	 * @ignore
	 */
	public $return_val = 1;
	
	public $nickcolname = 'nickname';
	/**
	 * Account name e.g: sina, qq, facebook
	 *
	 * @ignore
	 */
	public $type = '';
	
	public $account;
	/**
	 * boundary of multipart
	 * @ignore
	 */
	public static $boundary = '';
	/**
	 * Format and sign an OAuth / API request
	 *
	 * @return string
	 * @ignore
	 */
	function oAuthRequest($url, $method, $parameters, $multi = false) {

		if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) {
			$url = "{$this->host}{$url}";
			if(!empty($this->format)) $this->addURLFormat($url,$parameters);
	     }
		
		 $parameters=$this->oauth_set_parameter($parameters);	 
			 
		switch ($method) {
			case 'GET':
				$url = $url . '?' . http_build_query($parameters);
				return $this->http($url, 'GET');
			default:
				$headers = array();
				if (!$multi && (is_array($parameters) || is_object($parameters)) ) {
					$body = http_build_query($parameters);
				} else {
					$body = self::build_http_query_multi($parameters);
					$headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
				}
				return $this->http($url, $method, $body, $headers);
		}
	}
	
	public function oauth_set_parameter($parameters){
		return $parameters;
	}
	
	public function addURLFormat(&$url,&$parameters){
		
	}
	
	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	function http($url, $method, $postfields = NULL, $headers = array()) {
		$this->http_info = array();
		//prevent php exec time out error
		set_time_limit(60);
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
//		curl_setopt($ci,CURLOPT_PROXY,'127.0.0.1:8888');
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					$this->postdata = $postfields;
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
		}

//		if ( isset($this->access_token) && $this->access_token )
//			$headers[] = "Authorization: OAuth2 ".$this->access_token;

//		$headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
		curl_setopt($ci, CURLOPT_URL, $url );
		if(!empty($this->header)){
			curl_setopt($ci, CURLOPT_HTTPHEADER, array($this->header));
		}
		
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

		$response = curl_exec($ci);
		if($response === false){
			throw new LocalAuthException(__('Auth connect time out'));
		}
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;

		
		curl_close ($ci);
		return $response;
	}

	/**
	 * Get the header info to store.
	 *
	 * @return int
	 * @ignore
	 */
	function getHeader($ch, $header) {
		$i = strpos($header, ':');
		if (!empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}

	/**
	 * @ignore
	 */
	public static function build_http_query_multi($params) {
		if (!$params) return '';

		uksort($params, 'strcmp');

		$pairs = array();

		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary. '--';
		$multipartbody = '';

		foreach ($params as $parameter => $value) {

			if( in_array($parameter, array('pic', 'image')) && $value{0} == '@' ) {
				$url = ltrim( $value, '@' );
				$content = file_get_contents( $url );
				$array = explode( '?', basename( $url ) );
				$filename = $array[0];

				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
				$multipartbody .= "Content-Type: image/unknown\r\n\r\n";
				$multipartbody .= $content. "\r\n";
			} else {
				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'content-disposition: form-data; name="' . $parameter . "\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}

		}

		$multipartbody .= $endMPboundary;
		return $multipartbody;
	}
	
	/**
	 * GET wrappwer for oAuthRequest.
	 *
	 * @return mixed
	 */
	function get($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response, true);
		}
		return $response;
	}
	
	public function checkUserExist($type,$auth_key){
		$db=MysqlConnection::get();
		$usertable=Config::$tbl_prefix."users";
		$oauthtable=Config::$tbl_prefix."user_oauths";
		$sql="select {$usertable}.* from {$usertable} inner join {$oauthtable} on {$usertable}.id={$oauthtable}.user_id 
						where {$oauthtable}.auth_type='".mysql_escape_string($type)."' and auth_key='".mysql_escape_string($auth_key)."' ";
		$res = $db->query($sql);
		$userinfo=$res->fetchRows();
		$res->free();
		if(!empty($userinfo)){
			$loginuser=$userinfo[0];
			$curr_user=new stdClass();
			foreach($loginuser as $key=>$val){
				$curr_user->$key=$val;
			}
			 $isactive=$loginuser['active'];
			 if(!empty($isactive)){
				 if(MEMBER_VERIFY=='1' && $curr_user->member_verify!='1'){
					 throw new LocalAuthException(__('being reviewed'));
				 }else{
					SessionHolder::set('user/id', $curr_user->id);
					SessionHolder::set('user/login', $curr_user->login);
					SessionHolder::set('user/passwd', $curr_user->passwd);
					SessionHolder::set('user/s_role', $curr_user->s_role);
					SessionHolder::set('user/email', $curr_user->email);
					SessionHolder::set('user/lastlog_time', $curr_user->lastlog_time);
					SessionHolder::set('user/lastlog_ip', $curr_user->lastlog_ip);
					SessionHolder::set('user/member_verify', $curr_user->member_verify);
					SessionHolder::set('user/active', $curr_user->active);
					$o_user = new User($curr_user->id);
					$user_info=array();
					$user_info['lastlog_time']= time();
					$user_info['lastlog_ip']= $_SERVER['REMOTE_ADDR'];
					$o_user->set($user_info);
					$o_user->save();
				 }
			 }else{
				 throw new LocalAuthException(__('This account was prohibited from login, please contact the administrator.'));
			 }
			 return true;
		}
		return false;
	}
	
	public function getAccountAppInfo(){
		 $type=$this->type;
		 global $db;
		 $account=ThirdAccount::findAll2(" account_type='".mysql_escape_string($type)."' ");
          if(empty($account) || $account[0]['active'] != 1){
			throw new LocalAuthException(__('Illegal third party account hint'));
		 }else{
			$this->account=$account[0];
		 }
	}
	
	public function oauth_bind_user($type,$auth_user_info,$user_id){
		$auth_key=$auth_user_info['the_auth_key'];
		$nickname=$auth_user_info[$this->nickcolname];
		 $oauth=  UserOauth::findAll2(" auth_type='".mysql_escape_string($type)."' and auth_key='".mysql_escape_string($auth_key)."' ");
		 if(empty($oauth)){
			$o_user = new UserOauth();
			$o_user ->set(array('user_id'=>$user_id,'auth_type'=>$type,'nickname'=>$nickname,'auth_key'=>$auth_key));
			$o_user ->save();
//			$db->insert('user_oauth',array('user_id'=>$user_id,'auth_type'=>$type,'nickname'=>$nickname,'auth_key'=>$auth_key));
		 }
	}
	
	public function generateUserField(){
		return array();
	}
	
	protected function uriquery($mod,$act,$params){
			return ($this->gtRootUrl().($this->norewrite_uriquery($mod,$act,$params)));
	}
	
	protected function norewrite_uriquery($module, $action, $params = array()){
		$s_query = 'index.php?_m='.urlencode($module).'&_a='.urlencode($action);

		if (!empty($params)/*sizeof($params) > 0*/) {
			foreach ($params as $key => $val) {
				$s_query .= '&'.$key.'='.urlencode($val);
			}
		}
		 return $s_query;
	}
	
	 /**
     * Get the URL address(Protocol)
     *
     * @static
     */
	protected  function gtRootUrl()
    {
		//缓存结果，同一个request不重复计算
	   static $gtrooturl;
	   if(empty($gtrooturl)){
    	// Protocol
			$s = !isset($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on') ? 's' : '';
			$protocol = strtolower($_SERVER['SERVER_PROTOCOL']);
			$protocol = substr($protocol,0,strpos($protocol,'/')).$s.'://';
			// Port
			$port = ($_SERVER['SERVER_PORT'] == 80) ? '' : ':'.$_SERVER['SERVER_PORT'];
			// Server name
			$server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'].$port : getenv('SERVER_NAME').$port;
			// Host
			$host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : $server_name;
			$subdir=dirname($_SERVER['SCRIPT_NAME']);
			 $subdir=rtrim(str_replace('\\', '/', $subdir), '/');
			 $gtrooturl=$protocol.$host.$subdir.'/';
	    }
        return $gtrooturl;
    }
}

class LocalAuthException extends Exception {}

class OAuthException extends Exception {
	// pass
	public $error;
	public $errormsg;
	
	public function __construct($error,$errormsg) {
		$this->error=$error;
		$this->errormsg=$errormsg;
	}
	
}
?>
