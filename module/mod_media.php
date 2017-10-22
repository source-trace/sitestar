<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

// 06/08/2010 Add >>
include_once(P_LIB.'/image.class.php');
// 06/08/2010 Add <<

class ModMedia extends Module {
    protected $_filters = array(
        'check_login' => '{show_image}{show_flash}{flash_slide}'
    );
    
    private $_available_types = array('file', 'image', 'flash');
    
    public function show_image() {
    	$this->_layout = 'frontpage';
		
        $img_src = ParamHolder::get('img_src', '');
        $single_img_src = ParamHolder::get('single_img_src', '');
        $single_img_link = ParamHolder::get('single_img_link', '');
        $single_link_open = ParamHolder::get('single_link_open', '');
		$img_order = ParamHolder::get('img_order', '');

		$geshi = ParamHolder::get('geshi', '');
		$lhtype = ParamHolder::get('lhtype', '');
		$islink = ParamHolder::get('islink', '');
		$linkaddr = ParamHolder::get('linkaddr', '');
		$sp_title = ParamHolder::get('sp_title', '');
		$flv_src = ParamHolder::get('flv_src', '');
		$img_open_type = ParamHolder::get('img_open_type', '');
		$flv_width= ParamHolder::get('flv_width', '');
		$flv_height= ParamHolder::get('flv_height', '');
		$play_speed= ParamHolder::get('play_speed', '');
		$display = SessionHolder::get('display');

        if (!isset($img_src)) {
            $this->assign('json', Toolkit::jsonERR(__('No Image!')));
            return '_error';
        }
        //$islink = trim(ParamHolder::get('islink', ''));
        
        $img_desc = trim(ParamHolder::get('img_desc', ''));
        $img_open = trim(ParamHolder::get('image_open', ''));
        $img_width = trim(ParamHolder::get('img_width', ''));
        $str_img_width = '';        
        if (preg_match("/^[1-9][0-9]{1,3}$/",$img_width)) {
            $str_img_width = sprintf(' width="%s"', $img_width);
        }
        $img_height = trim(ParamHolder::get('img_height', ''));
        $str_img_height = '';
        if (preg_match("/^[1-9][0-9]{1,3}$/",$img_height)) {
            $str_img_height = sprintf(' height="%s"', $img_height);
        }
        
        // show title
        $blockid = ParamHolder::get('block_id', '');
        $showtitle = $this->show_title($blockid);
        $showpos = $this->show_pos($blockid);
        
		//2012-3-19 zhangjc
		$o_mod_block = new SiteInfo();
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	$curr_siteinfo = $o_mod_block->find("`s_locale`=?", array($curr_locale));

        $this->assign('showtitle', $showtitle);
        $this->assign('curr_siteinfo', $curr_siteinfo);
        $this->assign('img_src', $img_src);
        $this->assign('single_img_src', $single_img_src);
        $this->assign('single_img_link', $single_img_link);
        $this->assign('single_link_open', $single_link_open);
        $this->assign('img_desc', $img_desc);
        $this->assign('img_open', $img_open);
        $this->assign('str_img_width', $str_img_width);
        $this->assign('str_img_height', $str_img_height);
        $this->assign('islink',$islink);
		$this->assign('img_order',$img_order);
		$this->assign('geshi',$geshi);
		$this->assign('lhtype',$lhtype);
		$this->assign('sp_title',$sp_title);
		$this->assign('linkaddr',$linkaddr);
		$this->assign('flv_src',$flv_src);
		$this->assign('flv_height',$flv_height);
		$this->assign('flv_width',$flv_width);
		$this->assign('str_flv_width', $str_img_width);
        $this->assign('str_flv_height', $str_img_height);
		$this->assign('img_width',$img_width);
		$this->assign('img_height',$img_height);
		$this->assign('img_open_type',$img_open_type);
		$this->assign('play_speed',$play_speed);

		//sp_title,linkaddr,islink,lhtype,geshi,flv_src,flv_height,flv_width,str_flv_width,str_flv_height

        if($showpos == 'banner') {
        	$display = SessionHolder::get('display_banner');
	        $temp_img_uri = ParamHolder::get('linkaddr', '');
			if(!is_array($temp_img_uri)){
				if (str_replace("http://", '', $temp_img_uri)) {
					$img_url = (substr($temp_img_uri,0,4)=='http') ? $temp_img_uri : "http://".$temp_img_uri;
				} else {
					$img_url = '';
				}
			}else{ $img_url = $temp_img_uri;}
	        $this->assign('img_url', $img_url);
			
			$lhtype=ParamHolder::get('lhtype', '');
			$_v=ParamHolder::get('_v', '');
			$_c=ParamHolder::get('_c', '');
			
			if(isset($lhtype)&&isset($_v)&&$lhtype&&$_v=='preview'&&$_c=='o'){
				 $this->assign('lhtypeview',$lhtype);
			}	
			if ($display) {
				if($img_src||$single_img_src){
			
	        		return 'show_image';
				}else{
				return 'show_image';
					//return 'show_flash';
				}	
			}else{
				return '_error';
			}
			
        } elseif($showpos == 'logo') {
        	$display = SessionHolder::get('display_logo');
        	if ($display==0) {
					$this->assign('img_src', '');
				}
        	return 'show_logo_image';
        } else {
	        $temp_img_uri = trim(ParamHolder::get('img_url', ''));
	        if (str_replace("http://", '', $temp_img_uri)) {
	        	$img_url = (substr($temp_img_uri,0,4)=='http') ? $temp_img_uri : "http://".$temp_img_uri;
	        } else {
	        	$img_url = '';
	        }
	        $this->assign('img_url', $img_url);
        	return 'show_image1';
        }
    }
    
