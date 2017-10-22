<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModMenuItem extends Module {
	
	protected $_filters = array(
        'check_admin' => '{getId}'
    );
	
	public function menu_sort() {
		$tree = ParamHolder::get('tree', '');
		if (empty($tree)) {
			$this->assign('json', Toolkit::jsonERR('No input!'));
            return '_result';
		}
		
		$items = explode(",", $tree);
		$ln = count($items);
		for($i=0; $i<$ln; $i++) {
			$tokens = $mi_info = array();
			$tokens = explode("-", $items[$i]);
			$o_mi = new MenuItem($tokens[0]);
			$mi_info['menu_item_id'] = $tokens[1];
			$mi_info['i_order'] = $i + 1;
            $o_mi->set($mi_info);
            $o_mi->save();
		}
		
		$this->assign('json', Toolkit::jsonOK());
        return '_result';
	}
	
	public function menu_rename() {
		$rename_id = ParamHolder::get('renameId', 0);
		$new_name = trim(ParamHolder::get('newName', ''));
		
		if (($rename_id == 0) || empty($new_name)) {
			$this->assign('json', Toolkit::jsonERR('-1'));
            return '_result';
		}
		
		try {
			$o_mi = new MenuItem($rename_id);
			$mi_info['name'] = $new_name;
            $o_mi->set($mi_info);
            $o_mi->save();
		} catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR('-2'));
            return '_result';
        }
		
		$this->assign('json', Toolkit::jsonOK());
        return '_result';
	}
	
	
	public function menu_del() {
		$del_id = ParamHolder::get('deleteIds', 0);
		
		if ($del_id == 0) {
			$this->assign('json', Toolkit::jsonERR('-1'));
            return '_result';
		}
		$arr_del_id = explode(',',$del_id);
		if (in_array('1',$arr_del_id) && in_array('2',$arr_del_id)) {//防止删掉所有导航
			$this->assign('json', Toolkit::jsonERR('-1'));
            return '_result';
		}
		try {
			$curr_mi = new MenuItem($del_id);
			if(intval($curr_mi->id) > 0){
				if ($curr_mi->mi_category=="static") {
					// wl 11-5-5
					//delete static page id in table static_contents									
					//$sc_id_str = $curr_mi->link;
					//$sc_id = substr($sc_id_str,strrpos($sc_id_str,"=")+1);
					//$ot_staticontent = new StaticContent($sc_id);
					//$ot_staticontent->delete();
				}
				MenuItem::delete_r($curr_mi->id);
				$curr_mi->delete();
			}
		} catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR('-2'));
            return '_result';
        }
		
		$this->assign('json', Toolkit::jsonOK());
        return '_result';
	}
	
    public function admin_add() {
        $this->_layout = 'content';

        $menu_id = ParamHolder::get('menu_id', 0);
        if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}

        $mod_locale = $curr_locale;

        $this->assign('content_title', __('New Menu Item'));
        $this->assign('next_action', 'admin_create');

