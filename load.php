<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
ini_set("display_errors","off");
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

if(!(ini_get('date.timezone'))){
date_default_timezone_set("Etc/GMT-8");
}

define('DS', DIRECTORY_SEPARATOR);
define('IS_INSTALL', 1); // 0:share 1:install
define('FCK_UPLOAD_PATH','../../../');
define('FCK_UPLOAD_PATH_AB','/admin/fckeditor/upload/');
define('ROOT', dirname(__FILE__));
define('SCREENSHOT_URL','http://screenshots.sitestar.cn/');

if ( IS_INSTALL ) {
	$lockfile = ROOT.'/install.lock';
	if(!file_exists($lockfile)) {
		echo 'please install Sitestar!';
		exit("<script>window.location.href='install';</script>");
	}
}
define('P_FLT', ROOT.'/filter');
define('P_INC', ROOT.'/include');
define('P_LIB', ROOT.'/library');
define('P_MDL', ROOT.'/model');
define('P_MOD', ROOT.'/module');
define('P_MTPL', ROOT.'/m-template');

include_once(ROOT.'/config.php');
include_once(P_LIB.'/memorycache.php');
include_once(P_LIB.'/pager.php');

include_once(P_LIB.'/toolkit.php');
include_once(P_INC.'/json_encode.php');
//include_once(P_INC.'/china_ds_data.php');

header("Content-type: text/html; charset=utf-8");
define("DOMAINCONST",Toolkit::domainconst());
include_once(P_LIB.'/'.Config::$mysql_ext.'.php');
$db = new MysqlConnection(
    Config::$db_host,
    Config::$db_user,
    Config::$db_pass,
    Config::$db_name
);
if (Config::$enable_db_debug === true) {
    $db->debug = true;
}

include_once(P_INC.'/autoload.php');

define('CACHE_DIR', ROOT.'/cache');
include_once(P_LIB.'/record.php');
include_once(P_LIB.'/validator.php');

include_once(P_INC.'/db_param.php');
include_once(P_INC.'/userlevel.php');

if (intval(DB_SESSION) == 1) {
    include_once(P_LIB.'/session_db.php');
}

include_once(P_INC.'/magic_quotes.php');

define('P_TPL', ROOT.'/template/'.DEFAULT_TPL);
define('P_TPL_VIEW','.');
define('P_SCP', 'script');
define('P_TPL_WEB', 'template/'.DEFAULT_TPL);
include_once(P_INC.'/template_limit.php');
// Include template infomation
include_once(P_TPL.'/template_info.php');

//include_once(P_LIB.'/rand_math.php');
include_once(P_LIB.'/param.php');
include_once(P_LIB.'/notice.php');
SessionHolder::initialize();
Notice::dump();

/**
 * Edit 02/08/2010
 */
$act =& ParamHolder::get('_m');
switch ($act) {
	case 'mod_order':
		include_once(P_INC.'/china_ds_data.php');
		break;
	case 'mod_auth':
	case 'mod_message':
		include_once(P_LIB.'/rand_math.php');
		break;
}

define('P_LOCALE', ROOT.'/locale');
//include_once(P_LIB.'/php-gettext/gettext.inc');
include_once(P_INC.'/locale.php');

include_once(P_INC.'/siteinfo.php');

include_once(P_LIB.'/acl.php');
ACL::loginGuest();

include_once(P_LIB.'/module.php');
include_once(P_LIB.'/form.php');

include_once(P_LIB.'/content.php');

include_once(P_LIB.'/to_pinyin.php');
include_once(P_INC.'/global_filters.php');
/*
if(!Toolkit::getAuthTpl()){
	_e('Template Corp');
	exit;
}
*/
$curr_locale = trim(SessionHolder::get('_LOCALE'));
if (file_exists("data/adtool/xml/config.php")) {
	
   	$c_str = file_get_contents("data/adtool/xml/config.php");
   	$w_arr = unserialize($c_str);
   	if (!empty($w_arr)) {
   		foreach ($w_arr as $k=>$v){
	   		if ($k==$curr_locale) {
	   			foreach ($w_arr[$k] as $ad_k=>$ad_v){
	   				define(trim("$ad_k"),$ad_v);
	   			}
	   		}
	   	}
   	}
}
if (file_exists('data/adtool/xml/config_'.$curr_locale.'.xml')) {
	$str = file_get_contents('data/adtool/xml/config_'.$curr_locale.'.xml');
	$filename = "data/adtool/xml/config.xml";
	$file = fopen($filename, "w");      //以写模式打开文件
	fwrite($file, $str);      //写入
	fclose($file);
}
if (file_exists('data/adtool/xml/couplet_'.$curr_locale.'.xml')) {//对联广告不同语言的配置文件
	$str = file_get_contents('data/adtool/xml/couplet_'.$curr_locale.'.xml');
	$filename = "data/adtool/xml/couplet.xml";
	$file = fopen($filename, "w");      //以写模式打开文件
	fwrite($file, $str);      //写入
	fclose($file);
}
include_once('process.php');

Content::dispatch();

$db->close();
?>
