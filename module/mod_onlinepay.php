<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModOnlinepay extends Module {
    protected $_filters = array(
        'check_login' => ''
    );

    public function index() {
        $this->assign('page_title', __('Online Payment'));

        $curr_user_id = SessionHolder::get('user/id');
        $curr_order_id = ParamHolder::get('o_id', 0);
        if (intval($curr_order_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        $o_order = new OnlineOrder();
        $curr_order =& $o_order->find("id=? AND user_id=?", array($curr_order_id, $curr_user_id));
        if (!$curr_order) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $o_userext = new UserExtend();
        $curr_userext =& $o_userext->find("user_id=?", array($curr_user_id));
        if (!$curr_userext) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $this->assign('curr_userext', $curr_userext);
        $this->assign('curr_order', $curr_order);

        $this->_getEnabledPayAccounts();
    }

    
    public function saving() {
        $this->assign('page_title', __('Online Saving'));

        $this->_getEnabledPayAccounts();
    }

    public function do_payment() {
        $this->assign('page_title', __('Sending Payment Infomation, please wait...'));

        $curr_user_id = SessionHolder::get('user/id');
        $use_balance = ParamHolder::get('use_balance');
        
        if (!$curr_user_id) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $curr_order_id = ParamHolder::get('o_id', 0);
        if (intval($curr_order_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        $o_order = new OnlineOrder();
        $curr_order =& $o_order->find("id=? AND user_id=?", array($curr_order_id, $curr_user_id));
        if (!$curr_order) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        // Get all product names
        $order_prods = __('Product Order');
        $curr_order->loadRelatedObjects(REL_CHILDREN);
        $prd_num = sizeof($curr_order->slaves['OrderProduct']);
        if (sizeof($curr_order->slaves['OrderProduct']) > 0) {
            $order_prods = '';
            foreach ($curr_order->slaves['OrderProduct'] as $order_product) {
                $order_prods .= $order_product->product_name.';';
            }
        }

        $payacct_id =& ParamHolder::get('paygate', 0);
        if (intval($payacct_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        $curr_payacct = new PaymentAccount($payacct_id);
        $curr_payacct->loadRelatedObjects(REL_PARENT);

        // Now we have a valid payment account, add payment history
        $new_histo = new OnlinepayHistory();
        $new_histo->user_id = $curr_user_id;
        $new_histo->outer_oid = "ord".$curr_order->oid;
        $new_histo->payment_provider_id = $curr_payacct->payment_provider_id;
        $new_histo->send_time = time();
        $new_histo->return_time = 0;
        $new_histo->finished = '0';
        //currency
        $curr_code = CURRENCY;
        if (empty($curr_code)) {
        	$curr_locale = trim(SessionHolder::get('_LOCALE'));
        	if ($curr_locale=='zh_CN') {
        		$curr_code = "CNY";
        	}else{
        		$curr_code = "USD";
        	}
        }

        // Specific code for return use
        $spec_code = ">$curr_user_id,{$new_histo->send_time}";
        if ($use_balance == 1) {
        	$o_userext = new UserExtend();
	        $curr_userext =& $o_userext->find("user_id=?", array($curr_user_id));
	        if (!$curr_userext) {
	            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
	            return '_error';
	        }
	        $curr_order->discount_price = $curr_order->discount_price-$curr_userext->balance;
	        if ($curr_order->discount_price<=0) {
	        	 $this->assign('json', Toolkit::jsonERR(__('Balance sufficient, please use the balance to pay'). Html::input('button', 'direct_buy', __('Buy Now'), 'class="submit_order orange" onclick="window.location.href=\'index.php?_m=mod_order&amp;_a=confirm_buynow&amp;o_id='.$curr_order->id.'\'"')));
	            return '_error';
	        }else{
	        	$curr_userext->total_payment = floatval($curr_userext->total_payment) + floatval($curr_order->balance);
                $curr_userext->balance = 0;
                $curr_userext->save();
	        }
        }

        if ($curr_payacct->masters['PaymentProvider']->name == 'alipay') {
            $strReturn = str_replace('index.php', 'onlinepay/alipay/return.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
			$strNotify = str_replace('index.php', 'onlinepay/alipay/notify.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
            include_once(ROOT.'/onlinepay/alipay/alipay_service.php');
            $parameter = array(
                "service"        => "trade_create_by_buyer",  //交易类型
                "partner"        => $curr_payacct->partner_id,         //合作商户号
                "return_url"     => $strReturn,      //同步返回
                "notify_url"     => $strNotify,      //异步返回
                "_input_charset" => "utf-8",  //字符集，默认为GBK
                "subject"        => $order_prods,       //商品名称，必填
                "body"           => $order_prods.$spec_code,       //商品描述，必填
                "out_trade_no"   => "ord".$curr_order->oid,     //商品外部交易号，必填（保证唯一性）
                "price"          => strval($curr_order->discount_price),           //商品单价，必填（价格不能为0）
                "payment_type"   => "1",              //默认为1,不需要修改
                "quantity"       => "1",              //商品数量，必填

                "logistics_fee"      => strval($curr_order->delivery_fee),        //物流配送费用
                "logistics_payment"  =>'BUYER_PAY',   //物流费用付款方式：SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
                "logistics_type"     =>'EXPRESS',     //物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)

                "show_url"       => $curr_payacct->seller_site_url,        //商品相关网站
                "seller_email"   => $curr_payacct->seller_account     //卖家邮箱，必填
            );
            $alipay = new alipay_service($parameter, $curr_payacct->partner_key, "MD5");
            $link = $alipay->create_url();
            $postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";

            // Save history when the payment account available
            $new_histo->save();
        } else if ($curr_payacct->masters['PaymentProvider']->name == '99bill') {
            $curr_user = new User(SessionHolder::get('user/id'));
			$strReceive = str_replace('index.php', 'onlinepay/99bill/receive.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
            // 支付参数收集
            $merchantAcctId = $curr_payacct->partner_id;    //人民币网关账户号
            $key = trim($curr_payacct->partner_key);    //人民币网关密钥
            $inputCharset = "1";  //字符集.固定选择值。可为空。1代表UTF-8; 2代表GBK; 3代表gb2312
            $bgUrl = $strReceive;   //服务器接受支付结果的后台地址.与[pageUrl]不能同时为空。必须是绝对地址。
            $version = "v2.0";    //网关版本.固定值
            $language = "1";  //语言种类.固定选择值。1代表中文；2代表英文
            $signType = "1";	 //签名类型.固定值，1代表MD5签名
            $payerName = $curr_user->login; //支付人姓名
            $payerContactType = "1";  //支付人联系方式类型.固定选择值
            $payerContact = $curr_user->email;   //支付人联系方式，只能选择Email或手机号
            $orderId = "ord".$curr_order->oid;    //商户订单号
            $orderAmount = strval(intval(floatval($curr_order->total_amount) * 100));   //订单金额，以分为单位，必须是整型数字
            $orderTime = date('YmdHis');  //订单提交时间。14位数字。年[4位]月[2位]日[2位]时[2位]分[2位]秒[2位]
            $productName = $order_prods; //商品名称
            $productNum = $prd_num;    //商品数量
            $productId = "";  //商品代码
            $productDesc = $order_prods;    //商品描述
            $ext1 = $spec_code;   //扩展字段1
            $ext2 = "";   //扩展字段2
            //支付方式.固定选择值
            ///只能选择00、10、11、12、13、14
            ///00：组合支付（网关支付页面显示快钱支持的各种支付方式，推荐使用）10：银行卡支付（网关支付页面只显示银行卡支付）.11：电话银行支付（网关支付页面只显示电话支付）.12：快钱账户支付（网关支付页面只显示快钱账户支付）.13：线下支付（网关支付页面只显示线下支付方式）
            $payType = "00";
            //同一订单禁止重复提交标志
            ///固定选择值： 1、0
            ///1代表同一订单号只允许提交1次；0表示同一订单号在没有支付成功的前提下可重复提交多次。默认为0建议实物购物车结算类商户采用0；虚拟产品类商户采用1
            $redoFlag = "0";
            $pid = "";//$curr_payacct->seller_account;    //快钱的合作伙伴的账户号

            // 计算签名
            $signMsgVal = $this->_appendParam($signMsgVal, "inputCharset" ,$inputCharset);
            $signMsgVal = $this->_appendParam($signMsgVal, "bgUrl" ,$bgUrl);
            $signMsgVal = $this->_appendParam($signMsgVal, "version" ,$version);
            $signMsgVal = $this->_appendParam($signMsgVal, "language" ,$language);
            $signMsgVal = $this->_appendParam($signMsgVal, "signType" ,$signType);
            $signMsgVal = $this->_appendParam($signMsgVal, "merchantAcctId" ,$merchantAcctId);
            $signMsgVal = $this->_appendParam($signMsgVal, "payerName" ,$payerName);
            $signMsgVal = $this->_appendParam($signMsgVal, "payerContactType" ,$payerContactType);
            $signMsgVal = $this->_appendParam($signMsgVal, "payerContact" ,$payerContact);
            $signMsgVal = $this->_appendParam($signMsgVal, "orderId" ,$orderId);
            $signMsgVal = $this->_appendParam($signMsgVal, "orderAmount" ,$orderAmount);
            $signMsgVal = $this->_appendParam($signMsgVal, "orderTime" ,$orderTime);
            $signMsgVal = $this->_appendParam($signMsgVal, "productName" ,$productName);
            $signMsgVal = $this->_appendParam($signMsgVal, "productNum" ,$productNum);
            $signMsgVal = $this->_appendParam($signMsgVal, "productId" ,$productId);
            $signMsgVal = $this->_appendParam($signMsgVal, "productDesc" ,$productDesc);
            $signMsgVal = $this->_appendParam($signMsgVal, "ext1" ,$ext1);
            $signMsgVal = $this->_appendParam($signMsgVal, "ext2" ,$ext2);
            $signMsgVal = $this->_appendParam($signMsgVal, "payType" ,$payType);
            $signMsgVal = $this->_appendParam($signMsgVal, "redoFlag" ,$redoFlag);
            $signMsgVal = $this->_appendParam($signMsgVal, "pid" ,$pid);
            $signMsgVal = $this->_appendParam($signMsgVal, "key" ,$key);
            $signMsg= strtoupper(md5($signMsgVal));

            // Generation FORM
            $postform = <<<FORM
<form name="kqPay" method="post" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm">
    <input type="hidden" name="inputCharset" value="$inputCharset"/>
    <input type="hidden" name="bgUrl" value="$bgUrl"/>
    <input type="hidden" name="version" value="$version"/>
    <input type="hidden" name="language" value="$language"/>
    <input type="hidden" name="signType" value="$signType"/>
    <input type="hidden" name="signMsg" value="$signMsg"/>
    <input type="hidden" name="merchantAcctId" value="$merchantAcctId"/>
    <input type="hidden" name="payerName" value="$payerName"/>
    <input type="hidden" name="payerContactType" value="$payerContactType"/>
    <input type="hidden" name="payerContact" value="$payerContact"/>
    <input type="hidden" name="orderId" value="$orderId"/>
    <input type="hidden" name="orderAmount" value="$orderAmount"/>
    <input type="hidden" name="orderTime" value="$orderTime"/>
    <input type="hidden" name="productName" value="$productName"/>
    <input type="hidden" name="productNum" value="$productNum"/>
    <input type="hidden" name="productId" value="$productId"/>
    <input type="hidden" name="productDesc" value="$productDesc"/>
    <input type="hidden" name="ext1" value="$ext1"/>
    <input type="hidden" name="ext2" value="$ext2"/>
    <input type="hidden" name="payType" value="$payType"/>
    <input type="hidden" name="redoFlag" value="$redoFlag"/>
    <input type="hidden" name="pid" value="$pid"/>
</form>
<script type="text/javascript" language="javascript">
<!--
    document.forms["kqPay"].submit();
//-->
</script>

FORM;

            // Save history when the payment account available
            $new_histo->save();
        } elseif($curr_payacct->masters['PaymentProvider']->name == 'alipaymed') {
        	$return_url = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return_url.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	$notify_url = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/notify_url.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	include_once(ROOT."/onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/class/alipay_service.php");
            $parameter = array(
                "service"        => "create_partner_trade_by_buyer",  //交易类型
            	"payment_type"   => "1",              //默认为1,不需要修改
                "partner"        => $curr_payacct->partner_id,         //合作商户号
            	"seller_email"   => $curr_payacct->seller_account,     //卖家邮箱，必填
                "return_url"     => $return_url,
                "notify_url"     => $notify_url,
                "_input_charset" => "utf-8",  //字符集，默认为GBK
            	"show_url"       => $curr_payacct->seller_site_url,        //商品相关网站
                "subject"        => $order_prods,       //商品名称，必填
                "body"           => $order_prods.$spec_code,       //商品描述，必填
                "out_trade_no"   => "ord".$curr_order->oid,     //商品外部交易号，必填（保证唯一性）
                "price"          => strval($curr_order->discount_price),           //商品单价，必填（价格不能为0）
                
                "quantity"       => "1",              //商品数量，必填

                "logistics_fee"      => strval($curr_order->delivery_fee),        //物流配送费用
                "logistics_payment"  =>'BUYER_PAY',   //物流费用付款方式：SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
                "logistics_type"     =>'EXPRESS'     //物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)

            );
            $alipay = new alipay_service($parameter, $curr_payacct->partner_key, "MD5");
            $link = $alipay->create_url();
            $postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";

            // Save history when the payment account available
            $new_histo->save();
        } else if ($curr_payacct->masters['PaymentProvider']->name == 'paypal') {
        	//TODO:
        	//$order_prods总商品列表
        	$notify_url = str_replace('index.php', 'onlinepay/paypal/paypal_notify.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	$return_url = str_replace('index.php', 'onlinepay/paypal/paypal_return.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	$amount = strval($curr_order->discount_price);
        	$shipping = strval($curr_order->delivery_fee);
        	$product_name = __('Product Order');
        	$paypal_interface_address = 'https://www.paypal.com/cgi-bin/webscr';//测试地址待修改
        	$item_number = "ord".$curr_order->oid;
        	
        	$postform = <<<FORM
<form name="kqPay" action="$paypal_interface_address" method="post">
<input type="hidden" name="notify_url" value="$notify_url" /><!--测试地址待修改-->
<input type="hidden" name="return" value="$return_url" /><!--测试地址待修改-->
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="$curr_payacct->seller_account">
<input type="hidden" name="item_name" value="$product_name">
<input type="hidden" name="item_number" value="$item_number">
<input type="hidden" name="currency_code" value="$curr_code"><!--CNY 人民币 USD 美元-->
<input type="hidden" name="amount" value="$amount">
<input type="hidden" name="shipping" value="$shipping">
<input type="hidden" name="custom" value="$spec_code">
<input type="hidden" name="charset" value="utf-8"> 
<input type='hidden' name='no_note' value=''>
</form>
<script type="text/javascript" language="javascript">
<!--
    document.forms["kqPay"].submit();
//-->
</script>

FORM;
			$new_histo->save();
        } else if ($curr_payacct->masters['PaymentProvider']->name == 'paypalen') {
        	//TODO:
        	//$order_prods总商品列表
        	$notify_url = str_replace('index.php', 'onlinepay/paypal/paypal_notify.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	$return_url = str_replace('index.php', 'onlinepay/paypal/paypal_return.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	$amount = strval($curr_order->discount_price);
        	$shipping = strval($curr_order->delivery_fee);
        	$product_name = __('Product Order');
        	$paypal_interface_address = 'https://www.paypal.com/cgi-bin/webscr';//测试地址待修改
        	$item_number = "ord".$curr_order->oid;
        	
        	$postform = <<<FORM
<form name="kqPay" action="$paypal_interface_address" method="post">
<input type="hidden" name="notify_url" value="$notify_url" /><!--测试地址待修改-->
<input type="hidden" name="return" value="$return_url" /><!--测试地址待修改-->
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="$curr_payacct->seller_account">
<input type="hidden" name="item_name" value="$product_name">
<input type="hidden" name="item_number" value="$item_number">
<input type="hidden" name="currency_code" value="$curr_code"><!--CNY 人民币 USD 美元-->
<input type="hidden" name="amount" value="$amount">
<input type="hidden" name="shipping" value="$shipping">
<input type="hidden" name="custom" value="$spec_code">
<input type="hidden" name="charset" value="utf-8"> 
<input type='hidden' name='no_note' value=''>
</form>
<script type="text/javascript" language="javascript">
<!--
    document.forms["kqPay"].submit();
//-->
</script>

FORM;
			$new_histo->save();
        }else if ($curr_payacct->masters['PaymentProvider']->name == 'moneybookers') {
        	//TODO:
        	//$order_prods总商品列表
        	$notify_url = str_replace('index.php', 'onlinepay/paypal/paypal_notify.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	$return_url = str_replace('index.php', 'onlinepay/paypal/paypal_return.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	$amount = strval($curr_order->discount_price);
        	$shipping = strval($curr_order->delivery_fee);
        	$product_name = __('Product Order');
        	$paypal_interface_address = 'https://www.moneybookers.com/app/payment.pl';//测试地址待修改
        	$item_number = "ord".$curr_order->oid;

        	$CURRENCY = CURRENCY;
        	$postform = <<<FORM
<form name="kqPay" action="$paypal_interface_address" method="post">
<input type="hidden" name="pay_to_email" value="$curr_payacct->seller_account">
<input type="hidden" name="status_url" value="$curr_payacct->seller_account">
<input type="hidden" name="language" value="CN">
<input type="hidden" name="amount" value="$amount">
<input type="hidden" name="currency" value="$CURRENCY">
<input type="hidden" name="detail1_description" value="$product_name">
<input type="hidden" name="detail1_text" value="$product_name">
</form>
<script type="text/javascript" language="javascript">
<!--
    document.forms["kqPay"].submit();
//-->
</script>

FORM;
			$new_histo->save();
        }else if ($curr_payacct->masters['PaymentProvider']->name == 'tencentimd') {
        	include_once("onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/classes/PayRequestHandler.class.php");
        	$bargainor_id = $curr_payacct->seller_account;//商户号
        	$key = $curr_payacct->partner_key;//密钥
        	
        	$return_url = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return_url.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	
        	//date_default_timezone_set(PRC);
			$strDate = date("Ymd");
			$strTime = date("His");
			
			//4位随机数
			$randNum = rand(1000, 9999);
			$strReq = $strTime . $randNum;
			
			$sp_billno = "ord".$curr_order->oid;//订单号
			
			/* 财付通交易单号，规则为：10位商户号+8位时间（YYYYmmdd)+10位流水号 */
			$transaction_id = $bargainor_id . $strDate . $strReq;
			
			$total_fee = strval(intval(floatval($curr_order->total_amount) * 100));//商品价格（包含运费），以分为单位
			
			$product_name = $order_prods;
			$desc = mb_convert_encoding($product_name, 'GB2312', 'UTF-8');
			$attach = ">{$curr_user_id}_{$new_histo->send_time}_{$sp_billno}";

			/* 创建支付请求对象 */
			$reqHandler = new PayRequestHandler();
			$reqHandler->init();
			$reqHandler->setKey($key);
			
			/* 设置支付参数 */
			$reqHandler->setParameter("bargainor_id", $bargainor_id);			//商户号
			$reqHandler->setParameter("sp_billno", $sp_billno);					//商户订单号
			$reqHandler->setParameter("transaction_id", $transaction_id);		//财付通交易单号
			$reqHandler->setParameter("total_fee", $total_fee);					//商品总金额,以分为单位
			$reqHandler->setParameter("return_url", $return_url);				//返回处理地址
			$reqHandler->setParameter("desc", $desc);	//商品名称
			$reqHandler->setParameter("attach", $attach);//商家附加信息
			//用户ip,测试环境时不要加这个ip参数，正式环境再加此参数
			$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);

			$new_histo->save();
			
			$reqHandler->doSend();
        } else if($curr_payacct->masters['PaymentProvider']->name == 'tencentmed') {
        	include_once("onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/classes/MediPayRequestHandler.class.php");
        	
        	//date_default_timezone_set(PRC);
			$curDateTime = date("YmdHis");
			$randNum = rand(1000, 9999);
			$key = $curr_payacct->partner_key;//平台商密钥
			$chnid = $curr_payacct->seller_account;//平台商账号
			$seller = $curr_payacct->seller_account;//卖家账号
			
			$mch_desc = __('Thank you for your shopping');//交易说明
			$mch_desc = mb_convert_encoding($mch_desc, 'GB2312', 'UTF-8');
		
			$mch_name = $order_prods;
			$mch_name = mb_convert_encoding($mch_name, 'GB2312', 'UTF-8');//产品名称有字符限制
			
			$mch_price = strval(intval(floatval($curr_order->discount_price) * 100));//产品总价
        	$transport_fee = strval(intval(floatval($curr_order->delivery_fee) * 100));//运费
        	$mch_returl = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/mch_returl.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	
        	$mch_vno = "ord".$curr_order->oid;
        	$show_url = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/show_url.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	/* 物流公司或物流方式说明 */
			$transport_desc = __('Depending on bargainor');
			$transport_desc = mb_convert_encoding($transport_desc, 'GB2312', 'UTF-8');//运输描述有字符限制
			
			$attach = ">{$curr_user_id}_{$new_histo->send_time}_{$mch_vno}";
        	/* 创建支付请求对象 */
			$reqHandler = new MediPayRequestHandler();
			$reqHandler->init();
			$reqHandler->setKey($key);
			
			//设置支付参数
			$reqHandler->setParameter("chnid", $chnid);						//平台商帐号
			$reqHandler->setParameter("encode_type", "1");					//编码类型 1:gbk 2:utf-8
			$reqHandler->setParameter("mch_desc", $mch_desc);				//交易说明
			$reqHandler->setParameter("mch_name", $mch_name);				//商品名称
			$reqHandler->setParameter("mch_price", $mch_price);				//商品总价，单位为分
			$reqHandler->setParameter("mch_returl", $mch_returl);			//回调通知URL
			$reqHandler->setParameter("mch_type", "1");						//交易类型：1、实物交易，2、虚拟交易
			$reqHandler->setParameter("mch_vno", $mch_vno);					//商家的定单号
			$reqHandler->setParameter("need_buyerinfo", "2");				//是否需要在财付通填定物流信息，1：需要，2：不需要。
			$reqHandler->setParameter("seller", $seller);					//卖家财付通帐号
			$reqHandler->setParameter("show_url",	$show_url);				//支付后的商户支付结果展示页面
			$reqHandler->setParameter("transport_desc", $transport_desc);	//物流公司或物流方式说明
			$reqHandler->setParameter("transport_fee", $transport_fee);		//需买方另支付的物流费用
			$reqHandler->setParameter("attach", $attach);					//商家附加信息
			$new_histo->save();
			
			//重定向到财付通支付
			$reqHandler->doSend();
        }  elseif($curr_payacct->masters['PaymentProvider']->name == 'alipayimd') {
        	
        	$return_url = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return_url.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	$notify_url = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/notify_url.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	include_once(ROOT."/onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/class/alipay_service.php");
            $parameter = array(
                "service"        => "create_direct_pay_by_user",  //交易类型
           		"payment_type"   => "1",              //默认为1,不需要修改
                "partner"        => $curr_payacct->partner_id,         //合作商户号
            	"seller_email"   => $curr_payacct->seller_account,     //卖家邮箱，必填
                "return_url"     => $return_url,
                "notify_url"     => $notify_url,
                "_input_charset" => "utf-8",  //字符集，默认为GBK
            	"show_url"       => $curr_payacct->seller_site_url,        //商品相关网站
            	"out_trade_no"   => "ord".$curr_order->oid,     //商品外部交易号，必填（保证唯一性）
                "subject"        => $order_prods,       //商品名称，必填
                "body"           => $order_prods.$spec_code,       //商品描述，必填
             //   "total_fee"      => strval($curr_order->discount_price) + strval($curr_order->delivery_fee)           //商品单价，必填（价格不能为0）
                "total_fee"      => strval($curr_order->total_amount)            //商品单价，必填（价格不能为0）
            );
            $alipay = new alipay_service($parameter, $curr_payacct->partner_key, "MD5");
            $link = $alipay->create_url();
            $postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";

            // Save history when the payment account available
            $new_histo->save();
        } else {
            $postform = __('Payment gateway not supported!');
        }

        $this->assign('postform', $postform);
    }

    public function do_sav_payment() {
        $this->assign('page_title', __('Sending Payment Infomation, please wait...'));

        $curr_user_id = SessionHolder::get('user/id');
        if (!$curr_user_id) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $saving_amount = ParamHolder::get('amount', '0.00');
        if (number_format($saving_amount, 2) == '0.00') {
            $this->assign('json', Toolkit::jsonERR(__('Saving amount could not be empty or 0.00')));
            return '_error';
        }

        $payacct_id =& ParamHolder::get('paygate', 0);
        if (intval($payacct_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        $curr_payacct = new PaymentAccount($payacct_id);
        $curr_payacct->loadRelatedObjects(REL_PARENT);

        $order_seed = date('YmdHis');
        // Now we have a valid payment account, add payment history
        $new_histo = new OnlinepayHistory();
        $new_histo->user_id = $curr_user_id;
        $new_histo->outer_oid = "sav".$order_seed;
        $new_histo->payment_provider_id = $curr_payacct->payment_provider_id;
        $new_histo->send_time = time();
        $new_histo->return_time = 0;
        $new_histo->finished = '0';

        // Specific code for return use
        $spec_code = ">$curr_user_id,{$new_histo->send_time}";

        if ($curr_payacct->masters['PaymentProvider']->name == 'alipay') {
            $strReturn = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
			$strNotify = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/notify.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
            include_once(ROOT.'/onlinepay/alipay/alipay_service.php');
            $parameter = array(
                "service"        => "trade_create_by_buyer",  //交易类型
                "partner"        => $curr_payacct->partner_id,         //合作商户号
                "return_url"     => $strReturn,
                "notify_url"     => $strNotify,
                "_input_charset" => "utf-8",  //字符集，默认为GBK
                "subject"        => __('Online Saving'),       //商品名称，必填
                "body"           => $spec_code,       //商品描述，必填
                "out_trade_no"   => "sav".$order_seed,     //商品外部交易号，必填（保证唯一性）
                "price"          => strval($saving_amount),           //商品单价，必填（价格不能为0）
                "payment_type"   => "1",              //默认为1,不需要修改
                "quantity"       => "1",              //商品数量，必填

                "logistics_fee"      => "0",        //物流配送费用
                "logistics_payment"  =>'BUYER_PAY',   //物流费用付款方式：SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
                "logistics_type"     =>'EXPRESS',     //物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)

                "show_url"       => $curr_payacct->seller_site_url,        //商品相关网站
                "seller_email"   => $curr_payacct->seller_account     //卖家邮箱，必填
            );
            $alipay = new alipay_service($parameter, $curr_payacct->partner_key, "MD5");
            $link = $alipay->create_url();
            $postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";

            // Save history when the payment account available
            $new_histo->save();
        } else if ($curr_payacct->masters['PaymentProvider']->name == '99bill') {
            $strReceive = str_replace('index.php', 'onlinepay/99bill/receive.php', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
            $curr_user = new User(SessionHolder::get('user/id'));

            // 支付参数收集
            $merchantAcctId = $curr_payacct->partner_id;    //人民币网关账户号
            $key = $curr_payacct->partner_key;    //人民币网关密钥
            $inputCharset = "1";  //字符集.固定选择值。可为空。1代表UTF-8; 2代表GBK; 3代表gb2312
            $bgUrl = $strReceive;   //服务器接受支付结果的后台地址.与[pageUrl]不能同时为空。必须是绝对地址。
            $version = "v2.0";    //网关版本.固定值
            $language = "1";  //语言种类.固定选择值。1代表中文；2代表英文
            $signType = "1";	 //签名类型.固定值，1代表MD5签名
            $payerName = $curr_user->login; //支付人姓名
            $payerContactType = "1";  //支付人联系方式类型.固定选择值
            $payerContact = $curr_user->email;   //支付人联系方式，只能选择Email或手机号
            $orderId = "sav".$order_seed;    //商户订单号
            $orderAmount = strval(intval(floatval($saving_amount) * 100));   //订单金额，以分为单位，必须是整型数字
            $orderTime = date('YmdHis');  //订单提交时间。14位数字。年[4位]月[2位]日[2位]时[2位]分[2位]秒[2位]
            $productName = __('Online Saving'); //商品名称
            $productNum = 0;    //商品数量
            $productId = "";  //商品代码
            $productDesc = "";    //商品描述
            $ext1 = $spec_code;   //扩展字段1
            $ext2 = "";   //扩展字段2
            //支付方式.固定选择值
            ///只能选择00、10、11、12、13、14
            ///00：组合支付（网关支付页面显示快钱支持的各种支付方式，推荐使用）10：银行卡支付（网关支付页面只显示银行卡支付）.11：电话银行支付（网关支付页面只显示电话支付）.12：快钱账户支付（网关支付页面只显示快钱账户支付）.13：线下支付（网关支付页面只显示线下支付方式）
            $payType = "00";
            //同一订单禁止重复提交标志
            ///固定选择值： 1、0
            ///1代表同一订单号只允许提交1次；0表示同一订单号在没有支付成功的前提下可重复提交多次。默认为0建议实物购物车结算类商户采用0；虚拟产品类商户采用1
            $redoFlag = "0";
            $pid = $curr_payacct->seller_account;    //快钱的合作伙伴的账户号

            // 计算签名
            $signMsgVal = $this->_appendParam($signMsgVal, "inputCharset" ,$inputCharset);
            $signMsgVal = $this->_appendParam($signMsgVal, "bgUrl" ,$bgUrl);
            $signMsgVal = $this->_appendParam($signMsgVal, "version" ,$version);
            $signMsgVal = $this->_appendParam($signMsgVal, "language" ,$language);
            $signMsgVal = $this->_appendParam($signMsgVal, "signType" ,$signType);
            $signMsgVal = $this->_appendParam($signMsgVal, "merchantAcctId" ,$merchantAcctId);
            $signMsgVal = $this->_appendParam($signMsgVal, "payerName" ,$payerName);
            $signMsgVal = $this->_appendParam($signMsgVal, "payerContactType" ,$payerContactType);
            $signMsgVal = $this->_appendParam($signMsgVal, "payerContact" ,$payerContact);
            $signMsgVal = $this->_appendParam($signMsgVal, "orderId" ,$orderId);
            $signMsgVal = $this->_appendParam($signMsgVal, "orderAmount" ,$orderAmount);
            $signMsgVal = $this->_appendParam($signMsgVal, "orderTime" ,$orderTime);
            $signMsgVal = $this->_appendParam($signMsgVal, "productName" ,$productName);
            $signMsgVal = $this->_appendParam($signMsgVal, "productNum" ,$productNum);
            $signMsgVal = $this->_appendParam($signMsgVal, "productId" ,$productId);
            $signMsgVal = $this->_appendParam($signMsgVal, "productDesc" ,$productDesc);
            $signMsgVal = $this->_appendParam($signMsgVal, "ext1" ,$ext1);
            $signMsgVal = $this->_appendParam($signMsgVal, "ext2" ,$ext2);
            $signMsgVal = $this->_appendParam($signMsgVal, "payType" ,$payType);
            $signMsgVal = $this->_appendParam($signMsgVal, "redoFlag" ,$redoFlag);
            $signMsgVal = $this->_appendParam($signMsgVal, "pid" ,$pid);
            $signMsgVal = $this->_appendParam($signMsgVal, "key" ,$key);
            $signMsg= strtoupper(md5($signMsgVal));

            // Generation FORM
            $postform = <<<FORM
<form name="kqPay" method="post" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm">
    <input type="hidden" name="inputCharset" value="$inputCharset"/>
    <input type="hidden" name="bgUrl" value="$bgUrl"/>
    <input type="hidden" name="version" value="$version"/>
    <input type="hidden" name="language" value="$language"/>
    <input type="hidden" name="signType" value="$signType"/>
    <input type="hidden" name="signMsg" value="$signMsg"/>
    <input type="hidden" name="merchantAcctId" value="$merchantAcctId"/>
    <input type="hidden" name="payerName" value="$payerName"/>
    <input type="hidden" name="payerContactType" value="$payerContactType"/>
    <input type="hidden" name="payerContact" value="$payerContact"/>
    <input type="hidden" name="orderId" value="$orderId"/>
    <input type="hidden" name="orderAmount" value="$orderAmount"/>
    <input type="hidden" name="orderTime" value="$orderTime"/>
    <input type="hidden" name="productName" value="$productName"/>
    <input type="hidden" name="productNum" value="$productNum"/>
    <input type="hidden" name="productId" value="$productId"/>
    <input type="hidden" name="productDesc" value="$productDesc"/>
    <input type="hidden" name="ext1" value="$ext1"/>
    <input type="hidden" name="ext2" value="$ext2"/>
    <input type="hidden" name="payType" value="$payType"/>
    <input type="hidden" name="redoFlag" value="$redoFlag"/>
    <input type="hidden" name="pid" value="$pid"/>
</form>
<script type="text/javascript" language="javascript">
<!--
    document.forms["kqPay"].submit();
//-->
</script>

FORM;

            // Save history when the payment account available
            $new_histo->save();
        } else if($curr_payacct->masters['PaymentProvider']->name == 'alipayimd') {
        	$return_url = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return_url.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	$notify_url = str_replace('index.php', "onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/notify_url.php", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        	include_once(ROOT."/onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/class/alipay_service.php");
        	$parameter = array(
                "service"        => "create_direct_pay_by_user",  //交易类型
           		"payment_type"   => "1",              //默认为1,不需要修改
                "partner"        => $curr_payacct->partner_id,         //合作商户号
            	"seller_email"   => $curr_payacct->seller_account,     //卖家邮箱，必填
                "return_url"     => $return_url,
                "notify_url"     => $notify_url,
                "_input_charset" => "utf-8",  //字符集，默认为GBK
            	"show_url"       => $curr_payacct->seller_site_url,        //商品相关网站
            	"out_trade_no"   => "sav".$order_seed,     //商品外部交易号，必填（保证唯一性）
                "subject"        => __('Online Saving'),       //商品名称，必填
                "body"           => $spec_code,       //商品描述，必填
                "total_fee"      => strval($saving_amount)
            );
            $alipay = new alipay_service($parameter, $curr_payacct->partner_key, "MD5");
            $link = $alipay->create_url();
            $postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";

            // Save history when the payment account available
            $new_histo->save();
        } else {
            $postform = __('Payment gateway not supported!');
        }

        $this->assign('postform', $postform);

        return 'do_payment';
    }

    private function _appendParam($returnStr, $paramId, $paramValue) {
		if ($returnStr != "") {
            if ($paramValue != "") {
                $returnStr .= "&".$paramId."=".$paramValue;
            }
		} else {
            if ($paramValue != "") {
                $returnStr = $paramId."=".$paramValue;
            }
        }
        return $returnStr;
	}

    private function _getEnabledPayAccounts() {
        $acct_select_array = array();
        $o_payacct = new PaymentAccount();
        $enabled_accts =& $o_payacct->findAll("`enabled`='1'");
        if (sizeof($enabled_accts) > 0) {
            foreach ($enabled_accts as $account) {
                $account->loadRelatedObjects(REL_PARENT);
                if ($account->masters['PaymentProvider']->disp_name=='支付宝') {
                	$account->masters['PaymentProvider']->disp_name = '支付宝双接口';
                }
                $acct_select_array[strval($account->id)] = $account->masters['PaymentProvider']->disp_name;
            }
        }

        $this->assign('payaccts', $acct_select_array);
    }
}
?>
