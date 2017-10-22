<?php
define('IN_CONTEXT', 1);
require_once("../payment_load.php");
require_once("alipay_notify.php");

$has_error = false;
$error_msg = '';

$o_payacct =& new PaymentAccount();
$payacct =& $o_payacct->find("`payment_provider_id`='1' AND `enabled`='1'");
if (!$payacct) {
    $has_error = true;
    $error_msg = __('Payment account error! Cannot continue!');
}

if (!$has_error) {
    $partner        = $payacct->partner_id;       //合作伙伴ID
    $security_code  = $payacct->partner_key;       //安全检验码
    $seller_email   = $payacct->seller_account;       //卖家支付宝帐户
    $_input_charset = "utf-8";  //字符编码格式  目前支持 GBK 或 utf-8
    $sign_type      = "MD5";    //加密方式  系统默认(不要修改)
    $transport      = "https";  //访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式(系统默认,不要修改)
    
    $alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
    $verify_result = $alipay->return_verify();
    if($verify_result) {
       $outer_oid    = $_GET['out_trade_no'];
       $total_fee  = $_GET['total_fee'];
       $body = $_GET['body'];
       
       // Check history
       $spec_code = parse_speccode($body);
       $pay_histo = check_history($spec_code[0], $outer_oid, '1', $spec_code[1], '0');
       
       if ($pay_histo) {
           if (substr($outer_oid, 0, 3) == 'ord') {
               $ok_script = 'return_ok_ord.php';
               $order_id = substr($outer_oid, 3);
               $rs = update_order($spec_code[0], $order_id, $total_fee);
               if (!$rs) {
                    $has_error = true;
                    $error_msg = __('Unknown Order!');
               } else {
                   $pay_histo->finished = '1';
                   $pay_histo->return_time = time();
                   $pay_histo->save();
               }
           } else if (substr($outer_oid, 0, 3) == 'sav') {
               $ok_script = 'return_ok_sav.php';
               $rs = save_money($spec_code[0], $total_fee, 'alipay');
               if (!$rs) {
                    $has_error = true;
                    $error_msg = __('Cannot save your money!');
               } else {
                   $pay_histo->finished = '1';
                   $pay_histo->return_time = time();
                   $pay_histo->save();
               }
           } else {
                $has_error = true;
                $error_msg = __('Unknown Order!');
           }
       } else {
            $has_error = true;
            $error_msg = __('Unknown Payment!');
       }
    }
    else {
        $has_error = true;
        $error_msg = __('Data verification failed! Cannot continue!');
    }
}

if ($has_error) {
    include_once(ROOT.'/view/onlinepay'.DS.'return_err.php');
} else {
    include_once(ROOT.'/view/onlinepay'.DS.$ok_script);
}
?>