<?php

if (!defined('IN_CONTEXT')) die('access violation error!');

class ModStatic extends Module {
    
	protected $_filters = array(
        'check_admin' => ''
    );
	public function seo() {
		$this->assign("css_tag",'seo');
		$this->_layout = 'content';
	}
    public function admin_list() {
        $this->_layout = 'content';
        
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_static/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        SessionHolder::set('mod_static/_LOCALE', $lang_sw);
        
        $scontent_data =& 
            Pager::pageByObject('StaticContent', "s_locale=? and id>?", array($lang_sw,4), 
                "ORDER BY `create_time` DESC");
        
        $this->assign('scontents', $scontent_data['data']);
        $this->assign('pager', $scontent_data['pager']);
         $this->assign('page_mod', $scontent_data['mod']);
		$this->assign('page_act', $scontent_data['act']);
		$this->assign('page_extUrl', $scontent_data['extUrl']);
        
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
    }
    
    public function admin_add() {
        $this->_layout = 'content';
        
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_static/_LOCALE', $curr_locale));
        
        $this->assign('content_title', __('New Content'));
        $this->assign('next_action', 'admin_create');
        
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
        return '_form';
    }
    
    public function admin_mi_quick_add() {
        if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}
        
        $this->assign('content_title', __('New Content'));
        $this->assign('next_action', 'admin_create');
        
        $this->assign('mod_locale', $curr_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
        $link_type_text = trim(ParamHolder::get('txt'));
        $this->assign('type_text', $link_type_text);

        $this->_layout = 'clean';
        return '_mi_quick_add_form';
    }
    
