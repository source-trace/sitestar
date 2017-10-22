<?php

//---------------------------------------------------------
//财付通即时到帐支付应答（处理回调）示例
//---------------------------------------------------------
define('IN_CONTEXT', 1);
require_once("../payment_load.php");
require_once ("../../config.php");
require_once ("./classes/PayResponseHandler.class.php");

/* 获取密钥 */
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
WHERE pp.name = 'tencentimd'
SQL;

$result = mysql_query($sql,$link);
while ($row = mysql_fetch_array($result, MYSQL_NUM))
{
	$key = $row[0];
}
mysql_free_result($result);
mysql_close($link);

/* 创建支付应答对象 */
$resHandler = new PayResponseHandler();
$resHandler->setKey($key);

//判断签名
if($resHandler->isTenpaySign()) 
{
	
	//交易单号
	$transaction_id = $resHandler->getParameter("transaction_id");
	
	//金额,以分为单位
	$total_fee = $resHandler->getParameter("total_fee");
	
	//支付结果
	$pay_result = $resHandler->getParameter("pay_result");
	
	//商户附加信息
	$attach = $resHandler->getParameter("attach");
	
	$spec_code = array();
	$current_time = '';
	if(!empty($attach))
	{
		$temp = explode('>',$attach);
		$spec_code = explode('_',$temp[1]);
		$current_time = date('Y-m-d h:m:s',$spec_code[1]);
	}
	
	if( "0" == $pay_result )
	{
		if (substr($spec_code[2], 0, 3) == 'ord') {
			$pay_histo = check_history($spec_code[0],$spec_code[2],'5',$spec_code[1],'0');
			if($pay_histo)
			{
				$order_id = substr($spec_code[2], 3);
				$rs = update_order($spec_code[0],$order_id,$total_fee/100);
				if(!$rs)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}可能已经完成交易，但交易金额与您公布的价格不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$pay_histo->finished = '1';
					$pay_histo->return_time = time();
					$pay_histo->save();
				}
			}
			else
			{
				$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}可能已经完成交易，但与客户历史记录不符，请慎重对待！";
				logRegister('notice.txt',$error_msg);
			}
			//------------------------------
			//处理业务完毕
			//------------------------------	
			
			//调用doShow, 打印meta值跟js代码,告诉财付通处理成功,并在用户浏览器显示$show页面.
			$show = "http://{$_SERVER['HTTP_HOST']}/index.php?_m=mod_order&_a=userlistorder";
			$resHandler->doShow($show);
		}
		elseif(substr($spec_code[2], 0, 3) == 'sav')
		{
			unset($pay_histo);
			$tag_i = 0;
			if($tag_i==0){
				$rs = save_money($spec_code[0], $total_fee/100, 'tencentimd');
				if (!$rs) {
					$error_msg = __('Cannot save your money!');
					logRegister('notice.txt',"\n$error_msg");
					exit;
				} 
				$pay_histo = check_history($spec_code[0],$spec_code[2],'5',$spec_code[1],'0');
				$pay_histo->finished = '1';
				$pay_histo->return_time = time();
				$pay_histo->save();
				$tag_i++;
			}	
			$show = "http://{$_SERVER['HTTP_HOST']}/index.php?_m=mod_order&_a=useraccountstate";
			$resHandler->doShow($show);
			exit;
		}
	}
	else
	{
		//当做不成功处理
		$error_msg = __('Order Payment Failure.');
		$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}可能$error_msg，请慎重对待！";
		logRegister('notice.txt',$error_msg);
	}
	
} 
else 
{
	$error_msg = __('Certificate Signing Failure.');
	$time1 = time();
	logRegister('notice.txt',"\n在$time1".$error_msg);
}

//echo $resHandler->getDebugInfo();
echo $error_msg;
?>