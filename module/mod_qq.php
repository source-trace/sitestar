<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModQq extends Module {
    protected $_filters = array(
        'check_login' => '{qqlist}'
    );
    
    public function qqlist() {
		$where = "s_locale = '".SessionHolder::get('_LOCALE')."'";
        $show_acct = trim(ParamHolder::get('qq_show_account'));
        $show_name = trim(ParamHolder::get('qq_show_name'));
        // for sitestarv1.3
        $show_style = trim(ParamHolder::get('qq_show_style'));
        $this->assign('show_style', $show_style);
        $this->assign('show_acct', $show_acct);
        $this->assign('show_name', $show_name);
		$o_qq = new OnlineQq();
		$qqs =& $o_qq->findAll($where." and published='1'  order by i_order desc");
		$this->assign('qqs', $qqs);
    }
    
}
?>
