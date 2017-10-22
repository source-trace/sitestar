<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModNews extends Module {

	protected $_filters = array(
        'check_admin' => ''
    );
    
    private $article_category_id = 2;
	
    public function admin_list() {
        $this->_layout = 'content';

        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_article/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        SessionHolder::set('mod_article/_LOCALE', $lang_sw);

        $where = "s_locale=?";
        $params = array($lang_sw);

//        $caa_sw = trim(ParamHolder::get('caa_sw', '-'));
//        if (is_numeric($caa_sw)) {
//            $where .= " AND article_category_id=?";
//            $params[] = $caa_sw;
//        }

        $where .= " AND article_category_id=?";
        $params[] = $this->article_category_id;
        
        $article_data =&
            Pager::pageByObject('Article', $where, $params,
                "ORDER BY `i_order` DESC");

        $this->assign('next_action', 'admin_order');
        $this->assign('articles', $article_data['data']);
        $this->assign('pager', $article_data['pager']);
         $this->assign('page_mod', $article_data['mod']);
		$this->assign('page_act', $article_data['act']);
		$this->assign('page_extUrl', $article_data['extUrl']);

        $this->assign('lang_sw', $lang_sw);
//        $this->assign('caa_sw', $caa_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());

        // Prepare article category for select list view
        $all_categories =& ArticleCategory::listCategories(0, "s_locale=?", array($lang_sw));
        $select_categories = array();
        ArticleCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('-' => __('View All'), '0' => __('Uncategorised')));

        $this->assign('select_categories', $select_categories);
    }

    public function admin_add() {
        $this->_layout = 'content';

        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_article/_LOCALE', $curr_locale));

        $this->assign('article_title', __('News'));
        $this->assign('next_action', 'admin_create');

        $all_categories =& ArticleCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        ArticleCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('0' => __('Uncategorised')));

        $this->assign('select_categories', $select_categories);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());

        return '_form';

    }

    public function admin_create() {

        $article_info =& ParamHolder::get('article', array());
        if (sizeof($article_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing article information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
            // Re-arrange publish time
        	$article_info['pub_start_time'] = -1;
        	$article_info['pub_end_time'] = -1;
            // Re-arrange publish status
//            if ($article_info['published'] == '1') {
//                $article_info['published'] = '1';
//            } else {
//                $article_info['published'] = '0';
//            }
			$article_info['published'] = '1';
            $article_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // The create time
            $article_info['create_time'] = time();
            $article_info['v_num'] = 0;
            $article_info['intro'] = 'news_intro';
            $article_info['tags'] = strip_tags($article_info['tags']);
            $article_info['source'] = strip_tags($article_info['source']);
            $article_info['author'] = strip_tags($article_info['author']);
            $article_info['title'] = strip_tags($article_info['title']);
            $article_info['article_category_id'] = 2;
            $article_info['i_order'] =
            	Article::getMaxOrder($article_info['article_category_id']) + 1;
            // Data operation
            $o_article = new Article();
            $o_article->set($article_info);
            $o_article->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_news', 'admin_list'))));
        return '_result';
    }
	public function admin_order() {

		$order_info =& ParamHolder::get('i_order', array());
		if (!is_array($order_info)) {
            $this->assign('json', Toolkit::jsonERR(__('Missing article order information!')));
            return '_result';
        }
		try {
			foreach($order_info as $key => $val) {
				$article_info['i_order'] = $val;
				$o_article = new Article($key);
				$o_article->set($article_info);
				$o_article->save();
			}
		} catch (Exception $ex) {
			$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		Notice::set('mod_article/msg', __('Article order added successfully!'));
		Content::redirect(Html::uriquery('mod_news', 'admin_list'));
	}
    public function admin_edit() {
        $this->_layout = 'content';

        $article_id = ParamHolder::get('article_id', '0');
        if (intval($article_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
       try {
            $curr_article = new Article($article_id);
            $this->assign('curr_article', $curr_article);
            $all_categories =& ArticleCategory::listCategories(0, "s_locale=?",
                array($curr_article->s_locale));
            $select_categories = array();
            ArticleCategory::toSelectArray($all_categories, $select_categories,
                0, array($curr_article->id), array('0' => __('Uncategorised')));

            $this->assign('select_categories', $select_categories);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

        $this->assign('article_title', __('Edit News'));
        $this->assign('next_action', 'admin_update');
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());

        return '_form';

    }
    public function admin_update() {

        $article_info =& ParamHolder::get('article', array());
        if (sizeof($article_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing article information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
            // Re-arrange publish time
        	$article_info['pub_start_time'] = -1;
        	$article_info['pub_end_time'] = -1;
            // Re-arrange publish status
//            if ($article_info['published'] == '1') {
//                $article_info['published'] = '1';
//            } else {
//                $article_info['published'] = '0';
//            }
            $article_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            $article_info['intro'] = 'news_intro';
            $article_info['tags'] = strip_tags($article_info['tags']);
            $article_info['source'] = strip_tags($article_info['source']);
            $article_info['author'] = strip_tags($article_info['author']);
            $article_info['title'] = strip_tags($article_info['title']);


            // Data operation
            $o_article = new Article($article_info['id']);
            $o_article->set($article_info);
            $o_article->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_news', 'admin_list'))));
        return '_result';
    }
    public function admin_delete() {

        $article_id = trim(ParamHolder::get('article_id', '0'));
        if (intval($article_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			if (strpos($article_id, '_') > 0) {
				$tmp_arr = explode('_', $article_id);
				$len = sizeof($tmp_arr) - 1;
				for ($i = 0; $i< $len; $i++){
					$curr_article = new Article($tmp_arr[$i]);
					$curr_article->delete();
				}

			} else {
				$curr_article = new Article($article_id);
				$curr_article->delete();
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
 	public function admin_pic()
    {
    	$news_info = array();
    	$news_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($news_id))
    	{
    		$o_news = new Article($news_id);
            if($o_news->published == 1)
            {
            	$news_info['published'] = '0';
            	$o_news->set($news_info);
            	$o_news->save();
				die('0');
            }
            elseif($o_news->published == 0)
            {
            	$news_info['published'] = '1';
            	$o_news->set($news_info);
            	$o_news->save();
				die('1');
            }
    	}
    }
}
?>
