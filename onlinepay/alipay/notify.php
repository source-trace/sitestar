<?php
define('IN_CONTEXT', 1);
require_once("../payment_load.php");
require_once("alipay_notify.php");

$o_payacct =& new PaymentAccount();
$payacct =& $o_payacct->find("`payment_provider_id`='1' AND `enabled`='1'");
if (!$payacct) {
    echo "fail";
}

$partner        = $payacct->partner_id;       //合作伙伴ID
$security_code  = $payacct->partner_key;       //安全检验码
$seller_email   = $payacct->seller_account;       //卖家支付宝帐户
$_input_charset = "utf-8";  //字符编码格式  目前支持 GBK 或 utf-8
$sign_type      = "MD5";    //加密方式  系统默认(不要修改)
$transport      = "https";  //访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式(系统默认,不要修改)

$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
$verify_result = $alipay->notify_verify();
if($verify_result) {
    $dingdan   = $_POST['out_trade_no'];
    $total     = $_POST['total_fee'];

    $receive_name    =$_POST['receive_name'];
	$receive_address =$_POST['receive_address'];
	$receive_zip     =$_POST['receive_zip'];
	$receive_phone   =$_POST['receive_phone'];
	$receive_mobile  =$_POST['receive_mobile'];

	if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
		echo "success";
	}
	else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
		echo "success";
	}
	else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
		echo "success";
	}
	else if($_POST['trade_status'] == 'TRADE_FINISHED') {
		echo "success";
	}
	else {
		echo "fail";
	}
}
else  {
	echo "fail";
}
?>