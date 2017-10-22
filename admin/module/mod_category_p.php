<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModCategoryP extends Module {
    
	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_category_p/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_category_p/_LOCALE', $lang_sw);
        
        $all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($lang_sw));
        
        $this->assign('categories', $all_categories);
        
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
    }
    
    public function admin_add() {
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_category_p/_LOCALE', $curr_locale));
        
        $this->assign('content_title', __('New Category'));
        $this->assign('next_action', 'admin_create');
        
        $all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        ProductCategory::toSelectArray($all_categories, $select_categories, 
	        	0, array(), array('0' => __('Top Level')));
        
        $this->assign('select_categories', $select_categories);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        // auto checked "published"
        $curr_category_p->published = '1';
        $this->assign('curr_category_p', $curr_category_p);
        
        return '_form';
    }
    
    public function admin_create() {
        
        $cate_p_info =& ParamHolder::get('cap', array());
        if (sizeof($cate_p_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing category information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
        	$cate_p_info['alias'] = 'cap_'.Toolkit::randomStr(8);
        	// Re-arrange publish status
            if ($cate_p_info['published'] == '1') {
                $cate_p_info['published'] = '1';
            } else {
                $cate_p_info['published'] = '0';
            }
            $cate_p_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // Calculate order
            $cate_p_info['i_order'] = 
            	ProductCategory::getMaxOrder($cate_p_info['product_category_id']) + 1;
            
            // Data operation
            $o_category_p = new ProductCategory();
            $o_category_p->set($cate_p_info);
            $o_category_p->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_category_p', 'admin_list'))));
        return '_result';
    }
    
    public function admin_quick_create() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        //$cate_p_info['name'] = ParamHolder::get('name', '');
	//	$cate_p_info['name'] = iconv("GB2312","UTF-8",ParamHolder::get('name', ''));
	$browser= $_SERVER['HTTP_USER_AGENT'];
	if(strpos(strtolower($browser), "msie ")){
		$cate_p_info['name'] = iconv("GB2312","UTF-8",ParamHolder::get('name', ''));
	} else {
		$cate_p_info['name'] = trim(ParamHolder::get('name', ''));
	}
        $cate_p_info['product_category_id'] = ParamHolder::get('parent', '0');
        $cate_p_info['s_locale'] = ParamHolder::get('locale', $curr_locale);
        $cate_p_info['alias'] = 'cap_'.Toolkit::randomStr(8);
        $cate_p_info['published'] = '1';
        $cate_p_info['for_roles'] = '{member}{admin}{guest}';
        $cate_p_info['i_order'] = 
            	ProductCategory::getMaxOrder($cate_p_info['product_category_id']) + 1;

        try {
            // Data operation
            $o_category_p = new ProductCategory();
            $o_category_p->set($cate_p_info);
            $o_category_p->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('id' => $o_category_p->id)));
        return '_result';
    }
    
    public function admin_edit() {
    	$this->_layout = 'content';
    	
        $cap_id = ParamHolder::get('cap_id', '0');
		$cap_id = intval($cap_id);
        if ($cap_id == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        
        try {
            $curr_category_p = new ProductCategory($cap_id);
            $this->assign('curr_category_p', $curr_category_p);
        
	        $all_categories =& ProductCategory::listCategories(0, "s_locale=?", 
	        	array($curr_category_p->s_locale));
	        $select_categories = array();
	        ProductCategory::toSelectArray($all_categories, $select_categories, 
	        	0, array($curr_category_p->id), array('0' => __('Top Level')));
	        
	        $this->assign('select_categories', $select_categories);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
        
        $this->assign('content_title', __('Edit Category'));
        $this->assign('next_action', 'admin_update');
        
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
        return '_form';
    }
    
    public function admin_update() {
        $products_info = array();
        $flag = false;
        $cate_p_info =& ParamHolder::get('cap', array());
        if (sizeof($cate_p_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing category information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
        	// Re-arrange publish status
            if ($cate_p_info['published'] == '1') {
                $cate_p_info['published'] = '1';
                $flag = true;
            } else {
                $cate_p_info['published'] = '0';
                $flag = false;
            }
            $cate_p_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            
            // Data operation
            $o_category_p = new ProductCategory($cate_p_info['id']);
             if($o_category_p->product_category_id != $cate_p_info['product_category_id']) $cate_p_info['i_order']=time()+$cate_p_info['i_order'];
            $o_category_p->set($cate_p_info);
            $o_category_p->save();
            
            $o_product = new Product();
            $products = $o_product->findAll("product_category_id=?",array($cate_p_info['id']));
            if(!empty($products))
            {
            	foreach($products as $k => $v)
            	{
            		$products_info['published'] = ($flag) ? '1' : '0';
            		$products_info['for_roles'] = $cate_p_info['for_roles'];
            		$obj_product = new Product($v->id);
            		$obj_product->set($products_info);
            		$obj_product->save();
            	}
            }
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_category_p', 'admin_list'))));
        return '_result';
    }
    
    public function admin_delete() {
        
        $cap_id = trim(ParamHolder::get('cap_id', '0'));
        if (intval($cap_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			$tmp_arr = explode('_', $cap_id);
			$len = sizeof($tmp_arr);
			for ($i = 0; $i< $len; $i++){
				if (!empty($tmp_arr[$i])) {
					$o_p = new ProductCategory();
					$res = $o_p->findAll('product_category_id=?',array($tmp_arr[$i]));
					if (!empty($res)) {
						 $this->assign('json', Toolkit::jsonERR(__('Please delete sub-categories!')));
	        			 return '_result';
					}
					$o_prd = new Product();
					$res = $o_prd->findAll('product_category_id=?',array($tmp_arr[$i]));
					if (!empty($res)) {
						 $this->assign('json', Toolkit::jsonERR(__('You can not delete this category, please delete this category of products!')));
	        			 return '_result';
					}
					$curr_category_p = new ProductCategory($tmp_arr[$i]);
					//ProductCategory::delete_r($curr_category_p->id);
					$curr_category_p->delete();
				}
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_move() {
        
        $cap_id = trim(ParamHolder::get('cap_id', '0'));
        $sib_cap_id = trim(ParamHolder::get('sib_cap_id', '0'));
        if (intval($cap_id) == 0 || intval($sib_cap_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        
        try {
            $curr_category_p = new ProductCategory($cap_id);
            $sib_category_p = new ProductCategory($sib_cap_id);
            
            $tmp_order = $curr_category_p->i_order;
            $curr_category_p->i_order = $sib_category_p->i_order;
            $sib_category_p->i_order = $tmp_order;
            
            $curr_category_p->save();
            $sib_category_p->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
	public function admin_pic()
    {
    	$product_category_info = array();
    	$product_category_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($product_category_id))
    	{
    		$o_product_category = new ProductCategory($product_category_id);
            if($o_product_category->published == 1)
            {
            	$product_category_info['published'] = '0';
            	$o_product_category->set($product_category_info);
            	$o_product_category->save();
				die('0');
            }
            elseif($o_product_category->published == 0)
            {
            	$product_category_info['published'] = '1';
            	$o_product_category->set($product_category_info);
            	$o_product_category->save();
				die('1');
            }
    	}
    }
}
?>