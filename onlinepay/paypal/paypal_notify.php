<?php
define('IN_CONTEXT', 1);
require_once("../payment_load.php");

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value)
{
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}
// post back to PayPal system to validate
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) ."\r\n\r\n";

$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);    //如果是测试账户，则投递到www.sandbox.paypal.com，否则投递到www.paypal.com
foreach($_POST as $k => $v)
{
	$$k = $v;
}

if (!$fp)
{
	fclose($fp);
	return false;
}
else
{
	fputs($fp, $header . $req);
	while (!feof($fp))
	{
		$res = fgets($fp, 1024);
		if (strcmp($res, 'VERIFIED') == 0)
		{
			//付款成功的代码
			$spec_code = parse_speccode($custom);
			$pay_histo = check_history($spec_code[0],$item_number,'3',$spec_code[1],'0');
			if($pay_histo)
			{
				if (substr($item_number, 0, 3) == 'ord') 
				{
					$order_id = substr($item_number, 3);
					$rs = update_order($spec_code[0], $order_id, $mc_gross);
					if(!$rs)
					{
						$has_error = true;
						$current_time = date('Y-m-d h:m:s',$spec_code[1]);
						$msg = "\n订单号:{$item_number}金额在{$current_time}可能已经完成交易，但交易金额与您公布的价格不符，请慎重对待！";
						if(file_exists('notice.txt'))
						{
							chmod('notice.txt',0755);
							$handle = '';
							if(is_writable('notice.txt'))
							{
								if ($handle = fopen('notice.txt', 'a')) 
								{
							    	fwrite($handle, $msg);
							    }
							}
						}
						else
						{
							file_put_contents('notice.txt',$msg);
						}
					}
					else
					{
						$pay_histo->finished = '1';
                        $pay_histo->return_time = time();
                        $pay_histo->save();
					}
				}
				elseif(substr($item_number, 0, 3) == 'sav')
				{
					$rs = save_money($spec_code[0], $mc_gross, 'paypal');
					if (!$rs) {
						$error_msg = __('Cannot save your money!');
						file_put_contents('notice.txt',$error_msg);
					} else {
						$pay_histo->finished = '1';
						$pay_histo->return_time = time();
						$pay_histo->save();
					}
				}
				else
				{
                    $error_msg = __('Unknown Order!');
                    logRegister('notice.txt',$error_msg);
				}
			}
			else
			{
				$error_msg = __('Unknown Order!');
				logRegister('notice.txt',$error_msg);
			}
		}
		elseif (strcmp($res, 'INVALID') == 0)
		{
			//付款失败的代码
			$error_msg = __('Unknown Order!');
			logRegister('notice.txt',$error_msg);
			fclose($fp);
//			return false;
		}
	}
}
?>