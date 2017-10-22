<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModProduct extends Module {
    protected $_filters = array(
        'check_login' => '{newprd}{prdlist}{view}{recmndprd}'
    );
    
    private $stack = array();
    private $findout = array();
    
    public function newprd() {
        $list_size = trim(ParamHolder::get('prd_newlst_size'));
        $show_price = trim(ParamHolder::get('prd_newlst_price'),'0');
        $show_price2 = trim(ParamHolder::get('prd_newlst_price2'),'0');
        $show_cate = trim(ParamHolder::get('prd_newlst_cate'),'0');
        $show_category = trim(ParamHolder::get('prd_newlst_category'),'0');
        if (!is_numeric($list_size) || strlen($list_size) == 0) {
            $list_size = '5';
        }
        $cap_id = trim(ParamHolder::get('product_category_list', '0'));
        
        if (is_array($cap_id)){
	        foreach ($cap_id as $k=>$v){
	        	$cap_id[$k] = intval($v);
	        	
	        }
        }else{
	        if(empty($cap_id)){
				 $cap_id='0';
			}
        	$pattern = '/^[0-9][\,0-9]*$/';
        	if(!preg_match($pattern, $cap_id)){
        		die('access violation error!');;
        	}
        }
        
        $p_cols = trim(ParamHolder::get('prd_newlst_d', '1'));
        $this->assign('p_cols', $p_cols);
        $this->assign('show_price', $show_price);
        $this->assign('show_price2', $show_price2);
        $this->assign('show_cate', $show_cate);
        $this->assign('show_category', $show_category);

        $ret = $this->_getNewPrds($list_size, $cap_id);
        if ($ret) {
            return $ret;
        }

        return 'newprd';
    }

    public function recmndprd() {
        $list_size = trim(ParamHolder::get('prd_recmndlst_size'));
        $show_price = trim(ParamHolder::get('prd_recmndlst_price'));
        $show_price2 = trim(ParamHolder::get('prd_recmndlst_price2'));
        $show_cate = trim(ParamHolder::get('prd_newlst_cate'),'0');
        $show_category = trim(ParamHolder::get('prd_newlst_category'),'0');
        if (!is_numeric($list_size) || strlen($list_size) == 0) {
            $list_size = '5';
        }
        $cap_id = trim(ParamHolder::get('product_category_list', '0'));
        $p_cols = trim(ParamHolder::get('prd_recmndlst_d', '1'));
        $this->assign('p_cols', $p_cols);
        $this->assign('show_price', $show_price);
        $this->assign('show_price2', $show_price2);
         $this->assign('show_cate', $show_cate);
        $this->assign('show_category', $show_category);
				
        $ret = $this->_getRecmndPrds($list_size, $cap_id);
        if ($ret) {
            return $ret;
        }

        return 'newprd';
    }

    public function prdlist() {
    	$this->_layout = 'frontpage';
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));

        // The default product category
        $curr_product_category = new ProductCategory();
		if(MOD_REWRITE=='3'){
        	include(P_INC.'/custom.php');
        	$o_url = new MenuItem();
        	$_path=strtolower(ParamHolder::get('_path', ''));
//        	if (strrpos($_path,'p-')) {
			$exarr = explode('-',$_path);
			// xqf 是否自定义判断
        	if (!is_numeric($exarr[1])) {
					//存在分页
				//$use_str = substr($_path,0,strrpos($_path,'p')-1);
	        	$arr = explode('-',$_path);
				$use_str = $arr[0];
				$url_res = $o_url->find('url=? and s_locale=?',array($use_str,$curr_locale));
        	}else{
        		 $url_res = $o_url->find('url=? and s_locale=?',array($_path,$curr_locale));
        	}
        	//ECHO'ＰＲＩＮＴ：';PRINT_R($url_res);
        	if (!empty($url_res)) {//用户自定义的链接处理
	        	$u_link = $url_res->link;
	        	$cap_id = substr($u_link,strrpos($u_link,'=')+1);//取类别ID
	        }else{
	        	$arr = explode("-",$_path);
	        	$cap_id = intval($arr[1]);
	        	$cap_id = str_replace('_',',',$cap_id);//适应伪静态
	        }
        }else{
			$cap_id = trim(ParamHolder::get('cap_id', '0'));
		}
		$cap_id = intval($cap_id);

        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
       
		$page_title = new MenuItem();         
        $title_info = $page_title->find(" `link`=?  and  s_locale=?",array("_m=mod_product&_a=prdlist",$curr_locale)," limit 1"); 
