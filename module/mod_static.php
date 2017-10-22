<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

class ModStatic extends Module {
    protected $_filters = array(
        'check_login' => '{view}{custom_html}{company_intro}{seo}'
    );
    
    public function view() {
        $this->_layout = 'frontpage';
    	include_once(P_LIB.'/pager.php');
    	$o_scontent = new StaticContent();
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	if(MOD_REWRITE=='3'){
    		include(P_INC.'/custom.php');
    		$_path=strtolower(ParamHolder::get('_path', ''));
    		//2013.12.06 处理预览 StaticContent内容
    		if($_path=='company-_v-preview'){$_path_preview='company';}
    		if($_path=='contact-_v-preview'){$_path_preview='contact';}
    		if(strpos($_path,'-_v-preview')){$_path = str_ireplace('-_v-preview','',$_path);}////存在预览 
			$o = new MenuItem();
			$s = $o->find("`url`='".$_path."' and s_locale='".$curr_locale."'");
			if(!$s){//如果用户没有自定义URL，则到StaticContent表中查询ID
				if(strpos($_path,'-')){//自定义页面
					if( $_path_preview){
						$o_sta = new StaticContent();
						$o_res = $o_sta->find(' flag=? and s_locale=?',array($_path_preview,$curr_locale));
						$sc_id=$o_res->id;
					}
					/*elseif ($_path_preview_ct!=''){
						$o_sta = new StaticContent();
						$o_res = $o_sta->find(' flag=? and s_locale=?',array($_path_preview_ct,$curr_locale));
						$sc_id=$o_res->id;						
					}*/
					else
					{
						$arr=explode("-",$_path);
						$sc_id=$arr[1];
					}
				}else{//公司简介，联系我们

					$o_sta = new StaticContent();
					if($_path_preview){
					$o_res = $o_sta->find(' flag=? and s_locale=?',array($_path_preview,$curr_locale));
					}
					else{
					$o_res = $o_sta->find(' flag=? and s_locale=?',array($_path,$curr_locale));
					}
					$sc_id=$o_res->id;
				}
			}else{

				$sc_id =  substr($s->link,strrpos($s->link,'=')+1);
			}
			
    	}else{
        	$sc_id = ParamHolder::get('sc_id', '0');
    	}
		$sc_id = intval($sc_id);
        if ($sc_id == 0) {//echo $_SERVER['REQUEST_URI'];die("mod_static");
            ParamParser::goto404();
        }
        
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        try {
            
            //wl  11-03-04
        //check table static_contents 
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $count_num = $o_scontent->count("s_locale=?",array($curr_locale),"ORDER BY `id` DESC");
        if ($count_num<"2") {
        	echo "<script>alert('".__("Page data has error,rebulid it please!")."');</script>";
        }//end
            $menu_items = new MenuItem();// for show title
            if (ACL::requireRoles(array('admin'))) {
                $curr_scontent =& $o_scontent->find("`id`=? AND s_locale=?", array($sc_id, $curr_locale));
            } else {
                $curr_scontent =& $o_scontent->find("`id`=? AND for_roles LIKE ? AND s_locale=?", 
                        array($sc_id, '%'.$user_role.'%', $curr_locale));
            }
			if (!$curr_scontent) {
				ParamParser::goto404();
				exit;
			}
 			$source_data = &Pager::pageByText( $curr_scontent->content,array('sc_id'=>$sc_id));
 			$curr_scontent->content=$source_data['data'];
            $this->assign('page_mod', $source_data['mod']);
		    $this->assign('page_act', $source_data['act']);
		  	$this->assign('page_extUrl', $source_data['extUrl']);
            $this->assign('pagetotal', $source_data['total']);
            $this->assign('pagenum', $source_data['cur_page']);
            $this->assign('sc_id', $sc_id);
            //结束
			$page_cat = isset($curr_scontent->title)?$curr_scontent->title:'';
            $this->assign('page_cat', $page_cat);
            $this->assign('curr_scontent', $curr_scontent);
        } catch (Exception $ex) {
            ParamParser::goto404();
        }
    }
    
    public function custom_html() {
    	//echo ParamHolder::get('block_id', '');
        $this->setVar('html', ParamHolder::get('html', ''));
    }
	
    
    public function company_intro() {
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	$user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
    	
    	if (preg_match('/^\d+$/i', trim(ParamHolder::get('cpy_intro_number')))) {
    		$cpy_intro_number = trim(ParamHolder::get('cpy_intro_number'));
    	} else {
    		$cpy_intro_number = 150;
    	}
    	try {
    		// get company_intro id
    		$staticontent_info = array();
    		$ot_staticontent = new StaticContent();
    		 //wl  11-03-04
	        //check table static_contents 
	        $curr_locale = trim(SessionHolder::get('_LOCALE'));
	        $count_num = $ot_staticontent->count("s_locale=?",array($curr_locale),"ORDER BY `id` DESC");
	        if ($count_num<"2") {
	        	echo "<script>alert('".__("Page data has error,rebulid it please!")."');</script>";
	        }//end
	        $staticontent_data = $ot_staticontent->findAll("s_locale=? AND published='1'", array($curr_locale), "ORDER BY `id`  LIMIT 2");
	        $company_id = $staticontent_data[1]->id;
	        
            $o_scontent = new StaticContent();
    	    if (ACL::requireRoles(array('admin'))) {
                $curr_scontent =& $o_scontent->find("`id`=? AND "
                            ."published='1' AND s_locale=?", 
                        array($company_id, $curr_locale));
            } else {
                $curr_scontent =& $o_scontent->find("`id`=? AND "
                            ."published='1' AND for_roles LIKE ? AND s_locale=?", 
                        array($company_id, '%'.$user_role.'%', $curr_locale));
            }
            $this->assign('curr_scontent', $curr_scontent);
            $this->assign('cpy_intro_number', $cpy_intro_number);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
    }
}
?>