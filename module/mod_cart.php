<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModCart extends Module {
    public function addtocart() {
        $p_id =& ParamHolder::get('p_id', '0');
        if (!$p_id || !is_numeric($p_id)) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        $p_num =& ParamHolder::get('p_num', '0');
        if (!is_numeric($p_num)) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        
        if($p_num <= 0)//防负数
        {
        	$this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        
        $n_prds = ShoppingCart::addProduct(intval($p_id), intval($p_num));

        $this->assign('json', Toolkit::jsonOK(array('n_prds' => $n_prds)));
        return '_result';
    }

    public function delfromcart() {
        $p_id =& ParamHolder::get('p_id', '0');
        if (!$p_id || !is_numeric($p_id)) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        $n_prds = ShoppingCart::removeProduct(intval($p_id));

        $this->assign('json', Toolkit::jsonOK(array('n_prds' => $n_prds)));
        return '_result';
    }

    public function updateprodnum() {
        $p_id =& ParamHolder::get('p_id', '0');
        if (!$p_id || !is_numeric($p_id)) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        $p_num =& ParamHolder::get('p_num', '0');
        if (!is_numeric($p_num)) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        
        if($p_num <= 0)//防负数
        {
        	$this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }

        $n_prds = ShoppingCart::updateProductNum($p_id, $p_num);

        $this->assign('json', Toolkit::jsonOK(array('n_prds' => $n_prds)));
        return '_result';
    }

    public function cartstatus() {
        $this->assign('n_prds', $this->_countProductsInCart());
    }

    public function viewcart() {
        $this->assign('page_title', __('My Shopping Cart'));
		$user_id = SessionHolder::get('user/id','0');
        $products = array();

        $this->assign('n_prds', $this->_countProductsInCart());
        if (isset($_COOKIE['prds'.$user_id])) {
            foreach ($_COOKIE['prds'.$user_id] as $key => $val) {
                $cart_prod = new Product($key);
                if (isset($cart_prod->online_orderable)) {
                    $products[] = $cart_prod;
                }
            }
        }
        $this->assign('products', $products);
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
