<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModOrder extends Module {

	protected $_filters = array(
        'check_admin' => '{admin_view}'
    );
	
    // admin functions : admin
    public function admin_list() {
        $this->_layout = 'content';

        $where = false;
        $params = false;

        $orders =&
            Pager::pageByObject('OnlineOrder', $where, $params,
                "ORDER BY `order_time` DESC");

        $this->assign('orders', $orders['data']);
        $this->assign('pager', $orders['pager']);
        $this->assign('page_mod', $orders['mod']);
		$this->assign('page_act', $orders['act']);
		$this->assign('page_extUrl', $orders['extUrl']);
    }

    public function admin_view() {
        $this->_layout = 'content';

        $curr_order_id = ParamHolder::get('o_id', 0);
        if (intval($curr_order_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        $curr_order = new OnlineOrder($curr_order_id);
        if (!$curr_order) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $this->assign('curr_order', $curr_order);

        $curr_order->loadRelatedObjects(REL_CHILDREN);
        $order_prods =& $curr_order->slaves['OrderProduct'];
        for ($i = 0; $i < sizeof($order_prods); $i++) {
            $order_prods[$i]->ttl_price = number_format(floatval($order_prods[$i]->price) * intval($order_prods[$i]->amount), 2);
        }
        $this->assign('order_prods', $order_prods);
    }

    public function admin_update() {

        $order_info =& ParamHolder::get('order', array());
        if (sizeof($order_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing order information!')));
            return '_result';
        }

        try {
            $curr_order = new OnlineOrder($order_info['id']);
            if (intval($curr_order->order_status) == 100) {
                $this->assign('json', Toolkit::jsonERR(__('Cannot change a finished order!')));
                return '_result';
            }
            if (intval($order_info['order_status']) == 100 &&
                intval($curr_order->order_status) != 100) {
                $this->assign('json', Toolkit::jsonERR(__('Cannot change order to the selected status!')));
                return '_result';
            }
            $curr_order->set($order_info);
            $curr_order->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    // admin functions : end
    public function userdelorder() {
        $curr_user_id = SessionHolder::get('user/id');
        $curr_order_id = ParamHolder::get('o_id');
        if (intval($curr_order_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }

        try {
            $o_order = new OnlineOrder();
            $curr_order =& $o_order->find("id=?" , array($curr_order_id));
            if (!$curr_order) {
                $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                return '_result';
            }

            // Remove order product first
            $db =& MysqlConnection::get();
            $db->query("DELETE FROM `".Config::$tbl_prefix."order_products` WHERE online_order_id=?", array($curr_order_id));

            // Delete order now
            $curr_order->delete();

            $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_order', 'admin_list'))));
            return '_result';
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
    }
}
?>
