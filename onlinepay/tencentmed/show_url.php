<?php

//---------------------------------------------------------
//财付通中介担保支付应答（处理回调）示例
//---------------------------------------------------------
define('IN_CONTEXT', 1);
require_once("../payment_load.php");
require_once ("../../config.php");
require_once ("./classes/MediPayResponseHandler.class.php");

/* 平台商密钥 */
$host = Config::$db_host;
$role = Config::$db_user;
$pass = Config::$db_pass;
$prefix = Config::$tbl_prefix;
$db = Config::$db_name;
$port = Config::$port;

$link = mysql_connect("$host:$port",$role,$pass);
mysql_select_db($db, $link);

$key = '';
$sql = <<<SQL
SELECT pa.partner_key FROM {$prefix}payment_providers pp
INNER JOIN {$prefix}payment_accounts pa ON pp.id = pa.payment_provider_id
WHERE pp.name = 'tencentmed'
SQL;

$result = mysql_query($sql,$link);
while ($row = mysql_fetch_array($result, MYSQL_NUM))
{
	$key = $row[0];
}
mysql_free_result($result);
mysql_close($link);

/* 创建支付应答对象 */
$resHandler = new MediPayResponseHandler();
$resHandler->setKey($key);

//判断签名
if($resHandler->isTenpaySign()) {
	
	//财付通交易单号
	$cft_tid = $resHandler->getParameter("cft_tid");
	
	//金额,以分为单位
	$total_fee = $resHandler->getParameter("total_fee");
	
	//返回码
	$retcode = $resHandler->getParameter("retcode");
	
	//状态
	$status = $resHandler->getParameter("status");	
	
	//商户附加信息
	$attach = $resHandler->getParameter("attach");	
		
	//------------------------------
	//处理业务开始
	//------------------------------
	
	//注意交易单不要重复处理
	//注意判断返回金额
	
	//返回码判断
	if( "0" == $retcode ) {
		if( "3" == $status ) {
			//支付成功
			header("Location:http://{$_SERVER['HTTP_HOST']}/index.php?_m=mod_order&_a=userlistorder");
			die;
		}
	} else {
		$error_msg = "\n支付失败";
		logRegister('notice.txt',$error_msg);
		echo "支付失败";
	}
	
	//------------------------------
	//处理业务完毕
	//------------------------------	
		
} else {
	$error_msg = "\n认证签名失败";
	logRegister('notice.txt',$error_msg);
	echo "<br/>" . "认证签名失败" . "<br/>";
}

//echo $resHandler->getDebugInfo();

?>