//        $all_mis =& MenuItem::listMenuItems(0, "menu_id=? AND s_locale=? AND menu_item_id=?",
//            array($menu_id, $mod_locale, 0));
		$all_mis = & MenuItem::listMenuItems(0, "menu_id=? AND s_locale=?",
                array($menu_id,$mod_locale));
        $select_mis = array();
        MenuItem::toSelectArray($all_mis, $select_mis,
                0, array(), array('0' => __('Top Level')));

        $this->assign('menu_id', $menu_id);
        $this->assign('select_mis', $select_mis);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        include_once(P_INC.'/menus.php');
        $menuitems = MenuItem::toSectionArray($menus);
        $this->assign('menus', $menuitems);

        //return '_form';
    }

    public function admin_create() {
        $tmp_id = trim(ParamHolder::get('tmp_id', ''));
        $mi_info =& ParamHolder::get('mi', array());
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        if (sizeof($mi_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing menu item information!')));
            return '_result';
        }
        
         if (!empty($mi_info['url']) && !preg_match('/^[a-zA-Z][a-zA-Z0-9\-]+$/',$mi_info['url'])) {
        	$this->assign('json', Toolkit::jsonERR(__('Custom address can not contain special characters')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
            include_once(P_INC.'/menus.php');
            $tmplink = '';
            $link_type_parts = explode('|', $mi_info['link_type']);
            $mi_info['mi_category'] = $link_type_parts[0];
            if($mi_info['mi_category'] == 'outer_url') {
                $tmplink = $tmp_id;
            } else if(empty($tmp_id)) {
                $tmplink = '_m='.$menus[$mi_info['mi_category']]['mod_addr']['mod_name'].'&_a='.$menus[$mi_info['mi_category']]['mod_addr']['addr'];
                // Hard code for "Company Introduction" and "Contact Us"
                $ret_sc = StaticContent::getSC($curr_locale);
				if ($ret_sc=='0') {
					$this->assign('json', Toolkit::jsonERR('No input!'));
					return '_result';
				}
                if ($mi_info['mi_category'] == 'company_info') {
					$sc_id=$ret_sc[0]['id'];
                    $tmplink .= '&'.$menus[$mi_info['mi_category']]['id_category'].'='.$sc_id;
					$mi_info['content_id'] = $sc_id;
                } else if ($mi_info['mi_category'] == 'contact_info') {
					$sc_id=$ret_sc[1]['id'];
                    $tmplink .= '&'.$menus[$mi_info['mi_category']]['id_category'].'='.$sc_id;
					$mi_info['content_id'] = $sc_id;
                } // End : Hard code
            } else {
                $tmplink = '_m='.$menus[$mi_info['mi_category']]['mod_addr']['mod_name'].'&_a='.$menus[$mi_info['mi_category']]['mod_addr']['addr'].'&'.$menus[$mi_info['mi_category']]['id_category'].'='.$tmp_id;
                $mi_info['content_id'] = $tmp_id;
            }
			$mi_info['link'] = $tmplink;
            // Re-arrange publish status
            /*
            if ($mi_info['published'] == '1') {
                $mi_info['published'] = '1';
            } else {
                $mi_info['published'] = '0';
            }
            */
            $mi_info['published'] = '1';
            $mi_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // Calculate order
            $mi_info['i_order'] =
                MenuItem::getMaxOrder($mi_info['menu_item_id']) + 1;

            // Data operation
            $o_mi = new MenuItem();
            $o_mi->set($mi_info);
            $o_mi->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_menu_item', 'admin_list', array("menu_id" => $mi_info['menu_id'])))));
        return '_result';
    }

    public function admin_edit() {
        $this->_layout = 'content';

        $mi_id = ParamHolder::get('mi_id', '0');
        if (intval($mi_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        try {
            $curr_mi = new MenuItem($mi_id);
            $this->assign('curr_mi', $curr_mi);

//            $all_mis =& MenuItem::listMenuItems(0, "menu_id=? AND s_locale=? AND menu_item_id=? ",
//                array($curr_mi->menu_id, $curr_mi->s_locale, 0));
			$all_mis = & MenuItem::listMenuItems(0, "menu_id=? AND s_locale=?",
                array($curr_mi->menu_id,$curr_mi->s_locale));
            $this->assign('menu_id', $curr_mi->menu_id);
            $select_mis = array();
            MenuItem::toSelectArray($all_mis, $select_mis,
                0, array($curr_mi->id), array('0' => __('Top Level')));

            $this->assign('select_mis', $select_mis);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }


        $this->assign('content_title', __('Edit Menu Item'));
        $this->assign('next_action', 'admin_update');

        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        include_once(P_INC.'/menus.php');
        $menuitems = MenuItem::toSectionArray($menus);
        $this->assign('menus', $menuitems);
        return '_form';
    }
    public function admin_addsub() {
        $this->_layout = 'content';

        $menu_id = ParamHolder::get('menu_id', 0);
		$mi_id = ParamHolder::get('mi_id', '0');
        if (intval($mi_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
		$curr_mi = new MenuItem($mi_id);
        $this->assign('curr_mi', $curr_mi);

        if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}

        $mod_locale = $curr_locale;

        $this->assign('content_title', __('New Menu Item'));
        $this->assign('next_action', 'admin_sub_create');

//        $all_mis =& MenuItem::listMenuItems(0, "menu_id=? AND s_locale=? AND menu_item_id=?",
//            array($menu_id, $mod_locale, 0));
		$all_mis = & MenuItem::listMenuItems(0, "menu_id=? AND s_locale=?",
                array($menu_id,$mod_locale));
        $select_mis = array();
        MenuItem::toSelectArray($all_mis, $select_mis,
                0, array(), array('0' => __('Top Level')));

        $this->assign('menu_id', $menu_id);
        $this->assign('select_mis', $select_mis);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        include_once(P_INC.'/menus.php');
        $menuitems = MenuItem::toSectionArray($menus);
        $this->assign('menus', $menuitems);
    }

	public function admin_sub_create() {
        $tmp_id = trim(ParamHolder::get('tmp_id', ''));
        $mi_info =& ParamHolder::get('mi', array());
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        if (sizeof($mi_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing menu item information!')));
            return '_result';
        }
         if (!empty($mi_info['url']) && !preg_match('/^[a-zA-Z][a-zA-Z0-9\-]+$/',$mi_info['url'])) {
        	$this->assign('json', Toolkit::jsonERR(__('Custom address can not contain special characters')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
            include_once(P_INC.'/menus.php');
            $tmplink = '';
            $link_type_parts = explode('|', $mi_info['link_type']);
            $mi_info['mi_category'] = $link_type_parts[0];
            if($mi_info['mi_category'] == 'outer_url') {
                $tmplink = $tmp_id;
            } else if(empty($tmp_id)) {
                $tmplink = '_m='.$menus[$mi_info['mi_category']]['mod_addr']['mod_name'].'&_a='.$menus[$mi_info['mi_category']]['mod_addr']['addr'];
                // Hard code for "Company Introduction" and "Contact Us"
                 $ret_sc = StaticContent::getSC($curr_locale);
				if ($ret_sc=='0') {
					$this->assign('json', Toolkit::jsonERR('No input!'));
					return '_result';
				}
                if ($mi_info['mi_category'] == 'company_info') {
					$sc_id=$ret_sc[0]['id'];
                    $tmplink .= '&'.$menus[$mi_info['mi_category']]['id_category'].'='.$sc_id;
					$mi_info['content_id'] = $sc_id;
                } else if ($mi_info['mi_category'] == 'contact_info') {
					$sc_id=$ret_sc[1]['id'];
                    $tmplink .= '&'.$menus[$mi_info['mi_category']]['id_category'].'='.$sc_id;
					$mi_info['content_id'] = $sc_id;
                } // End : Hard code
            } else {
                $tmplink = '_m='.$menus[$mi_info['mi_category']]['mod_addr']['mod_name'].'&_a='.$menus[$mi_info['mi_category']]['mod_addr']['addr'].'&'.$menus[$mi_info['mi_category']]['id_category'].'='.$tmp_id;
                $mi_info['content_id'] = $tmp_id;
            }
			$mi_info['link'] = $tmplink;
            // Re-arrange publish status
            /*
            if ($mi_info['published'] == '1') {
                $mi_info['published'] = '1';
            } else {
                $mi_info['published'] = '0';
            }
            */
            
            $mi_info['published'] = '1';
            $mi_info['published'] .= '|'.$mi_info['open_style'];
            $mi_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // Calculate order
            $mi_info['i_order'] =
                MenuItem::getMaxOrder($mi_info['menu_item_id']) + 1;

            // Data operation
            $o_mi = new MenuItem();
            $o_mi->set($mi_info);
            $o_mi->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_menu_item', 'admin_list', array("menu_id" => $mi_info['menu_id'])))));
        return '_result';
    }
    
    public function admin_update() {
    	$is_click = trim(ParamHolder::get('is_click', ''));
    	$arr_param = unserialize(ParamHolder::get('url_param', array()));
        $mi_info =& ParamHolder::get('mi', array());
		$tmp_id = trim(ParamHolder::get('tmp_id', ''));
        if (sizeof($mi_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing menu item information!')));
            return '_result';
        }
        if (!empty($mi_info['url']) && !preg_match('/^[a-zA-Z][a-zA-Z0-9\-]+$/',$mi_info['url'])) {
        	$this->assign('json', Toolkit::jsonERR(__('Custom address can not contain special characters')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
            //$link_type_parts = explode('|', $mi_info['link_type']);
            //$mi_info['mi_category'] = $link_type_parts[0];

			include_once(P_INC.'/menus.php');
            // Re-arrange publish status
            
            if ($mi_info['published'] == '1') {
                $mi_info['published'] = '1';
            } else {
                $mi_info['published'] = '0';
            }
            $mi_info['published'] .= '|'.$mi_info['open_style'];
            $mi_info['for_roles'] = ACL::explainAccess(intval($is_member_only));

            // Data operation
            $o_mi = new MenuItem($mi_info['id']);            
        	if(empty($mi_info['layout'])) {
        		$mi_info['layout'] = 'default';
        	}
        	
            $o_mi->set($mi_info);
            $o_mi->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }

        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_menu_item', 'admin_list', array("menu_id" => $mi_info['menu_id'])))));
        return '_result';
    }

    public function admin_list() {
        $this->_layout = 'mtree';

        if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}
        SessionHolder::set('mod_menu_item/_LOCALE', $curr_locale);
        $menu_id = ParamHolder::get('menu_id', 0);

        $all_menuitems =& MenuItem::listMenuItems(0, "s_locale=? and menu_id=?", array($curr_locale, $menu_id));

        $this->assign('menuitems', $all_menuitems);

        $this->assign('lang_sw', $curr_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
    }

    public function admin_delete() {

        $mi_id = trim(ParamHolder::get('mi_id', '0'));
        if (intval($mi_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
            $curr_mi = new MenuItem($mi_id);
            MenuItem::delete_r($curr_mi->id);
            $curr_mi->delete();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }

    /** useless now
 	public function subcategory() {
        include_once(P_INC.'/menus.php');
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_menu_item/_LOCALE', $curr_locale));

        $flag = ParamHolder::get('flag');
        $_menus = MenuItem::toSelectLink($menus, $flag);
        if($flag == 'outer_url') {
            $_menus['link'] ='';
        }
        $_menus['flag'] = $flag;
		//2009-10-12 update zhangjc
		if($_menus[$flag]['is_id']) {
			$_obj = new $_menus[$flag]['obj_name']();
			$sub_items = $_obj->findAll("s_locale =? and published=?",array($mod_locale, 1));
			$arr = array();
			$j=0;
			for($i=0;$i<sizeof($sub_items);$i++) {
				$arr[$j++] = $sub_items[$i]->id;
				$arr[$j++] = $sub_items[$i]->$_menus[$flag]['obj_field'];
			}
			$_menus['zhangjc'] = $arr;
		}
        $this->assign('json', Toolkit::jsonOK($_menus));
        return '_result';
    }
    */

    public function getid() {
        $this->_layout = 'content';
        $obj_name = ParamHolder::get('_c');
        $obj_field = ParamHolder::get('_field');
        $id_c = ParamHolder::get('id_c');

        if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}
        SessionHolder::set('mod_article/_LOCALE', $curr_locale);
        $_data =&
            Pager::pageByObject($obj_name, "s_locale=?", array($curr_locale),
                "ORDER BY `id` DESC");
        $this->assign('_field', trim($obj_field));
        $this->assign('id_c', $id_c);
        $this->assign('datas', $_data['data']);
        $this->assign('pager', $_data['pager']);
        $this->assign('page_mod', $_data['mod']);
		$this->assign('page_act', $_data['act']);
		$this->assign('page_extUrl', $_data['extUrl']);

    }
    
    public function admin_move() {

        $mi_id = trim(ParamHolder::get('mi_id', '0'));
        $sib_mi_id = trim(ParamHolder::get('sib_mi_id', '0'));
        if (intval($mi_id) == 0 || intval($sib_mi_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }

        try {
            $curr_mi = new MenuItem($mi_id);
            $sib_mi = new MenuItem($sib_mi_id);

            $tmp_order = $curr_mi->i_order;
            $curr_mi->i_order = $sib_mi->i_order;
            $sib_mi->i_order = $tmp_order;

            $curr_mi->save();
            $sib_mi->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_link_content_select() {
        $this->_layout = 'clean';
        $link_type = trim(ParamHolder::get('pt'));
        
        $link_type_text = trim(ParamHolder::get('txt'));
        $this->assign('type_text', $link_type_text);
        
        switch ($link_type) {
            case 'article_list':
                return $this->_load_article_categories();
                break;
            case 'product_list':
                return $this->_load_product_categories();
                break;
            case 'article':
                return $this->_load_articles();
                break;
            case 'product':
                return $this->_load_products();
                break;
            case 'static':
                return $this->_load_static_contents();
                break;
            case 'outer_url':
                return $this->_load_external_url_input();
                break;
            case 'download_list':
            	return $this->_load_download_categories();
            	break;
            default:
                $this->assign('json', Toolkit::jsonERR(__('Link Type Error!')));
                return ('_error');
        }
    }
    
    private function _load_article_categories() {
        if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}
        
        $all_categories =& ArticleCategory::listCategories(0, "s_locale=? AND published='1'", array($curr_locale));
        
        $this->assign('categories', $all_categories);
        
        // Prepare article category for select list view
        $all_categories =& ArticleCategory::listCategories(0, "s_locale=? AND published='1'", array($curr_locale));
        $select_categories = array();
        ArticleCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('0' => __('Top Level')));

        $this->assign('select_categories', $select_categories);

        $this->assign('mod_locale', $curr_locale);
        
        return '_select_article_category';
    }
    
    private function _load_product_categories() {
       if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	};
        
        $all_categories =& ProductCategory::listCategories(0, "s_locale=? AND published='1'", array($curr_locale));
        
        $this->assign('categories', $all_categories);
        
		// Prepare Product category for select list view
        $all_categories =& ProductCategory::listCategories(0, "s_locale=? AND published='1'", array($curr_locale));
        $select_categories = array();
        ProductCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('0' => __('Top Level')));

        $this->assign('select_categories', $select_categories);

        $this->assign('mod_locale', $curr_locale);
        
        return '_select_product_category';
    }
    
    private function _load_download_categories() {
       if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	};
        
        $all_categories =& DownloadCategory::listCategories(0, "s_locale=? AND published='1'", array($curr_locale));
        
        $this->assign('categories', $all_categories);
        
		// Prepare Product category for select list view
        $all_categories =& DownloadCategory::listCategories(0, "s_locale=? AND published='1'", array($curr_locale));
        $select_categories = array();
        DownloadCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('0' => __('Top Level')));

        $this->assign('select_categories', $select_categories);

        $this->assign('mod_locale', $curr_locale);
        
        return '_select_download_category';
    }
    
    private function _load_articles() {
        if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	};
        $where = "s_locale=?";
        $params = array($curr_locale);

		$where .=  " AND article_category_id <> 2 AND published='1'";//article can't see News'infomation.
        
        // 伪静态下“添加页面”模块分页用
        if (MOD_REWRITE == '2' || MOD_REWRITE == '3') {
	        $article_data =&
	            Pager::pageByObject('Article', $where, $params,
	                "ORDER BY `i_order` DESC", 'p', 'popupwin');
    	} else {
	    	$article_data =&
	            Pager::pageByObject('Article', $where, $params,
	                "ORDER BY `i_order` DESC");	
    	}

        $this->assign('articles', $article_data['data']);
        $this->assign('pager', $article_data['pager']);
        $this->assign('page_mod', $article_data['mod']);
		$this->assign('page_act', $article_data['act']);
		$this->assign('page_extUrl', $article_data['extUrl']);

        return '_select_article';
    }
    
    private function _load_products() {
        if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	};
        $where = "s_locale=? AND published='1'";
        $params = array($curr_locale);

		// 伪静态下“添加页面”模块分页用
        if (MOD_REWRITE == '2') {
        $product_data =&
            Pager::pageByObject('Product', $where, $params,
                "ORDER BY `i_order` DESC", 'p', 'popupwin');
        } else {
	        $product_data =&
	            Pager::pageByObject('Product', $where, $params,
	                "ORDER BY `i_order` DESC");	
        }

        $this->assign('products', $product_data['data']);
        $this->assign('pager', $product_data['pager']);
         $this->assign('page_mod', $product_data['mod']);
		$this->assign('page_act', $product_data['act']);
		$this->assign('page_extUrl', $product_data['extUrl']);

        return '_select_product';
    }
    
    private function _load_static_contents() {
       if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	};
        $scontent_data =& 
            Pager::pageByObject('StaticContent', "s_locale=? AND id>? AND published='0'", array($curr_locale,2), 
                "ORDER BY `create_time` DESC");
        
        $this->assign('scontents', $scontent_data['data']);
        $this->assign('pager', $scontent_data['pager']);
        $this->assign('page_mod', $scontent_data['mod']);
		$this->assign('page_act', $scontent_data['act']);
		$this->assign('page_extUrl', $scontent_data['extUrl']);

        return '_select_static_content';
    }
    
    private function _load_external_url_input() {
        return '_input_external_url';
    }
    
	public function admin_pic()
    {
    	$menu_item_info = array();
    	$mi_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($mi_id))
    	{
    		$o_mi = new MenuItem($mi_id);
            if($o_mi->published == 1)
            {
            	$menu_item_info['published'] = '0';
            	$o_mi->set($menu_item_info);
            	$o_mi->save();
				die('0');
            }
            elseif($o_mi->published == 0)
            {
            	$menu_item_info['published'] = '1';
            	$o_mi->set($menu_item_info);
            	$o_mi->save();
				die('1');
            }
    	}
    }
    
    public function admin_dashboard() {
    	$this->_layout = 'default';
    }
	
	public function add_page() {
		$this->_layout = 'content';

        $menu_id = ParamHolder::get('menu_id', 0);
        if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}

        $mod_locale = $curr_locale;
        $this->assign('content_title', __('New Menu Item'));
        $this->assign('next_action', 'admin_create');

//        $all_mis =& MenuItem::listMenuItems(0, "menu_id=? AND s_locale=? AND menu_item_id=?",
//            array($menu_id, $mod_locale, 0));
		$all_mis = & MenuItem::listMenuItems(0, "menu_id=? AND s_locale=?",
                array($menu_id,$mod_locale));
        $select_mis = array();
        MenuItem::toSelectArray($all_mis, $select_mis,
                0, array(), array('0' => __('Top Level')));

        $this->assign('menu_id', $menu_id);
        $this->assign('select_mis', $select_mis);
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        include_once(P_INC.'/menus.php');
		unset($menus['bulletins']);//去除公告
        $menuitems = MenuItem::toSectionArray($menus);
        $this->assign('menus', $menuitems);
	}
	
	public function save_page() {
		$tmp_id = trim(ParamHolder::get('tmp_id', ''));
        $mi_info =& ParamHolder::get('mi', array());
		$url = $mi_info['url'];
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
        if (sizeof($mi_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing menu item information!')));
            return '_result';
        }
        if (!empty($mi_info['url']) && !preg_match('/^[a-zA-Z][a-zA-Z0-9\-]+$/',$mi_info['url'])) {
        	$this->assign('json', Toolkit::jsonERR(__('Custom address can not contain special characters')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
        try {
            include_once(P_INC.'/menus.php');
            $tmplink = '';
            $link_type_parts = explode('|', $mi_info['link_type']);
            $mi_info['mi_category'] = $link_type_parts[0];
            if($mi_info['mi_category'] == 'outer_url') {
                $tmplink = $tmp_id;
            } else if(empty($tmp_id)) {
                $tmplink = '_m='.$menus[$mi_info['mi_category']]['mod_addr']['mod_name'].'&_a='.$menus[$mi_info['mi_category']]['mod_addr']['addr'];
                // Hard code for "Company Introduction" and "Contact Us"
				$ret_sc = StaticContent::getSC($curr_locale);
				if ($ret_sc=='0') {
					$this->assign('json', Toolkit::jsonERR('No input!'));
					return '_result';
				}
                if ($mi_info['mi_category'] == 'company_info') {
					$sc_id=$ret_sc[0]['id'];
                    $tmplink .= '&'.$menus[$mi_info['mi_category']]['id_category'].'='.$sc_id;
					$mi_info['content_id'] = $sc_id;
                } else if ($mi_info['mi_category'] == 'contact_info') {
					$sc_id=$ret_sc[1]['id'];
                    $tmplink .= '&'.$menus[$mi_info['mi_category']]['id_category'].'='.$sc_id;
					$mi_info['content_id'] = $sc_id;
                } // End : Hard code
            } else {
				if($menus[$mi_info['mi_category']]['is_id']){
                	$tmplink = '_m='.$menus[$mi_info['mi_category']]['mod_addr']['mod_name'].'&_a='.$menus[$mi_info['mi_category']]['mod_addr']['addr'].'&'.$menus[$mi_info['mi_category']]['id_category'].'='.$tmp_id;
					$mi_info['content_id'] = $tmp_id;
				}else{
					$tmplink = '_m='.$menus[$mi_info['mi_category']]['mod_addr']['mod_name'].'&_a='.$menus[$mi_info['mi_category']]['mod_addr']['addr'];
					$mi_info['content_id'] = 0;
				}
            }
			$mi_info['link'] = $tmplink;
            // Re-arrange publish status
           //2012-4-9 zhangjc 1 代表在导航中显示 0 代表不显示
            if ($mi_info['published'] == '1') {
                $mi_info['published'] = '1';
            } else {
                $mi_info['published'] = '0';
            }
            $mi_info['published'] .= '|'.$mi_info['open_style'];
            $mi_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            $mi_info['i_order'] = MenuItem::getMaxOrder($mi_info['menu_item_id']) + 1;

            if(empty($mi_info['layout'])) {
            	$mi_info['layout'] = 'default';
            }
            
            // Data operation
            $o_mi = new MenuItem();
            $res = $o_mi->find("s_locale = '{$mi_info['s_locale']}' AND link = '{$mi_info['link']}'");
            
            if(!empty($res->id)) {
            	$this->assign('json', Toolkit::jsonERR(__('This page has existed')));
            	return '_result';
            }
			
            $o_mi->set($mi_info);
            $o_mi->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
		if(MOD_REWRITE=='3'){
			if(empty($mi_info['link']))
			{
				$this->assign('json', Toolkit::jsonOK(array('forward' => '../index.html')));
			}
			else
			{
				include(P_INC.'/custom.php');
				parse_str($mi_info['link'],$output);
				foreach($custom as $k=>$v){
					if($v['m']==$output['_m'] && $v['a']==$output['_a']){
						$path=$v['path'];
						$p = $v['param'];
						$_k=$k;
						
						if($v['m']=='mod_static' && $v['a']=='view'){
							$_sc_id=$output['sc_id'];
						
							$o_scontent = new StaticContent();
							$curr_scontent =& $o_scontent->find("`id`=?", array($_sc_id));
							$flag = $curr_scontent->flag;
							$mi_category = $custom[$flag]['mi_category'];
						}else{
							$mi_category=$v['mi_category'];
						}
						break;
					}
				}
				//echo 'xxx='.$mi_category;die();
				if(isset($output[$p])){
					$param = $output[$p];
				}else{
					$param=0;
				}

				switch($mi_category)
				{
					case 'frontpage':
						if(empty($url)){
							$forward='index.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'company_info':
						if(empty($url)){
							$forward='company.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'contact_info':
						if(empty($url)){
							$forward='contact.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'static':
						if(empty($url)){
							$forward='s-'.$_sc_id.'.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'message':
						if(empty($url)){
							$forward='message.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'article':
						if(empty($url)){
							$forward='a-'.$param.'.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'product':
						if(empty($url)){
							$forward='p-'.$param.'.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'article_list':
						if(empty($url)){
							$forward='al-'.$param.'.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'product_list':
						if(empty($url)){
							$forward='pl-'.$param.'.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'download_list':
						if(empty($url)){
							$forward='dl-'.$param.'.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'download':
						if(empty($url)){
							$forward='d-'.$param.'.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					case 'link_list':
						if(empty($url)){
							$forward='friendlink.html';
						}else{
							$forward=$url.'.html';
						}
						break;
					default:
						if(empty($url)){
							$forward='index.html';
						}else{
							$forward=$url.'.html';
						}
						break;
				}
				$forward='http://'.DOMAINCONST.'/'.$forward;
				$this->assign('json', Toolkit::jsonOK(array('forward' =>$forward)));        	
			}
		}else{
			if(empty($mi_info['link'])){
				$this->assign('json', Toolkit::jsonOK(array('forward' => '../index.php')));
			}else{
				$this->assign('json', Toolkit::jsonOK(array('forward' => '../index.php?'.$mi_info['link'])));        	
			}
		}
        
        return '_result';
	}
	
	public function del_page() {
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
		$query_str = '';
        $dispage = urldecode(ParamHolder::get('query', ''));
        if (!empty($dispage)) {
        	// for rewrite
        	if (MOD_REWRITE == 2) {
        		$result = $this->rewrite($dispage);
        		$dispage = preg_replace('/'.$result['pattern'].'/i', $result['replace'], $dispage);
        	}
            $url_info = parse_url($dispage);

            if (isset($url_info['query'])) {
                $query_str = trim($url_info['query']);
            }else{
            	$query_str = $dispage;
            }
            //if (!strrpos($query_str, ".php?")) $query_str = "index.php?".$query_str;
            $query_hash = Toolkit::calcMQHash($query_str);
			
            try {
            	// delete menu_items
            	if (!empty($query_str)) {
	            	$o_mi = new MenuItem();
	            	$del_menu = $o_mi->findAll("link='".str_replace('index.php?', '', $query_str)."' and s_locale='{$curr_locale}'");
					
	            	//if (sizeof($del_menu) > 0) {
				        //foreach ($del_menu as $mi) {
							//echo $del_menu[0]->id;
							/*
				          	if($this->check_children_category($mi->id)){
				          		$o_mi->delete_r_all($del_menu);				           		
				            }else{
				            	$this->setVar('json', Toolkit::jsonERR('-3'));
        						return '_result';
				           		exit;
				            }
							*/
							$curr_mi = new MenuItem($del_menu[0]->id);
							if (intval($curr_mi->id) > 0) {
								if ($curr_mi->mi_category=="static") {
									// wl 11-5-5
									//delete static page id in table static_contents									
									/*$sc_id_str = $curr_mi->link;
									$sc_id = substr($sc_id_str,strrpos($sc_id_str,"=")+1);
									$ot_staticontent = new StaticContent($sc_id);
									$ot_staticontent->delete();*/
								}
					    		MenuItem::delete_r($curr_mi->id);
								$curr_mi->delete();
					    	}else{
				            	$this->setVar('json', Toolkit::jsonERR('-3'));
        						return '_result';
				           		exit;
				            }
							
				        // }
				     //}	            	
            	}
            	
            	// delete module_blocks
            	$o_mb = new ModuleBlock();
				$cur_module = $o_mb->findAll("s_query_hash='{$query_hash}'");
				
				if (isset($cur_module[0]->id) && $cur_module[0]->id) {
					$del_module = new ModuleBlock($cur_module[0]->id);
		        	$block_id = $del_module->id;
		        	$block_alias = $del_module->alias;
		        	$del_module->delete();
		        }
		        
		        $this->setVar('json', Toolkit::jsonOK());
		        return '_result';
			} catch (Exception $ex) {
	            $this->setVar('json', Toolkit::jsonERR('-2'));
	            return '_result';
	        }
        } else {
        	$this->setVar('json', Toolkit::jsonERR('-1'));
        	return '_result';
        }
	}
	/**
	 * 判断是否存在子栏目，如果存在，则不能删除当前栏目
	 *
	 * @param int $id
	 * @return unknown
	 */
	
	private function check_children_category($id){
		$o_mi = new MenuItem();
		$res = $o_mi->find("menu_item_id=".$id);		
		if (!empty($res)) {
			return false;
		}else {
			return true;
		}
		
	}
	private function rewrite($str) {
    	$result = array();
    	
    	if (preg_match("/([a-zA-Z_]{1,})\-([a-zA-Z_]{1,}).html$/i", $str)) {
			$result['pattern'] = "([a-zA-Z_]{1,})-([a-zA-Z_]{1,}).html$";
			$result['replace'] = "index.php?_m=\\1&_a=\\2";
		} else {
			$result['pattern'] = "([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([0-9]{1,}).html$";
			$result['replace'] = "index.php?_m=\\1&_a=\\2&\\3=\\4";
		}
		
		return $result;
    }
    
    public function check_url(){
    	$url = ParamHolder::get('url');
    	if(empty($url)){
    		echo 0;exit;
    	}
    	if (strpos($url,'-p')) {//输入的URL中与分页标识冲突
    		echo 2;exit;
    	}
    	if (!preg_match('/^[a-zA-Z][a-zA-Z0-9\-]+$/',$url)) {
    		echo 3;exit;
    	}
    	$o_men = new MenuItem();
    	$res = $o_men->find(' url=?',array($url));
    	if ($res) {
    		echo 1;
    	}else{
    		echo 0;
    	}
    	exit;
    }
}
?>
