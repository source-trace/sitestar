<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Constant PS_ALL
 * Access parameter in all input pre-defined global variables
 */
if (!defined('PS_ALL')) define('PS_ALL', 600);

/**
 * Constant PS_GET
 * Access parameter in $_GET global variables
 */
if (!defined('PS_GET')) define('PS_GET', 601);

/**
 * Constant PS_POST
 * Access parameter in $_POST global variables
 */
if (!defined('PS_POST')) define('PS_POST', 602);

/**
 * Constant PS_COOKIE
 * Access parameter in $_COOKIE global variables
 */
if (!defined('PS_COOKIE')) define('PS_COOKIE', 603);

/**
 * Constant PS_FILES
 * Access parameter in $_FILES global variables
 */
if (!defined('PS_FILES')) define('PS_FILES', 604);

/**
 * Constant PS_MANUAL
 * Access parameter that defined manually
 */
if (!defined('PS_MANUAL')) define('PS_MANUAL', 605);

/**
 * The root context path for storing session data according to different hostname
 *
 * @global string $GLOBALS['_SESSION_HOST_NAME']
 * @name $_SESSION_HOST_NAME
 */
$GLOBALS['_SESSION_HOST_NAME'] = null;

/**
 * Class for handling session data
 *
 * @package parameter
 */
class SessionHolder {
    /**
     * Initialize session
     *
     * @access public
     * @static
     * @global string
     */
    public static function initialize() {
        global $_SESSION_HOST_NAME;
        $_SESSION_HOST_NAME = Config::$cookie_prefix.$_SERVER['SERVER_NAME'];

		if(!empty($_REQUEST['PHPSESSIONID'])){
			 session_id($_REQUEST['PHPSESSIONID']); 
	    }
        session_start();
    }

    /**
     * Set session data
     *
     * @access public
     * @static
     * @global string
     * @param string $key_path The context path for storing session data
     * @param mixed $value The session data
     */
    public static function set($key_path, $value) {
        global $_SESSION_HOST_NAME;
        ParamParser::assign($_SESSION[$_SESSION_HOST_NAME], 
            $key_path, $value);
    }

    /**
     * Retrieve session data
     *
     * @access public
     * @static
     * @global string
     * @param string $key_path The context path for retrieving session data
     * @param mixed $value The default data as fallback
     * @return mixed
     */
    public static function &get($key_path, $default = false) {
        global $_SESSION_HOST_NAME;
        $rs =& ParamParser::retrive($_SESSION[$_SESSION_HOST_NAME], 
            $key_path, $default);
        return $rs;
    }

    /**
     * Check whether the value of the context path is available
     *
     * @access public
     * @static
     * @global string
     * @param string $key_path The context path for retrieving session data
     * @return bool
     */
    public static function has($key_path) {
        global $_SESSION_HOST_NAME;
        return ParamParser::has($_SESSION[$_SESSION_HOST_NAME], 
            $key_path);
    }

    /**
     * Destroy session
     *
     * @access public
     * @static
     * @global string
     */
    public static function destroy() {
        global $_SESSION_HOST_NAME;
        unset($_SESSION[$_SESSION_HOST_NAME]);
        $_SESSION_HOST_NAME = null;
    }
}

/**
 * Class for handling manually assigned parameters
 *
 * @package parameter
 */
class ManualParamHolder {
    /**
     * Set manual parameter
     *
     * @access public
     * @static
     * @global string
     * @param string $key_path The context path for storing manual data
     * @param mixed $value The manual data
     */
    public static function set($key_path, $value) {
        global $_SESSION_HOST_NAME;
        ParamParser::assign($_SESSION[$_SESSION_HOST_NAME]['_MANUAL'], 
            $key_path, $value);
    }

    /**
     * Retrieve manual parameter
     *
     * @access public
     * @static
     * @global string
     * @param string $key_path The context path for retrieving manual data
     * @param mixed $value The default data as fallback
     * @return mixed
     */
    public static function &get($key_path, $default = false) {
        global $_SESSION_HOST_NAME;
        $rs =& ParamParser::retrive($_SESSION[$_SESSION_HOST_NAME]['_MANUAL'], 
            $key_path, $default);
        return $rs;
    }

    /**
     * Check whether the value of the context path is available
     *
     * @access public
     * @static
     * @global string
     * @param string $key_path The context path for retrieving manual data
     * @return bool
     */
    public static function has($key_path) {
        global $_SESSION_HOST_NAME;
        return ParamParser::has($_SESSION[$_SESSION_HOST_NAME]['_MANUAL'], 
            $key_path);
    }
}

/**
 * The class for retrieving parameters in general context
 *
 * @package parameter
 */
class ParamHolder {
    /**
     * Retrieve parameter
     *
     * @access public
     * @static
     * @param string $key_path The context path for retrieving data
     * @param mixed $value The default data as fallback
     * @param int $scope The parameter context
     * @return mixed
     */
    public static function &get($key_path, $default = false, $scope = PS_ALL) {
		$getfilter="'|\b(alert|confirm|prompt)\b|<[^>]*?>|^\\+\/v(8|9)|%|\\b(and|or)\\b.+?(>|<|=|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
		$postfilter="^\\+\/v(8|9)|\|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)|WCRTESTINPUT000000";
		$cookiefilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)|%";
		
