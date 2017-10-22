<?php
define('IN_CONTEXT', 1);
require_once("../payment_load.php");

$has_error = false;
$error_msg = '';

$o_payacct =& new PaymentAccount();
$payacct =& $o_payacct->find("`payment_provider_id`='2' AND `enabled`='1'");
if (!$payacct) {
    $has_error = true;
    $error_msg = __('Payment account error! Cannot continue!');
}
w_file('notice.txt','start');
if (!$has_error) {
    $merchantAcctId=trim($_REQUEST['merchantAcctId']);
    $key=$payacct->partner_key;
    $version=trim($_REQUEST['version']);
    $language=trim($_REQUEST['language']);
    $signType=trim($_REQUEST['signType']);
    $payType=trim($_REQUEST['payType']);
    $bankId=trim($_REQUEST['bankId']);
    $orderId=trim($_REQUEST['orderId']);
    $orderTime=trim($_REQUEST['orderTime']);
    $orderAmount=trim($_REQUEST['orderAmount']);
    $dealId=trim($_REQUEST['dealId']);
    $bankDealId=trim($_REQUEST['bankDealId']);
    $dealTime=trim($_REQUEST['dealTime']);
    $payAmount=trim($_REQUEST['payAmount']);
    $fee=trim($_REQUEST['fee']);
    $ext1=trim($_REQUEST['ext1']);
    $ext2=trim($_REQUEST['ext2']);
    $payResult=trim($_REQUEST['payResult']);
    $errCode=trim($_REQUEST['errCode']);
    $signMsg=trim($_REQUEST['signMsg']);
    
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"merchantAcctId",$merchantAcctId);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"version",$version);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"language",$language);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"signType",$signType);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"payType",$payType);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"bankId",$bankId);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"orderId",$orderId);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"orderTime",$orderTime);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"orderAmount",$orderAmount);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"dealId",$dealId);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"bankDealId",$bankDealId);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"dealTime",$dealTime);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"payAmount",$payAmount);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"fee",$fee);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"ext1",$ext1);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"ext2",$ext2);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"payResult",$payResult);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"errCode",$errCode);
        $merchantSignMsgVal=appendParam($merchantSignMsgVal,"key",$key);
    $merchantSignMsg= md5($merchantSignMsgVal);
    
    if(strtoupper($signMsg)==strtoupper($merchantSignMsg)){
       // Check history
       $spec_code = parse_speccode($ext1);
       $pay_histo = check_history($spec_code[0], $orderId, '2', $spec_code[1], '0');
       w_file('notice.txt',$spec_code[0].'---'.$spec_code[1].'amount'.$payAmount.'\r\n');
      // print_r($pay_histo);
      // exit;
//      echo" <result>1</result><redirecturl>http://".$_SERVER['HTTP_HOST']."/index.php?_m=mod_order&_a=userlistorder</redirecturl>";
       if ($pay_histo) {
            switch($payResult){
                  case "10":
                           if (substr($orderId, 0, 3) == 'ord') {
                               $ok_script = 'return_ok_ord.php';
                               $order_id = substr($orderId, 3);
                               $rs = update_order($spec_code[0], $order_id, $payAmount);
                               w_file('notice.txt','update order:'.$rs.'\r\n');
                               if (!$rs) {
                                    $has_error = true;
                                    $error_msg = __('Unknown Order!');
                                                                 w_file('notice.txt', 'if order_id:'.$order_id.'addr:'.$_SERVER['HTTP_HOST']); 
                               } else {
                                   $pay_histo->finished = '1';
                                   $pay_histo->return_time = time();
                                   $pay_histo->save();
                                                                w_file('notice.txt', 'else order_id:'.$order_id.'addr:'.$_SERVER['HTTP_HOST']); 
                               }
                             w_file('notice.txt', 'order_id:'.$order_id.'addr:'.$_SERVER['HTTP_HOST']); 
                             echo " <result>1</result><redirecturl>http://".$_SERVER['HTTP_HOST']."/index.php?_m=mod_order&_a=userlistorder</redirecturl>";
                           } else if (substr($outer_oid, 0, 3) == 'sav') {
                               $ok_script = 'return_ok_sav.php';
                               $rs = save_money($spec_code[0], $payAmount, '99bill');
                               if (!$rs) {
                                    $has_error = true;
                                    $error_msg = __('Cannot save your money!');
                                    echo " <result>1</result><redirecturl>http://".$_SERVER['HTTP_HOST']."/index.php?_m=mod_order&_a=userlistorder</redirecturl>";
 // echo" <result>1</result><redirecturl>33</redirecturl>";
//exit;


                               } else {
                                   $pay_histo->finished = '1';
                                   $pay_histo->return_time = time();
                                   $pay_histo->save();
                                   echo " <result>1</result><redirecturl>http://".$_SERVER['HTTP_HOST']."/index.php?_m=mod_order&_a=userlistorder</redirecturl>";
 // echo" <result>1</result><redirecturl>44</redirecturl>";
//exit;

                               }
                           } else {
                           	 $rs = save_money($spec_code[0], $payAmount/100, '99bill');
                           	 /////////////////////////////////////2013.6.20
                           	 w_file('notice.txt','not order');
                           	 if (!$rs) {
                                    $has_error = true;
                                    $error_msg = __('Cannot save your money!');
					                                    echo" <result>1</result><redirecturl>http://".$_SERVER['HTTP_HOST']."/index.php?_m=mod_order&_a=userlistorder</redirecturl>";
                               } else {
                                   $pay_histo->finished = '1';
                                   $pay_histo->return_time = time();
                                   $pay_histo->save();
                                   echo" <result>1</result><redirecturl>http://".$_SERVER['HTTP_HOST']."/index.php?_m=mod_order&_a=useraccountstate</redirecturl>";
//  echo" <result>1</result><redirecturl>44</redirecturl>";
//exit;
                               }
                           	 
                           	 ////////////////////////////////////2013.6.20
/*                                $has_error = true;
                                $error_msg = __('Unknown Order!');
                                echo" <result>1</result><redirecturl>5555".$outer_oid."</redirecturl>";
//                                    echo" <result>1</result><redirecturl>http://".$_SERVER['HTTP_HOST']."/index.php?_m=mod_order&_a=userlistorder</redirecturl>";
echo '$outer_oid--55';
exit;*/
                                    echo" <result>1</result><redirecturl>http://".$_SERVER['HTTP_HOST']."/index.php?_m=mod_order&_a=userlistorder</redirecturl>";

                           }
                      break;
                  default:
                  	w_file('notice.txt','default');
                        $has_error = true;
                        $error_msg = __('Unknown Payment!');
                      break;
            }
       } else {
            $has_error = true;
            $error_msg = __('Unknown Payment!');
            w_file('notice.txt','unknow payment');
       }
    }else{
        $has_error = true;
        $error_msg = __('Data verification failed! Cannot continue!');
        w_file('notice.txt','Data verification failed! Cannot continue');
    }
}

function appendParam($returnStr,$paramId,$paramValue){
    if($returnStr!=""){
        if($paramValue!=""){
            $returnStr.="&".$paramId."=".$paramValue;
        }
    }else{
        If($paramValue!=""){
            $returnStr=$paramId."=".$paramValue;
        }
    }
    return $returnStr;
}

if ($has_error) {
    include_once(ROOT.'/view/onlinepay'.DS.'return_err.php');
} else {
    include_once(ROOT.'/view/onlinepay'.DS.$ok_script);
}
function w_file($file,$sou){
	$fp = fopen($file, "a+");//文件被清空后再写入
	$flag=fwrite($fp,$sou);
	fclose($fp); 
}
?>