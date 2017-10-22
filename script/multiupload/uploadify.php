<?php
if (!isset($_POST['folder']) || empty($_POST['folder'])) {
	die('access violation error!');
} else {
	define('IN_CONTEXT', 1);
	$root = realpath(dirname(__FILE__).'/../../');
	include_once($root.'/config.php');
	include_once($root.'/library/param.php');
	include_once($root.'/library/memorycache.php');
	SessionHolder::initialize();
	if (strstr($_POST['folder'],'%')) {
		die('access violation error!');
	}
	list($path, $target) = explode("|", $_POST['folder']);
	if (empty($target)) {
		die('access violation error!');
	}
	if (!preg_match ("/^[a-z0-9\.\/]*$/i",$path)) {
		die('access violation error!');
	}
	
}


//echo $path.$target;
include_once($root.'/library/'.Config::$mysql_ext.'.php');

$db = new MysqlConnection(
    Config::$db_host,
    Config::$db_user,
    Config::$db_pass,
    Config::$db_name
);
$db = MysqlConnection::get();
$prefix = Config::$tbl_prefix;
$sql = <<<SQL
SELECT * FROM {$prefix}parameters where `key`='WATERMARK_STATUS'
SQL;
$query =& $db->query($sql);
$row =& $query->fetchRow();
$WATERMARK_STATUS = $row['val'];
include_once('../../include/to_pinyin.php');
include_once('../../library/to_pinyin.php');
include_once('../../library/toolkit.php');
include_once('../../library/record.php');
$file_ext = substr($_FILES['Filedata']['name'],strrpos($_FILES['Filedata']['name'],".")+1);
$ext_arr = array('flv','swf','mp3','mp4','3gp','zip','rar','gif','jpg','png','bmp','doc','docx','xls','xlsx','ppt','pptx','wps','pdf');
$_FILES['Filedata']['name'] = date("YmdHis") . '_' . rand(10, 99) . '.' . $file_ext;
/*$_FILES['Filedata']['name'] = Toolkit::changeFileNameChineseToPinyin($_FILES['Filedata']['name']);
if (substr($_FILES['Filedata']['name'],0,1)==".") {
	$_FILES['Filedata']['name'] = date("YmdHis") . '_' . rand(10, 99) . '.' . $file_ext;;
	
}*/


if (!empty($_FILES) && in_array($file_ext,$ext_arr)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $path . '/';
	//echo $targetPath;
	$imgfile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
	// 解决Windows中文文件名乱码
	if (preg_match("/^WIN/i", PHP_OS)) {
		$imgfile = iconv('UTF-8', 'GBK', $imgfile);
	}
	
	move_uploaded_file($tempFile, $imgfile);
	ParamParser::fire_virus($imgfile);

	function img_restruck($imgfile_name,$root, $path = 'upload/image/') {
		define('SSFCK', 1);
		define('SSROOT', $root);
		include_once($root.'/library/image.func.php');

		$fullfilename = SSROOT."/$path".$imgfile_name;
		
		WaterImg($fullfilename, 'up');
    }

	if($WATERMARK_STATUS) img_restruck($_FILES['Filedata']['name'],$root);
	echo "1";
}
?>