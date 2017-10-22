<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModFriendlink extends Module {
    
	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
        $this->_layout = 'content';
        
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_friendlink/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        SessionHolder::set('mod_friendlink/_LOCALE', $lang_sw);
        
        //分页时使用
		if (MOD_REWRITE == '2' || MOD_REWRITE == '3') {
			 $friendlink_data =& 
            Pager::pageByObject('friendlink', "s_locale=?", array($lang_sw), 
                "ORDER BY i_order desc,`create_time` DESC", 'p', 'popupwin');
		}else{
        $friendlink_data =& 
            Pager::pageByObject('friendlink', "s_locale=?", array($lang_sw), 
                "ORDER BY i_order desc,`create_time` DESC");
        }
        //不分页时使用
//        $friendlink_data=array();
//        $curr_friendlink = new Friendlink();
//        $friendlink_data['data']= $curr_friendlink->findAll("s_locale=?", array($lang_sw),"ORDER BY `create_time` DESC");
        
        $this->assign('friendlinks', $friendlink_data['data']);
        $this->assign('pager', $friendlink_data['pager']);
        $this->assign('page_mod', $friendlink_data['mod']);
		$this->assign('page_act', $friendlink_data['act']);
		$this->assign('page_extUrl', $friendlink_data['extUrl']);
        
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
    }
    
    public function admin_delete() {
        
        $friendlink_id = trim(ParamHolder::get('friendlink_id', '0'));
        if (intval($friendlink_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			if (strpos($friendlink_id, '_') > 0) {
				$tmp_arr = explode('_', substr($friendlink_id, 0, -1));
				$len = sizeof($tmp_arr);
				for ($i = 0; $i< $len; $i++){
					$curr_friendlink = new Friendlink($tmp_arr[$i]);
					$curr_friendlink->delete();
					unlink(ROOT.'/upload/image/'.$curr_friendlink->fl_img);
				}
			} else {
				$curr_friendlink = new Friendlink($friendlink_id);
				$curr_friendlink->delete();
				unlink(ROOT.'/upload/image/'.$curr_friendlink->fl_img);
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_add() {
        $this->_layout = 'content';
        
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_friendlink/_LOCALE', $curr_locale));
        
        $this->assign('friendlink_title', __('New Friend Link'));
        $this->assign('next_action', 'admin_create');
                
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
        return '_form';
        
    }
    
    public function admin_create() {
        $friendlink_type = ParamHolder::get('fl_type', '0');
        $friendlink_info =& ParamHolder::get('friendlink', array());
        if (sizeof($friendlink_info) <= 0) {
            Notice::set('mod_friendlink/msg', __('Missing friendlink information!'));
            Content::redirect(Html::uriquery('mod_friendlink', 'admin_add'));
        }
        if($friendlink_type=="1"){
			$file_info =& ParamHolder::get('fl_file', array(), PS_FILES);
			$file_info["name"] = Toolkit::changeFileNameChineseToPinyin($file_info["name"]);

			if (empty($file_info)) {
				Notice::set('mod_friendlink/msg', __('Invalid post file data!'));
				Content::redirect(Html::uriquery('mod_friendlink', 'admin_add'));
			}
			if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_info["name"])) {
				Notice::set('mod_friendlink/msg', __('File type error!'));
				Content::redirect(Html::uriquery('mod_friendlink', 'admin_add'));
			}
			if(file_exists(ROOT.'/upload/image/'.$file_info["name"])) {
				//Notice::set('mod_friendlink/msg',__('Link image exists!'));
				//Content::redirect(Html::uriquery('mod_friendlink', 'admin_add'));
				$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
			}
			if (!$this->_savelinkimg($file_info)) {
				Notice::set('mod_friendlink/msg', __('Link image upload failed!'));
				Content::redirect(Html::uriquery('mod_friendlink', 'admin_add'));
			}
		}
        $is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
			if($friendlink_type=="1"){
				$friendlink_info['fl_img'] = $file_info["name"];
			}else if($friendlink_type=="2"){
				$friendlink_info['fl_img']="";
			}
            // Re-arrange publish status
//            if ($friendlink_info['published'] == '1') {
//                $friendlink_info['published'] = '1';
//            } else {
//                $friendlink_info['published'] = '0';
//            }
			$friendlink_info['published'] = '1';
            $friendlink_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // The create time
            $friendlink_info['create_time'] = time();
            $friendlink_info['fl_type'] = intval($friendlink_type);
            
            // Data operation
            $o_friendlink = new Friendlink();
            $o_friendlink->set($friendlink_info);
            $o_friendlink->save();
        } catch (Exception $ex) {
            Notice::set('mod_friendlink/msg', $ex->getMessage());
            Content::redirect(Html::uriquery('mod_friendlink', 'admin_add'));
        }
        
        Notice::set('mod_friendlink/msg', __('Link added successfully!'));
        Content::redirect(Html::uriquery('mod_friendlink', 'admin_list'));
    }

    public function admin_edit() {
        $this->_layout = 'content';
        
        $friendlink_id = ParamHolder::get('friendlink_id', '0');
		$friendlink_id = intval($friendlink_id);
        if ($friendlink_id == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
       try {
            $curr_friendlink = new Friendlink($friendlink_id);
            $this->assign('curr_friendlink', $curr_friendlink);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
        
        $this->assign('friendlink_title', __('Edit Friend Link'));
        $this->assign('next_action', 'admin_update');
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
        return '_eform';
        
    }
    
    public function admin_update() {
        $friendlink_type = ParamHolder::get('fl_type', '0');
        $friendlink_info =& ParamHolder::get('friendlink', array());
		
        if (sizeof($friendlink_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing friendlink information!')));
            return '_result';
        }
		if($friendlink_type=="1"){
			$file_info =& ParamHolder::get('fl_file', array(), PS_FILES);
			if (!empty($file_info["name"])) {
				if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_info["name"])) {
					Notice::set('mod_friendlink/msg', __('File type error!'));
					Content::redirect(Html::uriquery('mod_friendlink', 'admin_edit', array('friendlink_id' => $friendlink_info['id'])));
				}
				if(file_exists(ROOT.'/upload/image/'.$file_info["name"])) {
					$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
				}
				if (!$this->_savelinkimg($file_info)) {
					Notice::set('mod_friendlink/msg', __('Link image upload failed!'));
					Content::redirect(Html::uriquery('mod_friendlink', 'admin_edit', array('friendlink_id' => $friendlink_info['id'])));
				}
			}
		}
		
        $is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
			if($friendlink_type=="1"){
				//add image
				if (!empty($file_info["name"])) {
					$friendlink_info['fl_img'] = $file_info["name"];
				}
			}else if($friendlink_type=="2"){
				$friendlink_info['fl_img']="";
			}
			
            // Re-arrange publish status
//            if ($friendlink_info['published'] == '1') {
//                $friendlink_info['published'] = '1';
//            } else {
//                $friendlink_info['published'] = '0';
//            }
            $friendlink_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
			$friendlink_info['fl_type'] = intval($friendlink_type);
            
            // Data operation
            $o_friendlink = new Friendlink(intval($friendlink_info['id']));
            $o_friendlink->set($friendlink_info);
            $o_friendlink->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        Notice::set('mod_friendlink/msg', __('Link updated successfully!'));
        Content::redirect(Html::uriquery('mod_friendlink', 'admin_list'));
    }
    
	public function admin_pic()
    {
    	$friendlink_info = array();
    	$friendlink_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($friendlink_id))
    	{
    		$o_friendlink = new Friendlink($friendlink_id);
            if($o_friendlink->published == 1)
            {
            	$friendlink_info['published'] = '0';
            	$o_friendlink->set($friendlink_info);
            	$o_friendlink->save();
				die('0');
            }
            elseif($o_friendlink->published == 0)
            {
            	$friendlink_info['published'] = '1';
            	$o_friendlink->set($friendlink_info);
            	$o_friendlink->save();
				die('1');
            }
    	}
    }
    
    public function admin_order() {

		$order_info =& ParamHolder::get('i_order', array());
		if (!is_array($order_info)) {
            $this->assign('json', Toolkit::jsonERR(__('Missing product order information!')));
            return '_result';
        }
		try {
			foreach($order_info as $key => $val) {
				$fri['i_order'] = $val;
				$o_f = new Friendlink($key);
				$o_f->set($fri);
				$o_f->save();
			}
		} catch (Exception $ex) {
			$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		Content::redirect(Html::uriquery('mod_friendlink', 'admin_list'));
	}
    
    private function _savelinkimg($struct_file) {
    	$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
        move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/image/'.$struct_file['name']);
        return ParamParser::fire_virus(ROOT.'/upload/image/'.$struct_file['name']);
    }
}
?>