        switch ($scope) {
            case PS_GET:
				self::checkattack($_GET,$getfilter);
                $rs =& ParamParser::retrive($_GET, $key_path, $default);
                break;
            case PS_POST:
				self::checkattack($_POST,$postfilter);
                $rs =& ParamParser::retrive($_POST, $key_path, $default);
                break;
            case PS_COOKIE:
				self::checkattack($_COOKIE,$cookiefilter);
                $rs =& ParamParser::retrive($_COOKIE, $key_path, $default);
                break;
            case PS_FILES:
                $rs =& ParamParser::retrive($_FILES, $key_path, $default);
            	/**
            	 * get upload file type
            	 */
            	if (isset($rs["tmp_name"]) && !is_array($rs["tmp_name"]) && !empty($rs["tmp_name"])) {
            		$ftype = ParamParser::file_type($rs["tmp_name"]);
            		if ($ftype == 'unknown') die(__('Upload file type error,please retry!'));
            	}
                break;
            case PS_MANUAL:
                $rs =& ManualParamHolder::get($key_path, $default);
                break;
            case PS_ALL:
                if (ParamParser::has($_GET, $key_path)) {
					self::checkattack($_GET,$getfilter);
                    $rs =& ParamParser::retrive($_GET, $key_path, $default);
                } else if (ParamParser::has($_POST, $key_path)) {
					self::checkattack($_POST,$postfilter);
                    $rs =& ParamParser::retrive($_POST, $key_path, $default);
                } else if (ParamParser::has($_COOKIE, $key_path)) {
					self::checkattack($_COOKIE,$cookiefilter);
                    $rs =& ParamParser::retrive($_COOKIE, $key_path, $default);
                } else if (ParamParser::has($_FILES, $key_path)) {
                    $rs =& ParamParser::retrive($_FILES, $key_path, $default);
                } else if (ManualParamHolder::has($key_path)) {
                    $rs =& ManualParamHolder::get($key_path, $default);
                } else {
                    $rs = $default;
                }
                break;
            default:
                $rs = $default;
        }
        return $rs;
    }
	public static function checkattack($get,$reg){
		foreach($get as $key=>$value){
			self::stopattack($key,$value,$reg);
		}
	}
	public static function stopattack($StrFiltKey,$StrFiltValue,$ArrFiltReq){  

		$StrFiltValue=self::arr_foreach($StrFiltValue);
		if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){
				die('access violation error!');
		}
		if (preg_match("/".$ArrFiltReq."/is",$StrFiltKey)==1){
			die('access violation error!');
		}
	}

	public static function arr_foreach($arr) {
		static $str;
		if (!is_array($arr)) {
		return $arr;
		}
		foreach ($arr as $key => $val ) {

		if (is_array($val)) {

			self::arr_foreach($val);
		} else {

		  $str[] = $val;
		}
		}
		return implode($str);
	}
}

/**
 * The base parameter processing class
 *
 * @package parameter
 */
class ParamParser {
    /**
     * The context path delimiter
     *
     * @access private
     * @static
     * @var string
     */
    private static $_path_delimiter = '/';

    /**
     * Assign data to the context path in the collection
     *
     * @access public
     * @static
     * @param array &$collection The main collection variable
     * @param string $key_path The context path for storing data
     * @param mixed $value The data
     */
    public static function assign(&$collection, $key_path, $value) {
        $arr_path = explode(self::$_path_delimiter, $key_path, 2);
        if (empty($arr_path[0])) {
            return false;
        }
        if (sizeof($arr_path) > 1) {
            self::assign($collection[$arr_path[0]], $arr_path[1], $value);
        } else {
            $collection[$arr_path[0]] = $value;
        }
    }

    /**
     * Retrieve data from the context path in the collection
     *
     * @access public
     * @static
     * @param array &$collection The main collection variable
     * @param string $key_path The context path for retrieving data
     * @param mixed $value The default data for fallback
     * @return mixed
     */
    public static function &retrive(&$collection, $key_path, $default = false) {
        $arr_path = explode(self::$_path_delimiter, $key_path, 2);
        if (empty($arr_path[0]) || 
            !isset($collection[$arr_path[0]])) {
            return $default;
        }
        if (sizeof($arr_path) > 1) {
            $rs =& self::retrive($collection[$arr_path[0]], $arr_path[1], $default);
        } else {
            $rs =& $collection[$arr_path[0]];
        }
        return $rs;
    }

    /**
     * Check whether the value of the context path is available in collection
     *
     * @access public
     * @static
     * @global string
     * @param array &$collection The main collection variable
     * @param string $key_path The context path for retrieving data
     * @return bool
     */
    public static function has(&$collection, $key_path) {
        $arr_path = explode(self::$_path_delimiter, $key_path, 2);
        if (empty($arr_path[0]) || 
            !isset($collection[$arr_path[0]])) {
            return false;
        }
        if (sizeof($arr_path) > 1) {
            return self::has($collection[$arr_path[0]], $arr_path[1]);
        } else {
            return true;
        }
    }
    