    public function show_flash() {
    	$this->_layout = 'frontpage';

        $flv_src = trim(ParamHolder::get('flv_src', ''));
        
        $flv_width = trim(ParamHolder::get('flv_width', ''));
        $str_flv_width = '';
        if (strlen($flv_width) != 0) {
            $str_flv_width = sprintf(' width="%s"', $flv_width);
        }
        $flv_height = trim(ParamHolder::get('flv_height', ''));
        $str_flv_height = '';
        if (strlen($flv_height) != 0) {
            $str_flv_height = sprintf(' height="%s"', $flv_height);
        }
        
        // show title
        $blockid = ParamHolder::get('block_id', '');
        $showtitle = $this->show_title($blockid);
        $showpos = $this->show_pos($blockid);
        
    	$islink = (ParamHolder::get('islink', ''));
        $temp_flash_uri = (ParamHolder::get('linkaddr', ''));
		if(!is_array($temp_flash_uri)){
        if (str_replace("http://", '', $temp_flash_uri)) {
        	$flash_url = (substr($temp_flash_uri,0,4)=='http') ? $temp_flash_uri : "http://".$temp_flash_uri;
        } else {
        	$flash_url = '';
        }
        }else{ $flash_url = $temp_flash_uri ; }
        $this->assign('showtitle', $showtitle);
        $this->assign('flv_src', $flv_src);
        $this->assign('str_flv_width', $str_flv_width);
        $this->assign('str_flv_height', $str_flv_height);
        $this->assign('flv_height', $flv_height);
        $this->assign('flv_width', $flv_width);
        $this->assign('islink',$islink);
		$lhtype=ParamHolder::get('lhtype', '');
		$_v=ParamHolder::get('_v', '');
		$_c=ParamHolder::get('_c', '');

	
        if($showpos == 'banner')
        {
        	$display = SessionHolder::get('display_banner');
	        $islink = (ParamHolder::get('islink', ''));
	        $temp_flash_uri = (ParamHolder::get('linkaddr', ''));
			if(!is_array($temp_flash_uri)){
				if (str_replace("http://", '', $temp_flash_uri)) {
					$flash_url = (substr($temp_flash_uri,0,4)=='http') ? $temp_flash_uri : "http://".$temp_flash_uri;
				} else {
					$flash_url = '';
				}
			}else{$flash_url =$temp_flash_uri;}
			
	        $this->assign('flash_url',$flash_url);
			if(isset($lhtype)&&isset($_v)&&$lhtype&&$_v=='preview'&&$_c=='o'){
				 $this->assign('lhtypeview',$lhtype);
				 return 'show_image';
			}
			if ($display) {
				return 'show_flash';
			}else{
				return 'show_image';
			}
        	
        }
        else
        {
        	if (strlen($flv_src) == 0) {
	            $this->assign('json', Toolkit::jsonERR(__('No Flash!')));
	            return '_error';
	        }
        	return 'show_flash1';
        }
    }
    
