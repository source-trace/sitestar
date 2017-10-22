<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class ModTemplate extends Module {
    protected $_filters = array(
        'check_login' => ''
    );
    /*
    public function admin_template_list() {
        $this->_layout = 'content';
        
        if (!$this->_requireAdmin()) {
            return '_error';
        }
        
        $cat_sw =& ParamHolder::get('cat_sw', '-');
        // Now using "My Templates" as default category
        if ($cat_sw == '-') {
            $cat_sw = '999';
        }
        
        // Add new templates into the database : START
        $o_tpl = new Template();
        $tpls =& $o_tpl->findAll();
        $tpl_arr = array();
        for($i = 0; $i<count($tpls); $i++) {
            array_push($tpl_arr, $tpls[$i]->template);
        }
     
        $files = Toolkit::getDir(ROOT.'/template');
        for($i = 0; $i<count($files); $i++) {
            if(in_array($files[$i], $tpl_arr)) {
                continue;
            }else {
            	// skip loading template if no conf.php
            	if (!file_exists(ROOT.'/template/'.$files[$i].'/conf.php')) {
            		continue;
            	}
                $tpl_info = array();
                $o_template = new Template();
                include ROOT.'/template/'.$files[$i].'/conf.php';
                $tpl_info['template'] = $tpl_name;
                $tpl_info['template_category_id'] = 999;
                $o_template->set($tpl_info);
                $result = $o_template->save();
            }
        }
        // Add new templates into the database : END
        
        $select_categories =& TemplateCategory::allRemoteTplCategories();
        $this->assign('select_categories', $select_categories);
        
        if (intval($cat_sw) == 999) {
            $templates =& Template::getMyTemplates(999);
        } else {
            $templates =& Template::allRemoteTemplates($cat_sw);
        }
        $this->assign('templates', $templates);
        
        $this->assign('cat_sw', $cat_sw);
    }
    
    public function admin_upload() {
        $this->_layout = 'content';
        
        if (!$this->_requireAdmin()) {
            return '_error';
        }
    }
    
    public function admin_create() {
        if (!$this->_requireAdmin()) {
            return '_result';
        }

        $file_info =& ParamHolder::get('tpl_file', array(), PS_FILES);
        $file_name = ToolKit::get_filename($file_info["name"]);
        if (empty($file_info)) {
            Notice::set('mod_template/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_template', 'admin_upload'));
        }
        
        if(is_dir(ROOT.'/template/'.$file_name)) {
            Notice::set('mod_template/msg',__('Template with the same name exists!'));
            Content::redirect(Html::uriquery('mod_template', 'admin_template_list'));
        }
        
        if (!$this->_savetplFile($file_info)) {
            Notice::set('mod_template/msg', __('Uploading template file failed!'));
            Content::redirect(Html::uriquery('mod_template', 'admin_upload'));
        }
        
        $tpl_zip = new ZipArchive(); 
        $tpl_zip->open(ROOT.'/template/'.$file_info["name"]); 
        $tpl_zip->extractTo(ROOT."/template/".$file_name); 
        $tpl_zip->close();
        unlink(ROOT.'/template/'.$file_info["name"]);
        
        if (!file_exists(ROOT.'/template/'.$file_name.'/conf.php') || !file_exists(ROOT.'/template/'.$file_name.'/template_info.php')) {
            Notice::set('mod_template/msg', __('The file conf.php and(or) template_info.php does not exist'));
            Toolkit::rmdir_template($file_name);
            Content::redirect(Html::uriquery('mod_template', 'admin_upload'));
        }
        
        Notice::set('mod_template/msg', __('Uploading language file succeeded!'));
        Content::redirect(Html::uriquery('mod_template', 'admin_template_list'));
    }
    
    public function admin_make_default() {
        if (!$this->_requireAdmin()) {
            return '_result';
        }
        
        $curr_tpl_id = trim(ParamHolder::get('tpl_id', '0'));
        $is_remote_tpl = trim(ParamHolder::get('is_remote', '0'));
        if (intval($curr_tpl_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        if (intval($is_remote_tpl) == 1) {
            $template = $this->_downloadRemote($curr_tpl_id);
            if (!$template) {
                $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                return '_result';
            }
        } else {
            try {
                $curr_template = new Template($curr_tpl_id);
                $template = $curr_template->template;
            } catch (Exception $ex) {
                $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
                return ('_result');
            }
        }
        try {
            $o_param = new Parameter();
            $tpl_param =& $o_param->find("`key`='DEFAULT_TPL'");
            $tpl_param->val = $template;
            $tpl_param->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_delete() {
        if (!$this->_requireAdmin()) {
            return '_result';
        }
        
        $curr_tpl_id = trim(ParamHolder::get('tpl_id', '0'));
        if (intval($curr_tpl_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
            $curr_tpl = new Template($curr_tpl_id);
            if ($curr_tpl->template == DEFAULT_TPL) {
                $this->assign('json', Toolkit::jsonERR(__('Cannot delete default template!')));
                return '_result';
            } else {
                if(!file_exists(ROOT.'/template/'.$curr_tpl->template)){
                    $this->assign('json',ToolKit::jsonERR(__('File does not exist!')));
                    return '_result';
                }
                if (!Toolkit::rmdir_template($curr_tpl->template)) {
                    $this->assign('json', Toolkit::jsonERR(__('Delete template failed!')));
                    return '_result';
                }
                $curr_tpl->delete();
            }
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    private function _requireAdmin() {
        if (!ACL::requireRoles(array('admin'))) {
            $this->assign('json', Toolkit::jsonERR(__('No Permission!')));
            return false;
        }
        return true;
    }
    
    private function _savetplFile($struct_file) {
        return move_uploaded_file($struct_file['tmp_name'], ROOT.'/template/'.$struct_file['name']);
    }

    private function _downloadRemote($tplid) {
        $tpl_path = ROOT.DS.'template';
        
        // Get template info first
        $client =& Toolkit::initSoapClient();
        $tpl_info = unserialize($client->getTplInfo(EZSITE_UID, $tplid));
        if (!$tpl_info) {
            return false;
        }
        
        // Check whether the target download dir is writable
        if (!is_writable($tpl_path)) {
            return false;
        }
        
        // Check whether there is a template with the same name
        $folder_name = ToolKit::get_filename($tpl_info['archive']);
        if(file_exists($tpl_path.DS.$folder_name)) {
            return false;
        }
        
        // Try to download the file
        $remote_file = fopen($tpl_info['package_url'].'&uid='.EZSITE_UID, 'r');
        if (!$remote_file) {
            return false;
        }
        $local_file = fopen($tpl_path.DS.$tpl_info['archive'], 'w');
        while (!feof($remote_file)) {
            fwrite($local_file, 
                fread($remote_file, 4096), 
                4096);
        }
        fclose($local_file);
        fclose($remote_file);
        
        // Download finished. Now extract.
        $tpl_zip = new ZipArchive(); 
        $tpl_zip->open($tpl_path.DS.$tpl_info['archive']); 
        $tpl_zip->extractTo($tpl_path.DS.$folder_name); 
        $tpl_zip->close();
        unlink($tpl_path.DS.$tpl_info['archive']);
        
        if (!file_exists($tpl_path.DS.$folder_name.DS.'conf.php') || 
            !file_exists($tpl_path.DS.$folder_name.DS.'template_info.php')) {
            Toolkit::rmdir_template($folder_name);
            return false;
        }
        
        // Now save template info
        $new_tpl_info = array();
        $o_template = new Template();
        include $tpl_path.DS.$folder_name.DS.'conf.php';
        $new_tpl_info['template'] = $tpl_name;
        $new_tpl_info['template_category_id'] = 999;
        $o_template->set($new_tpl_info);
        $result = $o_template->save();
        
        return $tpl_name;
    }
	*/
}
?>
