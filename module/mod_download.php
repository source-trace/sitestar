<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModDownload extends Module {
    protected $_filters = array(
        'check_login' => '{fullist}{download}{recentdownloads}'
    );


    public function fullist() {
        $this->_layout = 'frontpage';
        $curr_locale = trim(SessionHolder::get('_LOCALE'));

        /* the default download category */
        $curr_download_category = new DownloadCategory();
        $curr_download_category->name = __('Downloads');

        $cad_id = trim(ParamHolder::get('cad_id', '0'));
        if(MOD_REWRITE=='3'){
        	$_path=strtolower(ParamHolder::get('_path', ''));
        	$o_url = new MenuItem();
        	if (strrpos($_path,'p-')) {
					//存在分页
				$use_str = substr($_path,0,strrpos($_path,'p')-1);
				$url_res = $o_url->find('url=? and s_locale=?',array($use_str,$curr_locale));
        	}else{
        		 $url_res = $o_url->find('url=? and s_locale=?',array($_path,$curr_locale));
        	}
	        if (!empty($url_res)) {//用户自定义的链接处理
	        	$u_link = $url_res->link;
	        	$cad_id = substr($u_link,strrpos($u_link,'=')+1);//取类别ID
	        }else{
	        	include(P_INC.'/custom.php');
	        	$arr = explode("-",$_path);
	        	$cad_id = $arr[1];
	        	$cad_id = str_replace('_',',',$cad_id);//适应伪静态
	        	
	        }
        }else{
        	$cad_id = trim(ParamHolder::get('cad_id', '0'));
        	$cad_id = str_replace('_',',',$cad_id);//适应伪静态
        }
        // No category
//        $cad_id = '0';
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        $curr_locale = trim(SessionHolder::get('_LOCALE'));

        $search_where = '';
        $search_params = array();
        $download_keyword = trim(ParamHolder::get('download_keyword', '',PS_POST))?Toolkit::baseEncode(trim(ParamHolder::get('download_keyword', '',PS_POST))):trim(ParamHolder::get('download_keyword', '',PS_GET));      
        $download_keyword = Toolkit::baseDecode($download_keyword);
        if (strlen($download_keyword) > 0) {
            $search_where = ' AND (name LIKE ? OR description LIKE ?)';
            $search_params = array('%'.$download_keyword.'%', '%'.$download_keyword.'%');
            $this->assign('download_keyword', $download_keyword);
        }
        if (intval($cad_id) > 1) {
            $search_where .= ' AND download_category_id=?';
            $search_params = array_merge($search_params, array($cad_id));
            $curr_download_category = new DownloadCategory($cad_id);
        }
        try {
            $now = time();
            $o_download = new Download();
            /**
             * Add 02/08/2010
             */
            include_once(P_LIB.'/pager.php');
            
            if (ACL::requireRoles(array('admin'))) {
                $download_data =&
                    Pager::pageByObject('download',
                        "((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND s_locale=?".$search_where,
                        array_merge(array($now, $now, $now, $now, $curr_locale), $search_params),
                        "ORDER BY `create_time` DESC");
            } else {
                $download_data =&
                    Pager::pageByObject('download',
                        "((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND for_roles LIKE ? AND s_locale=?".$search_where,
                        array_merge(array($now, $now, $now, $now, '%'.$user_role.'%', $curr_locale), $search_params),
                        "ORDER BY `create_time` DESC");
            }

           $this->assign('page_title', $curr_download_category->name);

            $this->assign('downloads', $download_data['data']);
            $this->assign('pager', $download_data['pager']);
            $this->assign('page_mod', $download_data['mod']);
			$this->assign('page_act', $download_data['act']);
			$this->assign('page_extUrl', $download_data['extUrl']);
            $this->assign('category', $curr_download_category);
            $this->assign('download_keyword', $download_keyword);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

    }

    public function recentdownloads() {
        $list_size = trim(ParamHolder::get('down_reclst_size'));
        $download_category_list = trim(ParamHolder::get('download_category_list'));
        if (strstr($download_category_list,",")) {
        	$download_category_list = explode(",",$download_category_list);
        }else{
        	$download_category_list = (array)$download_category_list;
        }
        
        if (!is_numeric($list_size) || strlen($list_size) == 0) {
            $list_size = '5';
        }
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        $o_download = new Download();
        foreach ($download_category_list as $id){
	        if (ACL::requireRoles(array('admin'))) {
	                $downloads[] = $o_download->findAll("published='1' AND s_locale=? and download_category_id=? ", array($curr_locale,$id), "ORDER BY `create_time` DESC LIMIT ".$list_size);
	        } else {
	                $downloads[] = $o_download->findAll("published='1' AND s_locale=? AND for_roles LIKE ? and download_category_id=? ", array($curr_locale, '%'.$user_role.'%',$id),"ORDER BY `create_time` DESC LIMIT ".$list_size);
	        }
        }
        foreach ($downloads as $k=>$v){
        	foreach ($v as $k2=>$v2){
        	$dow[] = $v2;
        	}
        }
        $this->assign('downloads', $dow);
    }
    
    public function download() {
        $this->_layout = 'null';
        $file_name = ParamHolder::get('file_name', '0');
		 if(MOD_REWRITE=='3'){
        	include(P_INC.'/custom.php');
        	$_path=strtolower(ParamHolder::get('_path', ''));
        	$arr = explode("-",$_path);
        
        	$dw_id = intval($arr[1]);
        }else{
        $dw_id = ParamHolder::get('dw_id', '0');
		}
        if (intval($dw_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        try {
        	$now = time();
            $o_dw = new Download();
            if (!ACL::isRoleAdmin()) {
                $curr_dw =& $o_dw->find("`id`=? AND "
                            ."((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND for_roles LIKE ? AND s_locale=?",
                        array($dw_id, $now, $now, $now, $now, '%'.$user_role.'%', $curr_locale));
            } else {
                $curr_dw =& $o_dw->find("`id`=? AND "
                            ."((`pub_start_time`<? AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`<? AND `pub_end_time`='-1') OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`>=?) OR "
                            ."(`pub_start_time`='-1' AND `pub_end_time`='-1')) AND "
                            ."published='1' AND s_locale=?",
                        array($dw_id, $now, $now, $now, $now, $curr_locale));
            }
            if(sizeof($curr_dw) <= 0) {
                $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
                return '_error';
            }
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
        if (!file_exists(ROOT.'/upload/file/'.$curr_dw->name)) {
            $this->assign('json', Toolkit::jsonERR(__('File does not exist!')));
            return '_error';
        }
		include_once P_LIB."/download.php";
		$o_filedownload = new file_download();
		$o_filedownload->downloadfile(ROOT.'/upload/file/'.$curr_dw->name);
        //$this->file_download(ROOT.'/upload/file/'.$curr_dw->name);
    }

    private function file_download($file_path) {
		$path_parts = pathinfo($file_path);
		$file_dir = $path_parts["dirname"].'/';
		$file_name = $path_parts["basename"];
		$file_ext = $path_parts["extension"];
		$file = fopen($file_dir.$file_name,"r");
		header( "Pragma: public" );
		header( "Expires: 0" );
		header( "Cache-Component: must-revalidate, post-check=0, pre-check=0" );
		header( "Content-type:application/octet-stream");
		header( "Content-Length: " . filesize($file_name));
		header( "Content-Disposition: attachment; filename=$file_name" );
		header( 'Content-Transfer-Encoding: binary' );
		readfile($file_dir.$file_name);
}

}
?>
