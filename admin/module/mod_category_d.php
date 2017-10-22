<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModCategoryD extends Module {
    
	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_category_d/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_category_d/_LOCALE', $lang_sw);
        
        $all_categories =& DownloadCategory::listCategories(0, "s_locale=?", array($lang_sw));
        
        $this->assign('categories', $all_categories);
        
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
    }
    
    public function admin_add() {
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_category_d/_LOCALE', $curr_locale));
        
        $this->assign('content_title', __('New Category'));
        $this->assign('next_action', 'admin_create');
        /*
        $all_categories =& DownloadCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        DownloadCategory::toSelectArray($all_categories, $select_categories, 
	        	0, array(), array('0' => __('Top Level')));
        
        $this->assign('select_categories', $select_categories);
		*/
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        // auto checked "published"
        $curr_category_d->published = '1';
        $this->assign('curr_category_d', $curr_category_d);
        
        return '_form';
    }
    
    public function admin_create() {
        
        $cate_d_info =& ParamHolder::get('cad', array());
        if (sizeof($cate_d_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing category information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
        	$cate_d_info['alias'] = 'cad_'.Toolkit::randomStr(8);
        	// Re-arrange publish status
			
            if ($cate_d_info['published'] == '1') {
                $cate_d_info['published'] = '1';
            } else {
                $cate_d_info['published'] = '0';
            }
			
            $cate_d_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // Calculate order
            $cate_d_info['i_order'] = 
            	DownloadCategory::getMaxOrder() + 1;
            // Data operation
            $o_category_d = new DownloadCategory();
            $o_category_d->set($cate_d_info);
            $o_category_d->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_category_d', 'admin_list',array('goto'=>10000)))));
        return '_result';
    }
    
    public function admin_quick_create() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
	$browser= $_SERVER['HTTP_USER_AGENT'];
	if(strpos(strtolower($browser), "msie ")){
		$cate_d_info['name'] = iconv("GB2312","UTF-8",ParamHolder::get('name', ''));
	} else {
		$cate_d_info['name'] = trim(ParamHolder::get('name', ''));
	}
        $cate_d_info['download_category_id'] = ParamHolder::get('parent', '0');
        $cate_d_info['s_locale'] = ParamHolder::get('locale', $curr_locale);
        $cate_d_info['alias'] = 'cad_'.Toolkit::randomStr(8);
        $cate_d_info['published'] = '1';
        $cate_d_info['for_roles'] = '{member}{admin}{guest}';
        $cate_d_info['i_order'] = 
            	DownloadCategory::getMaxOrder($cate_d_info['download_category_id']) + 1;

        try {
            // Data operation
            $o_category_d = new DownloadCategory();
            $o_category_d->set($cate_d_info);
            $o_category_d->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('id' => $o_category_d->id)));
        return '_result';
    }
    
    public function admin_edit() {
    	$this->_layout = 'content';
    	
        $cad_id = ParamHolder::get('cad_id', '0');
        if (intval($cad_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        
        try {
            $curr_category_d = new DownloadCategory($cad_id);
            $this->assign('curr_category_d', $curr_category_d);
        
	        $all_categories =& DownloadCategory::listCategories(0, "s_locale=?", 
	        	array($curr_category_d->s_locale));
	        $select_categories = array();
	        DownloadCategory::toSelectArray($all_categories, $select_categories, 
	        	0, array($curr_category_d->id), array('0' => __('Top Level')));
	        
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
        $flag = false;
        $cate_d_info =& ParamHolder::get('cad', array());
        if (sizeof($cate_d_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing category information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
        	// Re-arrange publish status
            if ($cate_d_info['published'] == '1') {
                $cate_d_info['published'] = '1';
                $flag = true;
            } else {
                $cate_d_info['published'] = '0';
                $flag = false;
            }
            $cate_d_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            
            // Data operation
            $o_category_d = new DownloadCategory($cate_d_info['id']);
			if($o_category_d->download_category_ids != $cate_d_info['download_category_id']) $cate_d_info['i_order']=time()+$cate_d_info['i_order'];
            $o_category_d->set($cate_d_info);
            $o_category_d->save();
            
            $o_download = new Download();
            $downloads = $o_download->findAll("download_category_id=?",array($cate_d_info['id']));
            if(!empty($downloads))
            {
            	foreach($downloads as $k => $v)
            	{
            		$downloads_info['published'] = ($flag) ? '1' : '0';
            		$downloads_info['for_roles'] = $cate_d_info['for_roles'];
            		$obj_download = new Download($v->id);
            		$obj_download->set($downloads_info);
            		$obj_download->save();
            	}
            }
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_category_d', 'admin_list'))));
        return '_result';
    }
    
    public function admin_delete() {
        
        $cad_id = trim(ParamHolder::get('cad_id', '0'));
        if (intval($cad_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			$tmp_arr = explode('_', $cad_id);
			$len = sizeof($tmp_arr);
			for ($i = 0; $i< $len; $i++){
				$curr_category_d = new DownloadCategory($tmp_arr[$i]);
				$curr_category_d->delete();
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_move() {
        
        $cad_id = trim(ParamHolder::get('cad_id', '0'));
        $sib_cad_id = trim(ParamHolder::get('sib_cad_id', '0'));
        if (intval($cad_id) == 0 || intval($sib_cad_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        
        try {
            $curr_category_d = new DownloadCategory($cad_id);
            $sib_category_d = new DownloadCategory($sib_cad_id);
            
            $tmp_order = $curr_category_d->i_order;
            $curr_category_d->i_order = $sib_category_d->i_order;
            $sib_category_d->i_order = $tmp_order;
            
            $curr_category_d->save();
            $sib_category_d->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
	public function admin_pic()
    {
    	$download_category_info = array();
    	$download_category_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($download_category_id))
    	{
    		$o_download_category = new DownloadCategory($download_category_id);
            if($o_download_category->published == 1)
            {
            	$download_category_info['published'] = '0';
            	$o_download_category->set($download_category_info);
            	$o_download_category->save();
				die('0');
            }
            elseif($o_download_category->published == 0)
            {
            	$download_category_info['published'] = '1';
            	$o_download_category->set($download_category_info);
            	$o_download_category->save();
				die('1');
            }
    	}
    }
}
?>