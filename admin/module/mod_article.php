<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModArticle extends Module {

	protected $_filters = array(
        'check_admin' => ''
    );
	public function admin_batch_create() {
		$file_allow_ext_pat = '/\.(csv)$/i';
		$file_info =& ParamHolder::get('batch_file', array(), PS_FILES);
		if (empty($file_info)) {
            Notice::set('mod_article/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_article', 'admin_batch'));
        }
		if(!preg_match($file_allow_ext_pat, $file_info["name"])) {
			Notice::set('mod_article/msg', __('File type error!'));
            Content::redirect(Html::uriquery('mod_article', 'admin_batch'));
		}

		$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
		if (!$this->_savetplFile($file_info)) {
            Notice::set('mod_article/msg', __('Uploading file failed!'));
            Content::redirect(Html::uriquery('mod_article', 'admin_batch'));
        }
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
		$article_info = array();
		$article_info['pub_start_time'] = -1;
		$article_info['pub_end_time'] = -1;
		$article_info['published'] = '1';
		$article_info['for_roles'] = '{member}{admin}{guest}';
		$article_info['create_time'] = time();
		$article_info['v_num'] = 0;
		$article_info['s_locale'] = $curr_locale;

		$handle = fopen(ROOT.'/upload/file/'.$file_info["name"],"r");
		$row = 1;
		while ($data = fgetcsv($handle)) {
			if($row == 1){
				$row++;
				continue;
			}
			$num = count($data);//9列
			$row++;//行数
			//for ($c=0; $c < $num; $c++) {
				$o_article = new Article();
				$article_info['i_order'] = Article::getMaxOrder(1) + 1;
				$article_info['title'] = iconv('gb2312','utf-8',strip_tags($data[0]));
				$article_info['author'] = iconv('gb2312','utf-8',strip_tags($data[1]));
				
				$article_class = iconv('gb2312','utf-8',strip_tags($data[2]));
				$o_article_class = new ArticleCategory();
				$article_arr = $o_article_class->findAll("name='".$article_class."'");
				$article_info['article_category_id'] = $article_arr[0]->id;

				$article_info['source'] = iconv('gb2312','utf-8',strip_tags($data[3]));
				$article_info['tags'] = iconv('gb2312','utf-8',strip_tags($data[4]));
				$article_info['intro'] = iconv('gb2312','utf-8',strip_tags($data[5]));
				$article_info['content'] = str_replace( array("/r/n", "/r", "/n"), array("\r\n", "\r", "\n"), iconv('gb2312','utf-8',$data[6]) );
				$o_article->set($article_info);
				$o_article->save();
			//}
		}
		fclose($handle);
/*
		require_once P_LIB.'/Excel/reader.php';
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('gb2312');
		$data->read(ROOT.'/upload/file/'.$file_info["name"]);
		
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
		$article_info = array();
		$article_info['pub_start_time'] = -1;
		$article_info['pub_end_time'] = -1;
		$article_info['published'] = '1';
		$article_info['for_roles'] = '{member}{admin}{guest}';
		$article_info['create_time'] = time();
		$article_info['v_num'] = 0;
		$article_info['s_locale'] = $curr_locale;
		
		for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
			$o_article = new Article();
			$article_info['i_order'] =
            	Article::getMaxOrder(1) + 1;
			
			$article_info['title'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][1]));
			$article_info['author'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][2]));
			
			$article_class = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][3]));
			$o_article_class = new ArticleCategory();
			$article_arr = $o_article_class->findAll("name='".$article_class."'");
			$article_info['article_category_id'] = $article_arr[0]->id;

			$article_info['source'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][4]));
			$article_info['tags'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][5]));
			$article_info['intro'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][6]));
			$article_info['content'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][7]));
			$o_article->set($article_info);
            $o_article->save();
		}
		*/
		@unlink(ROOT.'/upload/file/'.$file_info["name"]);
		Notice::set('mod_article/msg', __('Article order added successfully!'));
		Content::redirect(Html::uriquery('mod_article', 'admin_list'));
	}
	public function admin_batch() {
		die('Access deny');
		$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_article/_LOCALE', $curr_locale));
        
        $this->assign('content_title', __('Batch Import'));
        $this->assign('next_action', 'admin_batch_create');
        
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
	}
    public function admin_list() {
//    	echo md5_file(ROOT.'/library/toolkit.php');
        $this->_layout = 'content';
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_article/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_article/_LOCALE', $lang_sw);
		$keyword = trim(ParamHolder::get('hidkeyword', '',PS_POST))?Toolkit::baseEncode(trim(ParamHolder::get('hidkeyword', '',PS_POST))):trim(ParamHolder::get('hidkeyword', '',PS_GET));      
        $keyword = Toolkit::baseDecode($keyword);

        $where = "s_locale=?";
        $params = array($lang_sw);
        
        $caa_sw = trim(ParamHolder::get('caa_sw', '-'));
        // 02/06/2010 Edit >>
        $all_categories =& ArticleCategory::listCategories(0, "s_locale=?", array($lang_sw));
        if (is_numeric($caa_sw)) {
        	$childids = '';
	        $childids = $this->getCategoryChildIds( $caa_sw, $curr_locale );

	        $catids = !empty($childids) ? $childids.$caa_sw : $caa_sw;
			if ($caa_sw==0) {
	        	$where .= " AND article_category_id=0";
	        }else{
	        	 $where .= " AND article_category_id IN(".$catids.")";
	        }
	        //$where .= " AND article_category_id IN(".$catids.")";
            //$where .= " AND article_category_id=?";
            //$params[] = $caa_sw;
        }
        // 02/06/2010 Edit <<
		$where .=  " AND article_category_id <> 2";//article can't see News'infomation.
		if( trim($keyword) ) $where .=  " AND title LIKE '%{$keyword}%'";
        
        $article_data =&Pager::pageByObject('Article', $where, $params,"ORDER BY `i_order` DESC,`create_time` DESC");
		$this->assign('default_lang', trim(SessionHolder::get('_LOCALE')));
        $this->assign('next_action', 'admin_order');
        $this->assign('articles', $article_data['data']);
        $this->assign('pager', $article_data['pager']);
		$this->assign('page_mod', $article_data['mod']);
		$this->assign('page_act', $article_data['act']);
		$this->assign('page_extUrl', $article_data['extUrl']);
        $this->assign('lang_sw', $lang_sw);
        $this->assign('caa_sw', $caa_sw);
        $this->assign('keyword', $keyword);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());

        // Prepare article category for select list view
        //$all_categories =& ArticleCategory::listCategories(0, "s_locale=?", array($lang_sw));
        $select_categories = array();
        ArticleCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('-' => __('View All'), '0' => __('Uncategorised')));

		// 28/06/2010 excel export >>
		$act = trim(ParamHolder::get('act', ''));
		if( $act == '9999' ) {			
			$articles = $rows = array();
			$obj = new Article();
			$articles =& $obj->findAll($where, $params, "ORDER BY `create_time` DESC");

			$rows[] = array(__('Title'), __('Author'), __('Category'), __('Source'), __('Tags'), __('Abstract'), __('Article Content'), __('Add Date'));
			// article list
			foreach ($articles as $article) {
				$article->loadRelatedObjects(REL_PARENT, array('ArticleCategory'));
				$rows[] = array($article->title, $article->author, $article->masters['ArticleCategory']->name, $article->source, 
				             $article->tags, $article->intro, $article->content, date('Y-m-d H:i:s', $article->create_time));
			}
			
			include_once P_LIB."/Excel/export.class.php";
			$csv = new Export_CSV($rows, 'articles.csv');
			$csv->Export();
		}
		// 28/06/2010 excel export <<

        $this->assign('select_categories', $select_categories);
    }

    public function admin_add() {
        $this->_layout = 'content';

        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_article/_LOCALE', $curr_locale));

        $this->assign('article_title', __('New Article'));
        $this->assign('next_action', 'admin_create');

        $all_categories =& ArticleCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        ArticleCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('0' => __('Uncategorised')));

        $this->assign('select_categories', $select_categories);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('language_info',$mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
		$this->assign('act','add');
        return '_form';
    }
    
    public function admin_mi_quick_add() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_menu_item/_LOCALE', $curr_locale));

        $this->assign('article_title', __('New Article'));
        $this->assign('next_action', 'admin_create');

        $all_categories =& ArticleCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        ArticleCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('0' => __('Uncategorised')));

        $this->assign('select_categories', $select_categories);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
        $link_type_text = trim(ParamHolder::get('txt'));
        $this->assign('type_text', $link_type_text);

        $this->_layout = 'clean';
        return '_mi_quick_add_form';
    }

    public function admin_create() {

        $article_info =& ParamHolder::get('article', array());
        if (sizeof($article_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing article information!')));
            return '_result';
        }
        if($article_info['article_category_id'] == -1) {
        	$article_info['article_category_id'] = 0;
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
            $article_info['pub_start_time'] = -1;
            $article_info['pub_end_time'] = -1;
			$article_info['published'] = '1';
            $article_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            $article_info['create_time'] = strtotime($article_info['create_time']);
            $article_info['v_num'] = 0;
            $article_info['intro'] =strip_tags($article_info['intro'])?strip_tags($article_info['intro']):mb_substr($article_info['content'],0,120,'utf-8');
            $article_info['intro'] = str_replace('<div>','',$article_info['intro']);
            $article_info['intro'] = str_replace('</div>','',$article_info['intro']);
            $article_info['intro'] = trim($article_info['intro']);
            $article_info['tags'] = strip_tags($article_info['tags']);
            $article_info['source'] = strip_tags($article_info['source']);
            $article_info['author'] = strip_tags($article_info['author']);
            $article_info['title'] = strip_tags($article_info['title']);
            $article_info['i_order'] = Article::getMaxOrder($article_info['article_category_id']) + 1;
            $article_info['is_seo'] = $article_info['is_seo'];
            $article_info['description'] = strip_tags($article_info['description']);
            // Data operation
            $o_article = new Article();
            $o_article->set($article_info);
            $o_article->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_article', 'admin_list'), 
            'id' => $o_article->id, 'title' => $o_article->title)));
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
		Content::redirect(Html::uriquery('mod_article', 'admin_list'));
	}
    public function admin_edit() {
        $this->_layout = 'content';
		
        $article_id = ParamHolder::get('article_id', '0');
		$article_id = intval($article_id);
        if ($article_id == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
       try {
            $curr_article = new Article($article_id);
            $this->assign('curr_article', $curr_article);
            $this->assign('language_info',$curr_article->s_locale);
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

        $this->assign('article_title', __('Edit Article'));
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
		$is_member_only = intval($is_member_only);
        try {
            // Re-arrange publish time
            //if (intval(ParamHolder::get('pub_start_time', '0')) == 0) {
                $article_info['pub_start_time'] = -1;
            //} else {
            //    $article_info['pub_start_time'] = strtotime($article_info['pub_start_time']);
            //}
            //if (intval(ParamHolder::get('pub_end_time', '0')) == 0) {
                $article_info['pub_end_time'] = -1;
            //} else {
            //    $article_info['pub_end_time'] = strtotime($article_info['pub_end_time']);
            //}
            // Re-arrange publish status
//            if ($article_info['published'] == '1') {
//                $article_info['published'] = '1';
//            } else {
//                $article_info['published'] = '0';
//            }
            $article_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            $article_info['intro'] = strip_tags($article_info['intro']);
            $article_info['intro'] = str_replace('<div>','',$article_info['intro']);
            $article_info['intro'] = str_replace('</div>','',$article_info['intro']);
            $article_info['tags'] = strip_tags($article_info['tags']);
            $article_info['source'] = strip_tags($article_info['source']);
            $article_info['author'] = strip_tags($article_info['author']);
            $article_info['title'] = strip_tags($article_info['title']);
			$article_info['create_time'] = strtotime($article_info['create_time']);

            // Data operation
            $o_article = new Article($article_info['id']);
           // $pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
			//$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
			//$article_info['content'] = str_replace($path,"",$article_info['content']);
			
            $o_article->set($article_info);
            $o_article->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_article', 'admin_list'))));
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
    	$article_info = array();
    	$article_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($article_id))
    	{
    		$o_article = new Article($article_id);
            if($o_article->published == 1)
            {
            	$article_info['published'] = '0';
            	$o_article->set($article_info);
            	$o_article->save();
				die('0');
            }
            elseif($o_article->published == 0)
            {
            	$article_info['published'] = '1';
            	$o_article->set($article_info);
            	$o_article->save();
				die('1');
            }
    	}
    }
    
    public function admin_dashboard() {
    	$this->_layout = 'default';
    }
    
    // 02/06/2010 Add >>
	private function getCategoryChildIds( $cur_classid, $curr_locale ) 
    {
    	$childids = '';
    	$article_childcategories = array();
    	$article_category = new ArticleCategory();
    	$article_childcategories = $article_category->findAll("article_category_id = '{$cur_classid}' AND s_locale = '{$curr_locale}'");
    	
    	if ( count($article_childcategories) > 0 ) {
    		foreach( $article_childcategories as $val ) 
    		{
    			$childids .= $val->id.',';
    			$childids .= $this->getCategoryChildIds( $val->id, $curr_locale );
    		}
    	}
   
    	return $childids;
    }
    // 02/06/2010 Add <<

	private function _savetplFile($struct_file) {
    	$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
        move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/file/'.$struct_file['name']);
        return ParamParser::fire_virus(ROOT.'/upload/file/'.$struct_file['name']);
    }
    
    public function copy_article(){
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	$this->_layout = 'content';
    	$article = ParamHolder::get('article');
    	$articles = explode(",",$article);
    	if (count($articles)<=0) {
    		echo '<script>alert("'.__("Choose article please").'");history.go(-1);</script>';
    	}
    	$o_lan = new Language();
    	$lans = $o_lan->findAll();

		$article_token=Toolkit::token();
		SessionHolder::set('token/article', $article_token);
        $this->assign('lans', $lans);
        $this->assign('token', $article_token);
        $this->assign('article', $article);
    	
    }
    
    public function move_article(){
    	$this->_layout = 'content';
    	$article = ParamHolder::get('article');
    	$article_cate = new ArticleCategory();
    	$cate = $article_cate->getCategoryArray();
    	$cate_id = $article_cate->getPublishedCategoryArray();
    	
    	if (count($article)<=0) {
    		die('<script>alert("'.__("Choose article please").'");history.go(-1);</script>');
    	}
		$move_article_token=Toolkit::token();
		SessionHolder::set('token/movearticle', $move_article_token);
        $this->assign('article', $article);
        $this->assign('cates', $cate);
		$this->assign('token', $move_article_token);
        $this->assign('cate_ids', $cate_id);
    }
    
    public function save_move(){
    	$article = ParamHolder::get("article");
    	$articles = explode(",",$article);
    	$cate = ParamHolder::get('cate');
		$token = ParamHolder::get("token",'0');
		if(SessionHolder::get('token/movearticle')!=$token){
			die("access violation error!");
		}
		SessionHolder::set('token/movearticle', '');
    	foreach ($articles as $id){
    		if (!empty($id)) {
    			$o_art = new Article($id);
	    		$o_art->article_category_id = intval($cate);
	    		$o_art->save();
    		}
    		
    		
    	}
    	die('<script>alert("'.__("Move Success!").'");parent.location.reload();</script>');
    }
    
    public function save_copy(){
    	$curr_locale = trim(SessionHolder::get("mod_article/_LOCALE"));
    	$lans = ParamHolder::get("lan");
    	$token = ParamHolder::get("token",'0');
		if(SessionHolder::get('token/article')!=$token){
			die("access violation error!");
		}
		SessionHolder::set('token/article','');
    	if (count($lans)<=0) {
    		echo '<script>alert("'.__("Choose language please").'");history.go(-1);</script>';
    	}
    	$articles = ParamHolder::get("article");
    	$articles = explode(",",$articles);
    	if (count($lans>=1)) {
    		foreach ($lans as $k=>$lan){
    			foreach ($articles as $id){//对文章ID进行文章copy
    				$o_art = new Article($id);
    				if ($curr_locale=='zh_CN' || $curr_locale=="zh_TW") {
    					if ($lan=='zh_CN' || $lan=='zh_TW') {
    						$article_info['author'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->author);
				            $article_info['title'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->title);
				            $article_info['i_order'] = $o_art->i_order; 
				            $article_info['source'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->source);
				            $article_info['tags'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->tags);
				            $article_info['intro'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->intro);
				            $article_info['content'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->content);
				            $article_info['create_time'] = $o_art->create_time;
				            $article_info['s_locale'] = $lan;
				            $article_info['pub_start_time'] = $o_art->pub_start_time;
				            $article_info['pub_end_time'] = $o_art->pub_end_time;
				            $article_info['published'] = $o_art->published;
				            $article_info['for_roles'] = $o_art->for_roles;
				            $article_info['v_num'] = $o_art->v_num;
				            $article_info['article_category_id'] = $o_art->article_category_id;
				            $article_info['is_seo'] = $o_art->is_seo;
							$article_info['description'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->description);
    					}else{
		    				$article_info['author'] = $o_art->author;
				            $article_info['title'] = $o_art->title;
				            $article_info['i_order'] = $o_art->i_order; 
				            $article_info['source'] = $o_art->source;
				            $article_info['tags'] = $o_art->tags;
				            $article_info['intro'] = $o_art->intro;
				            $article_info['content'] = $o_art->content;
				            $article_info['create_time'] = $o_art->create_time;
				            $article_info['s_locale'] = $lan;
				            $article_info['pub_start_time'] = $o_art->pub_start_time;
				            $article_info['pub_end_time'] = $o_art->pub_end_time;
				            $article_info['published'] = $o_art->published;
				            $article_info['for_roles'] = $o_art->for_roles;
				            $article_info['v_num'] = $o_art->v_num;
				            $article_info['article_category_id'] = $o_art->article_category_id;
				            $article_info['is_seo'] = $o_art->is_seo;
							$article_info['description'] = $o_art->description;
	    				}
    				}else{
	    				$article_info['author'] = $o_art->author;
			            $article_info['title'] = $o_art->title;
			            $article_info['i_order'] = $o_art->i_order; 
			            $article_info['source'] = $o_art->source;
			            $article_info['tags'] = $o_art->tags;
			            $article_info['intro'] = $o_art->intro;
			            $article_info['content'] = $o_art->content;
			            $article_info['create_time'] = $o_art->create_time;
			            $article_info['s_locale'] = $lan;
			            $article_info['pub_start_time'] = $o_art->pub_start_time;
			            $article_info['pub_end_time'] = $o_art->pub_end_time;
			            $article_info['published'] = $o_art->published;
			            $article_info['for_roles'] = $o_art->for_roles;
			            $article_info['v_num'] = $o_art->v_num;
			            $article_info['article_category_id'] = $o_art->article_category_id;
			            $article_info['is_seo'] = $o_art->is_seo;
						$article_info['description'] = $o_art->description;
    				}
					$n_art = new Article();
		            $n_art->set($article_info);
    				$n_art->save();
    				
    			}
    		}
    	}else{//一个语言
    		echo 'aa';
    	}
    	
    	echo '<script>alert("'.__("Copy Success!").'");parent.location.href="index.php?_m=mod_article&_a=admin_list";</script>';
    	exit;
    }
    
  
}
?>
