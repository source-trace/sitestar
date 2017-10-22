<?php
define('IN_CONTEXT', 1);
define('P_INC', "../../include");
include_once('../../include/to_pinyin.php');
include_once('../../library/to_pinyin.php');
include_once('../../library/toolkit.php');
$fileArray = array();
foreach ($_POST as $key => $value) {
	if ($key != 'folder') {
		// 解决Windows中文文件名乱码
		$encode = false;
		$value = Toolkit::changeFileNameChineseToPinyin($value);	
		if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $value)) {
			$encode = true;
			$value = iconv('UTF-8', 'GBK', $value);
		}
		
		if ($_SERVER['DOCUMENT_ROOT'].substr($_POST['folder'], 0, strrpos($_POST['folder'],'|')).'/'.$value) {
			// 解决转码后JS弹出窗口无法接收中文文件名
			$fileArray[$key] = $encode ? iconv('GBK', 'UTF-8', $value) : $value;
		}
	}
}

echo json_encode($fileArray);
?>