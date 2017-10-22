<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModSite extends Module {
    
	protected $_filters = array(
        'check_admin' => ''
    );
	
    public function admin_list() {
    	$this->_layout = 'content';
    	$type = ParamHolder::get("type");
    	if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_site/_LOCALE', $lang_sw);
        $o_param = new Parameter();
$arr_params =& $o_param->findAll();

if (sizeof($arr_params) > 0) {
    foreach ($arr_params as $param) {
//货币常量处理
	    if ($param->key=="CURRENCY") {
	    	if (strstr($param->val,"|")) {
	    		$c_arr = explode("|",$param->val);
		    	if (is_array($c_arr)) {
		    		foreach ($c_arr as $arr){
		    			list($loc,$curr) = explode(",",$arr);
		    			if ($loc==$lang_sw) {
		    				if ($type=="sw") {
		    					$currency_ = $curr;
		    				}
		    				
		    			}
		    		}
		    	}
	    	}else{
	    		if (strstr($param->val,",")) {
	    			list($loc,$curr) = explode(",",$param->val);
		    		//如果只定义了一种货币，则就显示这种货币
		    		if ($type=="sw") {
		    					$currency_ = $curr;
		    				}
		    		define(trim("$param->key"), "$curr");
		    		continue;
	    		}else{
	    			if ($type=="sw") {
		    					$currency_ = $param->val;
		    			}
	    			define(trim("$param->key"), "$param->val");
	    		}
	    		
	    	}
	   	}
	   	if ($param->key=="CURRENCY_SIGN") {
	   		if (strstr($param->val,"|")) {
	   			$c_arr = explode("|",$param->val);
		    	if (is_array($c_arr)) {
		    		foreach ($c_arr as $arr){
		    			list($loc,$curr) = explode(",",$arr);
		    			if ($loc==$lang_sw) {
		    				if ($type=="sw") {
		    					$currency_sign_ = $curr;
		    			}
		    				define(trim("$param->key"), "$curr");
		    				continue;
		    			}
		    		}
		    	}
	   		}else{
	   			if (strstr($param->val,",")) {
	    			list($loc,$curr) = explode(",",$param->val);
		    		//如果只定义了一种货币符号，则就显示这种货币符号
		    		if ($type=="sw") {
		    					$currency_sign_ = $curr;
		    			}
		    		continue;
	    		}else{
	    			if ($type=="sw") {
		    					$currency_sign_ = $param->val;
		    			}
	    		}
	    	}
	   	}
    }
}//货币符号处理结束
	  if(defined('VERIFY_META')){
			$verify_meta=unserialize(VERIFY_META);
			if(is_array($verify_meta)){
				$meta_str=$verify_meta[$lang_sw];
				if(!empty($meta_str)) $this->assign('meta',$meta_str);
			}
		}	
		
        $o_siteinfo = new SiteInfo();
        $curr_siteinfo =& $o_siteinfo->find("s_locale=?", array($lang_sw));
        $this->assign('curr_siteinfo', $curr_siteinfo);
        $this->assign('lang_sw', $lang_sw);
        $this->assign('currency_', $currency_);
        $this->assign('currency_sign_', $currency_sign_);
        $this->assign('langs', Toolkit::loadAllLangs());
        
        try {
			if(strtolower($lang_sw) == 'zh_cn') {
				$cus_id = 1;
			} else if(strtolower($lang_sw) == 'en'){
				$cus_id = 3;
			}
			if (isset($cus_id)) {
				 $curr_cus = new StaticContent($cus_id);
				 $this->assign('curr_cus', $curr_cus);
			}
           
            

			if(strtolower($lang_sw) == 'zh_cn') {
				$co_id = 2;
			} else if(strtolower($lang_sw) == 'en') {
				$co_id = 4;
			}
			if (isset($co_id)) {
	            $curr_co = new StaticContent($co_id);
	            $this->assign('curr_co', $curr_co);
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }
        
        $this->assign('langs', Toolkit::loadAllLangs());
		//logo banner foot
		$o_mb = new ModuleBlock();
		//$curr_logo = $o_mb->find("s_locale=? and alias=? and module=? and action=?",array($lang_sw,'mb_logo','mod_media','show_image'));
		//$this->assign('curr_logo', $curr_logo);
		//$this->assign('p_logo', unserialize($curr_logo->s_param));
		//
		//$curr_banner = $o_mb->find("s_locale=? and alias=? and module=?",array($lang_sw,'mb_banner','mod_media'));
		//$this->assign('curr_banner', $curr_banner);
		//$this->assign('p_banner', unserialize($curr_banner->s_param));
		//
		$curr_foot = $o_mb->find("s_locale=? and alias=? and module=? and action=?",array($lang_sw,'mb_foot','mod_static','custom_html'));
		$this->assign('curr_foot', $curr_foot);
		if (isset($curr_foot->s_param)) {
			$this->assign('p_foot', unserialize($curr_foot->s_param));
		}
    }
	public function admin_seo() {
    	$this->_layout = 'content';
    	if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_site/_LOCALE', $lang_sw);
        
        $o_siteinfo = new SiteInfo();
        $curr_siteinfo =& $o_siteinfo->find("s_locale=?", array($lang_sw));
        
        $this->assign('curr_siteinfo', $curr_siteinfo);
        
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());

		
    }

	public function save_seo_info() {
		$this->_layout = 'content';

        //$site_param =& ParamHolder::get('sparam', array());
        $site_info =& ParamHolder::get('si', array());
       // $cus_info =& ParamHolder::get('cus', array());
		//$co_info =& ParamHolder::get('co', array());
//		$isLink = & ParamHolder::get('isLink', array());
//		$LinkAddr = & ParamHolder::get('LinkAddr', array());
		
    	/*if (sizeof($cus_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing contact us information!')));
            return '_result';
        }*/
        if (sizeof($site_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing site information!')));
            return '_result';
        }
    	
        try {
            // Data operation
            $o_siteinfo = new SiteInfo();
            $curr_siteinfo =& $o_siteinfo->find("s_locale=?", array($site_info['s_locale']));
            if (intval($site_info['id']) != intval($curr_siteinfo->id)) {
	            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
	            return '_result';
            }
            if ($curr_siteinfo) {
	            $curr_siteinfo->set($site_info);
	            $curr_siteinfo->save();
            } else {
                $o_siteinfo->set($site_info);
                $o_siteinfo->save();
            }
			
        	
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
    
        
   		echo '<script language="javascript">parent.window.location.href = "../index.php?_m=frontpage&_a=index";</script>';
   		
        return '_result';

    }
    public function save_info() {
		$this->_layout = 'content';

        $site_param =& ParamHolder::get('sparam', array());
        $site_info =& ParamHolder::get('si', array());
        $cus_info =& ParamHolder::get('cus', array());
        $page_lang = $site_info['s_locale'];
	   $verify_meta =& ParamHolder::get('verify_meta', '');
        if (sizeof($site_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing site information!')));
            return '_result';
        }
    	if (!isset($site_param['AUTO_LOCALE'])) {
            $site_param['AUTO_LOCALE'] = '0';
        }
        if (!isset($site_param['SITE_OFFLINE'])) {
            $site_param['SITE_OFFLINE'] = '0';
        }
    	if (!isset($site_param['EXCHANGE_SWITCH'])) {
            $site_param['EXCHANGE_SWITCH'] = '0';
        }
		if (!isset($site_param['MEMBER_VERIFY'])) {
            $site_param['MEMBER_VERIFY'] = '0';
        }
    	if (!isset($site_param['SITE_LOGIN_VCODE'])) {
            $site_param['SITE_LOGIN_VCODE'] = '0';
        }
        if (!isset($site_param['CREDITS_SWITCH'])) {
            $site_param['CREDITS_SWITCH'] = '0';
        }
    	if (!isset($site_param['USE_LANGUAGE'])) {
            $site_param['USE_LANGUAGE'] = SessionHolder::get('_LOCALE');
        }
        try {
            // Data operation
            $o_siteinfo = new SiteInfo();
            $curr_siteinfo =& $o_siteinfo->find("s_locale=?", array($site_info['s_locale']));
            if (intval($site_info['id']) != intval($curr_siteinfo->id)) {
	            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
	            return '_result';
            }
            if ($curr_siteinfo) {
	            $curr_siteinfo->set($site_info);
	            $curr_siteinfo->save();
            } else {
                $o_siteinfo->set($site_info);
                $o_siteinfo->save();
            }
			//add 2010-6-27 rewrite
			if($site_param['MOD_REWRITE'] == 2) {
				$htaccess = "RewriteEngine On \n";
				$htaccess .= 'RewriteBase /'.substr(ROOT,strlen($_SERVER['DOCUMENT_ROOT'])+1)." \n";
				$htaccess .= "RewriteCond %{REQUEST_FILENAME} !-f \n";
				$htaccess .= "RewriteCond %{REQUEST_FILENAME} !-d \n";
				$htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([0-9a-zA-Z_]{1,})-([a-zA-Z_]+)-([0-9a-zA-Z_\=\{\}]{1,}).html$ index\.php?_m=$1&_a=$2&$3=$4&$5=$6'." \n";
				$htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,}).html$ index.php?_m=$1&_a=$2'." \n";
				$htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z0-9_]{1,}).html$ index.php?_m=$1&_a=$2&$3=$4'." \n";
				$htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z0-9_]{1,})-([a-zA-Z_]{1,})-([0-9]{0,})-([a-zA-Z_]{1,})-([0-9a-zA-Z\=\{\}]{0,}).html$ index.php?_m=$1&_a=$2&$3=$4&$5=$6&$7=$8'." \n";
				$htaccess .= 'ErrorDocument 404 /404.html'." \n";
								
				$admin_htaccess = "RewriteEngine On \n";
				$admin_htaccess .= 'RewriteBase /'.substr(ROOT,strlen($_SERVER['DOCUMENT_ROOT'])+1)."/admin/ \n";
				$admin_htaccess .= "RewriteCond %{REQUEST_FILENAME} !-f \n";
				$admin_htaccess .= "RewriteCond %{REQUEST_FILENAME} !-d \n";
				$admin_htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([0-9]{1,})-([a-zA-Z_]{1,})-([0-9a-zA-Z\=\{\}\/_]{0,}).html$ index.php?_m=$1&_a=$2&$3=$4&$5=$6'." \n";
				$admin_htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z0-9]{1,}).html$ index.php?_m=$1&_a=$2&$3=$4'." \n";
				$admin_htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,}).html$ index.php?_m=$1&_a=$2'." \n";
				$admin_htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([0-9]{1,})-([a-zA-Z_]{1,})-([0-9]{0,})-([a-zA-Z_]{1,})-([0-9a-zA-Z\=\{\}]{0,}).html$ index.php?_m=$1&_a=$2&$3=$4&$5=$6&$7=$8'." \n";
				$admin_htaccess .= 'ErrorDocument 404 /404.html'." \n";

				//伪静态时候过滤掉动态链接的抓取
				$robots = "User-agent: * \n";
				$robots .= 'Disallow: /*?*';
				file_put_contents(ROOT.'/.htaccess',$htaccess);
				file_put_contents(ROOT.'/robots.txt',$robots);
				file_put_contents(ROOT.'/admin/.htaccess',$admin_htaccess);
			}else if($site_param['MOD_REWRITE'] == 3) {
				$htaccess = '<IfModule mod_rewrite.c>'." \n";
				$htaccess .= 'RewriteEngine On'." \n";
				$htaccess .= 'RewriteBase /'.substr(ROOT,strlen($_SERVER['DOCUMENT_ROOT'])+1)." \n";
				$htaccess .= 'RewriteRule ^index\.php$ - [L]'." \n";
				$htaccess .= 'RewriteCond %{REQUEST_FILENAME} !-f'." \n";
				$htaccess .= 'RewriteCond %{REQUEST_FILENAME} !-d'." \n";
				$htaccess .= 'RewriteRule ^([a-zA-Z0-9_-]{1,}).html$ index.php?_lang=zh_CN&_path=$1'." \n";
				$htaccess .= 'RewriteRule ^([a-zA-Z_]{1,})/([a-zA-Z0-9_-]{1,}).html$ index.php?_lang=$1&_path=$2'." \n";
				$htaccess .= '</IfModule>'." \n";
				$htaccess .= 'ErrorDocument 404 /404.html'." \n";
				
				$admin_htaccess = "RewriteEngine On \n";
				$admin_htaccess .= 'RewriteBase /'.substr(ROOT,strlen($_SERVER['DOCUMENT_ROOT'])+1)."/admin/ \n";
				$admin_htaccess .= 'ErrorDocument 404 /404.html'." \n";

				$robots = "User-agent: * \n";
				$robots .= 'Disallow: /*?*';
				file_put_contents(ROOT.'/.htaccess',$htaccess);
				file_put_contents(ROOT.'/robots.txt',$robots);
				file_put_contents(ROOT.'/admin/.htaccess',"$admin_htaccess");
			}else {
				$htaccess = 'ErrorDocument 404 /404.html'." \n";
				file_put_contents(ROOT.'/.htaccess',$htaccess);
				file_put_contents(ROOT.'/robots.txt','User-agent: *');
				file_put_contents(ROOT.'/admin/.htaccess','ErrorDocument 404 /404.html');
			}
            $o_param = new Parameter();
            $c_arr = $this->getCurr($site_param['CURRENCY'],$site_param['CURRENCY_SIGN']);
            $site_param['CURRENCY']= $c_arr['curr'];
            $site_param['CURRENCY_SIGN']=$c_arr['curr_sign'];
        	foreach ($site_param as $key => $val) {
        	    $param =& $o_param->find('`key`=?', array($key));
        	    if ($param) {
        	        $param->val = $val;
        	        $param->save();
        	    }else{
        	    	if ($key=='CREDITS_SWITCH') {
        	    		$o_param->key = 'CREDITS_SWITCH';
        	    		$o_param->val = $val;
        	    		$o_param->save();
        	    	}
        	    	if ($key=='CREDITS_RATE') {
        	    		$o_param->key = 'CREDITS_RATE';
        	    		$o_param->val = $val;
        	    		$o_param->save();
        	    	}
				}
        	}
        	// 2011/03/03 重置临时存储语言SESSION
            SessionHolder::set('SS_LOCALE', '');
            SessionHolder::set('mod_site/_LOCALE', '');
        	
        	//save language
        	//$curr_lang = new Language($site_param['USE_LANGUAGE']+1);
        	$curr_lang = new Language($site_param['USE_LANGUAGE']);
        	$url_lang = $curr_lang->locale;
            $o_param = new Parameter();
            $locale_param =& $o_param->find("`key`='DEFAULT_LOCALE'");
            $locale_param->val = $curr_lang->locale;
            $locale_param->save();
            SessionHolder::set('_LOCALE', $curr_lang->locale);
            $cus_info['create_time'] = time();
			
            $cus_info['published'] = 1;
            $cus_info['title'] = __('AboutUs');
			$cus_info['for_roles'] = '{member}{admin}{guest}';

			$co_info['create_time'] = time();
            $co_info['published'] = 1;
            $co_info['title'] = __('ContactUs');
		
			//加入网站认证代码			
			$oriverify_meta=array();
			if(defined('VERIFY_META')) $oriverify_meta=unserialize(VERIFY_META);
			if(!is_array($oriverify_meta)) $oriverify_meta=array();
			$langmeta=array();
			$langmeta[$page_lang]=$this->meta_str_filter($verify_meta);
			$neworiverify_meta=array_merge($oriverify_meta,$langmeta);
			Parameter::updateParameters(array('VERIFY_META'=>serialize($neworiverify_meta)));
			//用户升级时，自定义链接模式下更新用户的表menu_items,static_contents
			if (INIT3==0 && $site_param['MOD_REWRITE']==3) {
				//找到用户使用的语言列表
				$o_lang = new Language();
				$r_lan = $o_lang->findAll();
				foreach ($r_lan as $k=>$v){//不同语言的数据更新
					//更新menu_items
					$o_menu = new MenuItem();
					$r_menu = $o_menu->findAll(' s_locale=?',array($v->locale));
					if (sizeof($r_menu)>0) {
						foreach ($r_menu as $k2=>$v2){
							$id=$v2->id;
							$link=$v2->link;
							$num = intval(strrpos($link,'=')+1);
							$t=intval(substr($link,$num));
							$o_me = new MenuItem($id);
							$o_me->content_id = $t;
							$o_me->save();
							//记录公司简介和联系我们的ID
							if ($v2->mi_category=='company_info') {
								$comp[$v->locale] = $t;
							}
							if ($v2->mi_category=='contact_info') {
								$con[$v->locale] = $t;
							}
						}
					}
				}
				//更新static_contents
				$o_static = new StaticContent();
				if (sizeof($comp)>0) {
					foreach ($comp as $k3=>$v3){
						$r_sta = $o_static->find('id=?',array($v3));
						if (!empty($r_sta)) {
							$r_sta->flag = 'company';
							$r_sta->save();
						}
					}
				}
				if (sizeof($con)>0) {
					foreach ($con as $k4=>$v4){
						$r_sta = $o_static->find('id=?',array($v4));
						if (!empty($r_sta)) {
							$r_sta->flag = 'contact';
							$r_sta->save();
						}
					}
				}
				
				//重置标志为1，升级处理只执行1次
				$o_param = new Parameter();
				$r_param = $o_param->find( '`key`=?',array('INIT3'));
				$r_param->val = 1;
				$r_param->save();
			}
			
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_result';
        }
        $flag_flash = false;
		
        SessionHolder::set('mod_site/_LOCALE', $page_lang);
        $ico_file =& ParamHolder::get('ico_file', array(), PS_FILES);
		if (!empty($ico_file['name'])) {
			$this->_saveico($ico_file);
		}
	
		$param_info =& ParamHolder::get('param', array());
		$play_info =& ParamHolder::get('radio', array());
		$foot_info =& ParamHolder::get('foot', array());
		$foot_arr = array();
		$foot_info['module'] = 'mod_static';
		$foot_info['action'] = 'custom_html';
		$foot_info['alias'] = 'mb_foot';
		$foot_info['title'] = '';
		$foot_info['show_title'] = 0;
		$foot_info['s_pos'] = 'footer';
		$foot_info['s_locale'] = $page_lang;
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
		
		$music_file =& ParamHolder::get('music_file', '');
		
		if(!empty($music_file)) {
			
			
			if(substr($music_file,0,7)!='http://'){
				$music_file='http://'.$music_file;
			}
			$music_arr['BG_MUSIC'] = $music_file;
			$o_bgmusic = new BackgroundMusic();
			$bgmusic_items = $o_bgmusic->findAll();
			$db = MysqlConnection::get();
			$prefix = Config::$tbl_prefix;
			if(empty($bgmusic_items)) {
				$sql = <<<SQL
INSERT INTO {$prefix}background_musics VALUES(1,'{$music_arr['BG_MUSIC']}',{$play_info['play_type']},'')	
SQL;
			} else {
				$music_path = iconv("UTF-8", "gb2312", $bgmusic_items[0]->music_path);
				
				$sql = <<<SQL
UPDATE {$prefix}background_musics SET `music_path` = '{$music_arr['BG_MUSIC']}',`music_name` = '',`play` = {$play_info['play_type']} WHERE `id` = '{$bgmusic_items[0]->id}'		
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
UPDATE {$prefix}background_musics SET `play` = {$play_info['play_type']},`music_path`='{$music_arr['BG_MUSIC']}' WHERE `id` = '{$bgmusic_items[0]->id}'		
SQL;
				$result = $db->query($sql);
			}
		}
   		//Content::redirect("index.php?_m=mod_site&_a=admin_list&_r=_page"); 
   		$this->assign('json', 'ok');
   		$this->assign('flag', $site_param['MOD_REWRITE']);
   		// for redirect url
   		echo '<script language="javascript">parent.window.location.href = "../index.php?_m=frontpage&_a=index&_l='.$url_lang.'";</script>';
   		
        return '_result';

    }
    
    public function reset_tpl_data() {
        $db =& MySqlConnection::get();
//        $client =& Toolkit::initSoapClient();
//        $sqls = unserialize($client->getTplSampleData_SSv2(DEFAULT_TPL, EZSITE_LEVEL));
        if ($sqls == 'ERROR' && empty($sql)) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid template!')));
            return '_result';
        }
        
        foreach ($sqls as $sql) {
            $db->query($sql);
        }
        
        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
	public function admin_dat() {
		$this->_layout = 'dat';
		
		$domain = trim(Toolkit::getSubDomain($_SERVER['HTTP_HOST']));
		$key = sha1($domain."ssiuhIUAHSiu!husashu11dd@kjdjsah==");
		// 19/09/2010
		if (function_exists('curl_init') && function_exists('curl_exec')) {
			$curl = curl_init();
			$timeout = 30; 
			curl_setopt($curl, CURLOPT_URL, "http://licence.sitestar.cn/licencedat.php?domain={$domain}&key={$key}");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
			$str = curl_exec($curl);
			curl_close($curl);
		} else {
			$str = file_get_contents("http://licence.sitestar.cn/licencedat.php?domain={$domain}&key={$key}") or die('Request Failed!');
		}
		if($str=='1002'){
			Content::redirect(Html::uriquery('mod_site', 'licence'));
		}else if($str!='1001') {
			$dat = '<?php '.$str.' ?>';
			file_put_contents('../licence.dat', $dat);
		}

		Content::redirect(Html::uriquery('frontpage', 'dashboard'));
	}
	public function licence() {
		$this->_layout = 'dat';
		$this->assign('curr_banner', 111);
	}
	public function admin_dashboard() {
		$this->_layout = 'default';
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
    	move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/flash/'.$struct_file['name']);
    	return ParamParser::fire_virus(ROOT.'/upload/flash/'.$struct_file['name']);
    }
    
	private function _savelinkmusic($struct_file){
		$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
    	move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/media/'.$struct_file['name']);
    	return ParamParser::fire_virus(ROOT.'/upload/media/'.$struct_file['name']);
    }
    
    public function getCurr($currency,$currency_sign){
    	if (strstr($currency_sign,"|"||strstr($currency_sign,","))) {
    		echo "<script>alert('货币符号不能含用｜或,符号');history.go(-1);</script>";exit;
    	}
    	$val = array();
    	$o_param = new Parameter();
		$arr_params =& $o_param->findAll();
		$curr_locale = trim(SessionHolder::get('mod_site/_LOCALE'))?trim(SessionHolder::get('mod_site/_LOCALE')):trim(SessionHolder::get('_LOCALE'));
		if (sizeof($arr_params) > 0) {
		    foreach ($arr_params as $param) {
		    	//货币单位
			    if ($param->key=="CURRENCY") {
			    	if (strstr($param->val,"|")) {
			    		if (strstr($param->val,$curr_locale)) {
			    			$c_arr = explode("|",$param->val);
					    	if (is_array($c_arr)) {
					    		foreach ($c_arr as $k=>$arr){
					    			list($loc,$curr) = explode(",",$arr);
					    			if ($loc==$curr_locale) {//如果语言相同，替换语言的货币
					    				$curr = $currency;
					    				$c_arr[$k] = $curr_locale.",".$curr;
					    				$val['curr'] = implode("|",$c_arr);
					    			}
					    		}
					    	}
			    		}else{//否则，将当前的连接到原先的组成新的
			    			$val['curr'] = $param->val."|".$curr_locale.','.$currency;
			    		}
			    	}else{
			    		//对旧的数据进行组织更新,如，USD替换为en,USD
			    		if (strstr($param->val,$curr_locale)) {
			    			list($loc,$curr) = explode(",",$param->val);
			    			if ($loc==$curr_locale) {
			    				$val['curr'] = $curr_locale.','.$currency;
			    			}
			    		}else{
				    		if ($param->val==$currency) {//如果存储的和当前的相同
				    			$val['curr'] = $curr_locale.",".$currency;
				    		}else{//如果不相同，则重新组织原来的，并加上当前的进行存储
				    			if ($param->val=='CNY') {//对常用的三个货币进行组织
				    				$str_curr = "zh_CN,CNY";
				    			}elseif($param->val=='TWD'){
				    				$str_curr = "zh_TW,TWD";
				    			}elseif($param->val=='HKD'){
				    				$str_curr = "zh_TW,HKD";
				    			}elseif($param->val=='USD'){
				    				$str_curr = "en,USD";
				    			}
				    			$str_curr = $str_curr==""?$param->val:$str_curr;
				    			$val['curr'] = $str_curr."|".$curr_locale.','.$currency;
				    		}
			    		}
			    	}
			   	}
			   	//货币符号
			   	if ($param->key=="CURRENCY_SIGN") {
			   		if (strstr($param->val,"|")) {
			   			if (strstr($param->val,$curr_locale)) {//如果已经存在
			   				$c_arr = explode("|",$param->val);
					    	if (is_array($c_arr)) {
					    		foreach ($c_arr as $k=>$arr){
					    			list($loc,$curr) = explode(",",$arr);
					    			if ($loc==$curr_locale) {//如果已经存在，则更新原有的
					    				$c_arr[$k] =  $curr_locale.",".$currency_sign;
					    				$val['curr_sign'] = implode("|",$c_arr);
					    			}
					    		}
					    	}
			   			}else{//否则，将当前的连接到原先的组成新的
					    	$val['curr_sign'] = $param->val."|".$curr_locale.','.$currency_sign;
					    }
			    		
			    	}else{
			    		if (strstr($param->val,$curr_locale)) {
			    			list($loc,$curr) = explode(",",$param->val);
			    			if ($loc==$curr_locale) {
			    				$val['curr_sign'] = $curr_locale.','.$currency_sign;
			    			}
			    		}else{
				    		//对旧的数据进行组织更新,如，$替换为en
				    		if ($param->val==$currency_sign) {//如果存储的和当前的相同
				    			$val['curr_sign'] = $curr_locale.",".$currency_sign;
				    		}else{//如果不相同，则重新组织原来的，并加上当前的进行存储
				    			if ($param->val=='￥'&&$curr_locale=='zh_CN') {//对常用的三个货币进行组织
				    				$val['curr_sign'] =  "zh_CN,{$currency_sign}";
				    			}elseif($param->val=='$'&&$curr_locale=='en'){
				    				$val['curr_sign'] = "en,{$currency_sign}";
				    			}else{
				    				$val['curr_sign'] = $param->val."|".$curr_locale.','.$currency_sign;
				    			}
				    		}
			    		}
			    	}
			   	}
		    }
		}
//		var_dump($val);exit;
		return $val;	
	}
	
	/*
	* @DESC：站点背景设置
	* @author: zhangjc
	* @date:2013/1/16
	*/
	public function admin_bg() {
    	$this->_layout = 'content';
    	if (trim(SessionHolder::get('SS_LOCALE')) != '') {// 多语言切换用  		
    		$curr_locale = trim(SessionHolder::get('_LOCALE'));
    	} else {
    		$curr_locale = DEFAULT_LOCALE;
    	}
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_site/_LOCALE', $lang_sw);
        
        $o_siteinfo = new SiteInfo();
        $curr_siteinfo =& $o_siteinfo->find("s_locale=?", array($lang_sw));
        
        $this->assign('curr_siteinfo', $curr_siteinfo);
        
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());

    }
	
	/*
	* @DESC：站点背景设置
	* @author: zhangjc
	* @date:2013/1/17
	*/
	public function save_bg_info() {
		$this->_layout = 'content';
		$file_info =& ParamHolder::get('background_img', array(), PS_FILES);
		$bg_info =& ParamHolder::get('si', array());
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
		if($curr_locale!=$bg_info['s_locale']){
			$curr_locale=$bg_info['s_locale'];
		}
		$file_info['name'] = Toolkit::changeFileNameChineseToPinyin($file_info['name']);
		if (!empty($file_info["name"])) {
			if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_info["name"])) {
				Notice::set('mod_site/msg', __('image type error!'));
				Content::redirect(Html::uriquery('mod_site', 'admin_bg'));
			}

			if (!$this->_savelinkimg($file_info)) {
				Notice::set('mod_site/msg', __('image upload failed!'));
				Content::redirect(Html::uriquery('mod_site', 'admin_bg'));
			}
		}
		/*
		if($bg_info['fixed']=='fixed'){
			$bg_info['fixed']='fixed';
		}else{
			$bg_info['fixed']='scroll';
		}
		*/
		
		$o_param = new Parameter();
		$tpl_param =& $o_param->find("`key`='BACKGROUND_INFO'");
		$tmp=unserialize($tpl_param->val);
		if(empty($file_info["name"])){
			$file_info["name"]=$tmp[$curr_locale]['img'];
		}
		if(empty($bg_info['fixed'])){
			
			$bg_info['fixed']='scroll';
		}else{
			$bg_info['fixed']='fixed';
		}
		if(empty($bg_info["color"])){
			$bg_info["color"]=$tmp[$curr_locale]['color'];
		}
		if(empty($bg_info["position"])){
			$bg_info["position"]=$tmp[$curr_locale]['position'];
		}
		if(empty($bg_info["radio"])){
			$bg_info["radio"]=$tmp[$curr_locale]['radio'];
		}
		$arr = array("img"=>$file_info["name"],"color"=>$bg_info['color'],"position"=>$bg_info['postion'],"radio"=>$bg_info['radio'],"fixed"=>$bg_info['fixed']);
		$tmp[$curr_locale]=$arr;
		$tpl_param->val = serialize($tmp);
		$tpl_param->save();
		//Notice::set('mod_site/msg', __('Background added successfully!'));
		//Content::redirect(Html::uriquery('mod_site', 'admin_bg'));
		echo '<script language="javascript">parent.window.location.href = "../index.php?_m=frontpage&_a=index";</script>';
   		
        return '_result';
	}

	/*
	* @DESC：站点背景删除
	* @author: zhangjc
	* @date:2013/1/18
	*/
	public function del_bg_info() {
		$this->_layout = 'content';
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
		$lang_sw = trim(ParamHolder::get('lang_sw'));
		if(empty($lang_sw)){
			$lang_sw=$curr_locale;
		}
		$o_param = new Parameter();
		$tpl_param =& $o_param->find("`key`='BACKGROUND_INFO'");
		$tmp=unserialize($tpl_param->val);
		$tmp[$lang_sw]['img']='';
		$tpl_param->val = serialize($tmp);
		$tpl_param->save();
		die("1");
	}
	
	public function del_cache(){
		$root_dir = "../cache";
		 $files = scandir($root_dir);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $f_path = $root_dir.'/'.$file;
            if (is_dir($f_path)) {
                if (!self::_rmdir_r($f_path)) {
                    return false;
                }
            } else {
                if (!unlink($f_path)) {
                    return false;
                }
            }
        }
        echo 1;
        exit;
	}
	private function meta_str_filter($str){
		 $meta_str=stripslashes($str);
		 $meta_regexp='/<meta .+?\/>/i';
		 preg_match_all( $meta_regexp, $meta_str,$meta_arr);
		 if(is_array($meta_arr)) $meta_arr=$meta_arr[0];
		 $resultstr='';
		 if(is_array($meta_arr)){
			 foreach($meta_arr as $metastr){
				 if(!preg_match('/http-equiv\s*=\s*["\']?refresh["\']?/',$metastr)){
					 if(!empty($resultstr)) $resultstr.="\n";
					 $resultstr.=$metastr;
				 }
			 }
		 }
		 return $resultstr;
    }		
	
    private function _saveico($struct_file){
		$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
		if ($struct_file['type']!=='image/x-icon') {
			echo "<script>alert('".__("Image type must be ico!")."');history.go(-1);</script>";
			exit;
		}
    	if(move_uploaded_file($struct_file['tmp_name'], ROOT.'/favicon.ico')){
    		ParamParser::fire_virus(ROOT.'/favicon.ico');
    		$o_param = new Parameter();
        	$param =& $o_param->find('`key`="ICO_PATH"');
        	if ($param) {
        		$param->val = 'favicon.ico';
        	    $param->save();
        	}else{
        		$o_param->key = "ICO_PATH";
        		$o_param->val = 'favicon.ico'; 
        		$o_param->save();
        	}
        	return true;
    	}
    }
}
?>