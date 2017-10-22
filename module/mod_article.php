<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModArticle extends Module {
    protected $_filters = array(
        'check_login' => '{recentarticles}{article_content}{fullist}{recentshort}'
    );
    
    private $stack = array();
    private $findout = array();

    public function fullist() {
        $this->_layout = 'frontpage';
        $curr_locale = trim(SessionHolder::get('_LOCALE'));

        // The default article category
        $curr_article_category = new ArticleCategory();
        if(MOD_REWRITE=='3'){
        	$_path=strtolower(ParamHolder::get('_path', ''));
        	$o_url = new MenuItem();
//        	if (strrpos($_path,'p-')) {
			$exarr = explode('-',$_path);
			// xqf 是否自定义判断
        	if (!is_numeric($exarr[1])) {
					//存在分页ss
				//$use_str = substr($_path,0,strrpos($_path,'p')-1);
				$arr = explode('-',$_path);
				$use_str = $arr[0];
				$url_res = $o_url->find('url=? and s_locale=?',array($use_str,$curr_locale));
        	}else{
        		 $url_res = $o_url->find('url=? and s_locale=?',array($_path,$curr_locale));
        	}
	       
	        if (!empty($url_res)) {//用户自定义的链接处理
	        	$u_link = $url_res->link;
	        	$caa_id = substr($u_link,strrpos($u_link,'=')+1);//取类别ID
	        }else{
	        	include(P_INC.'/custom.php');
	        	$arr = explode("-",$_path);
	        	$caa_id = $arr[1];
	        	$caa_id = str_replace('_',',',$caa_id);
	        }
	        
        }else{
        	$caa_id = trim(ParamHolder::get('caa_id', '0'));
        	$caa_id = str_replace('_',',',$caa_id);//适应伪静态
        }
     /*   $pattern = '/^[0-9][\,0-9]*$/';
        if(!preg_match($pattern, $caa_id)){
        	die('access violation error!');;
        }*/
		$cap_id = intval($cap_id);
		
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        
 		$page_title = new MenuItem();         
        $title_info = $page_title->find(" `link`=? and  s_locale=?",array("_m=mod_article&_a=fullist",$curr_locale)," limit 1"); 
		if(isset($title_info->name)){
			$curr_article_category->name = $title_info->name;
		}else{
			$curr_article_category->name = '';
		}
		//取模块title,以_m _a 和页面模块的hash值进行寻找，避免找错
		$hash_v = $this->hash_mod($_SERVER[HTTP_REFERER]);
		$link_all = Toolkit::calcMQHash('_all');
		$link_mod = Toolkit::calcMQHash($hash_v);
		$o_block = new ModuleBlock();         
        $tit_res = $o_block->findAll(" `module`=? and `action`=? and  s_locale=? and s_query_hash=?",array("mod_article","recentarticles",$curr_locale,$link_mod)); 
        if (!$tit_res) {
        	$tit_res = $o_block->findAll(" `module`=? and `action`=? and  s_locale=? and s_query_hash=?",array("mod_article","recentarticles",$curr_locale,$link_all)); 
        	if (!$tit_res) {
        		$tit_res = $o_block->findAll(" `module`=? and `action`=? and  s_locale=? ",array("mod_article","recentarticles",$curr_locale)); 
        	}
        }
        if (sizeof($tit_res)>1) {
        	foreach ($tit_res as $k3=>$v3){
        		$spar = unserialize($v3->s_param);
        		//文章类别处理，整个类别的值和sparm中的不一样，需要判断
        		if (strstr($spar['article_category_list'],',')) {
        			if ($caa_id==$spar['article_category_list']) {
	        			$tit_res[0] = $v3;
	        		}
        		}else{
        			if (strstr($caa_id,$spar['article_category_list'])) {
        				$tit_res[0] = $v3;
        			}
        		}
        	}
        }
        $search_where = '';
        $search_params = array();
        $article_keyword = trim(ParamHolder::get('article_keyword', '',PS_POST))?Toolkit::baseEncode(trim(ParamHolder::get('article_keyword', '',PS_POST))):trim(ParamHolder::get('article_keyword', '',PS_GET)); 
        $article_keyword = Toolkit::baseDecode($article_keyword);
		$article_keyword=htmlspecialchars($article_keyword);
		$article_keyword =str_replace(array('<','>','"',"'"),array('','','',''),$article_keyword);
		
        if (strlen($article_keyword) > 0) {
            $search_where = ' AND (title LIKE ? OR content LIKE ?)';
            $search_params = array('%'.$article_keyword.'%', '%'.$article_keyword.'%');
            $this->assign('article_keyword', $article_keyword);
        }else if (intval($caa_id) > 1) {
        	$article_category = new ArticleCategory();
        	$article_categories = $article_category->findAll();
        	if(empty($article_categories)) $article_categories = array();
        	foreach($article_categories as $k => $v)
        	{
        		$this->stack[$v->id] = $v->article_category_id;
        	}
        	$this->findout[] = $caa_id;
        	$this->getCategoryList();
        	$search_where = " AND article_category_id IN ('' ";
    		foreach($this->findout as $k => $v)
    		{
    			$search_where .= ",$v";
    			if (strstr($v,',')) {
    				$more = true;
    			}
    		}
//    		$len = strlen($search_where);
//    		$search_where[$len-1] = ''; 
    		$search_where .= ') AND article_category_id <> 0';//die($search_where);
            $curr_article_category = new ArticleCategory($caa_id);
        }
        try {
            $now = time();
            $o_article = new Article();
            include_once(P_LIB.'/pager.php');

            if (ACL::requireRoles(array('admin'))) {
            	$str_sql = "((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND article_category_id<>2";
//                if(empty($caa_id))
//                {
//                	$str_sql .= " AND article_category_id=2";//article_category is news.
//                }         
                
                $article_data =&
                    Pager::pageByObject('article',
                        $str_sql. " AND s_locale=?".$search_where,
                        array_merge(array($now, $now, $now, $now, $curr_locale), $search_params),
                        "ORDER BY `i_order` DESC,`create_time` DESC");
            } else {
            	$str_sql = "((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND article_category_id<>2";
//            	if(empty($caa_id))
//                {
//                	$str_sql .= " AND article_category_id=2";//article_category is news.
//                }         
                         
                $article_data =&
                    Pager::pageByObject('article',
                        $str_sql .= " AND for_roles LIKE ? AND s_locale=?".$search_where,
                        array_merge(array($now, $now, $now, $now, '%'.$user_role.'%', $curr_locale), $search_params),
                        "ORDER BY `i_order` DESC,`create_time` DESC");
            }
			if(isset($curr_article_category->name)){
				$this->assign('page_title', $curr_article_category->name);
			}else{
				$this->assign('page_title', '');
			}
			if(!$more){
				$this->assign('mod_title',$curr_article_category->name);
			}else{
				$this->assign('mod_title',$tit_res[0]->title);
			}
            $this->assign('category', $curr_article_category);
            $this->assign('articles', $article_data['data']);
            $this->assign('pager', $article_data['pager']);
            $this->assign('page_mod', $article_data['mod']);
			$this->assign('page_act', $article_data['act']);
			$this->assign('page_extUrl', $article_data['extUrl']);
            $this->assign('caa_id', $caa_id);
            $this->assign('article_keyword', $article_keyword);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

    }

    public function article_content() {
    	 $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $this->_layout = 'frontpage';
        if(MOD_REWRITE=='3'){
        	include(P_INC.'/custom.php');
        	$_path=strtolower(ParamHolder::get('_path', ''));
        	if(strrpos($_path,'-_v-preview')) $_path = str_ireplace('-_v-preview','',$_path);//存在预览       	
        	if (strpos($_path,'-')) {//系统默认Url
        		$arr = explode("-",$_path);
        		$m_url = $arr[1];
        	}else{//用户自定义URL
        		$m_url = $_path;
        	}
			$o = new MenuItem();
			$s = $o->find("`url`='".$m_url."' and s_locale='".$curr_locale."'");
			if(!$s){//如果是系统的，取文章ID
				$article_id = intval($arr[1]);
			}else{//如果是自定义的，取表中ID
				$article_id = intval($s->content_id);
			}
			if (strpos($_SERVER[HTTP_REFERER],'admin') ) {
				if (empty($article_id)) {
					$article_id = ParamHolder::get('article_id');
				}
			}
        }else{
        	$article_id = ParamHolder::get('article_id', '0');
        }
//        echo $article_id;exit;
       
        if (intval($article_id) == 0) {
            ParamParser::goto404();
        }
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        try {
            $now = time();
            $o_article = new Article();
            if (ACL::requireRoles(array('admin'))) {
                $curr_article =& $o_article->find("`id`=? AND "
                            ."((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND article_category_id<>2",
                        array($article_id, $now, $now, $now, $now));
				if($curr_article){
					$curr_article->v_num++;
					$curr_article->save();
				}else{
					ParamParser::goto404();
				}
            } else {
                $curr_article =& $o_article->find("`id`=? AND "
                            ."((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND article_category_id<>2 AND for_roles LIKE ?",
                        array($article_id, $now, $now, $now, $now, '%'.$user_role.'%'));
							//var_dump(sizeof($curr_article));
                if(sizeof($curr_article) > 0) {
					if($curr_article){
						$curr_article->v_num++;
						$curr_article->save();
					}else{
					 	ParamParser::goto404();
					}
                } else {
                	ParamParser::goto404();
                }
            }
            include_once(P_LIB.'/pager.php');
            $page_title = new MenuItem();       	 
        	
            if ($page_title->count(" `link`=?  and  s_locale=?",array("_m=mod_article&_a=article_content&article_id={$article_id}",$curr_locale))) {
         		$title_info = $page_title->find(" `link`=?  and  s_locale=?",array("_m=mod_article&_a=article_content&article_id={$article_id}",$curr_locale)," limit 1 "); 
         		$this->assign('page_title', $title_info->name);
            }else{
            	$this->assign('page_title', $curr_article->title);
            }
            $article_category_id = $curr_article->article_category_id;
            $nextAndPrevArr = $this->getNextAndPrev($article_id,$article_category_id);
            $content=$curr_article->content;
            $article_data=&Pager::pageByText( $content,array('article_id'=>$article_id));
            $curr_article->content=$article_data['data'];
            $this->assign('page_mod', $article_data['mod']);
		    $this->assign('page_act', $article_data['act']);
		  	$this->assign('page_extUrl', $article_data['extUrl']);
            $this->assign('pagetotal', $article_data['total']);
            $this->assign('pagenum', $article_data['cur_page']);
            $this->assign('curr_article', $curr_article);
            $this->assign('nextAndPrevArr', $nextAndPrevArr);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
    }

    public function recentarticles() {
        $list_size = trim(ParamHolder::get('article_reclst_size'));
        if (!is_numeric($list_size) || strlen($list_size) == 0) {
            $list_size = '5';
        }
        
        $article_category = ParamHolder::get('article_category_list', '0');
		if(empty($article_category)){
			 $article_category='0';
		}
		
        $pattern = '/^[0-9][\,0-9]*$/';
        if(!preg_match($pattern, $article_category)){
        	die('access violation error!');;
        }

        $this->assign('article_category', $article_category);
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        // 02/06/2010 Add >>
        $childids = $this->getCategoryChildIds($article_category, $curr_locale);
        $article_category = !empty($childids) ? $childids.$article_category : $article_category;
        $article_category = $this->arrUnique($article_category);
        // 02/06/2010 Add <<
        $o_article = new Article();
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        if (ACL::requireRoles(array('admin'))) {
            if($article_category == 0) {
                $articles = $o_article->findAll("article_category_id<>2 AND published='1' AND s_locale=? ", array($curr_locale),
                            "ORDER BY `i_order` DESC, `create_time` DESC LIMIT ".$list_size);
            } else {
//                $articles = $o_article->findAll("article_category_id<>2 AND published='1' AND s_locale=? and article_category_id=? ", array($curr_locale,$article_category),
//                            "ORDER BY `i_order` DESC, `create_time` DESC LIMIT ".$list_size);		
                $articles = $o_article->findAll("article_category_id<>2 AND published='1' AND s_locale=? and article_category_id IN(".$article_category.") ", array($curr_locale),
                            "ORDER BY `i_order` DESC, `create_time` DESC LIMIT ".$list_size);            }
        } else {
			if($article_category == 0) {
                $articles = $o_article->findAll("article_category_id<>2 AND published='1' AND s_locale=? AND for_roles LIKE ? ", array($curr_locale, '%'.$user_role.'%'),
                            "ORDER BY `i_order` DESC, `create_time` DESC LIMIT ".$list_size);
            } else {
//                $articles = $o_article->findAll("article_category_id<>2 AND published='1' AND s_locale=? and article_category_id=? AND for_roles LIKE ? ", array($curr_locale,$article_category, '%'.$user_role.'%'),
//                            "ORDER BY `i_order` DESC, `create_time` DESC LIMIT ".$list_size);
                $articles = $o_article->findAll("article_category_id<>2 AND published='1' AND s_locale=? and article_category_id IN(".$article_category.") AND for_roles LIKE ? ", array($curr_locale, '%'.$user_role.'%'),
                            "ORDER BY `i_order` DESC, `create_time` DESC LIMIT ".$list_size);
            }
        }
        $article_category = str_replace(',','_',$article_category);
        $this->assign('article_category', $article_category);
        $this->assign('articles', $articles);
    }

    public function recentshort() {
        return $this->recentarticles();
    }
    
	public function getCategoryList()
    {
    	$i = $j = count($this->stack);
    	$flag = true;
    	while(($j < $i) || $flag)
    	{
	    	$i = $j;
    		foreach($this->stack as $k => $v)
	    	{
	    		if(in_array($v,$this->findout))
	    		{
	    			$this->findout[] = $k;
	    			unset($this->stack[$k]);
	    		}
	    	}
	    	$j = count($this->stack);
	    	$flag = false;
    	}
    }
    
    // 02/06/2010 Add >>
    private function getCategoryChildIds( $parentid, $curr_locale )
    {
//			echo $parentid;
//    	$article_childcategories = array();
//    	$article_category = new ArticleCategory();
//    	$article_childcategories = $article_category->findAll("article_category_id IN({$parentid}) AND s_locale = '{$curr_locale}'");
//    	
//    	$childids = '';
//    	if ( count($article_childcategories) ) {
//    		foreach( $article_childcategories as $val )
//    		{
//    			$childids .= $val->id.',';
//				$childids .= $this->getCategoryChildIds($val->id, $curr_locale);
//    		}
//    	}
//    	
//    	return $childids;
		$where="s_locale = '{$curr_locale}' ";		
		$childids = array();		
		$par_ids=explode(',', $parentid);	
		foreach($par_ids as $parent_id){
			$procategories=ArticleCategory::listCategories($parent_id, $where);		
			$childarr=$this->fetchIdstr($procategories);
			$childids=array_merge($childids,$childarr);
		}
		$childstr=implode(',', $childids);
		if(!empty($childstr)) $childstr.=',';
		
		return $childstr;
    }
		
	private function fetchIdstr($catearr){
		$ids=array();
		foreach($catearr as $cate){
			$ids[]=$cate->id;
			if(!empty($cate->slaves['ArticleCategory'])){
					$childids=$this->fetchIdstr($cate->slaves['ArticleCategory']);
					$ids=array_merge($ids,$childids);
			}
		}
		
		return $ids;
	}
    
    private function arrUnique($str) {
    	$arrtmp = $result = array();
    	if (empty($str) || !isset($str)) {
    		return '0';
    	} else if (strrpos($str, ",") === false) {
			return $str;
    	} else {
    		$arrtmp = explode(",", $str);
    		$result = array_unique($arrtmp);
    		return join(",", $result);
    	}
    }
    
    private function getNextAndPrev($id,$article_category_id){
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	$prev = array();
    	$next = array();
    	$arr = array();
		$arr2 = array();
		$o_article = new Article();
        $articles =& $o_article->findAll(" published='1' AND article_category_id=".$article_category_id,array(),' order by i_order desc,create_time desc');
		
		foreach($articles as $article){
			$arr[$article->id] = $article->title;
		} 
//		ksort($arr);
		$count = count($arr);
		$j = 0;
		foreach($arr as $k=>$v){
			$arr2[$j]['id'] = $k;
			$arr2[$j]['title'] = $v;
			$j++;
		}		
		for ($i = 0; $i < $count; $i++){
			if($arr2[$i][id]==$id){
				if($count==1){
					$prev['str'] = __("No prev product")."<br>";
					$next['str'] = __("No next product")."<br>";
				}else{
					if($i==0){
						$prev['str'] = __("No prev article")."<br>";
						$next['id'] = $arr2[$i+1]['id'];
						$next['title'] = $arr2[$i+1]['title'];
					}elseif($i==$count-1){
						$next['str'] = __("No next article")."<br>";
						$prev['id'] = $arr2[$i-1]['id'];
						$prev['title'] = $arr2[$i-1]['title'];
					}else{
						$prev['id'] = $arr2[$i-1]['id'];
						$prev['title'] = $arr2[$i-1]['title'];
						$next['id'] = $arr2[$i+1]['id'];
						$next['title'] = $arr2[$i+1]['title'];
					}
				}
			}
		}
        $str = '<div><font style="color:#595959">'.__('Prev article').'</font>:';
        $str .= is_string($prev['str'])?$prev['str']:"<a href=".Html::uriquery2('mod_article', 'article_content', array('article_id' =>$prev['id'])).">".$prev['title']."</a><br>";
        $str .= '<font style="color:#595959">'.__('Next article').'</font>:';
        $str .= is_string($next['str'])?$next['str']:"<a href=".Html::uriquery2('mod_article', 'article_content', array('article_id' => $next['id'])).">".$next['title']."</a></div>";
        return $str;
               
    }
    
    //返回来源query string信息
    public function hash_mod($q_str){
    	$q_str = substr($q_str,strrpos($q_str,'/')+1);
    	$q_str = str_replace('.html','',$q_str);
    	$locales = trim(SessionHolder::get('_LOCALE'));
	    if(MOD_REWRITE=='2'){
	    	$i1 = 0;
			$return_uri='';
			$ur_arr = explode('-',$q_str);
		    foreach($ur_arr as $k => $v){
		    	if ($k==0) {
		    		$return_uri .=  '_m=' .$v ;
		    	}
		    	if ($k==1) {
		    		$return_uri .=  '&_a=' .$v ;
		    	}
		    	
		    }
	    }else{
	    	$return_uri = str_replace('index.php?','',$q_str);
	    }
		if(MOD_REWRITE=='3'){//自定义url处理，
			include(P_INC.'/custom.php');
	        if (strpos($q_str,'-')) {
	        	$arr = explode("-",$q_str);
	        	$m_url = $arr[1];
	        }else{//用户自定义URL
	        	$m_url = $q_str;
	        }
			$o = new MenuItem();
			$s = $o->find("`url`='".$m_url."' and s_locale='".$locales."'");
			if(!$s){//不是用户自定义链接，则分析找系统的链接
				foreach ($custom as $k=>$pa_v){
				  	if ($m_url==$k) {//系统定义的以key为查询条件,如message.html,contact.html
						$_m = $custom[$k]['m'];
						$_a = $custom[$k]['a'];
						$return_uri = '_m='.$_m.'&_a='.$_a;
					}elseif($pa_v['path'] == $arr[0] && strpos($q_str,'-')){//系统定义的，以path为查询条件,如al-0.html,pl-23.html
						$_m = $pa_v['m'];
						$_a = $pa_v['a'];
						if ($m_url==0) {//全部显示
							$return_uri = '_m='.$_m.'&_a='.$_a;
						}else{//分类显示，加上ID参数
	        				$caa_id = str_replace('_',',',$m_url);//适应伪静态
							$return_uri = '_m='.$_m.'&_a='.$_a.'&'.$pa_v['param'].'='.$caa_id;
						}
					}
				}
			}else{
				
				$return_uri = $s->link;
			}
		}
		return $return_uri;
    }
}
?>