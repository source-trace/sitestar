<?php
class Frontpage extends Module {
    public function index() {
    	$this->setVar('page_title', __('Frontpage'));
		
		$curr_locale=SessionHolder::get('_LOCALE');
		if(defined('VERIFY_META')){
			$verify_meta=unserialize(VERIFY_META);
			if(is_array($verify_meta)){
				$meta_str=$verify_meta[$curr_locale];
				$this->setVar('meta_str', $meta_str);
			}
		}	
			
		//counter 
		if(SITE_COUNTER == 1) {
			if(SessionHolder::get('counter', '0') == 0) {
				$o_param = new Parameter();
				$param =& $o_param->find('`key`=?', array("SITE_COUNTER_NUM"));
				$param->val = $param->val + 1;
				$param->save();
				SessionHolder::set('counter', '1');
			}
		}
    }
}
?>