<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModPayaccount extends Module {

	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
    	$this->_layout = 'content';
        $this->_getProviders();
    }

    public function admin_update() {

        $acct_info =& ParamHolder::get('payacct', array());
        if (sizeof($acct_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing account information!')));
            return '_result';
        }
        /** Check keys **/
       	if (isset($acct_info['partner_key'])&&isset($acct_info['re_partner_key'])&&$acct_info['partner_key'] != $acct_info['re_partner_key']) {
            $this->assign('json', Toolkit::jsonERR(__('Partner key mismatch!')));
            return '_result';
        }
        //if (isset($acct_info['enabled'])) {
        	if (isset($acct_info['enabled']) && $acct_info['enabled'] == '1') {
	            $acct_info['enabled'] = '1';
	        } else {
	            $acct_info['enabled'] = '0';
	        }
        //}
        
        try {
            $curr_payacct = new PaymentAccount($acct_info['id']);

            $curr_payacct->set($acct_info);
            $curr_payacct->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }

    private function _getProviders() {
        $o_payprov = new PaymentProvider();
        $providers =& $o_payprov->findAll(false,false,'ORDER BY id asc');
        $this->assign('providers', $providers);
    }
}
?>
