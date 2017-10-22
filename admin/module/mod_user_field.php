<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModUserField extends Module {
    protected $_filters = array(
        'check_admin' => ''
    );
    
	
    public function admin_list() {

          $this->_layout = 'content';
		$fields=  UserField::findAll2(""," order by i_order");

		$field_types=  $this->field_type_opts();
		 $this->assign('fields', $fields);
		$this->assign('field_types', $field_types);
    }
    
	public function admin_add() {
		$this->_layout = 'content';
          $this->assign('next_action', 'admin_save');
					
		$cur_locale=  SessionHolder::get('_LOCALE', '');
		
		$all_lang=$this->loadpublishlocale();
		$field_types=  $this->field_type_opts();
		unset($field_types[0]);
		$this->assign('act','add');
		 $this->assign('curr_field', array());
		 $this->assign('cur_locale', $cur_locale);
		 $this->assign('all_lang', $all_lang);
		 $this->assign('field_types', $field_types);
		return '_form';
    }
		
	public function admin_edit() {
       $this->_layout = 'content';

        $cat_id = ParamHolder::get('id', '0');
        if (intval($cat_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
		
		 $curr_field = new UserField($cat_id);
		if (!$curr_field) {
				$this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
				return '_error';
		}
		 $cur_locale=  SessionHolder::get('_LOCALE', '');
		$all_lang=$this->loadpublishlocale();
		 
		$field_types=  $this->field_type_opts();
		unset($field_types[0]);
		$this->assign('curr_field', $curr_field->to_hash());
		$this->assign('act','edit');
		$this->assign('cur_locale', $cur_locale);
		 $this->assign('all_lang', $all_lang);
		 $this->assign('field_types', $field_types);
		 
        $this->assign('next_action', 'admin_save');
        return '_form';

    }		
	
	public function admin_save() {
		$this->_layout=NO_LAYOUT;
        $field_info =& ParamHolder::get('field', array());
        if (sizeof($field_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(admin__('Missing Account information!')));
            return '_result';
        }
	
	   $cur_locale=  SessionHolder::get('_LOCALE', '');
	   $labelinfo=$field_info['label'];
		 $labels=array();
		foreach($labelinfo as $locale=>$label){
			if(!empty($label)) $labels[$locale]=$label;
		}
		$field_info['label']= serialize($labels);
		 if ($field_info['showinlist'] == '1') {
				$field_info['showinlist'] = '1';
		} else {
				$field_info['showinlist'] = '0';
		}
		 if ($field_info['required'] == '1') {
				$field_info['required'] = '1';
		} else {
				$field_info['required'] = '0';
		}
	   $act=ParamHolder::get('act', 'add');
        try {
             			
			if($act=='add'){
				 // Re-arrange publish status
				unset($field_info['id']);
				$max_order=UserField::getMaxOrder();
				if($field_info['field_type']=='3'||$field_info['field_type']=='4'){
							$opts=$this->genOptionParam();
							if(!empty($opts)) $field_info['options']=serialize($opts);
				}
				$field_info['i_order'] =$max_order+1;
				$o_field = new UserField();
				$o_field->set($field_info);
				$o_field->save();
			}else{
				$cat_id=$field_info['id'];
				if(empty($cat_id)){
					$this->assign('json', Toolkit::jsonERR(admin__('Missing Account information!')));
					return '_result';
				}
				$o_field =new UserField($cat_id);
				if(!empty($o_field)){
					$curr_field =$o_field->to_hash();
					if($curr_field['field_type']=='3'||$curr_field['field_type']=='4'){
								$opts=$this->genOptionParam();
								if(!empty($opts)) $field_info['options']=serialize($opts);
					}
				}
				
				unset($field_info['id']);
				unset($field_info['field_type']);
				$o_field->set($field_info);
				$o_field->save();
			}			
		
		 $this->assign('json', Toolkit::jsonOK(array()));
			return '_result';
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
	}	
	
	public function admin_order() {
		$this->_layout=NO_LAYOUT;
		$order_info =& ParamHolder::get('i_order', array());
		if (!is_array($order_info)) {
            $this->assign('json', Toolkit::jsonERR(__('Missing Account information!')));
            return '_result';
        }
		try {
			$db =& MySqlConnection::get();
			foreach($order_info as $key => $val) {
				$sql = "update ".Config::$tbl_prefix."user_fields set `i_order`='".mysql_escape_string($val)."' where id='".mysql_escape_string($key)."' ";
				$db->query($sql);
			}
		} catch (Exception $ex) {
			$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		$this->assign('json', Toolkit::jsonOK());
		return '_result';
	}
	
	private function genOptionParam(){
		$opts_keys=& ParamHolder::get('key', array());
		$opts_values=& ParamHolder::get('val', array());
		$opts=& ParamHolder::get('opts', array());
		$opts_data=array();
		foreach($opts_keys as $k){
			$optval=$opts_values[$k];
			if(!empty($optval)){
				$labels=array();
				foreach($optval as $locale=>$label){
					if(!empty($label)) $labels[$locale]=$label;
				}
				if(!empty($labels)) $opts_data[]=array('key'=>$k,'val'=>$labels);
			}
		}
		$opts['data']=$opts_data;
		return $opts;
		
	}	
		
	public function admin_delete() {
		$this->_layout=NO_LAYOUT;
        $field_id = trim(ParamHolder::get('field_id', '0'));
        if (intval($field_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
		
	   $field_id=	intval($field_id);
	    try {
			$curr_field = new UserField($field_id);
			$curr_field->delete();
			
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
		
	public function admin_pic(){
		$this->_layout=NO_LAYOUT;
		$_tag = trim(ParamHolder::get('_tag', ''));
		$field_id=trim(ParamHolder::get('_id', ''));
		$field_info=array();
		if(in_array($_tag, array('showinlist','required'))){
			if(!empty($field_id))
		    	{
				$o_field = new UserField($field_id);
		            if($o_field->$_tag == 1)
		            {
		            	$field_info[$_tag] = '0';
		            	$o_field->set($field_info);
		            	$o_field->save();
					die('0');
		            }
		            elseif($o_field->$_tag == 0)
		            {
		            	$field_info[$_tag] = '1';
		            	$o_field->set($field_info);
		            	$o_field->save();
					die('1');
		            }
		    	}
		}
		exit;
	}	
		
	private function field_type_opts(){
		return array(
			0=>__('System default'),
			1=>__('Text box')	,
			2=>__('Text area')	,
			3=>__('Check box'),	
			4=>__('Radio'),	
			5=>__('Date picker')	
		);
	}
	
	private function loadpublishlocale(){
		$o_language = new Language();
		$langs=array();
		$all_langs =& $o_language->findAll("published = '1' ");
		foreach($all_langs as $l){
			$langs[$l->locale]=$l->name;
		}
		return $langs;
	}
}
?>