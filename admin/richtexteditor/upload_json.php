<?php
header('Content-type: text/html; charset=UTF-8');
define('IN_CONTEXT', 1);

$root = realpath(dirname(__FILE__).'/../../');

include_once($root.'/config.php');
include_once($root.'/library/param.php');
include_once($root.'/library/memorycache.php');

SessionHolder::initialize();

if(SessionHolder::get('user/s_role')=='{guest}'){
	die('access violation error!');
}
$DOCUMENT_ROOT = str_replace("/", "\\",$_SERVER['DOCUMENT_ROOT']);

$document_tem=str_replace($DOCUMENT_ROOT, "",$root);
$document_tem=str_replace('\\', "/",$document_tem);
$dirname=str_replace("\\", "/", dirname(__FILE__));
$adminRoot = str_replace("\\", "/", substr($dirname, 0, -15));
define('SSROOT', str_replace("\\", "/", realpath($adminRoot."/..")));
require_once 'JSON.php';

$php_path = SSROOT . '/';
$php_url = dirname($_SERVER['PHP_SELF']) . '/';
//文件保存目录路径
$save_path = $php_path . 'upload/';
//文件保存目录URL
$save_url = '/upload/';
//定义允许上传的文件扩展名

$ext_arr = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	'flash' => array('swf', 'flv'),
	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
);
//最大文件大小
$max_size = 1000000;

$save_path = realpath($save_path) . '/';

//PHP上传失败
if (!empty($_FILES['imgFile']['error'])) {
	switch($_FILES['imgFile']['error']){
		case '1':
			$error = '超过php.ini允许的大小。';
			break;
		case '2':
			$error = '超过表单允许的大小。';
			break;
		case '3':
			$error = '图片只有部分被上传。';
			break;
		case '4':
			$error = '请选择图片。';
			break;
		case '6':
			$error = '找不到临时目录。';
			break;
		case '7':
			$error = '写文件到硬盘出错。';
			break;
		case '8':
			$error = 'File upload stopped by extension。';
			break;
		case '999':
		default:
			$error = '未知错误。';
	}
	alert($error);
}

//有上传文件时
if (empty($_FILES) === false) {
	//原文件名
	$file_name = $_FILES['imgFile']['name'];
	//服务器上临时文件名
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//文件大小
	$file_size = $_FILES['imgFile']['size'];
	//检查文件名
	if (!$file_name) {
		alert("请选择文件。");
	}
	//检查目录
	if (@is_dir($save_path) === false) {
		alert("上传目录不存在。");
	}
	//检查目录写权限
	if (@is_writable($save_path) === false) {
		alert("上传目录没有写权限。");
	}
	//检查是否已上传
	if (@is_uploaded_file($tmp_name) === false) {
		alert("上传失败。");
	}
	//检查文件大小
	if ($file_size > $max_size) {
		alert("上传文件大小超过限制。");
	}
	//检查目录名
	$dir_name = empty($_GET['dir']) ? '' : trim($_GET['dir']);
	if (empty($ext_arr[$dir_name])) {
		alert("目录名不正确。");
	}
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//检查扩展名
	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
	}
	//创建文件夹
	if ($dir_name !== '') {
		$save_path .= $dir_name . "/";
		$save_url .= $dir_name . "/";
		if (!file_exists($save_path)) {
			mkdir($save_path);
		}
	}
	//$ymd = date("Ymd");
	//$save_path .= $ymd . "/";
	//$save_url .= $ymd . "/";
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	//新文件名
	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
	//移动文件
	$file_path = $save_path . $new_file_name;
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("上传文件失败。");
	}
		function img_restruck($imgfile_name,$root, $path = 'upload/image/') {
			
			define('SSFCK', 1);
			define('SSROOT', $root);
			include_once($root.'/library/image.func.php');
	
			$fullfilename = $imgfile_name;
			
			WaterImg($fullfilename, 'up');
	    }
	    include_once($root.'/library/'.Config::$mysql_ext.'.php');
		$db = new MysqlConnection(
		    Config::$db_host,
		    Config::$db_user,
		    Config::$db_pass,
		    Config::$db_name
		);
		$sql = "select val from ".Config::$tbl_prefix."parameters where `key`='WATERMARK_STATUS'";
		$res = $db->query($sql);
		$row = $res->fetchRow();
		if($row['val']!='0') img_restruck($file_path,$root);
	
	@chmod($file_path, 0644);
	$file_url = $save_url . $new_file_name;
	
	$json = new Services_JSON();
	if($document_tem=='\/' || $document_tem=='/'){
		$document_tem='';
	}
	if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
		$document_tem = '';
	}
	echo $json->encode(array('error' => 0, 'url' => $document_tem.$file_url));
	exit;
}

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
}