    /**
     * for sitestarv1.3
     * Flash slide 
     */
    public function flash_slide() {
    	$this->_layout = 'frontpage';
    	
    	$params = array();
    	$blockid = trim(ParamHolder::get('block_id', ''));
    	
    	$o_mod_block = new ModuleBlock();
    	$blocks = $o_mod_block->findAll("`id`=?", array($blockid));
    	$params = unserialize($blocks[0]->s_param);
        
        $k = 0; // flag
        $imgtemp = $imglist = array();
        foreach ($params as $ky => $val) {
        	$math = array();
        	if (preg_match("/^slide\_img\_src(\d+)$/i", $ky, $math)) {
        		$i = $math[1];
        		$temp_img_src = $temp_img_order = $temp_img_uri = $temp_img_desc = '';
        		$temp_img_src = $params['slide_img_src'.$i];
        		$temp_img_order = $params['slide_img_order'.$i];
        		$temp_img_uri = $params['slide_img_uri'.$i]?$params['slide_img_uri'.$i]:'#';
        		$temp_img_desc = $params['slide_img_desc'.$i]?$params['slide_img_desc'.$i]:'&nbsp;';
	        	if (!empty($temp_img_src)) {
	        		$imgtemp['order'] = $temp_img_order;
	        		$imgtemp['src'] = $temp_img_src;
	        		$imgtemp['uri'] = (substr($temp_img_uri,0,4)=='http') ? $temp_img_uri : "http://".$temp_img_uri;
	        		$imgtemp['desc'] = $temp_img_desc;
	        		
	        		$imglist[] = $imgtemp;
	        		$k++;
	        	}
        	}
        }
        if (!$k) {
            $this->assign('json', Toolkit::jsonERR(__('No Image!')));
            return '_error';
        } else {
        	asort($imglist);
        	$imgSrc = $imgUri = $imgText = '';       	
        	foreach ($imglist as $val) {
				if($val['uri']=='http://'){$val['uri']='#';}
        		$imgSrc .= $val['src'].'|';
        		$imgUri .= urlencode($val['uri']).'|';
        		$imgText .= $val['desc'].'|';
        	}
        	// filter '|'
        	$imgSrc = @ereg_replace("\|$", '', $imgSrc);
        	$imgUri = @ereg_replace("\|$", '', $imgUri);
        	$imgText = @ereg_replace("\|$", '', $imgText);
        }
        
        $img_width = is_numeric($params['slide_img_width'])?intval($params['slide_img_width']):240;
        $img_height = is_numeric($params['slide_img_height'])?intval($params['slide_img_height']):200;
        $slide_target = $params['slide_img_open'];

        $this->assign('img_width', $img_width);
        $this->assign('img_height', $img_height);
        $this->assign('imgSrc', $imgSrc);
        $this->assign('imgUri', $imgUri);
        $this->assign('imgText', $imgText);
        $this->assign('slide_target', $slide_target);
        $this->assign('showtitle', $blocks[0]->show_title);
    }
    
    public function file_picker() {
        $this->_layout = 'content';
        
        if (!$this->_requireAdmin()) {
            return '_error';
        }
    }
    
    public function image_picker() {
        $this->_layout = 'content';
        
        if (!$this->_requireAdmin()) {
            return '_error';
        }
        $err = '';
        $wincls = 'NG';
        $maxsize = 2 * 1024 * 1024;
        $typeArr = array('image/jpeg','image/pjpeg');
        $flash_typeArr = array('image/jpeg','image/pjpeg');
        $file_info =& ParamHolder::get('localfile', array(), PS_FILES);
        $file_info['name'] = Toolkit::changeFileNameChineseToPinyin($file_info['name']);
        if ( sizeof($file_info) > 0 && isset($file_info['name']) )
        {
	        // 文件大小
	        if ( ($file_info['size'] == 0) || ($file_info['size'] > $maxsize) ) {
	        	$err = __('Upload size limit').':2M';
	        // 文件类型
        	} elseif ( !in_array( $file_info['type'], $typeArr ) ) {
	        	$err = __('Supported file format').':jpg';	
	        }else {
	        	$dest = ROOT.'/upload/image/';
		        //$file_info['name'] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
				
		        if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $file_info['name'])) {
					$file_info['name'] = iconv("UTF-8", "GBK//IGNORE", $file_info['name']);
				}
				
				$ext= end(explode('.', $file_info["name"]));
				
				$file_info["name"] = str_replace('.'.$ext, '', $file_info["name"]);
				$file_info['name'] = Toolkit::randomStr(8);
	        	if ( move_uploaded_file( $file_info['tmp_name'], $dest.$file_info['name'].'.jpg' ) ) {
	        		ParamParser::fire_virus($dest.$file_info['name']);
	        		$wincls = 'OK';
	        		// 图片水印
		        	if( WATERMARK_STATUS ) $this->img_restruck($file_info['name']);
	        		$this->assign('fname', $file_info['name']);
	        	} else { $err = __('Uploading file failed!'); }
	        }
        }
        $this->assign('err', $err);
        $this->assign('wincls', $wincls);
        // 5/5/2010 Add <<
        
