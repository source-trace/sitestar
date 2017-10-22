<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
error_reporting(0);
ini_set('memory_limit', '128M');
class ModLang extends Module {
    
	protected $_filters = array(
        'check_admin' => '{admin_make_default}'
    );
	
    public function admin_list() {
        $this->_layout = 'content';
        
        $o_lang = new Language();
        $langs =& $o_lang->findAll();

        $this->assign('langs', $langs);
    }
    
    public function admin_add() {
        $this->_layout = 'content';
		 $o_lang = new Language();
        $langs =& $o_lang->findAll();        
        $this->assign('langs', $langs);
    }
	
	public function modify() {
        $this->_layout = 'content';
		$filename =& ParamHolder::get('_f', '');  
		$this->assign('filename', $filename);
    }
	
	public function modifya() {
        $this->_layout = 'content';
		$filename =& ParamHolder::get('_f', '');  
		$this->assign('filename', $filename);
    }
	
	public function file_save() {
        $this->_layout = 'content';
		$filename =& ParamHolder::get('filename', '');  
		$filecontent =& ParamHolder::get('filecontent', '');  
		
		
		try {
			$filename = "../locale/".$filename."/lang.php";
			$file = fopen($filename, "w");      //以写模式打开文件
			fwrite($file, $filecontent);      //写入
			fclose($file);        
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
			
		$this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_lang', 'admin_list'))));
        return '_result';
    }
	public function file_savea() {
        $this->_layout = 'content';
		$filename =& ParamHolder::get('filename', '');  
		$filecontent =& ParamHolder::get('filecontent', '');  
		
		
		try {
			$filename = "locale/".$filename."/lang.php";
			$file = fopen($filename, "w");      //以写模式打开文件
			fwrite($file, $filecontent);      //写入
			fclose($file);        
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
			
		$this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_lang', 'admin_list'))));
        return '_result';
    }
    
	
    public function admin_create() {
        $locale_info =& ParamHolder::get('lang', array());  
		
        try {
        	$tmparr = array();
            $o_lang = new Language();
            // existed or not
            $tmparr = $o_lang->findAll('locale=?', array($locale_info['locale']));
        	if (count($tmparr) > 0) {
        		$this->assign('json', Toolkit::jsonERR(__('Language has existed!')));
            	return '_result';
            //} else if (!$this->make_locale($locale_info['locale'],$locale_info['copy'])) {
            } else if (!$this->make_locale($locale_info['locale'],DEFAULT_LOCALE)) {
	        	$this->assign('json', Toolkit::jsonERR(__('Add language failed!')));
	            return '_result';
        	} else {
        		$locale_info['published'] = '0';
	            $o_lang->set($locale_info);
	            $o_lang->save();
        	}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_lang', 'admin_list'))));
        return '_result';
    }
    
    public function admin_delete() {
        
        $curr_lang_id = trim(ParamHolder::get('l_id', '0'));
        if (intval($curr_lang_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
            $curr_lang = new Language($curr_lang_id);
            $md_lang_locale = $curr_lang->locale;
            $where = 's_locale=?';
	        $params = array($md_lang_locale);
            if ($md_lang_locale == SessionHolder::get('_LOCALE')) {
                $this->assign('json', Toolkit::jsonERR(__('Cannot delete default language!')));
                return '_result';
            } else {
            	if (!isset($curr_lang->locale) || empty($curr_lang->locale)) {
            		$this->assign('json', Toolkit::jsonERR(__('Request failed!')));
	                return '_result';
            	}
            	if (!Toolkit::rmdir_locale($curr_lang->locale) || !Toolkit::rmdir_locale($curr_lang->locale, 'front')) {
	                $this->assign('json', Toolkit::jsonERR(__('Deleting language failed!')));
	                return '_result';
            	}
                if ($curr_lang->delete()) {
                	// delete article categories
                	$del_article_category = new ArticleCategory();
                	$del_article_category_data = $del_article_category->findAll($where, $params);
                	foreach($del_article_category_data as $del_article_category) {
                		$od_article_category = new ArticleCategory($del_article_category->id);
                		$od_article_category->delete();
                	}
                	// delete articles
                	$del_article = new Article();
                	$del_article_data = $del_article->findAll($where, $params);
                	foreach($del_article_data as $del_article) {
                		$od_article = new Article($del_article->id);
                		$od_article->delete();
                	}
                	// delete product categories
                	$del_product_category = new ProductCategory();
                	$del_product_category_data = $del_product_category->findAll($where, $params);
                	foreach($del_product_category_data as $del_product_category) {
                		$od_product_category = new ProductCategory($del_product_category->id);
                		$od_product_category->delete();
                	}
                	// delete products
                	$del_product = new Product();
                	$del_product_data = $del_product->findAll($where, $params);
                	foreach($del_product_data as $del_product) {
                		$od_product = new Product($del_product->id);
                		$od_product->delete();
                	}
                	// delete bulletins
        			$del_bulletin = new Bulletin();
        			$del_bulletin_data = $del_bulletin->findAll($where, $params);
                	foreach($del_bulletin_data as $del_bulletin) {
                		$od_bulletin = new Bulletin($del_bulletin->id);
                		$od_bulletin->delete();
                	}
                	// delete friendlinks
        			$del_friendlink = new Friendlink();
        			$del_friendlink_data = $del_friendlink->findAll($where, $params);
                	foreach($del_friendlink_data as $del_friendlink) {
                		$od_friendlink = new Friendlink($del_friendlink->id);
                		$od_friendlink->delete();
                	}
                	// delete menu_items	
	    			$del_menuitem = new MenuItem();
	    			$del_menuitem_data = $del_menuitem->findAll($where, $params);
                	foreach($del_menuitem_data as $del_menuitem) {
                		$od_menuitem = new MenuItem($del_menuitem->id);
                		$od_menuitem->delete();
                	}
                	// delete module_blocks
		        	$del_moduleblock = new ModuleBlock();
					$del_moduleblock_data = $del_moduleblock->findAll($where, $params);
                	foreach($del_moduleblock_data as $del_moduleblock) {
                		$od_moduleblock = new ModuleBlock($del_moduleblock->id);
                		$od_moduleblock->delete();
                	}
					// delete site_infos
		        	$del_siteinfo = new SiteInfo();
		        	$del_siteinfo_data = $del_siteinfo->findAll($where, $params);
                	foreach($del_siteinfo_data as $del_siteinfo) {
                		$od_siteinfo = new SiteInfo($del_siteinfo->id);
                		$od_siteinfo->delete();
                	}
                	// delete static_contents
        			$del_staticontent = new StaticContent();
        			$del_staticontent_data = $del_staticontent->findAll($where, $params);
                	foreach($del_staticontent_data as $del_staticontent) {
                		$od_staticontent = new StaticContent($del_staticontent->id);
                		$od_staticontent->delete();
                	}
                }
            }
            // 2011/03/03 重置临时存储语言SESSION
            SessionHolder::set('SS_LOCALE', '');
            SessionHolder::set('mod_site/_LOCALE', '');
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_copydata() {
        $curr_lang_id = trim(ParamHolder::get('l_id', '0'));
        if (intval($curr_lang_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {//
        	// current site language
        	//$curr_locale = trim(SessionHolder::get('_LOCALE'));
			$curr_locale = trim(DEFAULT_LOCALE);
	        $mod_locale = trim(SessionHolder::get('mod_lang/_LOCALE', $curr_locale));
	       // $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
			 $lang_sw =$curr_locale;
	        SessionHolder::set('mod_lang/_LOCALE', $lang_sw);
        	// goto language
        	$curr_lang = new Language($curr_lang_id);
        	$cur_local = $curr_lang->locale;
        	
        	$where = 's_locale=?';
	        $params = array($lang_sw);
        	
        	// if existed then delete
        	$params2 = array($cur_local);
        	$tag_del = trim(ParamHolder::get('t_del', 'no'));
        	if ($tag_del == 'yes') {
        		// delete article categories
            	$del_article_category = new ArticleCategory();
            	$del_article_category_data = $del_article_category->findAll($where, $params2);
            	foreach($del_article_category_data as $del_article_category) {
            		$od_article_category = new ArticleCategory($del_article_category->id);
            		$od_article_category->delete();
            	}
            	// delete articles
            	$del_article = new Article();
            	$del_article_data = $del_article->findAll($where, $params2);
            	foreach($del_article_data as $del_article) {
            		$od_article = new Article($del_article->id);
            		$od_article->delete();
            	}
            	// delete product categories
            	$del_product_category = new ProductCategory();
            	$del_product_category_data = $del_product_category->findAll($where, $params2);
            	foreach($del_product_category_data as $del_product_category) {
            		$od_product_category = new ProductCategory($del_product_category->id);
            		$od_product_category->delete();
            	}
            	// delete products
            	$del_product = new Product();
            	$del_product_data = $del_product->findAll($where, $params2);
            	foreach($del_product_data as $del_product) {
            		$od_product = new Product($del_product->id);
            		$od_product->delete();
            	}
            	// delete bulletins
    			$del_bulletin = new Bulletin();
    			$del_bulletin_data = $del_bulletin->findAll($where, $params2);
            	foreach($del_bulletin_data as $del_bulletin) {
            		$od_bulletin = new Bulletin($del_bulletin->id);
            		$od_bulletin->delete();
            	}
            	// delete friendlinks
    			$del_friendlink = new Friendlink();
    			$del_friendlink_data = $del_friendlink->findAll($where, $params2);
            	foreach($del_friendlink_data as $del_friendlink) {
            		$od_friendlink = new Friendlink($del_friendlink->id);
            		$od_friendlink->delete();
            	}
            	// delete menu_items	
    			$del_menuitem = new MenuItem();
    			$del_menuitem_data = $del_menuitem->findAll($where, $params2);
            	foreach($del_menuitem_data as $del_menuitem) {
            		$od_menuitem = new MenuItem($del_menuitem->id);
            		$od_menuitem->delete();
            	}
            	// delete module_blocks
	        	$del_moduleblock = new ModuleBlock();
				$del_moduleblock_data = $del_moduleblock->findAll($where, $params2);
            	foreach($del_moduleblock_data as $del_moduleblock) {
            		$od_moduleblock = new ModuleBlock($del_moduleblock->id);
            		$od_moduleblock->delete();
            	}
				// delete site_infos
	        	$del_siteinfo = new SiteInfo();
	        	$del_siteinfo_data = $del_siteinfo->findAll($where, $params2);
            	foreach($del_siteinfo_data as $del_siteinfo) {
            		$od_siteinfo = new SiteInfo($del_siteinfo->id);
            		$od_siteinfo->delete();
            	}
            	// delete static_contents
    			$del_staticontent = new StaticContent();
    			$del_staticontent_data = $del_staticontent->findAll($where, $params2);
            	foreach($del_staticontent_data as $del_staticontent) {
            		$od_staticontent = new StaticContent($del_staticontent->id);
            		$od_staticontent->delete();
            	}
				
				// delete online_qqs
            	$del_online_qqs = new OnlineQq();
            	$del_online_qqs_data = $del_online_qqs->findAll($where, $params2);
            	foreach($del_online_qqs_data as $del_online_qq) {
            		$od_online_qqs = new OnlineQq($del_online_qq->id);
            		$od_online_qqs->delete();
            	}
        	}
	        
	        // Copy zh-CN -> zh_TW
	        $exchange = false;
	        if (($cur_local == 'zh_TW') && ($lang_sw == 'zh_CN')) {
	        	$exchange = true;
	        	ini_set('memory_limit', '32M');
	        	$zh2TW = $source = array();
	        	include_once(ROOT.'/data/ZhConversion.php');
				$source = array_keys($zh2TW);
	        }
	        
        	// articles category
        	$ot_article = new Article();
	        $ot_article_category = new ArticleCategory();
	        $article_info = $article_category_info = $article_category_ids = array();
	        $uparticle_category_info = $uparticle_info = array();
	        $article_category_data = $ot_article_category->findAll($where, $params);
        	foreach($article_category_data as $article_category) {
        		// copy article category
    			$o_article_category = new ArticleCategory();
        		$article_category_info = array_slice(get_object_vars($article_category), 7);
        		if ($exchange) {
        			$article_category_info['name'] = $this->gb2big5($source, $zh2TW, $article_category_info['name']);
        		}
        		$article_category_info['alias'] = 'caa_'.Toolkit::randomStr(8);
        		$article_category_info['s_locale'] = $cur_local;
        		$o_article_category->set($article_category_info);
        		$article_category_id = $o_article_category->save('get_insert_id');
				$article_category_ids[$article_category->id] = $article_category_id;
				// copy article
            	if ($article_category_id) {
            		$article_data = $ot_article->findAll($where." and article_category_id='{$article_category->id}'", 
        				$params);
        			foreach($article_data as $article) {
        				$o_article = new Article();
        				$article_info = array_slice(get_object_vars($article), 6);
        				if ($exchange) {
		        			$article_info['author'] = $this->gb2big5($source, $zh2TW, $article_info['author']);
		        			$article_info['title'] = $this->gb2big5($source, $zh2TW, $article_info['title']);
		        			$article_info['tags'] = $this->gb2big5($source, $zh2TW, $article_info['tags']);
		        			$article_info['intro'] = $this->gb2big5($source, $zh2TW, $article_info['intro']);
		        			$article_info['content'] = $this->gb2big5($source, $zh2TW, $article_info['content']);
		        		}
        				$article_info['s_locale'] = $cur_local;
        				$o_article->set($article_info);
        				$o_article->save();
        			}
            	}
        	}
        	// update articles category
        	$article_category_updata = $ot_article_category->findAll($where, array($cur_local));
	        foreach($article_category_updata as $uparticle_category) {
	        	if ($uparticle_category->article_category_id && $uparticle_category->article_category_id>0) {
	        		$op_article_category = new ArticleCategory($uparticle_category->id);
		        	$uparticle_category_info['article_category_id'] = intval($article_category_ids[$uparticle_category->article_category_id]);
		            $op_article_category->set($uparticle_category_info);
		            $op_article_category->save();
	        	} else continue;
	        }
	        // update articles
	        $article_updata = $ot_article->findAll($where, array($cur_local));
	        foreach($article_updata as $uparticle) {
	        	if ($uparticle->article_category_id) {
	        		$op_article = new Article($uparticle->id);
		        	$uparticle_info['article_category_id'] = intval($article_category_ids[$uparticle->article_category_id]);
		            $op_article->set($uparticle_info);
		            $op_article->save();
	        	} else continue;
	        }
        	
        	// products category
        	$ot_product = new Product();
	        $ot_product_category = new ProductCategory();
			$product_info = $product_category_info = $product_category_ids = $product_list_ids = array();
	        $upproduct_category_info = $upproduct_info = array();
	        $product_category_data = $ot_product_category->findAll($where, $params);
			
        	foreach($product_category_data as $product_category) {
        		// copy product category
    			$o_product_category = new ProductCategory();
        		$product_category_info = array_slice(get_object_vars($product_category), 7);
        		if ($exchange) {
        			$product_category_info['name'] = $this->gb2big5($source, $zh2TW, $product_category_info['name']);
        		}
        		$product_category_info['alias'] = 'cap_'.Toolkit::randomStr(8);
        		$product_category_info['s_locale'] = $cur_local;
        		$o_product_category->set($product_category_info);
        		$product_category_id = $o_product_category->save('get_insert_id');
				$product_category_ids[$product_category->id] = $product_category_id;
				// copy product
            	if ($product_category_id) {
            		$product_data = $ot_product->findAll($where." and product_category_id='{$product_category->id}'", 
        				$params);
        			foreach($product_data as $product) {
        				$o_product = new Product();
        				$product_info = array_slice(get_object_vars($product), 6);
        				if ($exchange) {
		        			$product_info['name'] = $this->gb2big5($source, $zh2TW, $product_info['name']);
		        			$product_info['introduction'] = $this->gb2big5($source, $zh2TW, $product_info['introduction']);
		        			$product_info['description'] = $this->gb2big5($source, $zh2TW, $product_info['description']);
		        		}
        				$product_info['s_locale'] = $cur_local;
        				$o_product->set($product_info);
        				$product_list_id =$o_product->save('get_insert_id');	
						$product_list_ids[$product->id] = $product_list_id;						
        			}
            	}
        	}
        	// update products category
        	$product_category_updata = $ot_product_category->findAll($where, array($cur_local));
	        foreach($product_category_updata as $upproduct_category) {
	        	if ($upproduct_category->product_category_id) {
	        		$op_product_category = new ProductCategory($upproduct_category->id);
		        	$upproduct_category_info['product_category_id'] = intval($product_category_ids[$upproduct_category->product_category_id]);
		            $op_product_category->set($upproduct_category_info);
		            $op_product_category->save();
	        	} else continue;
	        }
	        // update products
	        $product_updata = $ot_product->findAll($where, array($cur_local));
	        foreach($product_updata as $upproduct) {
	        	if ($upproduct->product_category_id) {
	        		$op_product = new Product($upproduct->id);
		        	$upproduct_info['product_category_id'] = intval($product_category_ids[$upproduct->product_category_id]);
		            $op_product->set($upproduct_info);
		            $op_product->save();
	        	} else continue;
	        }
        	
        	// bulletins
        	$ot_bulletin = new Bulletin();
        	$bulletin_info = array();
	        $bulletin_data = $ot_bulletin->findAll($where, $params);
        	foreach($bulletin_data as $bulletin) {
        		$o_bulletin = new Bulletin();
				$bulletin_info = array_slice(get_object_vars($bulletin), 6);
				if ($exchange) {
        			$bulletin_info['title'] = $this->gb2big5($source, $zh2TW, $bulletin_info['title']);
        			$bulletin_info['content'] = $this->gb2big5($source, $zh2TW, $bulletin_info['content']);
        		}
				$bulletin_info['s_locale'] = $cur_local;
				$bulletin_info['create_time'] = $_SERVER['REQUEST_TIME'];
				$o_bulletin->set($bulletin_info);
				$o_bulletin->save();
        	}
			
			 	// bulletins
        	$ot_OnlineQq = new OnlineQq();
        	$OnlineQq_info = array();
	        $OnlineQq_data = $ot_OnlineQq->findAll($where, $params);
        	foreach($OnlineQq_data as $OnlineQq) {
        		$o_OnlineQq = new OnlineQq();
				$OnlineQq_info = array_slice(get_object_vars($OnlineQq), 6);
				$OnlineQq_info['s_locale'] = $cur_local;			
				$o_OnlineQq->set($OnlineQq_info);
				$o_OnlineQq->save();
        	}
			
			if(QQ_ONLINE_TITLE){
				$QQ_ONLINE_TITLE_temp=unserialize(QQ_ONLINE_TITLE);
			}else{
				$QQ_ONLINE_TITLE_temp=array();
			}
				
			$o_param = new Parameter();				
			$param =& $o_param->find('`key`=?', array('QQ_ONLINE_TITLE'));					 
			if ($param) {							
				$arrtemp=array();$arrtemp=$QQ_ONLINE_TITLE_temp;$arrtemp[$cur_local]=$arrtemp[$lang_sw];$val=serialize($arrtemp); 
				$param->val = $val;
				$param->save();
			}

			// friendlinks
        	$ot_friendlink = new Friendlink();
        	$friendlink_info = array();
	        $friendlink_data = $ot_friendlink->findAll($where, $params);
        	foreach($friendlink_data as $friendlink) {
        		$o_friendlink = new Friendlink();
				$friendlink_info = array_slice(get_object_vars($friendlink), 6);
				if ($exchange) {
        			$friendlink_info['fl_name'] = $this->gb2big5($source, $zh2TW, $friendlink_info['fl_name']);
        		}
				$friendlink_info['s_locale'] = $cur_local;
				$friendlink_info['create_time'] = $_SERVER['REQUEST_TIME'];
				if(strpos($friendlink_info['fl_addr'],'http://')===false){
					$friendlink_info['fl_addr']='http://';
				}
				$o_friendlink->set($friendlink_info);
				$o_friendlink->save();
        	}

			// menu_items	
	    	$ot_menuitem = new MenuItem();
	    	$menuitem_info = $upmenuitem_info = $menu_item_ids = array();
	        $menuitem_data = $ot_menuitem->findAll($where.' and menu_id=0', $params);
	        foreach($menuitem_data as $menuitem) {
	        	// copy menu items
	        	$o_menuitem = new MenuItem();
	        	$menuitem_info = array_slice(get_object_vars($menuitem), 6);
	        	if ($exchange) {
        			$menuitem_info['name'] = $this->gb2big5($source, $zh2TW, $menuitem_info['name']);
        			$menuitem_info['link_type'] = $this->gb2big5($source, $zh2TW, $menuitem_info['link_type']);
        			$menuitem_info['selected_content'] = $this->gb2big5($source, $zh2TW, $menuitem_info['selected_content']);
        			$menuitem_info['meta_key'] = $this->gb2big5($source, $zh2TW, $menuitem_info['meta_key']);
        			$menuitem_info['meta_desc'] = $this->gb2big5($source, $zh2TW, $menuitem_info['meta_desc']);
        		}
	        	$menuitem_info['s_locale'] = $cur_local;
	        	$o_menuitem->set($menuitem_info);
	        	$menu_item_ids[$menuitem->id] = $o_menuitem->save('get_insert_id');
	        }
	        // update menu_item_id
			$ot_menuitem = new MenuItem();
	        $menuitem_updata = $ot_menuitem->findAll($where.' and menu_id=0', array($cur_local));
	        foreach($menuitem_updata as $upmenuitem) {
					$upmenuitem_info =  array();
					$mi_category='';
					
	        		$op_menuitem = new MenuItem($upmenuitem->id);
					if ($upmenuitem->menu_item_id && ($menu_item_id = intval($menu_item_ids[$upmenuitem->menu_item_id]))) {
						$upmenuitem_info['menu_item_id'] = $menu_item_id;
					} 
					$mi_category=$upmenuitem->mi_category;
					$oid = preg_replace('/[^\d]/i','',$upmenuitem->link);
					if($oid>0){
						if($mi_category=='product_list'){
							$link='_m=mod_product&_a=prdlist&cap_id='.intval($product_category_ids[$oid]);
							$upmenuitem_info['link'] = $link;
							$upmenuitem_info['content_id'] = intval($product_category_ids[$oid]);
						}
						if($mi_category=='article_list'){
							$link='_m=mod_article&_a=fullist&caa_id='.intval($article_category_ids[$oid]);
							$upmenuitem_info['link'] = $link;
							$upmenuitem_info['content_id'] = intval($article_category_ids[$oid]);
						}						
					}
					if($upmenuitem_info){
						$op_menuitem->set($upmenuitem_info);
						$op_menuitem->save();

					}        	
	        }
    
			// module_blocks
        	$ot_moduleblock = new ModuleBlock();
			$temarray = $temarray_marquee = $moduleblock_info = array();
	        $moduleblock_data = $ot_moduleblock->findAll($where, $params);
        	foreach($moduleblock_data as $moduleblock) {
        		$o_moduleblock = new ModuleBlock();
				$moduleblock_info = array_slice(get_object_vars($moduleblock), 6);
				if ($exchange) {
        			$moduleblock_info['title'] = $this->gb2big5($source, $zh2TW, $moduleblock_info['title']);
        			$moduleblock_info['s_param'] = $this->gb2big5($source, $zh2TW, $moduleblock_info['s_param']);
        		}
				$moduleblock_info['alias'] = in_array($moduleblock->alias, array('mb_logo','mb_foot','mb_banner')) ? $moduleblock->alias : 'mb_'.Toolkit::randomStr(8);
				// for article
				if (($moduleblock->module == 'mod_category_a') && ($moduleblock->action == 'category_a_menu')) {
					$parentids = $this->getParentIds('article_category_id', $cur_local);
					if (!empty($parentids)) {
						$article_param = array();
						$article_param = unserialize($moduleblock->s_param);
						$article_param['product_category_list'] = $parentids;
						$moduleblock_info['s_param'] = serialize($article_param);	
					}
				}
				// for product
				if (($moduleblock->module == 'mod_category_p') && ($moduleblock->action == 'category_p_menu')) {
					$parentids = $this->getParentIds('product_category_id', $cur_local);
					if (!empty($parentids)) {
						$product_param = array();
						$product_param = unserialize($moduleblock->s_param);
						$product_param['product_category_list'] = $parentids;
						$moduleblock_info['s_param'] = serialize($product_param);	
					}
				}
				$moduleblock_info['s_locale'] = $cur_local;
				$o_moduleblock->set($moduleblock_info);
				$get_insert_id = $o_moduleblock->save("get_insert_id");
				if($moduleblock->action == "marquee"){
					$temarray_marquee[$moduleblock->id] = $get_insert_id;							
				}
        	}
			
			// marquee
			foreach($temarray_marquee as $k => $v){			
			//更新block 里面的产品滚动类别
				$moduleblock_info = array();	
				$op_moduleBlock = new ModuleBlock($v);
				$str=$op_moduleBlock->s_param;
				$strd=unserialize($str);
				
				$strdtmp=explode(",",$strd['mar_prd_id']);
				//产品类别映射表
				$tem=$product_category_ids;
				
			
								
				foreach($strdtmp as $ks => $vs){				
					foreach($tem as $kss => $vss){					
						if($vs==$kss){$strdtmp[$ks]=$vss;}
					}	
				}
				
				$strd['mar_prd_id']=implode(",",$strdtmp);				
				$upmenuitem_info['s_param']=serialize($strd);
				
				$op_moduleBlock->set($upmenuitem_info);
				$op_moduleBlock->save();	
			
				//更新插入marquee
				$ot_marquee = new Marquee();				
				$marqueenfo_data = $ot_marquee->findAll("module_id='".$k."'");
				foreach($marqueenfo_data as $marqueenfo) {
					$marquee_info = array();
					$o_marqueeinfo = new Marquee();
					$marquee_info = array_slice(get_object_vars($marqueenfo), 6);
					$marquee_info['module_id']=$v;
					$marquee_info['link'] = !empty($product_list_ids[$marqueenfo->link]) ? $product_list_ids[$marqueenfo->link] : '0';
					if ($exchange) {
	        			$marquee_info['title'] = $this->gb2big5($source, $zh2TW, $marqueenfo->title);
	        		}
					$o_marqueeinfo->set($marquee_info);
					$o_marqueeinfo->save();
				}
				
			}
			
			$ot_moduleblock = new ModuleBlock();
			$moduleblock_info = array();
 	        $moduleblock_data = $ot_moduleblock->findAll($where, $params);			

			// site_infos
        	$ot_siteinfo = new SiteInfo();
        	$site_info = array();
	        $siteinfo_data = $ot_siteinfo->findAll($where, $params);
        	foreach($siteinfo_data as $siteinfo) {
        		$o_siteinfo = new SiteInfo();
				$site_info = array_slice(get_object_vars($siteinfo), 6);
				if ($exchange) {
        			$site_info['site_name'] = $this->gb2big5($source, $zh2TW, $site_info['site_name']);
        			$site_info['keywords'] = $this->gb2big5($source, $zh2TW, $site_info['keywords']);
        			$site_info['description'] = $this->gb2big5($source, $zh2TW, $site_info['description']);
        		}
				$site_info['s_locale'] = $cur_local;
				$o_siteinfo->set($site_info);
				$o_siteinfo->save();
        	}

			// static_contents
			$loop = 0;
        	$ot_staticontent = new StaticContent();
        	$staticontent_info = $update_menuitem_info = $update_static_list = array();
	        $staticontent_data = $ot_staticontent->findAll($where, $params, "ORDER BY `id`");
	        
        	foreach($staticontent_data as $staticontent) {
        		$up_menuitem = new MenuItem();
        		$o_staticontent = new StaticContent();
        		$tag_custom = $up_menuitem->find('link=?',array("_m=mod_static&_a=view&sc_id=".$staticontent->id));
				/*
        		if ( !($tag_custom->id) && $staticontent->id != 1 && $staticontent->id != 2) {	
        			$staticontent->delete($staticontent->id);
        			continue;
        		}
				*/
				$staticontent_info = array_slice(get_object_vars($staticontent), 6);
				if ($exchange) {
        			$staticontent_info['title'] = $this->gb2big5($source, $zh2TW, $staticontent_info['title']);
        			$staticontent_info['content'] = $this->gb2big5($source, $zh2TW, $staticontent_info['content']);
        		}
				$staticontent_info['s_locale'] = $cur_local;
				$staticontent_info['create_time'] = $_SERVER['REQUEST_TIME'];
				if ($staticontent_info['id'] == 1 || $staticontent_info['id'] == 2) {
					$staticontent_info['published'] == 1;
				}
				$o_staticontent->set($staticontent_info);
				$o_staticontent->save();
				$sc_id = $o_staticontent->id;
				$update_static_list[$staticontent->id]=$sc_id;
				//$sc_id = $o_staticontent->save('get_insert_id');
				if ($loop=="0") {// Contact Us
					$link = "_m=mod_static&_a=view&sc_id={$sc_id}";
					$temparr = array($cur_local, 'contact_info');
					$menuitem_updata = $up_menuitem->findAll($where.' AND mi_category=? ', $temparr);
					if(isset($menuitem_updata[0]->id)){
						
						$oup_menuitem = new MenuItem($menuitem_updata[0]->id);
						$update_menuitem_info['link'] = $link;
						$oup_menuitem->set($update_menuitem_info);
						$oup_menuitem->save();
					}
					//修复自定义页面banner拷贝时丢失bug
					$o_block = new ModuleBlock();
        			$r_block = $o_block->find('s_locale=? and s_pos="banner"',array($cur_local));
        			if(isset($r_block->id)){
        				$sparams = unserialize($r_block->s_param);
        				foreach ($sparams as $k=>$param_v){
        					if ($k=='_m=mod_static&_a=view&sc_id=1') {
        						$spa['_m=mod_static&_a=view&sc_id='.$sc_id] = $param_v;
        					}else{
        						$spa[$k] = $param_v;
        					}
        				}
        			}
        			$v_sparam = serialize($spa);
        			$r_block->s_param = $v_sparam;
        			$r_block->save();
				} elseif($loop=="1"){  // Company Intro
					$link = "_m=mod_static&_a=view&sc_id={$sc_id}";
					$temparr = array($cur_local, 'company_info');
					$menuitem_updata = $up_menuitem->findAll($where.' AND mi_category=? ', $temparr);
					if(isset($menuitem_updata[0]->id)){
						
						$oup_menuitem = new MenuItem($menuitem_updata[0]->id);
						$update_menuitem_info['link'] = $link;
						$oup_menuitem->set($update_menuitem_info);
						$oup_menuitem->save();
					}
					//修复自定义页面banner拷贝时丢失bug
					$o_block = new ModuleBlock();
        			$r_block = $o_block->find('s_locale=? and s_pos="banner"',array($cur_local));
					if(isset($r_block->id)){
        				$sparams = unserialize($r_block->s_param);
        				foreach ($sparams as $k=>$param_v){
        					if ($k=='_m=mod_static&_a=view&sc_id=2') {
        						$spa['_m=mod_static&_a=view&sc_id='.$sc_id] = $param_v;
        					}else{
        						$spa[$k] = $param_v;
        					}
        				}
        			}
        			$v_sparam = serialize($spa);
        			$r_block->s_param = $v_sparam;
        			$r_block->save();
				}else{
					$tag_custom = $up_menuitem->find('link=?',array("_m=mod_static&_a=view&sc_id=".$staticontent->id));
					$link = "_m=mod_static&_a=view&sc_id={$sc_id}";
					$temparr = array($cur_local, 'static');
					$menuitem_updata = $up_menuitem->findAll($where.' AND mi_category=? ', $temparr);
					if ((sizeof($menuitem_updata))>=1) {
						if(isset($menuitem_updata[$loop-2]->id)){
							$oup_menuitem = new MenuItem($menuitem_updata[$loop-2]->id);
							$update_menuitem_info['link'] = $link;
							$oup_menuitem->set($update_menuitem_info);
							$oup_menuitem->save();
						}	
					}	
					//修复自定义页面banner拷贝时丢失bug
					$o_block = new ModuleBlock();
        			$r_block = $o_block->find('s_locale=? and s_pos="banner"',array($cur_local));
					if(isset($r_block->id)){
        				$sparams = unserialize($r_block->s_param);
        				foreach ($sparams as $k=>$param_v){
        					if ($k=='_m=mod_static&_a=view&sc_id=2') {
        						$spa['_m=mod_static&_a=view&sc_id='.$sc_id] = $param_v;
        					}else{
        						$spa[$k] = $param_v;
        					}
        				}
        			}
        			$v_sparam = serialize($spa);
        			$r_block->s_param = $v_sparam;
        			$r_block->save();		
				}				
				$loop++;				
        	}
        	$o_block = new ModuleBlock();
        	$r_block = $o_block->find('s_locale=? and s_pos="banner"',array($cur_local));
        	
			
				foreach($update_static_list as $k => $v){
					//更新ModuleBlock marquee hash
					$ot_marquee = new ModuleBlock();	
					$link1 = "_m=mod_static&_a=view&sc_id=".$k;
					$link2 = "_m=mod_static&_a=view&sc_id=".$v;
					$link1 = Toolkit::calcMQHash($link1);	
					$link2 = Toolkit::calcMQHash($link2);				
					$marqueenfo_data = $ot_marquee->findAll("s_locale='".$cur_local."' and s_query_hash='".$link1."'");
					foreach($marqueenfo_data as $marqueenfo) {
						$marquee_info = array();
						$o_marqueeinfo = new ModuleBlock($marqueenfo->id);
						$marquee_info['s_query_hash']=$link2;				
						$o_marqueeinfo->set($marquee_info);
						$o_marqueeinfo->save();
					}
				}
			
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_copydata_existed() {
    	$curr_lang_id = trim(ParamHolder::get('l_id', '0'));
        if (intval($curr_lang_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        // current site language
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_lang/_LOCALE', $curr_locale));
       // $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
		 $lang_sw = $curr_locale;
       // SessionHolder::set('mod_lang/_LOCALE', $lang_sw);
    	// goto language
    	$curr_lang = new Language($curr_lang_id);
    	$cur_local = $curr_lang->locale;
    	
    	// exist or not

    	$self = -1;
    	if (DEFAULT_LOCALE == $cur_local) {
    		$self = 1;
	        $this->assign('json', Toolkit::jsonOK(array('self_tag'=>$self)));
    	} else {
	        $db =& MySqlConnection::get();
	        $sql = "SELECT id FROM ".Config::$tbl_prefix."products WHERE s_locale=?";
	        $rs =& $db->query($sql, array($cur_local));
	        $this->assign('json', Toolkit::jsonOK(array('rows'=>$rs->getRecordNum(), 'lang_id'=>$curr_lang_id, 'self_tag'=>$self)));
    	}
    	
        return ('_result');
    }
    
    public function tog_lang() {
    	$curr_lang_id = trim(ParamHolder::get('l_id', '0'));
    	$curr_published = trim(ParamHolder::get('_p', '0'));
        if (intval($curr_lang_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			
	        $curr_lang = new Language($curr_lang_id);
            $curr_lang->published = $curr_published;
            $curr_lang->save();	
			
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_make_default() {
        
        $curr_lang_id = trim(ParamHolder::get('l_id', '0'));
        $curr_local = trim(ParamHolder::get('lg', 'other'));
        if (intval($curr_lang_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
    		$curr_lang = new Language($curr_lang_id);
    		SessionHolder::set('SS_LOCALE', $curr_lang->locale);         
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK(array('local'=>$curr_local)));
        return '_result';
    }
    
	 public function admin_make_default_set() {
        
        $curr_lang_id = trim(ParamHolder::get('l_id', '0'));
		 $lang_name = trim(ParamHolder::get('lang_name', ''));
       // $curr_local = trim(ParamHolder::get('lg', 'other'));
	   $curr_local =SessionHolder::get('_LOCALE');
		
        if ((!$curr_lang_id)) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
     		
			$o_param = new Parameter(14);		
        	$locale_info['val'] = $lang_name;
	        $o_param->set($locale_info);
	        $o_param->save();
			
			$o_lang = new Language($curr_lang_id);
      		$locale_info['published'] = '1';
	        $o_lang->set($locale_info);
	        $o_lang->save();
			
			$curr_lang = new Language($curr_lang_id);
        	SessionHolder::set('SS_LOCALE', $curr_lang->locale);
			
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK(array('local'=>$curr_local)));
        return '_result';
    }
	
    public function langbar() {
        $o_lang = new Language();
        $langs = $o_lang->findAll();
        $this->assign('langs', $langs);
    }
    
    private function _saveLangFile($locale, $struct_file) {
    	$lang_file_path = Toolkit::mkdir_locale($locale);
        if (!$lang_file_path) {
            return false;
        }
        move_uploaded_file($struct_file['tmp_name'], $lang_file_path.'/messages.mo');
        return ParamParser::fire_virus($lang_file_path.'/messages.mo');
    }
    
    private function make_locale($local,$localcopy) {
    	$front_locale = ROOT."/locale/{$local}";
    	$admin_locale = ADMIN_ROOT."/locale/{$local}";
    	//$curr_locale = trim(SessionHolder::get('_LOCALE'));
		$curr_locale=$localcopy;
    	
    	// frontpage language packs
    	if (!file_exists("{$front_locale}/lang.php")) {
    		if (@mkdir($front_locale, 0755)) {
    			@copy(ROOT."/locale/{$curr_locale}/lang.php", "{$front_locale}/lang.php") or die('return false');
    		} else {
    			return false;
    		}
    	}
    	
    	// admin language packs
    	if (!file_exists("{$admin_locale}/lang.php")) {
    		if (@mkdir($admin_locale, 0755)) {
    			@copy(ADMIN_ROOT."/locale/{$curr_locale}/lang.php", "{$admin_locale}/lang.php") or die('return false');
    		} else {
    			return false;
    		}
    	}
    	
    	// 中文状态下添加繁体自动将lang.php转为繁体
		if (($localcopy == 'zh_CN') && ($local == 'zh_TW')) {
			$zh2TW = $source = array();
	    	include_once(ROOT.'/data/ZhConversion.php');
			$source = array_keys($zh2TW);
			$this->lang2tw($local, $localcopy, ROOT, $source, $zh2TW);
			$this->lang2tw($local, $localcopy, ADMIN_ROOT, $source, $zh2TW);
		}
    	
    	/*if (file_exists($admin_locale)) {
    		return true;
    	} else if (mkdir($admin_locale, 0755)) {
    		if (file_exists(P_LOCALE.'/en/lang.php')) {
    			copy(P_LOCALE.'/en/lang.php', $admin_locale.'/lang.php');
    		} else {
    			$ahd = fopen($admin_locale.'/lang.php', 'wb');
    			$content = "<?php\nreturn array(\n\t\"\" => \"\"\n);\n?>";
    			fwrite($ahd, $content);
    			fclose($ahd);
    		}
    	} else {
    		return false;
    	}
        
        if (file_exists($front_locale)) {
    		return true;
    	} else if (mkdir($front_locale, 0755)) {
    		if (file_exists(str_replace('admin/', '', P_LOCALE).'/en/lang.php')) {
    			copy(str_replace('admin/', '', P_LOCALE).'/en/lang.php', $front_locale.'/lang.php');
    		} else {
    			$fhd = fopen($front_locale.'/lang.php', 'wb');
    			$content2 = "<?php\nreturn array(\n\t\"\" => \"\"\n);\n?>";
    			fwrite($fhd, $content2);
    			fclose($fhd);
    		}
    	} else {
    		return false;
    	}*/
    	
    	return true;
    }
    
    private function getParentIds($tbl_key, $curr_locale) {
    	$parentids = '';
		$temparr = array();
		
		// 仅显示一级菜单
		switch ($tbl_key) {
			case 'article_category_id':
				$ot_category = new ArticleCategory();
				$all_categories =& ArticleCategory::listCategories(0, "s_locale=? And `article_category_id`=0", array($curr_locale));
		        $select_array = array('0' => __('Top Level'));
		        ArticleCategory::toSelectArray($all_categories, $select_array);
				break;
			case 'product_category_id':
				$ot_category = new ProductCategory();
				$all_categories =& ProductCategory::listCategories(0, "s_locale=? And `product_category_id`=0", array($curr_locale));
		        $select_array = array('0' => __('Top Level'));
		        ProductCategory::toSelectArray($all_categories, $select_array);
				break;
		}
        
        if (count($select_array)) {
        	$temparr = array_keys($select_array);
			$parentids = implode(",", $temparr);
        }
        
        return $parentids;
    }
    
    private function gb2big5($needle, $new_needle, $input) {
		return str_replace($needle, $new_needle, $input);
    }
    
    private function lang2tw($curlocal, $sitelocal, $dirext, $source, $zh2TW) {
    	$langstr = '';
		$langstr = file_get_contents($dirext."/locale/{$sitelocal}/lang.php");
		$langstr = $this->gb2big5($source, $zh2TW, $langstr);
		file_put_contents($dirext."/locale/{$curlocal}/lang.php", $langstr);
    }
}
?>