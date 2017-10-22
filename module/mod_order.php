<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModOrder extends Module {
    protected $_filters = array(
        'check_login' => ''
    );

    public function ordernow() {
        $this->assign('page_title', __('New Order'));
		$user_id = SessionHolder::get('user/id','0');
        // Get ordered products
        $products = array();

        $this->assign('n_prds', $this->_countProductsInCart());
        
        $order_price = 0;
        $order_delivery_fee = 0;
        $order_grand_ttl = 0;
        
        if (isset($_COOKIE['prds'.$user_id])) {           
            foreach ($_COOKIE['prds'.$user_id] as $key => $val) {
                $add_product = new Product($key);
                if (isset($add_product->online_orderable)) {
                    $add_product->order_num = $_COOKIE['n_prd'.$user_id][$key];
                    $add_product->order_ttl_price = number_format(floatval($add_product->discount_price) * intval($_COOKIE['n_prd'.$user_id][$key]), 2);

                    $order_price += floatval($add_product->discount_price) * intval($_COOKIE['n_prd'.$user_id][$key]);
                    $order_delivery_fee += floatval($add_product->delivery_fee);

                    $products[] = $add_product;
                }
            }
        }
        //修复cookie漏洞修复后userid由1 改为0
         //2014.4.1 未登录前userid=0产品Cookies信息
        if (isset($_COOKIE['prds0'])) {           
            foreach ($_COOKIE['prds0'] as $key => $val) {
                $add_product = new Product($key);
                if (isset($add_product->online_orderable)) {
                    $add_product->order_num = $_COOKIE['n_prd0'][$key];
                    $add_product->order_ttl_price = number_format(floatval($add_product->discount_price) * intval($_COOKIE['n_prd0'][$key]), 2);

                    $order_price += floatval($add_product->discount_price) * intval($_COOKIE['n_prd0'][$key]);
                    $order_delivery_fee += floatval($add_product->delivery_fee);

                    $products[] = $add_product;
                }
            }
        }        
        
        $order_grand_ttl = $order_price + $order_delivery_fee;

        $this->assign('order_price', number_format($order_price, 2));
        $this->assign('order_delivery_fee', number_format($order_delivery_fee, 2));
        $this->assign('order_grand_ttl', number_format($order_grand_ttl, 2));
        $this->assign('products', $products);

        // Get user delivery address
        $curr_user_id =& SessionHolder::get('user/id');
        $o_delivery_addr = new DeliveryAddress();
        $my_delivery_addrs =& $o_delivery_addr->findAll('user_id=?', array($curr_user_id));

        $this->assign('my_delivery_addrs', $my_delivery_addrs);
    }

    public function createorder() {
        $submit_order =& trim(ParamHolder::get('submit_order', '', PS_POST));
        if (strlen($submit_order) > 0) {
            $curr_user_id = SessionHolder::get('user/id');
            $cookies_user_id = $curr_user_id;
            $curr_addr_id = ParamHolder::get('selected_delivery_addr', '0');
			$curr_addr_id = intval($curr_addr_id);
            // 13/05/2010 >>
            $curr_message =& ParamHolder::get('message', '');
            $curr_message = htmlspecialchars($curr_message,ENT_QUOTES);
            // 13/05/2010 <<
            try {
                $order_price = 0;
                $order_discount_price = 0;
                $order_delivery_fee = 0;
                $order_grand_ttl = 0;

                
                $cookies_prds = $_COOKIE['prds'.$cookies_user_id];
                if (!isset($cookies_prds)) {
                    $cookies_user_id = '0';//未登录状态下userid==0
                }
                
                
                if (isset($_COOKIE['prds'.$cookies_user_id])) {
                    // Create an order first
                    $o_order = new OnlineOrder();
                    $o_order->oid = date('Y');
                    $o_order->user_id = $curr_user_id;
                    if (intval($curr_addr_id) > 0) {
                        // Get delivery address
                        $o_addr = new DeliveryAddress();
                        $selected_addr =& $o_addr->find("id=? AND user_id=?", array($curr_addr_id, $curr_user_id));
                        if (!$selected_addr) {
                            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                            return '_result';
                        }
                        $o_order->reciever_name = $selected_addr->reciever_name;
                        $o_order->prov_id = $selected_addr->prov_id;
                        $o_order->city_id = $selected_addr->city_id;
                        $o_order->dist_id = $selected_addr->dist_id;
                        $o_order->detailed_addr = $selected_addr->detailed_addr;
                        $o_order->postal = $selected_addr->postal;
                        $o_order->phone = $selected_addr->phone;
                    } else if (intval($curr_addr_id) == 0) {
                        $o_order->reciever_name = '';
                        $o_order->prov_id = '';
                        $o_order->city_id = '';
                        $o_order->dist_id = '';
                        $o_order->detailed_addr = '';
                        $o_order->postal = '';
                        $o_order->phone = '';
                    } else {
                        $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                        return '_result';
                    }
                    $o_order->delivery_fee = '0.00';
                    $o_order->total_price = '0.00';
                    $o_order->discount_price = '0.00';
                    $o_order->total_amount = '0.00';
                    $o_order->order_time = time();
                    $o_order->order_status = '1';
                    $o_order->anonymous_passwd = '';
                    // 13/05/2010 >>
                    $o_order->message = $curr_message;
                    // 13/05/2010 <<
                    $o_order->save();

                    foreach ($_COOKIE['prds'.$cookies_user_id] as $key => $val) {
                        $add_product = new Product($key);

                        if ($add_product->online_orderable) {
                            $o_ordproduct = new OrderProduct();
                            $o_ordproduct->product_id = $add_product->id;
                            $o_ordproduct->online_order_id = $o_order->id;
                            $o_ordproduct->product_name = $add_product->name;
                            $o_ordproduct->product_thumb = $add_product->feature_smallimg;
                            $o_ordproduct->price = $add_product->discount_price;

							//2013/5/9
							$is_c=Toolkit::cuncode($_COOKIE['n_prd'.$cookies_user_id][$key],$_COOKIE['n_prd2'.$cookies_user_id][$key]);
							if(!$is_c){
								$this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
								return '_result';
							}
                            $o_ordproduct->amount = $_COOKIE['n_prd'.$cookies_user_id][$key];
                            $o_ordproduct->save();

                            $order_price += floatval($add_product->price) * intval($_COOKIE['n_prd'.$cookies_user_id][$key]);
                            $order_discount_price += floatval($add_product->discount_price) * intval($_COOKIE['n_prd'.$cookies_user_id][$key]);
                            $order_delivery_fee += floatval($add_product->delivery_fee);
                        }
                        ShoppingCart::removeProduct($key);
                        ShoppingCart::discardProductNum();
                    }

                    if (intval($curr_addr_id) > 0) {
                        $order_grand_ttl = $order_discount_price + $order_delivery_fee;
                    } else {
                        $order_grand_ttl = $order_discount_price;
                    }

                    $o_order->oid = date('Y').str_pad(strval($o_order->id), 6, '0', STR_PAD_LEFT);
                    if (intval($curr_addr_id) > 0) { $o_order->delivery_fee = $order_delivery_fee; }
                    $o_order->total_price = $order_price;
                    $o_order->discount_price = $order_discount_price;
                    $o_order->total_amount = $order_grand_ttl;
                    $o_order->save();

                    $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_order', 'ordercreated', array('o_id' => $o_order->id)))));
                    return '_result';
                } else {
                    $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                    return '_result';
                }
            } catch (Exception $ex) {
                $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
                return '_result';
            }
        } else {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
    }

    public function ordercreated() {
        $this->assign('page_title', __('Current Order'));

        $curr_user_id = SessionHolder::get('user/id');
        $curr_order_id = ParamHolder::get('o_id', 0);
        if (intval($curr_order_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        
        $o_order = new OnlineOrder();
        if (strlen($curr_order_id)==10) {
        	 $curr_order =& $o_order->find("oid=? AND user_id=?", array($curr_order_id, $curr_user_id));
        }else{
        	 $curr_order =& $o_order->find("id=? AND user_id=?", array($curr_order_id, $curr_user_id));
        }
       
        if (!$curr_order) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $this->assign('curr_order', $curr_order);
         $o_payacct = new PaymentAccount();
        $enabled_accts =& $o_payacct->findAll("`enabled`='1'");
        $this->assign('payaccts', $enabled_accts);

        $curr_order->loadRelatedObjects(REL_CHILDREN);
        $order_prods =& $curr_order->slaves['OrderProduct'];
        for ($i = 0; $i < sizeof($order_prods); $i++) {
            $order_prods[$i]->ttl_price = number_format(floatval($order_prods[$i]->price) * intval($order_prods[$i]->amount), 2);
        }
        $this->assign('order_prods', $order_prods);
    }

    // user order functions : start
    public function userdelorder() {
        $curr_user_id = SessionHolder::get('user/id');
        $curr_order_id = ParamHolder::get('o_id', 0);
        if (intval($curr_order_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }

        try {
            $o_order = new OnlineOrder();
            $curr_order =& $o_order->find("id=? AND user_id=?", array($curr_order_id, $curr_user_id));
            if (!$curr_order) {
                $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                return '_result';
            }

            // Remove order product first
            $db =& MysqlConnection::get();
            $db->query("DELETE FROM `".Config::$tbl_prefix."order_products` WHERE online_order_id=?", array($curr_order_id));

            // Delete order now
            $curr_order->delete();

            $this->assign('json', Toolkit::jsonOK(array('forward' => 'index.php')));
            return '_result';
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
    }

    public function userlistorder() {
        $this->assign('page_title', __('My Orders'));

        $curr_user_id = SessionHolder::get('user/id');

        $o_order = new OnlineOrder();
        $my_orders =& $o_order->findAll("user_id=?", array($curr_user_id), "ORDER BY `order_time` DESC");

        $this->assign('my_orders', $my_orders);
    }

    public function uservieworder() {
        $this->ordercreated();
        $o_payacct = new PaymentAccount();
        $enabled_accts =& $o_payacct->findAll("`enabled`='1'");
        $this->assign('payaccts', $enabled_accts);
        return 'ordercreated';
    }

    public function userfinishorder() {
        $curr_user_id = SessionHolder::get('user/id');
        $curr_order_id = ParamHolder::get('o_id', 0);
        if (intval($curr_order_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }

        try {
            $o_order = new OnlineOrder();
            $curr_order =& $o_order->find("id=? AND user_id=?", array($curr_order_id, $curr_user_id));
            if (!$curr_order) {
                $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                return '_result';
            }

            $curr_order->order_status = '100';
            $curr_order->save();

            $this->assign('json', Toolkit::jsonOK());
            return '_result';
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
    }
    // user order functions : end


    public function useraccountstate() {
        $this->assign('page_title', __('My Account'));
        /**
         * Add 02/08/2010
         */
        include_once(P_LIB.'/pager.php');

        $curr_user_id = SessionHolder::get('user/id');

        $o_user_ext = new UserExtend();
        $curr_user_ext =& $o_user_ext->find("user_id=?", array($curr_user_id));
        if (!$curr_user_ext) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $this->assign('curr_user_ext', $curr_user_ext);

        $user_transactions =& Pager::pageByObject('Transaction', "user_id=?", array($curr_user_id),
                "ORDER BY `action_time` DESC");
        $this->assign('transactions', $user_transactions['data']);
        $this->assign('pager', $user_transactions['pager']);
        $this->assign('page_mod', $user_transactions['mod']);
		$this->assign('page_act', $user_transactions['act']);
		$this->assign('page_extUrl', $user_transactions['extUrl']);
    }

    public function confirm_buynow() {
        $this->assign('page_title', __('Confirm Order Payment'));

        $curr_user_id = SessionHolder::get('user/id');
        if (!$curr_user_id) {
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
        $this->assign('curr_order', $curr_order);
    }

    public function buynow() {
        $curr_user_id = SessionHolder::get('user/id');
        if (!$curr_user_id) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        $o_userext = new UserExtend();
        $curr_userext =& $o_userext->find("user_id=?", array($curr_user_id));
        if (!$curr_userext) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        $curr_order_id = ParamHolder::get('o_id', 0);
        if (intval($curr_order_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }

        $o_order = new OnlineOrder();
        $curr_order =& $o_order->find("user_id=? AND id=? AND `order_status`='1'", array($curr_user_id, $curr_order_id));
        if ($curr_order) {
            if (floatval($curr_order->total_amount) > floatval($curr_userext->balance)) {
                $this->assign('json', Toolkit::jsonERR(__('Not enough money to pay for your order!')));
                return '_result';
            } else {
                // update user account
                $curr_userext->total_payment = floatval($curr_userext->total_payment) + floatval($curr_order->total_amount);
                $curr_userext->balance = floatval($curr_userext->total_saving) - floatval($curr_userext->total_payment);
                $curr_userext->save();

                // add transaction history
                $o_transaction = new Transaction();
                $o_transaction->action_time = time();
                $o_transaction->user_id = $curr_user_id;
                $o_transaction->type = '2';
                $o_transaction->amount = $curr_order->total_amount;
                $o_transaction->memo = __('Order Payment').' ('.$curr_order->oid.')';
                $o_transaction->save();
                //计算积分，并记录进ss_users_point和更新ss_user_extends @phpdb.net 2013-03-05
				//读取积分比例：
				if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
					$curr_locale = trim(SessionHolder::get('_LOCALE'));
				} else {
					$curr_locale = DEFAULT_LOCALE;
				}
				$lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
				SessionHolder::set('mod_site/_LOCALE', $lang_sw);
				if (CREDITS_SWITCH==1) {//如果开启积分，则进行积分换算和写入
					//获取金额，计算积分：
					$total_amount = floatval($curr_order->total_amount);
					$jifen = (int)($total_amount * CREDITS_RATE);
					//获取会员的积分，累加后更新到会员扩展表：
					$curr_userext -> total_point = $curr_userext -> total_point + $jifen;
					$curr_userext->save();
				
					//记录到point表：
					$userPoint = array();
					$userPoint['userid'] = $curr_user_id;
					$userPoint['type'] = 'in';
					$userPoint['point'] = $jifen;
					$userPoint['momo'] = __('Buy Credits increase');
					$userPoint['create_time'] = time();
	
					$db =& MySqlConnection::get();
					$sql = "insert into ss_users_points values (null,'{$userPoint['userid']}','{$curr_order->oid}','{$userPoint['type']}','{$userPoint['point']}','{$userPoint['momo']}','{$userPoint['create_time']}')";
					// echo $sql;exit;
					$db->query($sql);
				}

                $curr_order->order_status = '2';
                $curr_order->save();
            }
        } else {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_order', 'uservieworder', array('o_id' => $curr_order_id)))));
        return '_result';
    }

    //兑换积分的函数：
	public function userjifen(){
		$this->assign('page_title', __('Redeem Points'));
        $curr_user_id = SessionHolder::get('user/id');

        $o_user_ext = new UserExtend();
        $curr_user_ext =& $o_user_ext->find("user_id=?", array($curr_user_id));
        if (!$curr_user_ext) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
		
		//获取兑换现金的比例：
		if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
			$curr_locale = trim(SessionHolder::get('_LOCALE'));
		} else {
			$curr_locale = DEFAULT_LOCALE;
		}
		$lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
		SessionHolder::set('mod_site/_LOCALE', $lang_sw);
		$user_transactions =& Pager::pageByObject('UsersPoint', "userid=? and `type`='out'", array($curr_user_id), "ORDER BY `create_time` DESC");
        $this->assign('transactions', $user_transactions['data']);
        $this->assign('pager', $user_transactions['pager']);
        $this->assign('page_mod', $user_transactions['mod']);
		$this->assign('page_act', $user_transactions['act']);
		$this->assign('page_extUrl', $user_transactions['extUrl']);
        $this->assign('curr_user_ext', $curr_user_ext);
        $this->assign('cash', $cash);
		
	}
	
	//兑换积分的逻辑动作：
	public function userjifen_save(){
		$integral = ParamHolder::get('exchange');
                $integral = intval($integral);
		if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
			$curr_locale = trim(SessionHolder::get('_LOCALE'));
		} else {
			$curr_locale = DEFAULT_LOCALE;
		}
		$lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
		SessionHolder::set('mod_site/_LOCALE', $lang_sw);
		
		//读取积分总额：
		$curr_user_id = SessionHolder::get('user/id');
        $o_user_ext = new UserExtend();
        $curr_user_ext =& $o_user_ext->find("user_id=?", array($curr_user_id));
        if (!$curr_user_ext) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
		
		if ($curr_user_ext->total_point < 100 ){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script type="text/javascript">
			alert("'.__('Sorry, membership points can be converted more than 100 Member Points').'");
			location.href="'.Html::uriquery('mod_order', 'userjifen').'";</script>';
			exit;
		}
		if ($curr_user_ext->total_point < $integral ){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo '<script type="text/javascript">
			alert("'.__('Sorry, your current score is not enough cannot change').'");
			location.href="'.Html::uriquery('mod_order', 'userjifen').'";</script>';
			exit;
		}
		switch ($integral){
			case 100:
				$cash = 10;
			break;
			case 200:
				$cash = 20;
			break;
			case 500:
				$cash = 50;
			break;
			case 1000:
				$cash = 100;
			break;
			default:
				$cash = 0;
			break;

		}
		if ($cash==0) {
			 $this->assign('json', Toolkit::jsonERR(__('Please select the options you want to exchange')));
            return '_error';
            exit;
		}
		//加入会员的账户余额:
		//读取账户的总额，加上兑换的余额，并更新总额：
		$curr_user_ext -> total_saving = $curr_user_ext -> total_saving + $cash;
		$curr_user_ext -> balance = $curr_user_ext -> balance +$cash;
		$curr_user_ext -> save();
		//生成一个交易记录：
		$o_transaction = new Transaction();
		$o_transaction->action_time = time();
		$o_transaction->user_id = $curr_user_id;
		$o_transaction->type = '1';
		$o_transaction->amount = $cash;
		$o_transaction->memo = __('Redeem account balance');
		$o_transaction->save();
		
		//记录进积分记录表：
		$userPoint = array();
		$userPoint['userid'] = $curr_user_id;
		$userPoint['type'] = 'out';
		$userPoint['point'] = $integral;
		$userPoint['momo'] = __('Redeem account balance');
		$userPoint['create_time'] = time();
		$db =& MySqlConnection::get();
		$sql = "insert into ss_users_points(id,userid,orderid,type,point,momo,create_time) values (null,'{$userPoint['userid']}','0','{$userPoint['type']}','{$userPoint['point']}','{$userPoint['momo']}','{$userPoint['create_time']}')";
		$db->query($sql);
		
		//清零会员的积分
		$curr_user_ext->total_point = $curr_user_ext->total_point-$integral;
		$curr_user_ext -> save();
		
		
		//完成，跳转回我的帐户页面：
		echo '<script type="text/javascript">location.href="'.Html::uriquery('mod_order', 'useraccountstate').'";</script>';
        return '_result';
	}
	
	//显示积分明细：
	public function userjifenlist(){
		$this->assign('page_title', __('My Account'));
        /**
         * Add 02/08/2010
         */
        include_once(P_LIB.'/pager.php');

        $curr_user_id = SessionHolder::get('user/id');

        $o_user_ext = new UserExtend();
        $curr_user_ext =& $o_user_ext->find("user_id=?", array($curr_user_id));
        if (!$curr_user_ext) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $this->assign('curr_user_ext', $curr_user_ext);

        $user_transactions =& Pager::pageByObject('UsersPoint', "userid=?", array($curr_user_id),
                "ORDER BY `create_time` DESC");
        $this->assign('transactions', $user_transactions['data']);
        $this->assign('pager', $user_transactions['pager']);
        $this->assign('page_mod', $user_transactions['mod']);
		$this->assign('page_act', $user_transactions['act']);
		$this->assign('page_extUrl', $user_transactions['extUrl']);
	
	}
	
    private function _countProductsInCart() {

        if (!isset($_COOKIE['n_prds'.SessionHolder::get('user/id','0')])) {
            return 0;
        } elseif(SessionHolder::get('page/status', 'view') != 'edit'&& SessionHolder::get('user/id','0')==1){
        	 return 0;
        }else {
            return $_COOKIE['n_prds'.SessionHolder::get('user/id','0')];
        }
    }
}
?>
