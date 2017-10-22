<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModNews extends Module
{
	protected $_filters = array(
		'check_login' => '{recentnews}{fullist}{news_content}'
	);
	
	public function recentnews()
	{
		$list_size = trim(ParamHolder::get('news_reclst_size'));
        if (!is_numeric($list_size) || strlen($list_size) == 0) 
        {
            $list_size = '5';
        }
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $o_article = new Article();//news and article's object share one table named article
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        if (ACL::requireRoles(array('admin'))) 
        {
            $news = $o_article->findAll("article_category_id=2 AND published='1' AND s_locale=? ", array($curr_locale),
                        "ORDER BY `i_order` DESC, `create_time` DESC LIMIT ".$list_size);
        } 
        else 
        {
            $news = $o_article->findAll("article_category_id=2 AND published='1' AND s_locale=? AND for_roles LIKE ? ", array($curr_locale, '%'.$user_role.'%'),
                        "ORDER BY `i_order` DESC, `create_time` DESC LIMIT ".$list_size);
        }

        $this->assign('news', $news);
	}
	
	public function news_content()
	{
		$this->_layout = 'mod_article';
        $news_id = ParamHolder::get('news_id', '0');
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        if (intval($news_id) == 0) 
        {
            ParamParser::goto404();
        }
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        try {
            $now = time();
            $o_article = new Article();
            if (ACL::requireRoles(array('admin'))) 
            {
                $curr_news =& $o_article->find("`id`=? AND "
                            ."((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND article_category_id=2 AND s_locale=?",
                        array($news_id, $now, $now, $now, $now, $curr_locale));
                $curr_news->v_num++;
                $curr_news->save();
            }
            else 
            {
                $curr_news =& $o_article->find("`id`=? AND "
                            ."((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND article_category_id=2 AND for_roles LIKE ? AND s_locale=?",
                        array($news_id, $now, $now, $now, $now, '%'.$user_role.'%', $curr_locale));
                if(sizeof($curr_news) > 0) 
                {
                    $curr_news->v_num++;
                    $curr_news->save();
                }
                else
                {
                    ParamParser::goto404();
                }
            }
            $this->assign('page_title', $curr_news->title);
            $this->assign('curr_news', $curr_news);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
	}
	
	public function fullist() 
	{
        $this->_layout = 'mod_article';

        // The default article category
        $curr_article_category = new ArticleCategory();
        $curr_article_category->name = __('All News');

        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        $curr_locale = trim(SessionHolder::get('_LOCALE'));

        $search_where = '';
        $search_params = array();
        $article_keyword = trim(ParamHolder::get('article_keyword', '',PS_POST))?Toolkit::baseEncode(trim(ParamHolder::get('article_keyword', '',PS_POST))):trim(ParamHolder::get('article_keyword', '',PS_GET));     
        $article_keyword = Toolkit::baseDecode($article_keyword);
        if (strlen($article_keyword) > 0)
        {
            $search_where = ' AND (title LIKE ? OR content LIKE ?)';
            $search_params = array('%'.$article_keyword.'%', '%'.$article_keyword.'%');
            $this->assign('article_keyword', $article_keyword);
        }
        
        try {
            $now = time();
            $o_article = new Article();
            /**
             * Add 02/08/2010
             */
            include_once(P_LIB.'/pager.php');
            
            if (ACL::requireRoles(array('admin'))) 
            {
                $article_data =&
                    Pager::pageByObject('article',
                        "((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND article_category_id=2 AND s_locale=?".$search_where,
                        array_merge(array($now, $now, $now, $now, $curr_locale), $search_params),
                        "ORDER BY `i_order` DESC,`create_time` DESC");
            } 
            else 
            {
                $article_data =&
                    Pager::pageByObject('article',
                        "((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND article_category_id=2 AND for_roles LIKE ? AND s_locale=?".$search_where,
                        array_merge(array($now, $now, $now, $now, '%'.$user_role.'%', $curr_locale), $search_params),
                        "ORDER BY `i_order` DESC,`create_time` DESC");
            }

            $this->assign('page_title', $curr_article_category->name);

            $this->assign('category', $curr_article_category);
            $this->assign('articles', $article_data['data']);
            $this->assign('pager', $article_data['pager']);
            $this->assign('page_mod', $article_data['mod']);
			$this->assign('page_act', $article_data['act']);
			$this->assign('page_extUrl', $article_data['extUrl']);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
    }
}
?>