<?php
/**
* 安装函数
* @copyright www.cndns.com
* @date 2010-1-12
*/
//if (!defined('IN_CONTEXT')) die('access violation error!');

function create_table($DBname, $DBPrefix, $sqlfile, $numOfInstall="" ){
	if(file_exists(ROOT.'/config.php')){
		include_once ROOT.'/config.php';
		if(Config::$mysql_ext=='mysqli'){
			$link = mysql_connect(Config::$db_host,Config::$db_user,Config::$db_pass);
		}
	}
	@mysql_select_db($DBname);
	$mqr = @get_magic_quotes_runtime();
	@set_magic_quotes_runtime(0);
	$query = fread(fopen($sqlfile, "r"), filesize($sqlfile)); 
	@set_magic_quotes_runtime($mqr);
	$pieces  = split_sql($query);
	for ($i=0; $i<count($pieces); $i++){
		$pieces[$i] = trim($pieces[$i]);
		if(!empty($numOfInstall)){
			if(strpos($pieces[$i],'payment_accounts') || strpos($pieces[$i],'payment_providers')){
				continue;
			}
		}
		if(!empty($pieces[$i]) && $pieces[$i] != "ss_"){
			$pieces[$i] = str_replace( "ss_", $DBPrefix, $pieces[$i]); 
			
			if (!$result = mysql_query($pieces[$i])) {
				return $errors[] = array (mysql_error(), $pieces[$i] );				
			}
		}
	}
}
function split_sql($sql){
	$sql = trim($sql);
	$sql = preg_replace("/\n#[^\n]*\n/", "\n", $sql);
	$buffer = array();
	$ret = array();
	$in_string = false;
	for($i=0; $i<strlen($sql)-1; $i++){
		if($sql[$i] == ";" && !$in_string){
			$ret[] = substr($sql, 0, $i);
			$sql = substr($sql, $i + 1);
			$i = 0;
		}
	if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\"){
		$in_string = false;
	}elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")){
		$in_string = $sql[$i];
	}
	if(isset($buffer[1])) {
		$buffer[0] = $buffer[1];
	}
		$buffer[1] = $sql[$i];
	}
	if(!empty($sql)){
		$ret[] = $sql;
	}
	return($ret);
}

function create_config($host,$user,$pwd,$dnname,$pre,$port){
	$str = "";
	$str .= "<?php \n";
	$str.="if (!defined('IN_CONTEXT')) die('access violation error!');\n";
	$str.="class Config {\n";
	$str .= "public static \$mysql_ext = 'mysql';\n";
	$str .= "public static \$db_host = '$host';\n";
	$str .= "public static \$db_user = '$user';\n";
	$str .= "public static \$db_pass = '$pwd';\n";
	$str .= "public static \$db_name = '$dnname';\n";
	$str .= "public static \$port = '$port';\n";
	$str .= "public static \$mysqli_charset = 'utf8';\n";
	$str .= "public static \$tbl_prefix = '$pre';\n";
	$str .= "public static \$cookie_prefix = '".randomStr(6)."_';\n";
	$str .= "public static \$enable_db_debug = false;\n";
	$str .= "}?>\n";
	file_put_contents("../config.php",$str);
}
function create_file($version){
	file_put_contents("../install.lock",'');
}

function uploadcopy($from,$to) {
        if(!is_dir($from)){
			return false;
		}
		$handle=dir($from);
		while($entry=$handle->read()) {
			if(($entry!=".")&&($entry!="..")){
				//if(!file_exists($to."/".$entry)){
					@copy($from."/".$entry,$to."/".$entry);
				//}
			}
		}
		  return true;
    }

function randomStr($len = 6, $alphanum = true) {
        $chars = 'abcdefghijklmnopqrstuvwxyz'
            .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            .'1234567890';
        if (!$alphanum) {
            $chars .= '~!@#$%^&*()_-`[]{}|";:,.<>/?';
        }
        $randstr = '';
        if (!is_integer($len) || $len < 6) {
            $len = 6;
        }
        for ($i = 0; $i < $len; $i++) {
            $idx = mt_rand(0, strlen($chars) - 1);
            $randstr .= substr($chars, $idx, 1);
        }
        
        return $randstr;
    }
?>