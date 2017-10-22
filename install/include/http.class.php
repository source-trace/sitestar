<?php
class Http { 

    private $_url = '';

    private $_params = '';

    private $_headers = '';

    private $_host = '';

    private $_port = '';

    private $_path = '';

    private $_query = '';

    private $_time_out = 10;

    private $_response = array();

    private $_version = ""; 

    public $err_no = '';

    public $err_str = '';

    public function Http($url = '') { 
        $this->_url = $url; 
    } 
    
    public function setURL($url = '') { 
        $this->_url = $url; 
    } 
    
    public function setTimeOut($timeout = 10) { 
        $this->_time_out = $timeout; 
    } 
    
    public function clear() { 
        $this->_url = ""; 
        $this->_params = ""; 
        $this->_headers = ""; 
        $this->err_no = ""; 
        $this->err_str = ""; 
    } 
    
    public function addHeader($str_header) { 
        $this->_headers .= $str_header ."\n"; 
    } 
    
    public function setHeader($str_header) { 
        $this->_headers = $str_header ."\n"; 
    } 
   
    public function addGetParam($arrParam) { 
        $this->_url .= strpos($this->_url,'?') === false?'?':'&'; 
        $this->_url .= http_build_query($arrParam); 
    } 
    
    public function addPostParam($arrParam) { 
        $this->_params .= strlen($this->_params) > 0?'&':''; 
        $this->_params .= http_build_query($arrParam); 
    } 
    
    public function setPostParam($arrParam) { 
        $this->_params = http_build_query($arrParam); 
    } 
    
    public function get() { 
        return $this->_request("GET"); 
    } 
    
    public function post() { 
        return $this->_request("POST"); 
    } 
    
    public function getResponse($item = "") { 
        return $item == ""?$this->_response:$this->_response[$item]; 
    } 
    
    public function getContent() { 
        return $this->_response['content']; 
    } 
    
    public function getVersion() { 
        return $this->_version; 
    } 
   
    public function is_error() { 
        return $this->err_no; 
    } 
    
    public function get_error() { 
        return $this->err_str; 
    } 

    private function _parse_url() { 
        $arr_url = parse_url($this->_url); 
        if(!is_array($arr_url)){ 
            $arr_url = array(); 
        } 
        $this->_host = $arr_url['host']; 
        $this->_port = isset($arr_url['port'])?$arr_url['port']:'80'; 
        $this->_path = $arr_url['path']; 
        $this->_query = $arr_url['query']; 
    }

    private function _request($method = "GET") { 
        $this->_parse_url(); 
        $fp = @fsockopen($this->_host,$this->_port,$this->err_no,$this->err_str,$this->_time_out); 

        if(!$fp){ 
            return false; 
        }else{ 
            $request = ''; 
            $request .= sprintf("%s %s%s%s HTTP/1.0\n", $method, $this->_path, $this->_query ? "?" : "", $this->_query); 
            $request .= "Host: ".$this->_host."\n"; 
            $request .= $this->_headers; 
            $request .= $method == "POST"?"Content-type: application/x-www-form-urlencoded\n":""; 
            $request .= $method == "POST"?"Content-length: ". strlen($this->_params) ."\n":""; 
            $request .= "Connection: close\n";

            $request .= $method == "POST"?"\n$this->_params\n":"";

            $request .= "\n"; 
            fputs($fp,$request); 
            $results = ""; 

            while(!feof($fp)) { 
                $line = fgets($fp,1024); 
                $results .= $line; 
            } 
            fclose($fp); 
            $this->_response['header'] = substr($results,0,strpos($results,"\r\n\r\n")+1);

            $this->_response['content'] = substr($results,strpos($results,"\r\n\r\n")+4);

            return true; 
        } 
    } 
} 
?>