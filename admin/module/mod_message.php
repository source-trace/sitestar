<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModMessage extends Module {
    
	protected $_filters = array(
        'check_admin' => ''
    );
	
	public function admin_delete() {
        
        $mess_id = trim(ParamHolder::get('mess_id', '0'));
        if (intval($mess_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			$tmp_arr = explode('_', $mess_id);
			$len = sizeof($tmp_arr);
			for ($i = 0; $i< $len; $i++){
				$curr_mess = new Message($tmp_arr[$i]);
				$curr_mess->delete();
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
	public function admin_view() {
		$this->_layout = 'blank';
        
        $mess_id = trim(ParamHolder::get('mess_id', '0'));
        if (intval($mess_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
		$o_mess = new Message($mess_id);
		$fields=  MsgField::findAll2(" showinlist='1' and field_type!='0' "," order by i_order");
		 $this->assign('user_fields', $fields);
		$this->assign('message', $o_mess);
		$this->assign('tplcss', 'style');
	}
    
	public function admin_list() {
    	$this->_layout = 'content';
    	
		$mess_data =& 
            Pager::pageByObject('Message', "1=?", array(1), 
                "ORDER BY `id` DESC");
        $this->assign('messages', $mess_data['data']);
		$this->assign('pager', $mess_data['pager']);
		$this->assign('page_mod', $mess_data['mod']);
		$this->assign('page_act', $mess_data['act']);
		$this->assign('page_extUrl', $mess_data['extUrl']);
        //$this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
    }
}
?>