    /**
     * return upload file type
     */
    public static function file_type($filename) { 
	    $file = @fopen($filename, "rb"); 
	    $bin = @fread($file, 2);
	    @fclose($file); 
	    $strInfo = @unpack("C2chars", $bin); 
	    $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
	    $fileType = ''; 
	    switch ($typeCode) {
	    	case 3780: 
	            $fileType = 'pdf';
	            break;
	        case 4838: 
	            $fileType = 'wma';
	            break;
	        case 6677: 
	            $fileType = 'bmp';
	            break;
	        case 6787: 
	        case 7087: 
	            $fileType = 'swf';
	            break;
	        case 6882:
	        	$fileType = 'sql';
	        	break;
	        case 7076: 
	            $fileType = 'flv';
	            break;
	        case 7173: 
	            $fileType = 'gif';
	            break;
			case 255251:
			case 255250:
	        case 7368:
	        	$fileType = 'mp3';
	        	break;
	        case 8075: 
	            $fileType = 'zip';
	            break;
	        case 8297: 
	            $fileType = 'rar';
	            break; 
	        case 13780: 
	            $fileType = 'png';
	            break;
	        case 31139:
	        	$fileType = 'gz';
	        	break;
	        case 115100:
	        	$fileType = 'txt';
	        	break;
	        case 6797:
	        case 178250:
	        case 183214:
	        	$fileType = 'csv';
	        	break;
	        case 208207:
	        	$fileType = 'xls';
	        	break;
	        case 255216: 
	            $fileType = 'jpg';
	            break; 
	        case 0:
	        	$fileType = 'ico';
	        	break;
	        default: 
	            $fileType = 'unknown'; 
	    } 
	    return $fileType; 
	} 
	
public static function fire_virus($imgfile){
	
		$val_file = str_replace(strchr($imgfile,'.'),'#.txt',$imgfile);
		
		$fp=fopen("$val_file", "w+"); //创建文件
		fclose($fp);
        copy($imgfile,str_replace(strchr($imgfile,'.'),'#.txt',$imgfile));
        $i = 0;
        $flag_img = 0;
		$take = false;
        if (file_exists($val_file)) {

			$f= fopen($val_file, "r");
			while (!feof($f)){
			  $line = fgetss($f,4096);
			  if (strstr($line,'eval') || strstr($line,'phpinfo') || strstr($line,'fopen') || strstr($line,'system') || strstr($line,'require') || strstr($line,'include')) {
			  	//如果文件非法
			  	$take = true;
			  	
			  	@unlink($imgfile);
			  	echo '该图片文件可能是伪装木马文件，已经将其删除，请换张上传';
			  	$flag_img = 1;
			  }
			}
			fclose($f);
         }
         //保留可疑文件，
         if(!$take)  @unlink($val_file);
         if ($flag_img) {
          	exit('上传已经停止');
         }else{
         	return true;
         }
         
	}
	
	public static function getDns(){
		$ips['domain'] = $_SERVER['SERVER_NAME'];
		if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
			$ips[] = "447f32d25b7d55524675c64d649ae5d9";
		}else{
			$win_ips = gethostbynamel(php_uname('n'));
			foreach ($win_ips as $ip){
				$ips[] = $ip;
			}
		}
		return array_unique($ips);
	}
	
	public static function goto404(){
		//echo ROOT;
		die('<script>alert("'.__('Unauthorized').'");window.location.href="/";</script>');
		//@header('HTTP/1.1 404 Not Found');
		//@header('Status: 404 Not Found');
		//exit;
    }
    public static function zh2tw($source,$target,$str){
		$zh2TW = $tmp = array();
		$str = trim($str);
		if(empty($str)) return $str;

		if($source != 'zh_CN' && $source != 'zh_TW') return $str;
		if($target != 'zh_CN' && $target != 'zh_TW') return $str;
		if($source == $target) return $str;

		include('../data/ZhConversion.php');
		if($source == 'zh_CN' && $target == 'zh_TW')
		{
			$tmp = array_keys($zh2TW);
		}
		elseif($source == 'zh_TW' && $target == 'zh_CN')
		{
			$tmp = array_unique(array_values($zh2TW));
			$zh2TW = array_flip($zh2TW);
		}
		else
		return $str;
		
		return str_replace($tmp, $zh2TW, $str);
	}
	
	public static function writeFile($file,$str,$type='log'){
    	if ($type=='log') {
    		$mode = 'a+';
    	}else{
    		$mode = 'w';
    	}
	    $oldmask = umask(0);
	    $fp = fopen($file,$mode);
	    flock($fp, 3);
	    if(!$fp){
	        Return false;
	    } else{
	        fwrite($fp,$str);
	        fclose($fp);
	        umask($oldmask);
	        Return true;
	    }
	} 
}
?>