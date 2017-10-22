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
	
	$spec_code = array();
	$current_time = '';
	if(!empty($attach))
	{
		$temp = explode('>',$attach);
		$spec_code = explode('_',$temp[1]);
		$current_time = date('Y-m-d h:m:s',$spec_code[1]);
	}
	else
	{
		$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}出现错误,attach丢失";
		logRegister('notice.txt',$error_msg);
		die;
	}
	
	 $o_order =& new OnlineOrder();
	 $order_id = substr($spec_code[2], 3);
     $curr_order =& $o_order->find("user_id=? AND oid=?", array($spec_code[0], $order_id));
     if(!$curr_order)
     {
     	$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}出现错误,当前订单信息丢失";
		logRegister('notice.txt',$error_msg);
		die;
     }
     
	//------------------------------
	//处理业务开始
	//------------------------------
	
	//注意交易单不要重复处理
	//注意判断返回金额
	
	//返回码判断
	if( "0" == $retcode ) {
		
		switch ($status) {
			case "1":	//交易创建
				
				$pay_histo = check_history($spec_code[0],$spec_code[2],'4',$spec_code[1],'0');
				if(!$pay_histo)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与客户历史记录不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '10';
           			$curr_order->save();
				}
				
				break;
			case "2":	//收获地址填写完毕
				
				$pay_histo = check_history($spec_code[0],$spec_code[2],'4',$spec_code[1],'0');
				if(!$pay_histo)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与客户历史记录不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '11';
           			$curr_order->save();
				}
				
				break;
			case "3":	//买家付款成功，注意判断订单是否重复的逻辑
				
				$pay_histo = check_history($spec_code[0],$spec_code[2],'4',$spec_code[1],'0');
				if(!$pay_histo)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与客户历史记录不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '2';
           			$curr_order->save();
				}
				
				break;
			case "4":	//卖家发货成功
				
				$pay_histo = check_history($spec_code[0],$spec_code[2],'4',$spec_code[1],'0');
				if(!$pay_histo)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与客户历史记录不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '3';
           			$curr_order->save();
				}
				
				break;
			case "5":	//买家收货确认，交易成功
				
				$pay_histo = check_history($spec_code[0],$spec_code[2],'4',$spec_code[1],'0');
				if(!$pay_histo)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与客户历史记录不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '100';
           			$curr_order->save();
           			$pay_histo->finished = '1';
					$pay_histo->return_time = time();
					$pay_histo->save();
				}
				
				break;
			case "6":	//交易关闭，未完成超时关闭
				
				$pay_histo = check_history($spec_code[0],$spec_code[2],'4',$spec_code[1],'0');
				if(!$pay_histo)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与客户历史记录不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '102';
           			$curr_order->save();
				}
				
				break;
			case "7":	//修改交易价格成功
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '12';
           			$curr_order->save();
				}
				break;
			case "8":	//买家发起退款
				
				$pay_histo = check_history($spec_code[0],$spec_code[2],'4',$spec_code[1],'1');
				if(!$pay_histo)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与客户历史记录不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '13';
           			$curr_order->save();
				}
				break;
				
			case "9":	//退款成功
				
				$pay_histo = check_history($spec_code[0],$spec_code[2],'4',$spec_code[1],'1');
				if(!$pay_histo)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与客户历史记录不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '14';
           			$curr_order->save();
				}
				break;
			
			case "10":	//退款关闭	
						
				$pay_histo = check_history($spec_code[0],$spec_code[2],'4',$spec_code[1],'1');
				if(!$pay_histo)
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与客户历史记录不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				
				if ($curr_order->total_amount != $total_fee/100) 
				{
					$error_msg = "\n订单号:{$spec_code[2]}金额在{$current_time}与订单总价不符，请慎重对待！";
					logRegister('notice.txt',$error_msg);
				}
				else
				{
					$curr_order->order_status = '101';
           			$curr_order->save();
				}
				break;
				
			default:
				//nothing to do
				break;
		}
		
	} else {
		$error_msg = "\n支付失败";
		logRegister('notice.txt',$error_msg);
	}
	
	//------------------------------
	//处理业务完毕
	//------------------------------	
	
	//调用doShow
	$resHandler->doShow();
	
	
} else {
	echo "<br/>" . "认证签名失败" . "<br/>";
}

//echo $resHandler->getDebugInfo();

?>