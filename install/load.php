<?php
/**
* 安装控制
* @copyright www.sitestar.cn
* @date 2010-1-12
*/
@session_start();
if (!defined('IN_CONTEXT')) die('access violation error!');

error_reporting(E_ALL ^ E_NOTICE);
define('DS', DIRECTORY_SEPARATOR);

define('ROOT', realpath(dirname(__FILE__).'/..'));
define('INSTALL_ROOT', dirname(__FILE__));
define('P_LIB', ROOT.'/library');
define('P_TPL', INSTALL_ROOT.'/template/view/frontpage');

header("Content-type: text/html; charset=utf-8");

include_once(P_LIB.'/toolkit.php');
include_once P_LIB."/param.php";
define("DOMAINCONST",Toolkit::domainconst());
include_once ROOT."/include/fun_install.php";
include_once INSTALL_ROOT."/include/http.class.php";
$_a = ParamHolder::get("_a","");
$_m = ParamHolder::get("_m","frontpage");
$db_host1 = ParamHolder::get("db_host","");
$db_user = ParamHolder::get("db_user","");
$db_pwd = ParamHolder::get("db_pwd","");
$db_name = ParamHolder::get("db_name","");
$db_prefix = ParamHolder::get("db_prefix","");
$db_port = ParamHolder::get("db_port","");
$admin_name = ParamHolder::get("admin_name","");
$admin_pwd = ParamHolder::get("admin_pwd","");
$demo = ParamHolder::get("demo","");
$db_host = $db_host1.":".$db_port;
$lockfile = ROOT.'/install.lock';
$pattern_db = '/[0-9a-zA-Z]*$/';
if(!preg_match($pattern_db, $db_name)||!preg_match($pattern_db, $db_user)){
	echo '1001';exit;
}
if(file_exists($lockfile) && ($_a=='template' || $_a=='setting' || $_a=='check')) {
		exit('please delete install.lock!');
}
if($_a=='template'){
	include P_TPL."/template.php";
}else if($_a=='check'){
	include P_TPL."/check.php";
}else if($_a=='setting'){
	$default_tpl = ParamHolder::get("default_tpl","jixie-110118-a16");
	$_SESSION['default_tpl'] = $default_tpl;
	include P_TPL."/setting.php";
}else if($_a=='result'){
	$domain = $_SERVER['HTTP_HOST'];
	if(isset($_SERVER['SERVER_ADDR'])){
		$ip = $_SERVER['SERVER_ADDR'];
	}else{
		$ip='127.0.0.1';
	}
	$version = 'sitestar_v2.7_build131012';
	$system = preg_replace('/\s/','',PHP_OS);
	$vphp = PHP_VERSION;
	$vmysql = $_SESSION['vmysql'];
	$tpl_name = $_SESSION['default_tpl'];
	$http = new Http("http://licence.sitestar.cn/feedback.php?domain=$domain&ip=$ip&version=$version&vphp=$vphp&vmysql=$vmysql&tpl_name=$tpl_name&vos=$system");
	$http->get();
	include P_TPL."/result.php";

    create_file($version);


}else if($_a=='checkconnection'){
	$link = @mysql_connect($db_host,$db_user,$db_pwd);
	if (!$link) {
		echo '1001';
		exit;
	}
	$r = mysql_select_db($db_name,$link);
	if(!$r){
		echo '1002';
		exit;
	}
}else if($_a=="create"){
	$link = mysql_connect($db_host,$db_user,$db_pwd);
	if (!$link) {
		echo '1001';
		exit;
	}
	$r = mysql_select_db($db_name,$link);
	if(!$r){
		echo '1002';
		exit;
	}
	
	$rtn = create_table($db_name,$db_prefix,INSTALL_ROOT.'/../sql/basic.sql');
	if(!empty($rtn)){
		echo '1005';
		exit;
	}
	mysql_query("INSERT INTO `".$db_prefix."parameters` (`id`, `key`, `val`) VALUES (NULL, 'DEFAULT_TPL', '".$_SESSION['default_tpl']."')");
	//uploadcopy(ROOT."/template/".$_SESSION['default_tpl']."/".$_SESSION['default_tpl']."_2_upload/image",ROOT."/upload/image");
	//uploadcopy(ROOT."/template/".$_SESSION['default_tpl']."/".$_SESSION['default_tpl']."_2_upload/flash",ROOT."/upload/flash");
	if($demo=='1'){
		create_table($db_name,$db_prefix,ROOT."/template/".$_SESSION['default_tpl']."/".$_SESSION['default_tpl'].'_2_sample.sql');
	} else {
		mysql_query("INSERT INTO `".$db_prefix."static_contents` (`id` ,`title` ,`content` ,`create_time` ,`s_locale` ,`published` ,`for_roles`) VALUES ('1', '', NULL , '', 'zh_CN', '1', '{member}{admin}{guest}');");
		mysql_query("INSERT INTO `".$db_prefix."static_contents` (`id` ,`title` ,`content` ,`create_time` ,`s_locale` ,`published` ,`for_roles`) VALUES ('2', '', NULL , '', 'zh_CN', '1', '{member}{admin}{guest}');");
		mysql_query("INSERT INTO `".$db_prefix."static_contents` (`id` ,`title` ,`content` ,`create_time` ,`s_locale` ,`published` ,`for_roles`) VALUES ('3', '', NULL , '', 'en', '1', '{member}{admin}{guest}');");
		mysql_query("INSERT INTO `".$db_prefix."static_contents` (`id` ,`title` ,`content` ,`create_time` ,`s_locale` ,`published` ,`for_roles`) VALUES ('4', '', NULL , '', 'en', '1', '{member}{admin}{guest}');");
	}
	
	echo '1003';
}else if($_a=="createadmin"){

	$link = mysql_connect($db_host,$db_user,$db_pwd);
	if (!$link) {
		echo '1001';
		exit;
	}
	$r = mysql_select_db($db_name,$link);
	if(!$r){
		echo '1002';
		exit;
	}
	mysql_query("set names utf8");
	mysql_select_db($db_name,$link);
	$mysql_query = mysql_query("select VERSION()");
	$mysql_row = mysql_fetch_row($mysql_query);
	$vmysql = $mysql_row[0];
	$_SESSION['vmysql'] = $mysql_row[0];
	$passwd = sha1($admin_pwd);
	$tme = time();
	if ($link) {
		create_config($db_host1,$db_user,$db_pwd,$db_name,$db_prefix,$db_port);
	}
	$query = mysql_query("insert into ".$db_prefix."users(login,passwd,email,lastlog_time,rstpwdreq_time,active,s_role) values('$admin_name','$passwd','admin@admin.com','$tme','0','1','{admin}')");
	$insert_id = mysql_insert_id();
	$query = mysql_query("insert into ".$db_prefix."user_extends(total_saving,total_payment,balance,user_id) values('0.00','0.00','0.00','$insert_id')");
	if($query){
		echo '1004';
	}
	
}else{
	
	include P_TPL."/index.php";
}
?>