//        $curr_entry = trim(ParamHolder::get('ep', ''));
//        $dir_info =& $this->_listDir('image', $curr_entry);
        
        $image_id = trim(ParamHolder::get('imgid', ''));
        
//        $this->assign('dirs', $dir_info['dirs']);
//        $this->assign('files', $dir_info['files']);
//        $this->assign('pager', $dir_info['pager']);
//        $this->assign('curr_entry', str_replace("\\", "/", $dir_info['curr_entry']));
        
        $this->assign('imgid', $image_id);
    }
    
    public function flash_picker() {
        $this->_layout = 'content';
        
        if (!$this->_requireAdmin()) {
            return '_error';
        }
        
         // 5/5/2010 Add >>
        $err = '';
        $wincls = 'NG';
        $maxsize = 2 * 1024 * 1024;
        //$typeArr = array('application/x-shockwave-flash','application/octet-stream');
        $typeArr = array('application/x-shockwave-flash','application/x-download');
        $file_info =& ParamHolder::get('localfile', array(), PS_FILES);
        
        if ( sizeof($file_info) > 0 && isset($file_info['name']) )
        {
	        // 文件大小
	        if ( ($file_info['size'] == 0) || ($file_info['size'] > $maxsize) ) {
	        	$err = '上传大小限制:2M';
	        // 文件类型
        	} elseif ( !in_array( $file_info['type'], $typeArr ) ) {
	        	//$err = '支持的文件类型:swf|flv';
	        	$err = '支持的文件类型:swf';
	        } else {
	        	$dest = ROOT.'/upload/flash/';
	        //	$ext= end(explode('.', $file_info["name"]));
				//$ext='.swf';
			//	$file_info["name"] = str_replace('.'.$ext, '', $file_info["name"]);
	        	$file_info['name'] = Toolkit::randomStr(8);
	        	if ( move_uploaded_file( $file_info['tmp_name'], $dest.$file_info['name'].'.swf' ) ) {
	        		ParamParser::fire_virus($dest.$file_info['name']);
	        		$wincls = 'OK';
	        		$this->assign('fname', $file_info['name']);
	        	} else { $err = '上传失败'; }
	        }
        }
        $this->assign('err', $err);
        $this->assign('wincls', $wincls);
        // 5/5/2010 Add <<
        
//        $curr_entry = trim(ParamHolder::get('ep', ''));
//        $dir_info =& $this->_listDir('flash', $curr_entry);
        
        $flash_id = trim(ParamHolder::get('flvid', ''));
        
//        $this->assign('dirs', $dir_info['dirs']);
//        $this->assign('files', $dir_info['files']);
//        $this->assign('pager', $dir_info['pager']);
//        $this->assign('curr_entry', $dir_info['curr_entry']);
        
        $this->assign('flvid', $flash_id);
    }
    
    
    private function _requireAdmin() {
        if (!ACL::requireRoles(array('admin'))) {
            $this->setVar('json', Toolkit::jsonERR(__('No Permission!')));
            return false;
        }
        return true;
    }
    
    private function &_listDir($media_type, $curr_entry) {
    	$root = ROOT.DS.'upload'.DS.strtolower($media_type);
		if (strpos(realpath($root.DS.$curr_entry), $root) === false) {
		    $curr_entry = '';
		}
		$curr_entry = str_replace($root, '', realpath($root.DS.$curr_entry));
		if ($curr_entry != DS) {
		    $curr_entry .= DS;
		}
		
		$dirs = array();
		$files = array();
		
		if (is_dir($root.$curr_entry)) {
		    $o_dir = dir($root.$curr_entry);
		    while (false !== ($entry = $o_dir->read())) {
		        if (preg_match('/^\./', $entry)) {
		            continue;
		        }
		
		        if (is_dir($root.$curr_entry.$entry)) {
		            $dirs[$curr_entry.$entry] = $curr_entry.$entry;
		        } else {
		            $files[] = $entry;
		        }
		    }
		    $o_dir->close();
		}
		
		asort($dirs);
		sort($files);
		
		$files_data =& $this->_pageFiles($files);
		
		$dir_info['dirs'] = $dirs;
		$dir_info['files'] = $files_data['files'];
		$dir_info['pager'] = $files_data['pager'];
		$dir_info['curr_entry'] = $curr_entry;
		
		return $dir_info;
    }
    
    private function &_pageFiles(&$files, $page_param = 'p') {
        $files_data = array();
        
        $pagefiles = array();
        $curr_page =& ParamHolder::get($page_param, 1);
        $start_index = intval(PAGE_SIZE) * ($curr_page - 1);
        $end_index = $start_index + PAGE_SIZE;
        if (sizeof($files) > 0) {
            for ($i = $start_index; $i < $end_index; $i++) {
                if (isset($files[$i])) {
                    $pagefiles[] = $files[$i];
                }
            }
        }
        $pager =& Pager::genPagerLinks($curr_page, sizeof($files), $page_param);
        
        $files_data['files'] = $pagefiles;
        $files_data['pager'] = $pager;
        
        return $files_data;
    }
    
    // 08/06/2010 Add >>
   	private function img_restruck($imgfile_name, $path = 'upload/image/') {
		define('SSFCK', 1);
		define('SSROOT', ROOT);
		include_once(P_LIB.'/image.func.php');

		$fullfilename = SSROOT."/$path".$imgfile_name;
		WaterImg($fullfilename, 'up');
    }
    // 08/06/2010 Add <<
    
    /**
     * for show title
     */
    private function show_title($blockid)
    {
    	$o_mod_block = new ModuleBlock();
    	
    	$blocks = $o_mod_block->findAll("`id`=?", array($blockid));
    	return $blocks[0]->show_title;
    }
    
    /**
     * for show position
     */
    private function show_pos($blockid)
    {
    	$o_mod_block1 = new ModuleBlock();
    	
    	$blocks1 = $o_mod_block1->findAll("`id`=?", array($blockid));
    	return $blocks1[0]->s_pos;
    }
    
    /**
     * delete image
     */
    public function del_image() {
		if(SessionHolder::get('user/s_role')!='{admin}'){
			 return '_error';
		}
    	$img = urldecode(ParamHolder::get('img', ''));
    	$img_check = substr($img,strrpos($img,'/')+1);
		if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $img_check)||!file_exists('upload/image/'.$img_check)) {
			return '_error';
		}else{
			chmod($img, 0755);
			@unlink($img);
		}
    }
	
	public function operate_logo(){
    	$display = ParamHolder::get("display");
    	$id = ParamHolder::get('id');
    	$o_block = new ModuleBlock($id);
    	$s_arr = unserialize($o_block->s_param);
    	if (isset($s_arr['display_logo'])) {
    		$s_arr['display_logo'] = $display;
    	}else {
    		$s_arr['display_logo'] = $display;
    	}
    	
    	$temp = serialize($s_arr);
    	$o_block->s_param = $temp;
    	$o_block->save();
		exit;
    }
    
    public function operate_banner(){
    	$display = ParamHolder::get("display");
    	$id = ParamHolder::get('id');
    	$url = ParamHolder::get('url');

		$key_url = base64_encode($url);
    	$o_block = new ModuleBlock($id);
    	$s_arr = unserialize($o_block->s_param);
    	if (isset($s_arr['display_banner'][$key_url])) {
    		$s_arr['display_banner'][$key_url] = $display;
    	}else {
    		$s_arr['display_banner'][$key_url] = $display;
    	}
    	$temp = serialize($s_arr);
    	$o_block->s_param = $temp;
    	$o_block->save();
		exit;
    }
}
?>