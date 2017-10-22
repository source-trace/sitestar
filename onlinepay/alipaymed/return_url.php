<?php
define('IN_CONTEXT', 1);
require_once("../payment_load.php");
require_once("class/alipay_notify.php");
//require_once("alipay_config.php");
header("Content-type:text/html;charset=utf-8");//xqf 
$has_error = false;
$error_msg = '';

$o_payacct =& new PaymentAccount();
$payacct =& $o_payacct->find("`payment_provider_id`='6' AND `enabled`='1'");
if (!$payacct) {
    $has_error = true;
    $error_msg = __('Payment account error! Cannot continue!');
}

if($has_error){
	include_once(P_TPL.DS.'onlinepay'.DS.'return_err.php');
	die;
	
} else {
	//构造通知函数信息
	//验证成功
	//获取支付宝的通知返回参数
//	$sOld_trade_status = 0;							//获取商户数据库中查询得到该笔交易当前的交易状态
//	$verify_resultShow = "验证成功";
	$partner        = $payacct->partner_id;       //合作伙伴ID
	$security_code  = $payacct->partner_key;       //安全检验码
	$seller_email   = $payacct->seller_account;       //卖家支付宝帐户
	$_input_charset = "utf-8";  //字符编码格式  目前支持 GBK 或 utf-8
	$sign_type      = "MD5";    //加密方式  系统默认(不要修改)
	$transport      = "http";  //访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式(系统默认,不要修改)
	$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
	//计算得出通知验证结果
	$verify_result = $alipay->return_verify();
	
	if($verify_result)
	{
		$outer_oid           = $_GET['out_trade_no'];		//获取订单号
		$total_fee         = $_GET['total_fee'];			//获取总价格
		$body = $_GET['body'];
	    if($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS')
	    {
			$spec_code = parse_speccode($body);
       		$pay_histo = check_history($spec_code[0], $outer_oid, '6', $spec_code[1], '0');
	    	if (substr($outer_oid, 0, 3) == 'ord') 
	    	{
               $ok_script = 'return_ok_ord.php';
               $order_id = substr($outer_oid, 3);
               $rs = update_order($spec_code[0], $order_id, $total_fee);
               if (!$rs) 
               {
                    $has_error = true;
                    $error_msg = __('Unknown Order!');
               } 
               else 
               {
               	   $has_error = false;
                   $pay_histo->finished = '1';
                   $pay_histo->return_time = time();
                   $pay_histo->save();
                   echo "success";
               }
           } 
           else
           {
                $has_error = true;
                $error_msg = __('Unknown Order!');
           }
	    }
	    else 
	    {
	      	$has_error = true;
        	$error_msg = __('Data verification failed! Cannot continue!');
	    }
	}
	else 
	{
	    $has_error = true;
        $error_msg = __('Data verification failed! Cannot continue!');
	}
}
if ($has_error) {
    include_once(P_VIEW.DS.'onlinepay'.DS.'return_err.php');
} else {
    include_once(P_VIEW.DS.'onlinepay'.DS.$ok_script);
}
?>