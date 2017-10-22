<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModCategoryA extends Module {
    
	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_category_a/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_category_a/_LOCALE', $lang_sw);
        
        $all_categories =& ArticleCategory::listCategories(0, "s_locale=?", array($lang_sw));
        
        $this->assign('categories', $all_categories);
        
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
    }
    
    public function admin_add() {
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_category_a/_LOCALE', $curr_locale));
        
        $this->assign('content_title', __('New Category'));
        $this->assign('next_action', 'admin_create');
        
        $all_categories =& ArticleCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        ArticleCategory::toSelectArray($all_categories, $select_categories, 
	        	0, array(), array('0' => __('Top Level')));
        
        $this->assign('select_categories', $select_categories);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        // auto checked "published"
        $curr_category_a->published = '1';
        $this->assign('curr_category_a', $curr_category_a);
        
        return '_form';
    }
    
    public function admin_create() {
        
        $cate_a_info =& ParamHolder::get('caa', array());
        if (sizeof($cate_a_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing category information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
        	$cate_a_info['alias'] = 'caa_'.Toolkit::randomStr(8);
        	// Re-arrange publish status
            if ($cate_a_info['published'] == '1') {
                $cate_a_info['published'] = '1';
            } else {
                $cate_a_info['published'] = '0';
            }
            $cate_a_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // Calculate order
            $cate_a_info['i_order'] = 
            	ArticleCategory::getMaxOrder($cate_a_info['article_category_id']) + 1;
            
            // Data operation
            $o_category_a = new ArticleCategory();
            $o_category_a->set($cate_a_info);
            $o_category_a->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_category_a', 'admin_list'))));
        return '_result';
    }
    
    public function admin_quick_create() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
	//$cate_a_info['name'] = ParamHolder::get('name', '');
	// $cate_a_info['name'] = iconv("GB2312","UTF-8",ParamHolder::get('name', ''));
	$browser= $_SERVER['HTTP_USER_AGENT'];
	if(strpos(strtolower($browser), "msie ")){
		$cate_a_info['name'] = iconv("GB2312","UTF-8",ParamHolder::get('name', ''));
	} else {
		$cate_a_info['name'] = trim(ParamHolder::get('name', ''));
	}
        $cate_a_info['article_category_id'] = ParamHolder::get('parent', '0');
        $cate_a_info['s_locale'] = ParamHolder::get('locale', $curr_locale);
        $cate_a_info['alias'] = 'caa_'.Toolkit::randomStr(8);
        $cate_a_info['published'] = '1';
        $cate_a_info['for_roles'] = '{member}{admin}{guest}';
        $cate_a_info['i_order'] = 
            	ArticleCategory::getMaxOrder($cate_a_info['article_category_id']) + 1;

        try {
            // Data operation
            $o_category_a = new ArticleCategory();
            $o_category_a->set($cate_a_info);
            $o_category_a->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('id' => $o_category_a->id)));
        return '_result';
    }
    
    public function admin_edit() {
    	$this->_layout = 'content';
    	
        $caa_id = ParamHolder::get('caa_id', '0');
        if (intval($caa_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        
        try {
            $curr_category_a = new ArticleCategory($caa_id);
            $this->assign('curr_category_a', $curr_category_a);
        
	        $all_categories =& ArticleCategory::listCategories(0, "s_locale=?", 
	        	array($curr_category_a->s_locale));
	        $select_categories = array();
	        ArticleCategory::toSelectArray($all_categories, $select_categories, 
	        	0, array($curr_category_a->id), array('0' => __('Top Level')));
	        
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
        $cate_a_info =& ParamHolder::get('caa', array());
        if (sizeof($cate_a_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing category information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
        	// Re-arrange publish status
            if ($cate_a_info['published'] == '1') {
                $cate_a_info['published'] = '1';
                $flag = true;
            } else {
                $cate_a_info['published'] = '0';
                $flag = false;
            }
            $cate_a_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            
            // Data operation
            $o_category_a = new ArticleCategory($cate_a_info['id']);
			if($o_category_a->article_category_ids != $cate_a_info['article_category_id']) $cate_a_info['i_order']=time()+$cate_a_info['i_order'];
            $o_category_a->set($cate_a_info);
            $o_category_a->save();
            
            $o_article = new Article();
            $articles = $o_article->findAll("article_category_id=?",array($cate_a_info['id']));
            if(!empty($articles))
            {
            	foreach($articles as $k => $v)
            	{
            		$articles_info['published'] = ($flag) ? '1' : '0';
            		$articles_info['for_roles'] = $cate_a_info['for_roles'];
            		$obj_article = new Article($v->id);
            		$obj_article->set($articles_info);
            		$obj_article->save();
            	}
            }
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_category_a', 'admin_list'))));
        return '_result';
    }
    
    public function admin_delete() {
        
        $caa_id = trim(ParamHolder::get('caa_id', '0'));
        if (intval($caa_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			$tmp_arr = explode('_', $caa_id);
			$len = sizeof($tmp_arr);
			for ($i = 0; $i< $len; $i++){
				if (!empty($tmp_arr[$i])) {
					$o_a = new ArticleCategory();
					$res = $o_a->findAll('article_category_id=?',array($tmp_arr[$i]));
					if (!empty($res)) {
						 $this->assign('json', Toolkit::jsonERR(__('Please delete sub-categories!')));
	        			 return '_result';
					}
					$o_article = new Article();
					$res = $o_article->findAll('article_category_id=?',array($tmp_arr[$i]));
					if (!empty($res)) {
						 $this->assign('json', Toolkit::jsonERR(__('You can not delete this category, please delete the article under this category!')));
	        			 return '_result';
					}
					$curr_category_a = new ArticleCategory($tmp_arr[$i]);
					//ArticleCategory::delete_r($curr_category_a->id);
					$curr_category_a->delete();
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
        
        $caa_id = trim(ParamHolder::get('caa_id', '0'));
        $sib_caa_id = trim(ParamHolder::get('sib_caa_id', '0'));
        if (intval($caa_id) == 0 || intval($sib_caa_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        
        try {
            $curr_category_a = new ArticleCategory($caa_id);
            $sib_category_a = new ArticleCategory($sib_caa_id);
            
            $tmp_order = $curr_category_a->i_order;
            $curr_category_a->i_order = $sib_category_a->i_order;
            $sib_category_a->i_order = $tmp_order;
            
            $curr_category_a->save();
            $sib_category_a->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
	public function admin_pic()
    {
    	$article_category_info = array();
    	$article_category_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($article_category_id))
    	{
    		$o_article_category = new ArticleCategory($article_category_id);
            if($o_article_category->published == 1)
            {
            	$article_category_info['published'] = '0';
            	$o_article_category->set($article_category_info);
            	$o_article_category->save();
				die('0');
            }
            elseif($o_article_category->published == 0)
            {
            	$article_category_info['published'] = '1';
            	$o_article_category->set($article_category_info);
            	$o_article_category->save();
				die('1');
            }
    	}
    }
}
?>