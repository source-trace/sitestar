<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModDownload extends Module {

	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
        $this->_layout = 'content';

        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_article/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        SessionHolder::set('mod_article/_LOCALE', $lang_sw);

        $download_data =&
            Pager::pageByObject('Download', "s_locale=?", array($lang_sw),
                "ORDER BY `create_time` DESC");

        $this->assign('downloads', $download_data['data']);
        $this->assign('pager', $download_data['pager']);
        $this->assign('page_mod', $download_data['mod']);
		$this->assign('page_act', $download_data['act']);
		$this->assign('page_extUrl', $download_data['extUrl']);

        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());

    }

    public function admin_delete() {

        $download_id = trim(ParamHolder::get('download_id', '0'));
        if (intval($download_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
            if (strpos($download_id, '_') > 0) {
                $tmp_arr = explode('_', substr($download_id, 0, -1));
                $len = sizeof($tmp_arr);
                for ($i = 0; $i< $len; $i++){
                    $curr_download = new Download($tmp_arr[$i]);
                    $curr_download->delete();
                    // for sitestarv1.3 16/09/2010
                    if (file_exists(ROOT.'/upload/file/'.$curr_download->name)) {
                    	unlink(ROOT.'/upload/file/'.$curr_download->name);
                    }
                }
            } else {
				$curr_download = new Download($download_id);
				$curr_download->delete();
				// for sitestarv1.3 16/09/2010
                if (file_exists(ROOT.'/upload/file/'.$curr_download->name)) {
					unlink(ROOT.'/upload/file/'.$curr_download->name);
				}
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
        $mod_locale = trim(SessionHolder::get('mod_download/_LOCALE', $curr_locale));

		$all_categories =& DownloadCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        DownloadCategory::toSelectArray($all_categories, $select_categories,0, array());

        $this->assign('select_categories', $select_categories);

        $this->assign('download_title', __('New Download'));
        $this->assign('next_action', 'admin_create');

        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());

        return '_form';

    }

    public function admin_create() {
		$this->_layout = 'content';

        $download_info =& ParamHolder::get('download', array());
        $d_type = ParamHolder::get('d_type');
        if ($d_type==2) {
        	$url = ParamHolder::get('url');
        }else{//上传文件处理
	        if (sizeof($download_info) <= 0) {
	            $this->assign('json', Toolkit::jsonERR(__('Missing download information!')));
	            return '_result';
	        }
	        $file_allow_ext_pat = '/\.('.FILE_ALLOW_EXT.')$/i';
	        $file_info =& ParamHolder::get('download_file', array(), PS_FILES);
			$file_info['name'] = Toolkit::changeFileNameChineseToPinyin($file_info['name']);
	        if (empty($file_info)) {
	            Notice::set('mod_download/msg', __('Invalid post file data!'));
	            Content::redirect(Html::uriquery('mod_download', 'admin_add'));
	        }
			if(!preg_match($file_allow_ext_pat, $file_info["name"])) {
				Notice::set('mod_download/msg', __('File type error!'));
	            Content::redirect(Html::uriquery('mod_download', 'admin_add'));
			}
	        if(file_exists(ROOT.'/upload/file/'.$file_info["name"])) {
	            $file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
	        }
	        if (!$this->_savetplFile($file_info)) {
	            Notice::set('mod_download/msg', __('Uploading file failed!'));
	            Content::redirect(Html::uriquery('mod_download', 'admin_add'));
	        } 
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
       $is_member_only = intval($is_member_only);
        try {
        	if ($d_type==1) {
        		 $download_info['name'] = $file_info['name'];
        	}else{//网络地址
        		if (!strstr($url,'http://')) {
        			$url = 'http://'.$url;
        		}
        		$download_info['name'] = $url;
        	}
           
            // Re-arrange publish time
            //if (intval(ParamHolder::get('pub_start_time', '0')) == 0) {
                $download_info['pub_start_time'] = -1;
            //} else {
            //    $download_info['pub_start_time'] = strtotime($download_info['pub_start_time']);
            //}
            //if (intval(ParamHolder::get('pub_end_time', '0')) == 0) {
                $download_info['pub_end_time'] = -1;
            //} else {
            //    $download_info['pub_end_time'] = strtotime($download_info['pub_end_time']);
            //}
            // Re-arrange publish status
//            if ($download_info['published'] == '1') {
//                $download_info['published'] = '1';
//            } else {
//                $download_info['published'] = '0';
//            }

			if (empty($download_info['download_category_id'])) {
				$download_info['download_category_id'] = 0;
			}
            $download_info['published'] = '1';
            $download_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // The create time
            $download_info['create_time'] = time();
            // Fix category
            //$download_info['download_category_id'] = '0';

            // Data operation
            $o_download = new Download();
            $o_download->set($download_info);
            $o_download->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        Notice::set('mod_download/msg', __('Uploading file succeeded!'));
        Content::redirect(Html::uriquery('mod_download', 'admin_list'));
    }

    public function admin_edit() {
        $this->_layout = 'content';

        $download_id = ParamHolder::get('download_id', '0');
		$download_id = intval($download_id);
        if ($download_id == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
       try {
            $curr_locale = trim(SessionHolder::get('_LOCALE'));
            $mod_locale = trim(SessionHolder::get('mod_download/_LOCALE', $curr_locale));
            $curr_download = new Download($download_id);
            $this->assign('curr_download', $curr_download);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
		$all_categories =& DownloadCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        DownloadCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('0' => __('Uncategorised')));

        $this->assign('select_categories', $select_categories);

        $this->assign('download_title', __('Edit Download'));
        $this->assign('next_action', 'admin_update');
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());

        return '_eform';

    }

    public function admin_update() {
        $download_info =& ParamHolder::get('download', array());
        $name = $download_info['name'];
        $d_type = ParamHolder::get('d_type');
        if ($d_type==2) {
        	$url = ParamHolder::get('url');
        }else{
	        if (sizeof($download_info) <= 0) {
	            $this->assign('json', Toolkit::jsonERR(__('Missing download information!')));
	            return '_result';
	        }
			$file_info =& ParamHolder::get('download_file', array(), PS_FILES);
			if (!empty($file_info["name"])) {
				if(!preg_match('/\.('.FILE_ALLOW_EXT.')$/i', $file_info["name"])) {
					Notice::set('mod_download/msg', __('File type error!'));
					Content::redirect(Html::uriquery('mod_download', 'admin_edit', array('download_id' => $download_info['id'])));
				}
				if(file_exists(ROOT.'/upload/file/'.$file_info["name"])) {
					$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
				}
				if (!$this->_savetplFile($file_info)) {
					Notice::set('mod_download/msg', __('Link image upload failed!'));
					Content::redirect(Html::uriquery('mod_download', 'admin_edit', array('download_id' => $download_info['id'])));
				}
			}
        }
        $is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
        	if ($d_type!=2) {
        		 $download_info['name'] = $file_info['name'];
        	}else{//网络地址
        		if (!strstr($url,'http://')) {
        			$url = 'http://'.$url;
        		}
        		$download_info['name'] = $url;
        	}
        	//没做任何操作时保存，在文件模式下名称为空bug
        	if (empty($download_info['name'])) {
        		$download_info['name'] = $name;
        	}

            $download_info['pub_start_time'] = -1;
            $download_info['pub_end_time'] = -1;
            $download_info['for_roles'] = ACL::explainAccess(intval($is_member_only));

            $o_download = new Download($download_info['id']);
            $o_download->set($download_info);
            $o_download->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        Notice::set('mod_download/msg', __('Download information update successfully!'));
        Content::redirect(Html::uriquery('mod_download', 'admin_list'));
    }
    
	public function admin_pic()
    {
    	$download_info = array();
    	$download_id = trim(ParamHolder::get('_id', ''));
    	if(!empty($download_id))
    	{
    		$o_download = new Download($download_id);
            if($o_download->published == 1)
            {
            	$download_info['published'] = '0';
            	$o_download->set($download_info);
            	$o_download->save();
				die('0');
            }
            elseif($o_download->published == 0)
            {
            	$download_info['published'] = '1';
            	$o_download->set($download_info);
            	$o_download->save();
				die('1');
            }
    	}
    } 

    private function _savetplFile($struct_file) {
    	$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
        move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/file/'.$struct_file['name']);
        return ParamParser::fire_virus(ROOT.'/upload/file/'.$struct_file['name']);
    }
}
?>
