<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$o_param = new Parameter();
$arr_params =& $o_param->findAll();
if (sizeof($arr_params) > 0) {
    foreach ($arr_params as $param) {
    	//admin 文件夹下的load.php也会到这里定义常量
    	if (file_exists(ROOT."/data/adtool/xml/config.php")) {
    		$c_str = file_get_contents(ROOT."/data/adtool/xml/config.php");
   			$w_arr = unserialize($c_str);
   			if (!empty($w_arr)) {//广告常量跳出，这里使用data/adtool/xml/config.php做为广告的配置文件
   				if (strstr($param->key,"ADVERT")) {
   					continue;
   				}
   			}
    	}
		if (strstr($param->key,"CURRENCY")) {
   			continue;
   		}
    	define(trim("$param->key"), "$param->val");
    }
    
    // for checking licence
     define('REMOTE_DOMAIN', 'http://licence.sitestar.cn/');

}

?>