//        $curr_product_category->name = $title_info->name;
        $search_where = '';
        $search_params = array();
        $prd_keyword = trim(ParamHolder::get('prd_keyword', '',PS_POST))?Toolkit::baseEncode(trim(ParamHolder::get('prd_keyword', '',PS_POST))):trim(ParamHolder::get('prd_keyword', '',PS_GET));  
        $prd_keyword = Toolkit::baseDecode($prd_keyword);
		$prd_keyword=htmlspecialchars($prd_keyword);
		$prd_keyword = str_replace(array('<','>','"',"'"),array('','','',''),$prd_keyword);
        if (strlen($prd_keyword) > 0) {
            $search_where .= ' AND (name LIKE ? OR description LIKE ?)';
            $search_params = array_merge($search_params,
            	array('%'.$prd_keyword.'%', '%'.$prd_keyword.'%'));
            $this->assign('prd_keyword', $prd_keyword);
        }// 02/06/2010 Edit >>
        else if (intval($cap_id) > 1) {
        //if (intval($cap_id) > 1) {
        // 02/06/2010 Edit <<
        	$product_category = new ProductCategory();
    		$product_categories = $product_category->findAll();
    		if(empty($product_categories)) $product_categories = array();
    		foreach($product_categories as $k => $v)
    		{
    			$this->stack[$v->id] = $v->product_category_id;
    		}
    		$this->findout[] = $cap_id;
    		$this->getCategoryList();
    		$search_where = " AND product_category_id IN (''";
    		foreach($this->findout as $k => $v)
    		{
    			$search_where .= ",$v";
    		}
    		$search_where .= ') AND product_category_id <> 0';
            $curr_product_category = new ProductCategory($cap_id);
        }

        try {
            $now = time();
            include_once(P_LIB.'/pager.php');
            
            if (ACL::requireRoles(array('admin'))) {
	            $product_data =&Pager::pageByObject('Product',
	                    "((`pub_start_time`<? AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
	                        ."published='1' AND s_locale=?".$search_where,
	                    array_merge(
	                    	array($now, $now, $now, $now, $curr_locale),
	                    	$search_params),
	                    "ORDER BY `i_order` DESC, `create_time` DESC");
            } else {
	            $product_data =&Pager::pageByObject('Product',"((`pub_start_time`<? AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
	                        ."published='1' AND for_roles LIKE ? AND s_locale=?".$search_where,
	                    array_merge(
	                    	array($now, $now, $now, $now, '%'.$user_role.'%', $curr_locale),
	                    	$search_params),
	                    "ORDER BY `i_order` DESC, `create_time` DESC");
            }
			$curr_product_category_name='';
			if(isset($curr_product_category->name)){
				$curr_product_category_name=$curr_product_category->name;
			}else{
                $curr_product_category_name= $title_info->name;
            }
            $this->assign('page_title', $curr_product_category_name);

            $this->assign('category', $curr_product_category);
            $this->assign('products', $product_data['data']);
            $this->assign('pager', $product_data['pager']);
            $this->assign('page_mod', $product_data['mod']);
			$this->assign('page_act', $product_data['act']);
			$this->assign('page_extUrl', $product_data['extUrl']);
            $this->assign('prd_keyword', $prd_keyword);
            $this->assign('cap_id',$cap_id);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
    }

    /** temporarily disabled **/
    /*
    public function newshow() {
        $list_size = trim(ParamHolder::get('prd_newshow_size', '3'));
        $cap_id = trim(ParamHolder::get('prd_newshow_cap_id', '0'));

        return $this->_getNewPrds($list_size, $cap_id);
    }

    public function recmndshow() {
        $list_size = trim(ParamHolder::get('prd_recmndshow_size', '3'));
        $cap_id = trim(ParamHolder::get('prd_recmndshow_cap_id', '0'));

        return $this->_getRecmndPrds($list_size, $cap_id);
    }
    */

    public function view() {
    	$this->_layout = 'frontpage';
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));
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
    		$arr = explode("-",$_path);
    		$o = new MenuItem();
			$s = $o->find("`url`='".$m_url."' and s_locale='".$curr_locale."'");
			if(!$s){
				$p_id = intval($arr[1]);
			}else{
				$p_id = intval($s->content_id);
			}
			if (strpos($_SERVER[HTTP_REFERER],'admin') ) {
				if (empty($p_id)) {
					$p_id = ParamHolder::get('p_id');
				}
			}
    		
    	}else{
        	$p_id = ParamHolder::get('p_id', '0');
    	}
        if ((intval($p_id) == 0) || !preg_match("/^\d+$/i", $p_id)) {
            ParamParser::goto404();
        }
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        try {
            $now = time();
            $o_product = new Product();
            if (ACL::requireRoles(array('admin'))) {
	            $curr_product =& $o_product->find("`id`=? AND "
	                        ."((`pub_start_time`<? AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
	                        ."published='1'",
	                    array($p_id, $now, $now, $now, $now));
            } else {
	            $curr_product =& $o_product->find("`id`=? AND "
	                        ."((`pub_start_time`<? AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
	                        ."published='1' AND for_roles LIKE ?",
	                    array($p_id, $now, $now, $now, $now, '%'.$user_role.'%'));
            }
			if(!$curr_product){
				ParamParser::goto404();
			}
            $curr_product->loadRelatedObjects(REL_PARENT, array('ProductCategory'));
            
             $page_title = new MenuItem();
             
            if ($page_title->count(" `link`=?  and  s_locale=?",array("_m=mod_product&_a=view&p_id={$p_id}",$curr_locale))) {
         		$title_info = $page_title->find(" `link`=?  and  s_locale=?",array("_m=mod_product&_a=view&p_id={$p_id}",$curr_locale)," limit 1 "); 
         		$this->assign('page_title', $title_info->name);
            }else{
            	$this->assign('page_title', $curr_product->name);
            }
            $product_category_id = $curr_product->product_category_id;
            $nextAndPrevArr = $this->getNextAndPrev($p_id,$product_category_id);
            include_once(P_LIB.'/pager.php');
            $content=$curr_product->description;
            $description=&Pager::pageByText( $content,array('p_id'=>$p_id));
            $curr_product->description=$description['data'];
            $this->assign('page_mod', $description['mod']);
		    $this->assign('page_act', $description['act']);
		    $this->assign('page_extUrl', $description['extUrl']);
            $this->assign('pagetotal', $description['total']);
            $this->assign('pagenum', $description['cur_page']);
            $this->assign('curr_product', $curr_product);
            $this->assign('nextAndPrevArr', $nextAndPrevArr);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
    }


    private function _getNewPrds($list_size, $cap_id) {
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        $curr_locale = trim(SessionHolder::get('_LOCALE'));

        $where = '';
        $params = array();
        if (strlen($cap_id) > 0) {
        	// 02/06/2010 Edit >>
        	$childids = $this->getCategoryChildIds($cap_id, $curr_locale);
        	$cap_id = !empty($childids) ? $childids.$cap_id : $cap_id;
        	$cap_id = $this->arrUnique($cap_id);
        	//$where = " AND product_category_id=?";
        	$where = " AND product_category_id IN(".$cap_id.")";
        	//$params = array($cap_id);
        	$params = array();
        	// 02/06/2010 Edit <<
        }
        try {
            $o_product = new Product();
            $now = time();
            if (ACL::requireRoles(array('admin'))) {
	            $products =&
	                $o_product->findAll("((`pub_start_time`<? AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
	                        ."published='1' AND s_locale=?".$where,
	                    array_merge(
	                    	array($now, $now, $now, $now, $curr_locale),
	                    	$params),
	                    "ORDER BY `i_order` DESC,`create_time` DESC LIMIT ".$list_size);
            } else {
	            $products =&
	                $o_product->findAll("((`pub_start_time`<? AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
	                        ."published='1' AND for_roles LIKE ? AND s_locale=?".$where,
	                    array_merge(
	                    	array($now, $now, $now, $now, '%'.$user_role.'%', $curr_locale),
	                    	$params),
	                    "ORDER BY `i_order` DESC,`create_time` DESC LIMIT ".$list_size);
            }
            $this->assign('products', $products);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

        return false;
    }

    private function _getRecmndPrds($list_size, $cap_id) {
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        $curr_locale = trim(SessionHolder::get('_LOCALE'));

        $where = '';
        $params = array();
        if (strlen($cap_id) > 0) {
            // 02/06/2010 Edit >>
        	$childids = $this->getCategoryChildIds($cap_id, $curr_locale);
        	$cap_id = !empty($childids) ? $childids.$cap_id : $cap_id;
        	$cap_id = $this->arrUnique($cap_id);
        	//$where = " AND product_category_id=?";
        	$where = " AND product_category_id IN(".$cap_id.")";
        	//$params = array($cap_id);
        	$params = array();
        	// 02/06/2010 Edit <<
        }
        try {
            $o_product = new Product();
            $now = time();
            if (ACL::requireRoles(array('admin'))) {
	            $products =&
	                $o_product->findAll("((`pub_start_time`<? AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
	                        ."published='1' AND s_locale=? AND "
	                        ."recommended='1'".$where,
	                    array_merge(
	                    	array($now, $now, $now, $now, $curr_locale),
	                    	$params),
	                    "ORDER BY `i_order` DESC,`create_time` DESC LIMIT ".$list_size);
            } else {
	            $products =&
	                $o_product->findAll("((`pub_start_time`<? AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
	                        ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
	                        ."published='1' AND for_roles LIKE ? AND s_locale=? AND "
	                        ."recommended='1'".$where,
	                    array_merge(
	                    	array($now, $now, $now, $now, '%'.$user_role.'%', $curr_locale),
	                    	$params),
	                    "ORDER BY `i_order` DESC,`create_time` DESC LIMIT ".$list_size);
            }
            $this->assign('products', $products);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

        return false;
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
	$where="s_locale = '{$curr_locale}' ";		
	$childids = array();		
	$par_ids=explode(',', $parentid);	
	foreach($par_ids as $parent_id){
		$procategories=ProductCategory::listCategories($parent_id, $where);		
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
			if(!empty($cate->slaves['ProductCategory'])){
					$childids=$this->fetchIdstr($cate->slaves['ProductCategory']);
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
    private function getNextAndPrev($id,$product_category_id){
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	$prev = array();
    	$next = array();
    	$arr = array();
		$arr2 = array();
		$o_product = new Product();
       
        $prods =& $o_product->findAll(" published='1' AND product_category_id=".$product_category_id,array(),' order by i_order desc,create_time desc');
		
		foreach($prods as $prod){
			$arr[$prod->id] = $prod->name;
		} 
//		ksort($arr);
		$count = count($arr);
		$j = 0;
		foreach($arr as $k=>$v){
			$arr2[$j]['id'] = $k;
			$arr2[$j]['name'] = $v;
			$j++;
		}		
		for ($i = 0; $i < $count; $i++){
			if($arr2[$i]['id']==$id){
				if($count==1){
					$prev['str'] = __("No prev product")."<br>";
					$next['str'] = __("No next product")."<br>";
				}else{
					if($i==0){
						$prev['str'] = __("No prev product")."<br>";
						$next['id'] = $arr2[$i+1]['id'];
						$next['name'] = $arr2[$i+1]['name'];
					}elseif($i==$count-1){
						$next['str'] = __("No next product")."<br>";
						$prev['id'] = $arr2[$i-1]['id'];
						$prev['name'] = $arr2[$i-1]['name'];
					}else{
						$prev['id'] = $arr2[$i-1]['id'];
						$prev['name'] = $arr2[$i-1]['name'];
						$next['id'] = $arr2[$i+1]['id'];
						$next['name'] = $arr2[$i+1]['name'];
					}
				}
			}
		}
        $str = '<div><font style="color:#595959">'.__('Prev product').'</font>：';
        $str .= is_string($prev['str'])?$prev['str']:"<a href=".Html::uriquery2("mod_product","view",array('p_id' =>$prev['id'])).">".$prev['name']."</a><br>";
        $str .= '<font style="color:#595959">'.__('Next product').'</font>：';
        $str .= is_string($next['str'])?$next['str']:"<a href=".Html::uriquery2("mod_product","view",array('p_id' =>$next['id'])).">".$next['name']."</a></div>";
        return $str;
    }

}
?>