    public function admin_create() {
        
        $scontent_info =& ParamHolder::get('sc', array());
        if (sizeof($scontent_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing content information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
        	// Re-arrange publish status
        	/*
            if ($scontent_info['published'] == '1') {
                $scontent_info['published'] = '1';
            } else {
                $scontent_info['published'] = '0';
            }
            */
            $scontent_info['published'] = '0';
            $scontent_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // The create time
            $scontent_info['create_time'] = time();
            
            // Data operation
            $o_scontent = new StaticContent();
            $o_scontent->set($scontent_info);
            $o_scontent->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_static', 'admin_list'), 
            'id' => $o_scontent->id, 'title' => $o_scontent->title)));
        return '_result';
    }
    
    public function admin_edit() {
        $this->_layout = 'content';
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
		$mod_locale = trim(SessionHolder::get('mod_static/_LOCALE', $curr_locale));
        $sc_id = ParamHolder::get('sc_id', '0');
        $isback = ParamHolder::get('_isback', '0');
        $sc_id = intval($sc_id);
        if ($sc_id == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        
        try {
            $curr_scontent = new StaticContent($sc_id);
            $this->assign('curr_scontent', $curr_scontent);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
         $this->assign('mod_locale', $mod_locale);
        $this->assign('content_title', __('Edit Content'));
        $this->assign('next_action', 'admin_update');
        
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        $this->assign('isback', $isback);
        
        return '_form';
    }
    
    public function admin_update() {
        $isback = & ParamHolder::get('isback', array());
        $scontent_info =& ParamHolder::get('sc', array());
        if (sizeof($scontent_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing content information!')));
            return '_result';
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
        	// Re-arrange publish status
        	/*
            if ($scontent_info['published'] == '1') {
                $scontent_info['published'] = '1';
            } else {
                $scontent_info['published'] = '0';
            }
            */
            $scontent_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            
            // Data operation
            $o_scontent = new StaticContent($scontent_info['id']);
           // $pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
			//$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
			//$scontent_info['content'] = str_replace($path,"",$scontent_info['content']);
			$o_scontent->set($scontent_info);
            $o_scontent->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
       
        if($isback == 1)
        {
			$this->assign('json', Toolkit::jsonOK(array('forward' => "close")));
        }else if($isback == 2){
        	$this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_static', 'admin_edit',array('sc_id' =>  $scontent_info['id'],'_isback' => 2)))));
        }else
        {
        	$this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_menu_item', 'admin_link_content_select',array('pt' => 'static','txt' => __('Custom Page'))))));
        }
        return '_result';
    }
    
    public function admin_delete() {
        
        $sc_id = trim(ParamHolder::get('sc_id', '0'));
        if (intval($sc_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			if (strpos($article_id, '_') > 0) {
			$tmp_arr = explode('_', substr($sc_id, 0, -1));
			$len = sizeof($tmp_arr);
			for ($i = 0; $i< $len; $i++){
				$curr_scontent = new StaticContent($tmp_arr[$i]);
				$curr_scontent->delete();
			}
			}else{
				$curr_scontent = new StaticContent($sc_id);
				$curr_scontent->delete();
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
	public function admin_info() {
		$this->_layout = 'content';
		try {
			$curr_locale = trim(SessionHolder::get('_LOCALE'));
			$mod_locale = trim(SessionHolder::get('mod_static/_LOCALE', $curr_locale));
			$lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
			SessionHolder::set('mod_static/_LOCALE', $lang_sw);
			if(strtolower($lang_sw) == 'zh_cn') {
				$cus_id = 1;
			} else if(strtolower($lang_sw) == 'en'){
				$cus_id = 3;
			}
            $curr_cus = new StaticContent($cus_id);
            $this->assign('curr_cus', $curr_cus);

			if(strtolower($lang_sw) == 'zh_cn') {
				$co_id = 2;
			} else if(strtolower($lang_sw) == 'en') {
				$co_id = 4;
			}
            $curr_co = new StaticContent($co_id);
            $this->assign('curr_co', $curr_co);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('lang_sw', $lang_sw);
		//logo banner foot
		$o_mb = new ModuleBlock();
		$curr_logo = $o_mb->find("s_locale=? and alias=? and module=? and action=?",array($lang_sw,'mb_logo','mod_media','show_image'));
		$this->assign('curr_logo', $curr_logo);
		$this->assign('p_logo', unserialize($curr_logo->s_param));
		//
		$curr_banner = $o_mb->find("s_locale=? and alias=? and module=?",array($lang_sw,'mb_banner','mod_media'));
		$this->assign('curr_banner', $curr_banner);
		$this->assign('p_banner', unserialize($curr_banner->s_param));
		//
		$curr_foot = $o_mb->find("s_locale=? and alias=? and module=? and action=?",array($lang_sw,'mb_foot','mod_static','custom_html'));
		$this->assign('curr_foot', $curr_foot);
		$this->assign('p_foot', unserialize($curr_foot->s_param));
        
	}
	public function contactus() {
		$cus_info =& ParamHolder::get('cus', array());
		$co_info =& ParamHolder::get('co', array());
        if (sizeof($cus_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing contact us information!')));
            return '_result';
        }
		try {
            $cus_info['create_time'] = time();
			
            $cus_info['published'] = 1;
            $cus_info['title'] = 1;
			
            $o_cus = new StaticContent($cus_info['id']);
            $o_cus->set($cus_info);
            $o_cus->save();

			$co_info['create_time'] = time();
			
            $co_info['published'] = 1;
            $co_info['title'] = 1;
			
            $o_o = new StaticContent($co_info['id']);
            $o_o->set($co_info);
            $o_o->save();

        } catch (Exception $ex) {
            Notice::set('mod_static/msg', $ex->getMessage());
            Content::redirect(Html::uriquery('mod_static', 'admin_info'));
        }
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_static', 'admin_info'))));
        return '_result';
	}
	public function admin_mod() {
		$flag_flash = false;
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
		$mod_locale = trim(SessionHolder::get('mod_static/_LOCALE', $curr_locale));
		$lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
		//SessionHolder::set('mod_static/_LOCALE', $lang_sw);
		//logo
		$logo_info =& ParamHolder::get('logo', array());
		$param_info =& ParamHolder::get('param', array());
		$logo_file =& ParamHolder::get('logo_file', array(), PS_FILES);
		$play_info =& ParamHolder::get('radio', array());
		
			$logo_arr = array();
			$logo_info['module'] = 'mod_media';
			$logo_info['action'] = 'show_image';
			$logo_info['alias'] = 'mb_logo';
			$logo_info['title'] = '';
			$logo_info['show_title'] = 0;
			$logo_info['s_pos'] = 'logo';
			$logo_info['s_locale'] = $lang_sw;
			$logo_info['s_query_hash'] = '_ALL';
			$logo_info['i_order'] = 0;
			$logo_info['published'] = 1;
			/*if ($logo_info['published'] == '1') {
                $logo_info['published'] = '1';
            } else {
                $logo_info['published'] = '0';
            }*/
			$logo_info['for_roles'] = '{member}{admin}{guest}';
			if($logo_file['name']) {
				if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $logo_file["name"])) {
					Notice::set('mod_static/msg', __('File type error!'));
					die(__('File type error!'));
					Content::redirect(Html::uriquery('mod_static', 'admin_mod'));
				}
				if(file_exists(ROOT.'/upload/image/'.$logo_file["name"])) {
					$logo_file["name"] = Toolkit::randomStr(8).strrchr($logo_file["name"],".");
				}
				if (!$this->_savelinkimg($logo_file)) {
					Notice::set('mod_static/msg', __('Link image upload failed!'));
					Content::redirect(Html::uriquery('mod_static', 'admin_mod'));
				}
				$logo_arr['img_src'] = 'upload/image/'.$logo_file["name"];
			} else {
				$logo_arr['img_src'] = $param_info['logo_img'];
			}
			$logo_arr['img_desc'] = '';
			$logo_arr['img_width'] = $param_info['logo_width'];
			$logo_arr['img_height'] = $param_info['logo_height'];
			$logo_info['s_param'] = serialize($logo_arr);
			if(!$logo_info['id']){
				$o_logo = new ModuleBlock();
				$o_logo->set($logo_info);
				$o_logo->save();
			} else {
				 $o_logo = new ModuleBlock($logo_info['id']);
				 $o_logo->set($logo_info);
				 $o_logo->save();
			}
		//logo end 
		//banner start
		$banner_info =& ParamHolder::get('banner', array());
		$banner_file =& ParamHolder::get('banner_file', array(), PS_FILES);
		if(!empty($banner_file['name']))
		{
			$banner_arr = array();
			$banner_info['module'] = 'mod_media';
			$banner_info['action'] = 'show_image';
			if(strpos($banner_file['name'],'swf'))
			{
				$banner_info['action'] = 'show_flash';
				$flag_flash = true;
			}
			$banner_info['alias'] = 'mb_banner';
			$banner_info['title'] = '';
			$banner_info['show_title'] = 0;
			$banner_info['s_pos'] = 'banner';
			$banner_info['s_locale'] = $lang_sw;
			$banner_info['s_query_hash'] = '_ALL';
			$banner_info['i_order'] = 0;
			$banner_info['published'] = 1;
			/*if ($banner_info['published'] == '1') {
	             $banner_info['published'] = '1';
	        } else {
	             $banner_info['published'] = '0';
	        }*/
			$banner_info['for_roles'] = '{member}{admin}{guest}';
			if($banner_file['name']) {
				$pic_allow_ext = PIC_ALLOW_EXT.'|swf';
				if(!preg_match('/\.('.$pic_allow_ext.')$/i', $banner_file["name"])) {
					Notice::set('mod_static/msg', __('File type error!'));
					die(__('File type error!'));
					Content::redirect(Html::uriquery('mod_static', 'admin_mod'));
				}
				
				if($flag_flash)
				{
					if(file_exists(ROOT."/upload/flash/".$banner_file["name"])) {
						$banner_file["name"] = Toolkit::randomStr(8).strrchr($banner_file["name"],".");
					}
					if (!$this->_savelinkflash($banner_file)) {
						Notice::set('mod_static/msg', __('Link flash upload failed!'));
						Content::redirect(Html::uriquery('mod_static', 'admin_mod'));
					}
					$banner_arr['flv_src'] = 'upload/flash/'.$banner_file["name"];
				}
				else
				{
					if(file_exists(ROOT.'/upload/image/'.$banner_file["name"])) {
						$banner_file["name"] = Toolkit::randomStr(8).strrchr($banner_file["name"],".");
					}
					if (!$this->_savelinkimg($banner_file)) {
						Notice::set('mod_static/msg', __('Link image upload failed!'));
						Content::redirect(Html::uriquery('mod_static', 'admin_mod'));
					}
					$banner_arr['img_src'] = 'upload/image/'.$banner_file["name"];
				}
			} else {
				$banner_arr['img_src'] = $param_info['banner_img'];
			}
				$banner_arr['img_desc'] = '';
				$banner_arr['flv_width'] = $banner_arr['img_width'] = $param_info['banner_width'];
				$banner_arr['flv_height'] = $banner_arr['img_height'] = $param_info['banner_height'];
				$banner_info['s_param'] = serialize($banner_arr);
				
				if(!$banner_info['id']){
					$o_banner = new ModuleBlock();
					$o_banner->set($banner_info);
					$o_banner->save();
				} else {
					$o_banner = new ModuleBlock($banner_info['id']);
					 $o_banner->set($banner_info);
					 $o_banner->save();
				}
		}
		elseif(empty($banner_file['name']) && !empty($param_info))
		{
			$o_mb = new ModuleBlock();
			$curr_banner = $o_mb->find("s_locale=? and alias=? and module=?",array($lang_sw,'mb_banner','mod_media'));
			$arr = unserialize($curr_banner->s_param);
			$banner_arr['img_desc'] = '';
			$banner_arr['flv_width'] = $banner_arr['img_width'] = $param_info['banner_width'];
			$banner_arr['flv_height'] = $banner_arr['img_height'] = $param_info['banner_height'];
			if($arr['flv_src']){
				$banner_arr['flv_src'] = $arr['flv_src'];
			}else{
				$banner_arr['img_src'] = $arr['img_src'];
			}
			$banner_info['s_param'] = serialize($banner_arr);
			$o_banner = new ModuleBlock($banner_info['id']);
			$o_banner->set($banner_info);
			$o_banner->save();
		}
		//banner end
		//foot start
		$foot_info =& ParamHolder::get('foot', array());
		$foot_arr = array();
		$foot_info['module'] = 'mod_static';
		$foot_info['action'] = 'custom_html';
		$foot_info['alias'] = 'mb_foot';
		$foot_info['title'] = '';
		$foot_info['show_title'] = 0;
		$foot_info['s_pos'] = 'footer';
		$foot_info['s_locale'] = $lang_sw;
		$foot_info['s_query_hash'] = '_ALL';
		$foot_info['i_order'] = 0;
		$foot_info['published'] = 1;
		/*if ($foot_info['published'] == '1') {
                $foot_info['published'] = '1';
            } else {
                $foot_info['published'] = '0';
            }*/
		$foot_info['for_roles'] = '{member}{admin}{guest}';
		
		$foot_arr['html'] = $param_info['html'];
		$foot_info['s_param'] = serialize($foot_arr);
		
		if(!$foot_info['id']){
			$o_foot = new ModuleBlock();
            $o_foot->set($foot_info);
            $o_foot->save();
		} else {
			$o_foot = new ModuleBlock($foot_info['id']);
			$o_foot->set($foot_info);
			$o_foot->save();
		}
		//foot end
		
		//background music
		$music_file =& ParamHolder::get('music_file', array(), PS_FILES);
		if(!empty($music_file['name'])) {
			if(!preg_match('/\.('.MUSIC_ALLOW_EXT.')$/i', $music_file["name"])) {
				Notice::set('mod_static/msg', __('File type error!'));
				die(__('File type error!'));
//				Content::redirect(Html::uriquery('mod_static', 'admin_mod'));
			}
			if(file_exists(ROOT.'/upload/media/'.$music_file["name"])) {
				$show_name = $music_file["name"];
				$music_file["name"] = Toolkit::randomStr(8).strrchr($music_file["name"],".");
			}else {
				$show_name = $music_file["name"];
			}
			if (!$this->_savelinkmusic($music_file)) {
				Notice::set('mod_static/msg', __('Link music upload failed!'));
				Content::redirect(Html::uriquery('mod_static', 'admin_mod'));
			}
			$music_arr['BG_MUSIC'] = 'upload/media/'.$music_file["name"];
			$o_bgmusic = new BackgroundMusic();
			$bgmusic_items = $o_bgmusic->findAll();
			$db = MysqlConnection::get();
			$prefix = Config::$tbl_prefix;
			if(empty($bgmusic_items)) {
				$sql = <<<SQL
INSERT INTO {$prefix}background_musics VALUES(1,'{$music_arr['BG_MUSIC']}',{$play_info['play_type']},'{$music_file["name"]}')	
SQL;
			} else {
				$music_path = iconv("UTF-8", "gb2312", $bgmusic_items[0]->music_path);
				if(file_exists(ROOT.'/'.$music_path)) {
					unlink(ROOT.'/'.$music_path);
				}
				$sql = <<<SQL
UPDATE {$prefix}background_musics SET `music_path` = '{$music_arr['BG_MUSIC']}',`music_name` = '$show_name',`play` = {$play_info['play_type']} WHERE `id` = '{$bgmusic_items[0]->id}'		
SQL;
			}
			$result = $db->query($sql);
		}else {
			$o_bgmusic = new BackgroundMusic();
			$bgmusic_items = $o_bgmusic->findAll();
			if(!empty($bgmusic_items)) {
				$db = MysqlConnection::get();
				$prefix = Config::$tbl_prefix;
				$sql = <<<SQL
UPDATE {$prefix}background_musics SET `play` = {$play_info['play_type']} WHERE `id` = '{$bgmusic_items[0]->id}'		
SQL;
				$result = $db->query($sql);
			}
		}
		Notice::set('mod_static/msg', __('added successfully!'));
        Content::redirect(Html::uriquery('mod_static', 'admin_info'));
	}

	public function admin_service_create() {
		$service =& ParamHolder::get('param', array());
        if (sizeof($service) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing service information!')));
            return '_result';
        }
		try {
            $o_param = new Parameter();
            $s_param =& $o_param->find("`key`='SERVICE53'");
            $s_param->val = $service['service'];
            $s_param->save();

        } catch (Exception $ex) {
            Notice::set('mod_static/msg', $ex->getMessage());
            Content::redirect(Html::uriquery('mod_static', 'admin_service'));
        }
        $this->assign('json', Toolkit::jsonOK(array('forward' => Html::uriquery('mod_static', 'admin_service'))));
        return '_result';
	}
	private function _savelinkimg($struct_file) {
		$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
        move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/image/'.$struct_file['name']);
        return ParamParser::fire_virus(ROOT.'/upload/image/'.$struct_file['name']);
    }
    
    private function _savelinkflash($struct_file){
    	$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
    	move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/flash/'.$struct_file['name']);
    	return ParamParser::fire_virus(ROOT.'/upload/flash/'.$struct_file['name']);
    }
    
	private function _savelinkmusic($struct_file){
		$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
    	move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/media/'.$struct_file['name']);
    	return ParamParser::fire_virus(ROOT.'/upload/media/'.$struct_file['name']);
    }
}
?>