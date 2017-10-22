<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModMedia extends Module {
	
	protected $_filters = array(
        'check_admin' => ''
    );

    /*
     * 编辑banner在当前页面显示
     */
    
    public function admin_banner() {
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_site/_LOCALE', $lang_sw);
           
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
        
        
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('lang_sw', $lang_sw);
		
		$o_mb = new ModuleBlock();
		
		$curr_banner = $o_mb->find("s_locale=? and s_pos=? and module=?",array($lang_sw,'banner','mod_media'));
		$this->assign('curr_banner', $curr_banner);
		$this->assign('p_banner', unserialize($curr_banner->s_param));
    }
    
    /*
     * 编辑 banner在所有页面显示
     */
    public function admin_banner1()
    {
    	$this->_layout = 'content';
    	
    	$curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_static/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
        
    	$o_mb = new ModuleBlock();
		$curr_banner = $o_mb->find("s_locale=? and s_pos=? and module=?",array($lang_sw,'banner','mod_media'));
		$this->assign('curr_banner', $curr_banner);
		$this->assign('p_banner', unserialize($curr_banner->s_param));
    }
    
    public function admin_logo() {
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_site/_LOCALE', $lang_sw);
        
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
		
        $o_mb = new ModuleBlock();
		$curr_logo = $o_mb->find("s_locale=? and alias=? and module=? and action=?",array($lang_sw,'mb_logo','mod_media','show_image'));
		$this->assign('curr_logo', $curr_logo);
		$this->assign('p_logo', unserialize($curr_logo->s_param));
    }
    
    public function save_logo() {
    	$this->_layout = 'content';
    	$site_info =& ParamHolder::get('si', array());

        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        $logo_info =& ParamHolder::get('logo', array());
		$param_info =& ParamHolder::get('param', array());
		$logo_file =& ParamHolder::get('logo_file', array(), PS_FILES);
		$logo_file['name'] = Toolkit::changeFileNameChineseToPinyin($logo_file['name']);
		
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
		$logo_info['for_roles'] = '{member}{admin}{guest}';
    	if($logo_file['name']) {//有此图片上传
				if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $logo_file["name"])) {
					if (!preg_match('/\.(swf)$/i', $logo_file["name"])) {//添加支持flash格式文件的logo
						Notice::set('mod_static/msg', __('File type error!'));
						die(__('File type error!'));
						Content::redirect(Html::uriquery('mod_static', 'admin_mod'));
					}
					
				}
				if(file_exists(ROOT.'/upload/image/'.$logo_file["name"])) {
					$logo_file["name"] = Toolkit::randomStr(8).strrchr($logo_file["name"],".");
				}
				if (!$this->_savelinkimg($logo_file)) {
					Notice::set('mod_static/msg', __('Link image upload failed!'));
					Content::redirect(Html::uriquery('mod_static', 'admin_mod'));
				}
				$logo_arr['img_src'] = 'upload/image/'.$logo_file["name"];
				/*$xml = new DOMDocument('1.0','utf-8');
				$xml->load('SitestarMaker/SitestarMaker.xml');
				$xml->getElementsByTagName('custom')->item(0)->nodeValue = 'no';
				$xml->save('SitestarMaker/SitestarMaker.xml');*/
				/**
				 * 删除编辑前的对应图片文件
				 */
				 if (file_exists(ROOT.'/'.$param_info['logo_img'])) {
				 	 //unlink(ROOT.'/'.$param_info['logo_img']);
				 }
			} else {//无此图片上传
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
			} else {//无论有无图片上传,走次
				 $o_logo = new ModuleBlock($logo_info['id']);
				 $o_logo->set($logo_info);
				 $o_logo->save();
			}
			echo "<script>alert('".__('Edit Success')."'); window.parent.location.reload();</script>";
			exit;
			//Content::redirect("index.php?_m=mod_media&_a=admin_logo&_r=_page");
    }
    
     public function save_banner() 
    {
    	$this->_layout = 'content';
    	
    	$site_info =& ParamHolder::get('si', array());
    	$isLink = & ParamHolder::get('isLink', array());
		$LinkAddr = & ParamHolder::get('LinkAddr', array());
		$title = & ParamHolder::get('title');
		$lhtype= & ParamHolder::get('lhtype', array());
		$sp_title = & ParamHolder::get('sp_title', array());
		$addv= & ParamHolder::get('addv', array());
		$ex_params= & ParamHolder::get('ex_params', array());
		$single_image = & ParamHolder::get('single_image','');
		$single_link = & ParamHolder::get('imglink','');
		$single_link_open = & ParamHolder::get('img_link_open','');
		$img_order= & ParamHolder::get('img_order', array());
		$delv= & ParamHolder::get('delv', array());
		$play_speed = ParamHolder::get("play_speed","5000");
		$single_link = strlen($single_link)>7?$single_link:'';
		
		ParamHolder::get('link_open1', array())!=''?$img_open_type[1] = & ParamHolder::get('link_open1', array()):'';
		ParamHolder::get('link_open2', array())!=''?$img_open_type[2] = & ParamHolder::get('link_open2', array()):'';
		ParamHolder::get('link_open3', array())!=''?$img_open_type[3] = & ParamHolder::get('link_open3', array()):'';
		ParamHolder::get('link_open4', array())!=''?$img_open_type[4] = & ParamHolder::get('link_open4', array()):'';
		ParamHolder::get('link_open5', array())!=''?$img_open_type[5] = & ParamHolder::get('link_open5', array()):'';
		
		$geshi= & ParamHolder::get('geshi', array());		
		$delall= & ParamHolder::get('delall', array());		
		$b= & ParamHolder::get('b', array());
		$realnum= & ParamHolder::get('realnum', array());	
		$barry=explode(" ",$b) ;
		
		//获取上传页面的url参数，用来区分不同页面不同banner
		$current_url_arr = & ParamHolder::get('getParams1', array());
		$current_url_arr = unserialize($current_url_arr);
		$current_url_str='';
		if(!empty($current_url_arr))
		{
			if(($current_url_arr['_m'] == 'frontpage' && empty($current_url_arr['_a'])) || ($current_url_arr['_a'] == 'index' && empty($current_url_arr['_m'])))
			{
				$current_url_str = "_m=frontpage&_a=index";
			}
			else
			{
				foreach($current_url_arr as $k => $v)
				{
					if($k == '_l' || $k == '_v') continue;
					$current_url_str .= "{$k}={$v}&";
				}
				$current_url_str = substr($current_url_str, 0,strlen($current_url_str)-1);
			}
		}
		else
		{
			$current_url_str = "_m=frontpage&_a=index";
		}
		if(MOD_REWRITE == 3){
			$current_url_str = Content::static3link($current_url_arr['_path']);
		}
    	if (sizeof($site_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing site information!')));
            return '_result';
        } 
       	$flag_flash = false;
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_static/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        $param_info =& ParamHolder::get('param', array());
        $banner_info =& ParamHolder::get('banner', array());
		$banner_file =& ParamHolder::get('banner_file', array(), PS_FILES);		
		$banner_file['name'] = Toolkit::changeFileNameChineseToPinyin($banner_file['name']);
		$banner_arr = array();
		if($geshi==3){
			
			if ($single_image=='') {											
				echo "<script>alert('".__("Upload file can not empty")."');location.href='index.php?_m=mod_media&_a=admin_banner';</script>";
				exit;
			}
		}
	
		//////////flash///////////

    	if(!empty($banner_file['name']))
		{		
			if(strpos($banner_file['name'],'swf') && $geshi==2)
			{
//				$banner_info['action'] = 'show_flash';//现统一取show_image作为默认方式
				$flag_flash = true;
			
			}else{
				echo "<script>alert('".__("Upload file must be flash")."');location.href='index.php?_m=mod_media&_a=admin_banner';</script>";
			}
					
			if($banner_file['name']) 
			{
				$pic_allow_ext = PIC_ALLOW_EXT.'|swf';
				if(!preg_match('/\.('.$pic_allow_ext.')$/i', $banner_file["name"])) {
					Notice::set('mod_static/msg', __('File type error!'));
					die(__('File type error!'));
				}

				if($flag_flash)
				{
					if(file_exists(ROOT."/upload/flash/".$banner_file["name"])) {
						$banner_file["name"] = Toolkit::randomStr(8).strrchr($banner_file["name"],".");
					}
					if (!$this->_savelinkflash($banner_file)) {
						die(__('Link flash upload failed!'));
					}		
					$banner_arr[$current_url_str]['flv_src'] = 'upload/flash/'.$banner_file["name"]; 
					$banner_arr[$current_url_str]['action'] = 'show_flash';
				}
			} 
		}
		$addv=$realnum;
		
		if($addv){	
			$banner_info['module'] = 'mod_media';
			if($geshi=="2"){
				$banner_info['action'] = 'show_flash';
			}else{
				$banner_info['action'] = 'show_image';
			}
			$banner_info['alias'] = 'mb_banner';			
			$banner_info['title'] = $title;
			$banner_info['show_title'] = 0;			
			$banner_info['s_pos'] = 'banner';
			$banner_info['s_locale'] = $lang_sw;			
			$banner_info['s_query_hash'] = '_ALL';
			$banner_info['i_order'] = 0;
			$banner_info['published'] = 1;		
			$banner_info['for_roles'] = '{member}{admin}{guest}';		
			$banner_arr[$current_url_str]['action'] = 'show_image';			
			$banner_arr[$current_url_str]['lhtype'] = $lhtype;
			$banner_arr[$current_url_str]['play_speed'] = $play_speed;
			$banner_arr[$current_url_str]['flv_width'] = $banner_arr[$current_url_str]['img_width'] = $param_info['banner_width'];
			$banner_arr[$current_url_str]['flv_height'] = $banner_arr[$current_url_str]['img_height'] = $param_info['banner_height'];				
			$res_arr = array();
			if(!$banner_info['id']){
				$o_banner = new ModuleBlock();
			} else {
				$o_banner = new ModuleBlock($banner_info['id']);
				$res_arr = unserialize($o_banner->s_param);
			}		
			$kk=0;
			$sp_titlex = array();
			$LinkAddrx = array();
			$isLinkx = array();
			$ex_paramsx = array();
			$img_orderx = array();
			if (!empty($ex_params[0])) {
				for($kk=1;$kk<$realnum+1;$kk++){
					if($barry[$kk]=="1"&&$ex_params[$kk-1]){
						$sp_titlex[]=$sp_title[$kk-1];
						$LinkAddrx[]=$LinkAddr[$kk-1];
						$isLinkx[]=$isLink[$kk-1];
						$ex_paramsx[]=$ex_params[$kk-1];
						$img_orderx[]=$img_order[$kk-1];
					}
				}
				if (!empty($single_image)) {
					$res_arr[$current_url_str]['single_img_src']=$single_image;
					$res_arr[$current_url_str]['single_img_link']=$single_link;
					$res_arr[$current_url_str]['single_link_open']=$single_link_open;
				}
			}else {
				if (!empty($single_image)) {
					$res_arr[$current_url_str]['single_img_src']=$single_image;
					$res_arr[$current_url_str]['single_img_link']=$single_link;
					$res_arr[$current_url_str]['single_link_open']=$single_link_open;
				}
			}
			if($ex_paramsx){
				$res_arr[$current_url_str]['sp_title']=$sp_titlex;
				$res_arr[$current_url_str]['linkaddr']=$LinkAddrx;
				$res_arr[$current_url_str]['islink']=$isLinkx;
				$res_arr[$current_url_str]['img_src']=$ex_paramsx;
				$res_arr[$current_url_str]['lhtype']=$lhtype;
				$res_arr[$current_url_str]['img_order']=$img_orderx;
			}
			
			$res_arr[$current_url_str]['geshi']=$geshi;
			$res_arr[$current_url_str]['img_open_type']=$img_open_type;			
			$res_arr[$current_url_str]['img_width']=$banner_arr[$current_url_str]['img_width'];
			$res_arr[$current_url_str]['flv_width']=$banner_arr[$current_url_str]['flv_width'];
			$res_arr[$current_url_str]['img_height']=$banner_arr[$current_url_str]['img_height'];
			$res_arr[$current_url_str]['flv_height']=$banner_arr[$current_url_str]['flv_height'];
			$res_arr[$current_url_str]['play_speed']=$banner_arr[$current_url_str]['play_speed'];
			$res_arr[$current_url_str]['action']=$banner_info['action'];
			if(!empty($banner_file['name'])){
				$res_arr[$current_url_str]['flv_src']=$banner_arr[$current_url_str]['flv_src'];
			}

			if($delall==9){unset($res_arr[$current_url_str]);}else{
				if(empty($banner_file['name'])&&!$ex_paramsx&&$geshi!=3){
				 unset($res_arr[$current_url_str]);
				}
				
			}
			
			$banner_info['s_param'] = serialize($res_arr);		
			$o_banner->set($banner_info);
			$o_banner->save();
		}
    }
    
    public function save_banner1()
    {
    	$this->_layout = 'content';
		//是否强制替换banner
    	$overfast =& ParamHolder::get('radio', array());
		
    	$site_info =& ParamHolder::get('si', array());
    	$isLink = & ParamHolder::get('isLink', array());
		$LinkAddr = & ParamHolder::get('LinkAddr', array());
		$title = & ParamHolder::get('title');
		$lhtype= & ParamHolder::get('lhtype', array());
		$sp_title = & ParamHolder::get('sp_title', array());
		$addv= & ParamHolder::get('addv', array());
		$ex_params= & ParamHolder::get('ex_params', array());
		$single_image = & ParamHolder::get('single_image','');
		$single_link = & ParamHolder::get('imglink','');
		$single_link_open = & ParamHolder::get('img_link_open','');
		$img_order= & ParamHolder::get('img_order', array());
		$delv= & ParamHolder::get('delv', array());
		$play_speed = ParamHolder::get("play_speed","5000");

		$single_link = strlen($single_link)>7?$single_link:'';
		ParamHolder::get('link_open1', array())!=''?$img_open_type[1] = & ParamHolder::get('link_open1', array()):'';
		ParamHolder::get('link_open2', array())!=''?$img_open_type[2] = & ParamHolder::get('link_open2', array()):'';
		ParamHolder::get('link_open3', array())!=''?$img_open_type[3] = & ParamHolder::get('link_open3', array()):'';
		ParamHolder::get('link_open4', array())!=''?$img_open_type[4] = & ParamHolder::get('link_open4', array()):'';
		ParamHolder::get('link_open5', array())!=''?$img_open_type[5] = & ParamHolder::get('link_open5', array()):'';
		$geshi= & ParamHolder::get('geshi', array());
		
		if($geshi==3){
			if ($single_image=='') {											
				echo "<script>alert('".__("Upload file can not empty")."');location.href='index.php?_m=mod_media&_a=admin_banner1';</script>";
				exit;
			}
		}
		
		$delall= & ParamHolder::get('delall', array());
		
		$b= & ParamHolder::get('b', array());
		$realnum= & ParamHolder::get('realnum', array());

	
		$barry=explode(" ",$b) ;
		
		$current_url_str = '_all';		
		
    	if (sizeof($site_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing site information!')));
            return '_result';
        }
     
       	$flag_flash = false;
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_static/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        $param_info =& ParamHolder::get('param', array());
        $banner_info =& ParamHolder::get('banner', array());
		
		$banner_file =& ParamHolder::get('banner_file', array(), PS_FILES);
		$banner_file['name'] = Toolkit::changeFileNameChineseToPinyin($banner_file['name']);
    		$banner_arr = array();
		
		//////////flash///////////

    	if(!empty($banner_file['name']))
		{		
			if(strpos($banner_file['name'],'swf'))
			{
//				$banner_info['action'] = 'show_flash';//现统一取show_image作为默认方式
				$flag_flash = true;
			
			}
			
			if($banner_file['name']) 
			{
				$pic_allow_ext = PIC_ALLOW_EXT.'|swf';
				if(!preg_match('/\.('.$pic_allow_ext.')$/i', $banner_file["name"])) {
					Notice::set('mod_static/msg', __('File type error!'));
					die(__('File type error!'));
				}

				if($flag_flash)
				{
					if(file_exists(ROOT."/upload/flash/".$banner_file["name"])) {
						$banner_file["name"] = Toolkit::randomStr(8).strrchr($banner_file["name"],".");
					}
					if (!$this->_savelinkflash($banner_file)) {
//						Notice::set('mod_static/msg', __('Link flash upload failed!'));
						die(__('Link flash upload failed!'));
					}
		
					$banner_arr[$current_url_str]['flv_src'] = 'upload/flash/'.$banner_file["name"]; 
					$banner_arr[$current_url_str]['action'] = 'show_flash';
				}

			} 
				

		}
		
		//////////flash//////////

		  $addv=$realnum;
		  if($addv){
		 
			
			
			$banner_info['module'] = 'mod_media';
			if($geshi=="2"){
			$banner_info['action'] = 'show_flash';
			}else{
			$banner_info['action'] = 'show_image';
			}
			$banner_info['alias'] = 'mb_banner';
			
			$banner_info['title'] = $title;
			/* if(!$banner_info['id']){
				$o_banner = new ModuleBlock();

			} else {
				$o_banner = new ModuleBlock($banner_info['id']);
				$res_arr = unserialize($o_banner->s_param);

			}
			
			$banner_info['s_param'] = serialize($res_arr);
			print_r($banner_info);
		echo 'c';
			$o_banner->set($banner_info);
			$o_banner->save();
	exit;
					*/
			$banner_info['show_title'] = 0;
			
			$banner_info['s_pos'] = 'banner';
			$banner_info['s_locale'] = $lang_sw;
			
				
			$banner_info['s_query_hash'] = '_ALL';
			$banner_info['i_order'] = 0;
			$banner_info['published'] = 1;
			
		
			
			$banner_info['for_roles'] = '{member}{admin}{guest}';		
			$banner_arr[$current_url_str]['action'] = 'show_image';
	
			
			//isLink,LinkAddr,lhtype,sp_title
			
			$banner_arr[$current_url_str]['lhtype'] = $lhtype;
			$banner_arr[$current_url_str]['flv_width'] = $banner_arr[$current_url_str]['img_width'] = $param_info['banner_width'];
			$banner_arr[$current_url_str]['flv_height'] = $banner_arr[$current_url_str]['img_height'] = $param_info['banner_height'];
			$banner_arr[$current_url_str]['play_speed'] = $play_speed;
		
			
			$res_arr = array();
			if(!$banner_info['id']){
				$o_banner = new ModuleBlock();

			} else {
				$o_banner = new ModuleBlock($banner_info['id']);
				$res_arr = unserialize($o_banner->s_param);

			}
			$display_banner = $res_arr['display_banner'];
			//echo '<hr>';
			if($overfast['overfast']=='1'){
				foreach($res_arr as $_k=>$_v){
					if($_k!='_all'){
						unset($res_arr[$_k]);
					}
				}
			}
			$kk=0;
			$sp_titlex = array();
			$LinkAddrx = array();
			$isLinkx = array();
			$ex_paramsx = array();
			$img_orderx = array();
			
			/*
			for($kk=0;$kk<5;$kk++){
				if(($addv-1)>=$kk){
						if($kk!=$delv&&$ex_params[$kk]){
							$sp_titlex[]=$sp_title[$kk];
							$LinkAddrx[]=$LinkAddr[$kk];
							$isLinkx[]=$isLink[$kk];
							$ex_paramsx[]=$ex_params[$kk];
							$img_orderx[]=$img_order[$kk];
						}
					}
			}
			*/
			for($kk=1;$kk<$realnum+1;$kk++){
	
				if($barry[$kk]=="1"&&$ex_params[$kk-1]){
					
							$sp_titlex[]=$sp_title[$kk-1];
							$LinkAddrx[]=$LinkAddr[$kk-1];
							$isLinkx[]=$isLink[$kk-1];
							$ex_paramsx[]=$ex_params[$kk-1];
							$img_orderx[]=$img_order[$kk-1];
	
					}
					
			}
			if (!empty($single_image)) {
					$res_arr[$current_url_str]['single_img_src']=$single_image;
					$res_arr[$current_url_str]['single_img_link']=$single_link;
					$res_arr[$current_url_str]['single_link_open']=$single_link_open;
				}
			if($ex_paramsx){
					$res_arr[$current_url_str]['sp_title']=$sp_titlex;
					$res_arr[$current_url_str]['linkaddr']=$LinkAddrx;
					$res_arr[$current_url_str]['islink']=$isLinkx;
					$res_arr[$current_url_str]['img_src']=$ex_paramsx;			
					$res_arr[$current_url_str]['img_order']=$img_orderx;
			}else{
				unset($res_arr[$current_url_str]['sp_title']);
				unset($res_arr[$current_url_str]['linkaddr']);
				unset($res_arr[$current_url_str]['islink']);
				unset($res_arr[$current_url_str]['img_src']);
				unset($res_arr[$current_url_str]['img_order']);
			}
			
			$res_arr[$current_url_str]['lhtype']=$lhtype;
			$res_arr[$current_url_str]['geshi']=$geshi;
			
			$res_arr[$current_url_str]['img_open_type']=$img_open_type;
			$res_arr[$current_url_str]['img_width']=$banner_arr[$current_url_str]['img_width'];
			$res_arr[$current_url_str]['flv_width']=$banner_arr[$current_url_str]['flv_width'];
			$res_arr[$current_url_str]['img_height']=$banner_arr[$current_url_str]['img_height'];
			$res_arr[$current_url_str]['flv_height']=$banner_arr[$current_url_str]['flv_height'];
			$res_arr[$current_url_str]['play_speed']=$banner_arr[$current_url_str]['play_speed'];
			$res_arr[$current_url_str]['action']=$banner_info['action'];
			if(!empty($banner_file['name'])){
				$res_arr[$current_url_str]['flv_src']=$banner_arr[$current_url_str]['flv_src'];
			}

		if($delall==9){unset($res_arr[$current_url_str]);}else{
		}
		if (sizeof($display_banner)>0) {
			foreach ($display_banner as $k=>$v){
				$res_arr['display_banner'][$k] = $v;
			}
		}
		

			$banner_info['s_param'] = serialize($res_arr);
	
			$o_banner->set($banner_info);
			$o_banner->save();
		  }	
    }
    
	private function _savelinkimg($struct_file) {
		$struct_file['name'] = Toolkit::changeFileNameChineseToPinyin($struct_file['name']);
		$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
        move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/image/'.$struct_file['name']);
        return ParamParser::fire_virus(ROOT.'/upload/image/'.$struct_file['name']);
    }
    
    private function _savelinkflash($struct_file){
    	$struct_file['name'] = Toolkit::changeFileNameChineseToPinyin($struct_file['name']);
    	$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
    	return move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/flash/'.$struct_file['name']);
    	return ParamParser::fire_virus(ROOT.'/upload/flash/'.$struct_file['name']);
    }
    
    public function admin_foot(){
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_site/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        SessionHolder::set('mod_site/_LOCALE', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('lang_sw', $lang_sw);
		//logo banner foot
		$o_mb = new ModuleBlock();

		$curr_foot = $o_mb->find("s_locale=? and alias=? and module=? and action=?",array($curr_locale,'mb_foot','mod_static','custom_html'));
		$this->assign('curr_foot', $curr_foot);
		$this->assign('p_foot', unserialize($curr_foot->s_param));
    }
    
    public function save_foot(){
    	$this->_layout = 'content';

        $site_param =& ParamHolder::get('sparam', array());
       
    	if (!isset($site_param['AUTO_LOCALE'])) {
            $site_param['AUTO_LOCALE'] = '0';
        }

        try {
            
            $o_param = new Parameter();
        	foreach ($site_param as $key => $val) {
        	    $param =& $o_param->find('`key`=?', array($key));
        	    if ($param) {
        	        $param->val = $val;
        	        $param->save();
        	    }
        	}
        	
        	
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
    
        $flag_flash = false;
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
		$mod_locale = trim(SessionHolder::get('mod_static/_LOCALE', $curr_locale));
		$lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));

		$param_info =& ParamHolder::get('param', array());
		$foot_info =& ParamHolder::get('foot', array());
		$foot_arr = array();
		$foot_info['module'] = 'mod_static';
		$foot_info['action'] = 'custom_html';
		$foot_info['alias'] = 'mb_foot';
		$foot_info['title'] = '';
		$foot_info['show_title'] = 0;
		$foot_info['s_pos'] = 'footer';
		//$foot_info['s_locale'] = $lang_sw;
		$foot_info['s_query_hash'] = '_ALL';
		$foot_info['i_order'] = 0;
		$foot_info['published'] = 1;
		
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
		$this->setVar('json', Toolkit::jsonOK());	
        return '_result';
   		//Content::redirect("index.php?_m=mod_media&_a=admin_foot&_r=_page");
    }
    
    public function admin_icp(){
    	$this->_layout = 'content';
    	
    }
    
    public function save_icp(){
    	$this->_layout = 'content';

        $site_param =& ParamHolder::get('sparam', array());
       
    	if (!isset($site_param['AUTO_LOCALE'])) {
            $site_param['AUTO_LOCALE'] = '0';
        }

        try {
            
            $o_param = new Parameter();
        	foreach ($site_param as $key => $val) {
        	    $param =& $o_param->find('`key`=?', array($key));
        	    if ($param) {
        	        $param->val = $val;
        	        $param->save();
        	    }
        	}
        	
        	
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
   
		$this->setVar('json', Toolkit::jsonOK());	
        return '_result';
   		//Content::redirect("index.php?_m=mod_media&_a=admin_foot&_r=_page");
    }
    function admin_company_introduction(){
    	$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_site/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_site/_LOCALE', $lang_sw);
        
        try {			
			$o_sc = new StaticContent();
			$curr_id = $o_sc->find("s_locale=? and flag=?",array($lang_sw,"company"),"ORDER BY `id`");
            $curr_cus = new StaticContent($curr_id->id);
            $this->assign('curr_co', $curr_cus);
//            var_dump($curr_cus);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('lang_sw', $lang_sw);
    }
    
     function save_company_introduction(){
	    $this->_layout = 'content';
        $site_info =& ParamHolder::get('si', array());
		$co_info =& ParamHolder::get('co', array());

        try {

			if (strchr($co_info['content'],"<!--")) {
				$pattern = "/\<\!\-\-(.+?)\-\-\>/s";
				$co_info['content'] = preg_replace($pattern,'',$co_info['content']);
			}
			
            $co_info['published'] = 1;
            $o_o = new StaticContent($co_info['id']);
            $o_o->set($co_info);
            $o_o->save();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
    
    Content::redirect("index.php?_m=mod_media&_a=admin_company_introduction&sc_id=".$co_info['id']);
	
    }

	
	public function image_pickers() {
   $this->_layout = 'content';
     $err = '';
        $wincls = 'NG';
        $maxsize = 2 * 1024 * 1024;
        $typeArr = array('image/bmp','image/gif','image/png','image/x-png','image/jpeg','image/pjpeg');
        $file_info =& ParamHolder::get('localfile', array(), PS_FILES);
        $file_info['name'] = Toolkit::changeFileNameChineseToPinyin($file_info['name']);
        if ( sizeof($file_info) > 0 && isset($file_info['name']) )
        {
	        // 文件大小
	        if ( ($file_info['size'] == 0) || ($file_info['size'] > $maxsize) ) {
	        	$err = __('Upload size limit').':2M';
	        // 文件类型
        	} elseif ( !in_array( $file_info['type'], $typeArr ) ) {
	        	$err = __('Supported file format').':gif|jpg|png|bmp';	
	        } else {
	        	$dest = ROOT.'/upload/image/';
		        //$file_info['name'] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
		        if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $file_info['name'])) {
					$file_info['name'] = iconv("UTF-8", "GBK//IGNORE", $file_info['name']);
				}
				if (!preg_match ("/^[a-z0-9_\.]*$/i", $file_info['name'])) {
					return false;
				}
	        	if ( move_uploaded_file( $file_info['tmp_name'], $dest.$file_info['name'] ) ) {
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

	private function img_restruck($imgfile_name, $path = 'upload/image/') {
		define('SSFCK', 1);
		define('SSROOT', ROOT);
		include_once(P_LIB.'/image.func.php');

		$fullfilename = SSROOT."/$path".$imgfile_name;
		
		WaterImg($fullfilename, 'up');
    }
}
?>