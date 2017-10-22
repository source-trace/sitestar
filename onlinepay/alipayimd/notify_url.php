<?php
define('IN_CONTEXT', 1);
require_once("../payment_load.php");
require_once("class/alipay_notify.php");

$o_payacct =& new PaymentAccount();
$payacct =& $o_payacct->find("`payment_provider_id`='7' AND `enabled`='1'");
if (!$payacct) {
    echo "fail";
    include_once(P_TPL.DS.'onlinepay'.DS.'return_err.php');
    die;
}

$partner        = $payacct->partner_id;       //合作伙伴ID
$security_code  = $payacct->partner_key;       //安全检验码
$seller_email   = $payacct->seller_account;       //卖家支付宝帐户
$_input_charset = "utf-8";  //字符编码格式  目前支持 GBK 或 utf-8
$sign_type      = "MD5";    //加密方式  系统默认(不要修改)
$transport      = "https";  //访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式(系统默认,不要修改)

$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);    //构造通知函数信息
$verify_result = $alipay->notify_verify();  //计算得出通知验证结果

if($verify_result) {
    //验证成功
    //获取支付宝的反馈参数
    $dingdan           = $_POST['out_trade_no'];	    //获取支付宝传递过来的订单号
    $total             = $_POST['total_fee'];	    //获取支付宝传递过来的总价格
    
    if($_POST['trade_status'] == 'TRADE_FINISHED' ||$_POST['trade_status'] == 'TRADE_SUCCESS') {    //交易成功结束
        echo "success";
    }
    else {
        echo "success";		//其他状态判断。普通即时到帐中，其他状态不用判断，直接打印success。
    }
}
else {
    //验证失败
    echo "fail";
}
?>