<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class ModNavigation extends Module {

	protected $_filters = array(
        'check_admin' => '{admin_default}'
    );
	
	public function admin_list() {
		$this->_layout = 'content';
		
		$o_nav = new Navigation();
        $navs =& $o_nav->findAll();
        $nav_arr = array();
        for($i = 0; $i<count($navs); $i++) {
            array_push($nav_arr, $navs[$i]->navigation);
        }
		$files = Toolkit::getNavDir(ROOT.'/navigation');
        for($i = 0; $i<count($files); $i++) {
            if(in_array($files[$i], $nav_arr)) {
                continue;
            }else {
                $nav_info = array();
                $o_nav = new Navigation();
                $nav_info['navigation'] = $files[$i];
                $o_nav->set($nav_info);
                $result = $o_nav->save();
            }
        }
		$navigations =& $o_nav->findAll();
		$this->assign('navigations', $navigations);
	}
    
	public function admin_create() {
	
		// 10/05/2010 >>
		include_once(P_LIB.'/zip.php');
		$deny = false;
		$file_path = ROOT.'/navigation';
		$dir = @opendir($file_path);
		
		if( @readdir($dir) == false ) $deny = true;
		@closedir($dir);
		$fp = @fopen($file_path.'/cf_tmp.txt', 'wb');
		if ( !$fp ) $deny = true;
		if ( @fwrite($fp, 'directory access testing.') == false ) $deny = true;
		fclose($fp);
		@unlink($file_path.'/cf_tmp.txt');
		
		if ( $deny ) {
			Notice::set('mod_navigation/msg', __($file_path.' isn\'t writable!'));
	        Content::redirect(Html::uriquery('mod_navigation', 'admin_upload'));
		}
		// 10/05/2010 <<
		
        $file_info =& ParamHolder::get('nav_file', array(), PS_FILES);
        $file_name = ToolKit::get_filename($file_info["name"]);
        
        if (empty($file_info)) {
            Notice::set('mod_navigation/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_navigation', 'admin_upload'));
        }
        
		$arr = explode('.',$file_info["name"]);
        if($arr[1] != 'zip') {
        	Notice::set('mod_navigation/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_navigation', 'admin_upload'));
        }
		
        if(is_dir(ROOT.'/navigation/'.$file_name)) {
            Notice::set('mod_navigation/msg',__('Navigation with the same name exists!'));
            Content::redirect(Html::uriquery('mod_navigation', 'admin_upload'));
        }
        
        if (!$this->_savetplFile($file_info)) {
            Notice::set('mod_navigation/msg', __('Uploading navigation file failed!'));
            Content::redirect(Html::uriquery('mod_navigation', 'admin_upload'));
        }
        
        /*
        $tpl_zip = new ZipArchive(); 
        $tpl_zip->open(ROOT.'/navigation/'.$file_info["name"]); 
        $tpl_zip->extractTo(ROOT."/navigation/".$file_name); 
        $tpl_zip->close();
        */
        if(!file_exists(ROOT."/navigation/".$file_name))
        {
        	mkdir(ROOT."/navigation/".$file_name);
        }
        $z = new zipper();
        $z->ExtractTotally(ROOT.'/navigation/'.$file_info["name"],ROOT."/navigation/".$file_name);
        
        unlink(ROOT.'/navigation/'.$file_info["name"]);
        $this->getFile(ROOT.'/navigation');
        // 4/12/2010 Jane Edit >>
		//Notice::set('mod_navigation/msg', __('Uploading language file succeeded!'));
        //Content::redirect(Html::uriquery('mod_navigation', 'admin_list'));
        if ( file_exists(ROOT.'/navigation/'.$file_name.'/index.html') ) {
        	Notice::set('mod_navigation/msg', __('Uploading language file succeeded!'));
        	Content::redirect(Html::uriquery('mod_navigation', 'admin_list'));
        } else {
        	Notice::set('mod_navigation/msg', __('\'index.html\' isn\'t exist!'));
        	$this->del_dir( ROOT.'/navigation/'.$file_name );
        	Content::redirect(Html::uriquery('mod_navigation', 'admin_upload'));
        }
        // 4/12/2010 Jane Edit <<
    }
    
    // 4/12/2010 Jane Add >>
    private function del_dir( $path ) 
	{
		$files = is_array(glob($path.'/*')) ? glob($path.'/*') : array();
		foreach( $files as $file )
		{
			if ( is_dir( $file ) ) {
				$this->del_dir( $file );
			} else {
				unlink( $file );
			}
		}
		
		rmdir( $path );
	}
	// 4/12/2010 Jane Add <<
    
	public function admin_upload() {
        $this->_layout = 'content';
    }
    
	public function admin_default() {
		$curr_nav_id = ParamHolder::get('nav_id', '0');
		$curr_nav_id = intval($curr_nav_id);
		if ($curr_nav_id == '0') {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
		try {
            $o_param = new Parameter();
            $tpl_param =& $o_param->find("`key`='DEFAULT_NAV'");
            $tpl_param->val = $curr_nav_id;
            $tpl_param->save();
			if($curr_nav_id == -1) {
				$tpl_param =& $o_param->find("`key`='DEFAULT_MODULE'");
				$tpl_param->val = 'frontpage';
				$tpl_param->save();
			} else {
				$tpl_param =& $o_param->find("`key`='DEFAULT_MODULE'");
				$tpl_param->val = 'mod_navigation';
				$tpl_param->save();
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
		$this->assign('json', Toolkit::jsonOK());
		return '_result';
	}
    
	public function admin_delete() {
		$curr_nav_id = ParamHolder::get('nav_id', '0');
		$curr_nav_id = intval($curr_nav_id);
		$curr_nav = ParamHolder::get('nav', '');
		if ( ($curr_nav_id == '0') || ($curr_nav == '') ) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
        	$file_path = ROOT.'/navigation/'.$curr_nav;
        	
        	$deny = false;
			$dir = @opendir($file_path);
			
			if( @readdir($dir) == false ) $deny = true;
			@closedir($dir);
			$fp = @fopen($file_path.'/cf_tmp.txt', 'wb');
			if ( !$fp ) $deny = true;
			if ( @fwrite($fp, 'directory access testing.') == false ) $deny = true;
			
			if ( $deny ) {
		        $this->assign('json', Toolkit::jsonERR(__($file_path.' isn\'t writable!')));
            	return ('_result');
			} else {
				fclose($fp);
				@unlink($file_path.'/cf_tmp.txt');
				$this->del_dir($file_path);
        		$nav = new Navigation($curr_nav_id);
	            $nav->delete($nav->id);
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        $this->assign('json', Toolkit::jsonOK());
		return '_result';
	}
    
    private function _savetplFile($struct_file) {
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
        move_uploaded_file($struct_file['tmp_name'], ROOT.'/navigation/'.$struct_file['name']);
        return ParamParser::fire_virus(ROOT.'/navigation/'.$struct_file['name']);
    }
    
    public function getFile($dir){
    	if($handle=opendir($dir)){
			while (false !== ($readdir = readdir($handle))) {
		        if ($readdir != "." && $readdir != "..") {
		        	$path = $dir.'/'.$readdir;
		        	if (strrpos($path,'.php')) {
		        		@unlink($path);
		        	}
		        	if (is_dir($path)) {
		        		$this->getFile($path);
		        	}
		        }
		    }
		}
		@closedir($handle);
    }
}
?>