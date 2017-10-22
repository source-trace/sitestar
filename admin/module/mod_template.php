<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class ModTemplate extends Module {
    protected $_filters = array(
        'check_admin' => ''
    );
    
    /**
     * 模板展示
     */
    public function admin_list() {
    	ini_set('max_execution_time', 600);//把php脚本运行超期时间设为10分钟
//     ini_set('memory_limit', '5M'); 
     static $exe_times=0;
	$exe_times++;
	try{
				$read_cache_flag = false;
				$is_ping = false;
				$templates_category_cache_time=24*60*60;//分类单独缓存，可以设置很长时间
				$templates_cache_time=24*60*60;//模板数据缓存时间
				$csvfile='../cache/templates_cache.csv';

					$this->_layout = 'content';

					if (!$this->_requireAdmin()) {
							return '_error';
					}

					$page_count = 16;//show 16 templates' shortcut
					$page_number =& ParamHolder::get('_p', 1);//page number
					$category_number =& ParamHolder::get('_cates', '_all');
					$category_sub_id =& ParamHolder::get('sub_id', 0);
					$category_tpl_id =& ParamHolder::get('tpl_id', 0);//搜索模板的ID

					if(!is_numeric($page_number)) $page_number = 1;
					$page_number = floor($page_number);//对输入页码的类型判断

					$template_owns = array();
					$template_owns_name=array();
	//        if ( IS_INSTALL ) {
			if($category_number == '_all'&&intval($category_tpl_id)==0)
			{
						$handle  = opendir(ROOT.'/template');
				while($file = readdir($handle)){
					$newpath=ROOT.'/template/'.$file;
					if(is_dir($newpath) && $file != "." && $file != ".." && $file != ".svn") {
						if(file_exists(ROOT.DS.'template'.DS.$file.DS.'conf.php') && file_exists(ROOT.DS.'template'.DS.$file.DS.'template_info.php'))
						{
							include  ROOT.DS.'template'.DS.$file.DS.'conf.php';
							if(!empty($template_name))
							{
								$template_owns[] = $file;
								$template_owns_name[] = $template_name;
							}
						}
					}
				}

				}elseif(file_exists(ROOT.DS.'template'.DS.DEFAULT_TPL.DS.'conf.php')){
				include_once(ROOT.DS.'template'.DS.DEFAULT_TPL.DS.'conf.php');
				$_template_name = $tpl_name;
			}
				$installed_templates =array_flip( $template_owns); //反转，记录$template_owns中本地模板字符串对应的位置
	//        } else {
						$cat_sw =& ParamHolder::get('cat_sw', '-');
						// Now using "My Templates" as default category
						if ($cat_sw == '-') $cat_sw = '1';

						//---------------templates cache strategy[start]----------------------
	//	        if(extension_loaded('soap'))
	//	        {

							$now = time();
	//		        $set_expired_time = 24;
							if(!file_exists('../cache/templates_cache.cache'))
							{
								!@chmod('../cache', 0755 );
								$read_cache_flag = false;
							}
							else
							{
								$arr = file_get_contents('../cache/templates_cache.cache');
								$arr = unserialize($arr);
								$set_time = $arr['set_time'];
	//		        	$expired_time = $arr['expired_time'];

								$read_cache_flag = ($set_time +$templates_category_cache_time>= $now) ? true : false;//estimate cache file's valid
							}

							if($read_cache_flag)
							{
		//	        	$tmp = unserialize($arr['templates_data']);
	//					$tmp = $arr['templates_data'];
												 //only cache templates_category
						$tmp_category = $arr['templates_category'];
						$is_ping = true;
							}
							else
							{
	//		        	if(extension_loaded('soap'))
	//		        	{
	//		        		$tmp =& Template::allRemoteTemplates($cat_sw);
									$tmp_category = & Template::allRemoteTemplatesCategories();
	//		        	}
		//	        	$ser_data = serialize($tmp);

	//		        	$is_ping = (empty($tmp) || empty($tmp_category)) ? false : true;
											$is_ping = (empty($tmp_category)) ? false : true;

								if(file_exists('../cache/templates_cache.cache'))
								{
									@unlink('../cache/templates_cache.cache');
								}

								if($is_ping)
								{
							$cache_string = array('set_time' => $now,/*,'expired_time' => $set_expired_time,'templates_data' => $tmp,*/'templates_category' => $tmp_category);
							$cache_string = serialize($cache_string);
									file_put_contents('../cache/templates_cache.cache',$cache_string);
								}
							}
	//	        }

							 if(!file_exists( $csvfile))
							{
									 @chmod('../cache', 0755 );
										$successdownload = Template::allRemoteTemplates($cat_sw);
							}elseif(filemtime( $csvfile)+ $templates_cache_time< $now){  //利用文件修改时间判断是否过期
									 $successdownload = Template::allRemoteTemplates($cat_sw);
							}else{
									$successdownload =true;
							}
							if(! $successdownload) {
									 _e('Abnormal network conditions');
									 return '_error';
							}
							include_once(P_LIB.'/csviterator.php');
							$tmp=new CSVIterator($csvfile,true);

							//遍历中就将分页完成，减少内存使用
							$sum_template =0; //模板总数
							$output_array = array();
					 $output_array_name = array();
							$pagestartnum= ($page_number - 1) * $page_count+1;
							$pageendnum=$page_number  * $page_count;
						//---------------templates cache strategy[end]----------------------
									//直接在这里遍历获取$template_owns，目录和编号查找可以减少遍历次数
						if($category_number == '_all'&&intval($category_tpl_id)==0)
						{
											 foreach($template_owns as $key=>$value){
											
													 //$sum_template++;
													 if($pagestartnum<=$sum_template&&$pageendnum>=$sum_template){
															 $output_array2[] = $value;
											 if(!empty($template_owns_name[$key])) $output_array_name[] = $template_owns_name[$key];
													 }
											 }

										$template_categories1 = &$tmp;
	//                    if(empty($template_categories1)) $template_categories1 = array();

											 foreach($template_categories1 as $k => $v)
											 {		
													if($v['is_install']=='1'){
														continue;
													}
													$str = substr($v['archive'],0,-4);
													$is_installed=FALSE;
													//无论是查找和显示列表都需要得到本地安装模板的信息
													if(isset($installed_templates[$str]))
													{
															$k1=$installed_templates[$str];
															$template_owns[$k1] .= '#$'. $v['demourl'].'#$'.$v['id'];
															
													}
													 if(!$is_installed){
															 $sum_template++;
	//                             $template_owns[] = $v;
																if($pagestartnum<=$sum_template&&$pageendnum>=$sum_template){
																	 $output_array[] =array_map(array('Template','gbktoutf8'), $v);
															 }
													 }

											}
						}else if($category_number == '_free'&&intval($category_tpl_id)==0){
							
							$template_categories1 = &$tmp;
									 foreach($template_categories1 as $k => $v)
											 {		
											if(substr($v['archive'],0,-4)== $_template_name){
											$template_name = $v['id'];
											}
													if($v['is_install']!='1'){
														continue;
													}
													$str = substr($v['archive'],0,-4);
													$is_installed=FALSE;
													//无论是查找和显示列表都需要得到本地安装模板的信息
													if(isset($installed_templates[$str]))
													{
															$k1=$installed_templates[$str];
															$template_owns[$k1] .= '#$'. $v['demourl'].'#$'.$v['id'];
															if($k1+1>=$pagestartnum&&$pageendnum>=$k1+1){
																	$output_array[$k1-$pagestartnum+1].= '#$'. $v['demourl'].'#$'.$v['id'];
															}
															$is_installed=TRUE;
													}
													 if(!$is_installed){
															 $sum_template++;
	//                             $template_owns[] = $v;
																if($pagestartnum<=$sum_template&&$pageendnum>=$sum_template){
																	 $output_array[] =array_map(array('Template','gbktoutf8'), $v);
															 }
													 }

											}
						}elseif($category_number == '_color'&&intval($category_tpl_id)==0){
							$colorval=$category_sub_id;
							$colorregxp='/(?:^|\|)'.preg_quote($colorval).'(?:\||$)/i';
							if(!empty($colorval)){
								foreach($tmp as $k => $v){//print_r($v);die();
									if(substr($v['archive'],0,-4)== $_template_name){
										$template_name = $v['id'];
									}
									$tmplcolor=$v['color'];
									if(preg_match($colorregxp,$tmplcolor)){
										 $sum_template++;
										 if($pagestartnum<=$sum_template&&$pageendnum>=$sum_template){
											 $output_array[] = array_map(array('Template','gbktoutf8'), $v);
										  }
									}
								}
									$this->assign('color', strtolower($colorval));
							}
						}
						else
						{
	//		        $template_categories1 = array();//某类型下的模板
	//		        if(empty($tmp)) $tmp = array();
							foreach($tmp as $k => $v)
							{//print_r($v);die();
						if(substr($v['archive'],0,-4)== $_template_name){
							$template_name = $v['id'];
						}

						if($category_sub_id!=0){
							if($category_sub_id == $v['sub_id'])
							{
								if(intval($category_tpl_id)==0||intval($category_tpl_id)==$v['id']){
	//                                      $template_categories1[] = $v;
																					 $sum_template++;
																						if($pagestartnum<=$sum_template&&$pageendnum>=$sum_template){
																							 $output_array[] = array_map(array('Template','gbktoutf8'), $v);
																					 }
																		}
							}
						}else{
							if($category_number == $v['ezsite_template_category_id']||$category_number == '_all') //处理查找模板编号
							{
								if(intval($category_tpl_id)==0||intval($category_tpl_id)==$v['id']){
	//                                      $template_categories1[] = $v;
																						$sum_template++;
																					 if($pagestartnum<=$sum_template&&$pageendnum>=$sum_template){
																							 $output_array[] = array_map(array('Template','gbktoutf8'), $v);
																					 }
																		}
							}
						}
							}
	//                            $template_owns= $template_categories1;
						}

							$tmp->close();
	//		 echo memory_get_peak_usage()/1024/1024;
				//------------------------分页处理[start]----------------------

	//	    $sum_template = count($template_owns);
				$page_sum_page = intval($sum_template / $page_count);//page_sum_page总页数
				if($sum_template % $page_count > 0) $page_sum_page++;



				//------------------------分页处理[end]----------------------
				$this->assign('is_ping', $is_ping);
				$this->assign('template_name', $template_name);
				$this->assign('last_page',$page_sum_page);//最后一页
				$this->assign('page_number',$page_number);//当前页码
				$this->assign('output_array', $output_array);//每页信息
				$this->assign('output_array_name', $output_array_name);
				$this->assign('template_owns', $template_owns);//总模板信息
				$this->assign('template_owns_name', $template_owns_name);
				$this->assign('tmplate_category',$tmp_category);//模板分类信息
				$this->assign('category_number',$category_number);//模板类型
				$this->assign('category_sub_id',$category_sub_id);//模板下级分类
				$this->assign('category_tpl_id',$category_tpl_id);//搜索模板分类
				 $this->assign('install_template_num',count($installed_templates));//本地安装模板数量
	}  catch (CSVBadFormatException $e){
		$localcsvfile='../cache/templates_cache.csv';
        	if(file_exists( $localcsvfile))
          {
            @unlink( $localcsvfile);
          }
		if($exe_times>=3){
			 $this->setVar('json', Toolkit::jsonERR(__('Abnormal network conditions')));
			 return '_error';
		}else{
			return $this->admin_list();
		}
	}
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

        include_once(P_LIB.'/zip.php');//加载zip功能代码
        
        $file_info =& ParamHolder::get('tpl_file', array(), PS_FILES);
        $file_name = ToolKit::get_filename($file_info["name"]);
        if (empty($file_info)) {
            Notice::set('mod_template/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_template', 'admin_upload'));
        }
        
        if(is_dir(ROOT.'/template/'.$file_name)) {
            Notice::set('mod_template/msg',__('Template with the same name exists!'));
            Content::redirect(Html::uriquery('mod_template', 'admin_list'));
        }
        
        if (!$this->_savetplFile($file_info)) {
            Notice::set('mod_template/msg', __('Uploading template file failed!'));
            Content::redirect(Html::uriquery('mod_template', 'admin_upload'));
        }
        
        if(extension_loaded('zip'))
        {
	        $tpl_zip = new ZipArchive(); 
	        $tpl_zip->open(ROOT.'/template/'.$file_info["name"]); 
	        $tpl_zip->extractTo(ROOT."/template/".$file_name); 
	        $tpl_zip->close();
        }
        else
        {
	        if(!file_exists(ROOT."/template/".$file_name))
	        {
	        	mkdir(ROOT."/template/".$file_name,0755);
	        }
	        $z = new zipper();
	        $z->ExtractTotally(ROOT.'/template/'.$file_info["name"],ROOT."/template/".$file_name);
        }
        @unlink(ROOT.'/template/'.$file_info["name"]);
        
        if (!file_exists(ROOT.'/template/'.$file_name.'/conf.php') || !file_exists(ROOT.'/template/'.$file_name.'/template_info.php')) {
            Notice::set('mod_template/msg', __('The file conf.php and(or) template_info.php does not exist'));
            Toolkit::rmdir_template($file_name);
            Content::redirect(Html::uriquery('mod_template', 'admin_upload'));
        }
        
        Notice::set('mod_template/msg', __('Uploading language file succeeded!'));
        Content::redirect(Html::uriquery('mod_template', 'admin_list'));
    }
    
    /**
     * 安装模板
     */
    public function admin_make_default($curr_tpl_id,$is_remote_tpl) {
        
        $template = '';
        $tpl_path = ROOT.DS.'template';
//        $curr_tpl_id = trim(ParamHolder::get('tpl_id', '0'));
//        $is_remote_tpl = trim(ParamHolder::get('is_remote', '0'));
        if (intval($curr_tpl_id) == 0) {
            return false;
        }
        if (intval($is_remote_tpl) == 1) {
            $template = $this->_downloadRemote($curr_tpl_id);
            if (!$template) {
                return false;
            }
        } else {
            try {
                $curr_template = new Template($curr_tpl_id);
                $template = $curr_template->template;
            } catch (Exception $ex) {
                return false;
            }
        }
        try {
			include_once(ROOT.'/template/'.DEFAULT_TPL.'/template_info.php');
        } catch (Exception $ex) {
            return false;
        }
        
        return true;
    }
	
    /**
     * 设为默认模板时触发,切模板
     */
    public function admin_make_owntpl($curr_tpl_id) {
        
        if(!file_exists(ROOT.'/template/'.$curr_tpl_id.'/template_info.php')) return false;
        
        try {
            $o_param = new Parameter();
            $tpl_param =& $o_param->find("`key`='DEFAULT_TPL'");
            $tpl_param->val = $curr_tpl_id;
            $tpl_param->save();
            
            $tpl_default_id =& $o_param->find("`key`='DEFAULT_TPL_ID'");
            $tpl_default_id->val = '0';
            $tpl_default_id->save();
			
			//2010-6-12 zhangjc two => three
			include_once(ROOT.'/template/'.DEFAULT_TPL.'/template_info.php');
			if(in_array("center",TplInfo::$positions)){
				$o_block = new ModuleBlock();
				$modules = $o_block->findAll("`s_pos`='right' and `module`='mod_product'");
				if(sizeof($modules) > 0){
					foreach($modules as $module){
						$module_info['s_pos'] = "center";
						$o_module = new ModuleBlock($module->id);
						$o_module->set($module_info);
						$o_module->save();
					}
				}
			}

			
        } catch (Exception $ex) {
            return false;
        }
        
        return true;
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
    
    /**
     * 重置模板数据
     */
    public function reset_tpl_data($tpl_name = DEFAULT_TPL,$flag = 1) {
		if (!$this->_requireAdmin()) {
            return '_result';
        }
		if($this->downloadsql($tpl_name)){
			//先删除原来数据
			Template::ResetTplData();
			// 2011/03/04 重置默认语言
			$this->initLanguage();
			//更新数据库
			include ROOT.'/include/fun_install.php';
			create_table(Config::$db_name,Config::$tbl_prefix,ROOT."/template/".$tpl_name."/".$tpl_name.'_2_sample.sql');
			$db = MysqlConnection::get();
			$prefix = Config::$tbl_prefix;
			$del_sql="delete from {$prefix}module_blocks where module='mod_static' and action='custom_html' and alias='mb_foot' and s_param like '%www.sitestar.cn%'";
			$db->query($del_sql);
			if(!Toolkit::getAgent() && !IS_INSTALL)
			{
				
				
				$update_qq = "UPDATE {$prefix}online_qqs SET account='12345678'";
				$update_siteinfo = "UPDATE {$prefix}site_infos SET site_name='站点名称',keywords='站点关键字',description='站点描述'";
				$update_moduleblocks = <<<SQL
UPDATE {$prefix}module_blocks SET s_param='a:4:{s:7:"img_src";s:20:"images/site_logo.png";s:8:"img_desc";s:0:"";s:9:"img_width";s:3:"288";s:10:"img_height";s:2:"48";}' where alias="mb_logo" and s_locale="zh_CN"		
SQL;
				$update_friendlinks = "UPDATE {$prefix}friendlinks SET fl_img = 'friend_link.gif',fl_addr='http://#',fl_name='links'";
				try{
					$db->query($update_qq);
					$db->query($update_siteinfo);
					$db->query($update_moduleblocks);
					$db->query($update_friendlinks);
				} catch(Exception $ex) {
					$this->assign('json', Toolkit::jsonERR(__('Install Error!')));
					return '_result'; 
				}
			}
			//upload文件操作
			$this->delDir(ROOT."/upload");
			$this->xCopy(ROOT."/template/".$tpl_name."/".$tpl_name."_2_upload/image",ROOT."/upload/image",1);
			$this->xCopy(ROOT."/template/".$tpl_name."/".$tpl_name."_2_upload/flash",ROOT."/upload/flash",1);
			$this->xCopy(ROOT."/template/".$tpl_name."/".$tpl_name."_2_upload/file",ROOT."/upload/file",1);
			$this->xCopy(ROOT."/template/".$tpl_name."/".$tpl_name."_2_upload/media",ROOT."/upload/media",1);
			$db = MysqlConnection::get();
			$prefix = Config::$tbl_prefix;
				
			$update_para = "UPDATE {$prefix}parameters SET `val`='' where `key`='BACKGROUND_INFO'";
			$db->query($update_para);
			@unlink(ROOT."/template/".$tpl_name."/".$tpl_name.'_2_sample.sql');
			$res1 = $this->full_rmdir(ROOT."/template/".$tpl_name."/".$tpl_name."_2_upload");
        	
			if($flag == 1)	
			{
				$str6 = <<<HTML
<script language="JavaScript">   
    updateProgress("模板重置成功", 390);
    top.location.reload();   
</script>
HTML;
//				$this->assign('json', Toolkit::jsonOK());
				echo $str6;
				flush();
				die;
			}
			else
			{
				$str6 = <<<HTML
<script language="JavaScript">   
    updateProgress("模板安装成功", 390);   
</script>
HTML;
				echo $str6;
				flush();
				return true;
			}
		} else {
			if($flag == 1)
			{
				$this->assign('json', Toolkit::jsonERR(__('Install Error!')));
			}
			else
			{
				return false;
			}
		}
		
        if($flag == 1)	return '_result';
	}
	
	/**
     * 安装模板流程
     */
    public function admin_install_template()
    {
    	try{
    	if (!$this->_requireAdmin()) {
			die(__('No Permission!'));
        }
        $curr_tpl_id = trim(ParamHolder::get('tpl_id', '0'));
        $is_remote_tpl1 = trim(ParamHolder::get('is_remote', '0'));
        $has_data = trim(ParamHolder::get('has_data', '0'));//新参数
        $tpl_name1 = trim(ParamHolder::get('tpl_name', '0'));
        if (intval($is_remote_tpl1) == 1)//模板文件远程下载安装
        {
        	if(!file_exists(ROOT.'/template/'.$tpl_name1))
        	{
	        	$res_bool = $this->admin_make_default($curr_tpl_id,$is_remote_tpl1);//取模板文件
	        	if(!$res_bool)
	        	{
					$this->err_msg(__('Install Error!'));
					//die(__('Install Error!'));
	        	}
        	}
        	
        	if($has_data == 1)//安装新数据替换旧数据（upload&sql）
        	{
        		$res = $this->reset_tpl_data($tpl_name1,2);
        		if(!$res)
        		{
					$this->err_msg(__('Install Error!'));
        		}
        	}
        	
        	$res_bool = $this->admin_make_owntpl($tpl_name1);
        	if(!$res_bool)
        	{
				$this->err_msg(__('Install Error!'));
        	}
        	
        	if($has_data == 1)
        	{
				$str7 = <<<HTML
<script type="text/javascript">
//alert("模板安装成功！");	
top.location.reload();
</script>
HTML;
				echo $str7;
				flush();
        	}
        }
        else//模板文件本地已存在
        {
        	if($has_data == 1)//安装新数据替换旧数据（upload&sql）
        	{
  				$res = $this->reset_tpl_data($tpl_name1,2);  	
        		if(!$res)
        		{
					die(__('Install Error!'));
        		}	
        	}
        	
        	$res_bool = $this->admin_make_owntpl($tpl_name1);
        	if(!$res_bool)
        	{
				die(__('Install Error!'));
        	}
        	if($has_data == 1)
        	{
				$str7 = <<<HTML
<script type="text/javascript">	
top.location.reload();
</script>
HTML;
				echo $str7;
				flush();
        	}
        }
        
		  $this->err_msg(__('Installation Completed'));
    	}
    	catch(Exception $ex)
    	{
    		$res_bool = $this->admin_make_owntpl($tpl_name1);
        	if(!$res_bool)
        	{
				die(__('Install Error!'));
        	}
    	}    	
    }
    
    /**
     * 模板在本地安装时安装界面
     */
    public function template_local()
    {
    	$tpl_name = trim(ParamHolder::get('tpl_name', '0'));
    	$this->_layout = 'template';
    	$this->assign('install_tag',"pass");
    	$this->assign('tpl_name',$tpl_name);
    	return 'template_local';
    }
    
    /**
     * 模板远程下载模式
     */
    public function template_remote()
    {
    	$tpl_id = trim(ParamHolder::get('tpl_id', '0'));
    	$remote_tpl = trim(ParamHolder::get('is_remote', '0'));
    	$tpl_name = trim(ParamHolder::get('tpl_name', '0'));
    	$this->_layout = 'template';
    	
    	$install_tag = trim(ParamHolder::get('tag'));
    	$this->assign('install_tag',$install_tag);
    	$this->assign('tpl_id',$tpl_id);
    	$this->assign('remote_tpl',$remote_tpl);
    	$this->assign('tpl_name',$tpl_name);
    	return 'template_remote';
    } 
    
    public function show_reset_data()
    {
    	$this->_layout = 'blank';
    	return 'reset_tpl_data';
    }
    
    private function _requireAdmin() {
        if (!ACL::requireRoles(array('admin'))) {
            $this->assign('json', Toolkit::jsonERR(__('No Permission!')));
            return false;
        }
        return true;
    }
    
    private function _savetplFile($struct_file) {
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
        move_uploaded_file($struct_file['tmp_name'], ROOT.'/template/'.$struct_file['name']);
        return ParamParser::fire_virus(ROOT.'/template/'.$struct_file['name']);
    }
	
    /**
     * 
     * @param $tplid
     * @return unknown_type
     */
    private function _downloadRemote($tplid) {
    	include_once(P_LIB.'/zip.php');//加载zip功能代码
    	ini_set('max_execution_time', 600);//把php脚本运行超期时间设为10分钟
        $tpl_path = ROOT.DS.'template';
        
        // Get template info first
        /*
        $client =& Toolkit::initSoapClient();
        $tpl_info = unserialize($client->getTplInfo(EZSITE_UID, $tplid));
        */
        $ezsite_uid = EZSITE_UID;
        $client_source = ParamParser::getDns();
   		$sour = serialize($client_source);
        $tpl_info = file_get_contents(SVC_TPL."getTplInfo.php?ezsite_uid=$ezsite_uid&tplid=$tplid&tag=$sour");

        $tpl_info = unserialize($tpl_info);
        
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
            Toolkit::removeDir($tpl_path.DS.$folder_name);
        }
         
        // Try to download the file
//        $remote_file = fopen($tpl_info['package_url'].'&uid='.EZSITE_UID, 'r');
		$remote_file = fopen(SVC_TPL."../template_jingjian/{$tpl_info['archive']}",'r');
        if (!$remote_file) {
            return false;
        }
        $local_file = fopen($tpl_path.DS.$tpl_info['archive'], 'w');
        while (!feof($remote_file)) {
            fwrite($local_file, 
                fgets($remote_file, 4096), 
                4096);
        }
        fclose($local_file);
        fclose($remote_file);
        
        // Download finished. Now extract.
        if(extension_loaded('zip'))
        {
	        $tpl_zip = new ZipArchive(); 
	        $tpl_zip->open($tpl_path.DS.$tpl_info['archive']); 
	        @$tpl_zip->extractTo($tpl_path.DS.$folder_name); 
	        $tpl_zip->close();
        }
        else
        {
	        if(!file_exists($tpl_path.DS.$folder_name))
	        {
	        	mkdir($tpl_path.DS.$folder_name,0755);
	        }
	        $zipper = new zipper();
	        $zipper->ExtractTotally($tpl_path.DS.$tpl_info['archive'],$tpl_path.DS.$folder_name);
        }
        @unlink($tpl_path.DS.$tpl_info['archive']);
        
        if (!file_exists($tpl_path.DS.$folder_name.DS.'conf.php') || 
            !file_exists($tpl_path.DS.$folder_name.DS.'template_info.php')) {
            Toolkit::rmdir_template($folder_name);
            return false;
        }
        
        if(file_exists($tpl_path.DS.$folder_name.DS.'conf.php'))
        {
        	include $tpl_path.DS.$folder_name.DS.'conf.php';
        }
        else
        {
        	die(__('Install Error!'));
        }
        
        
    	
        return $tpl_name;
    }
    
 	/**
 	 * 下载sql和upload时触发
 	 * @return 成功返回模板名，失败返回false
 	 */
    private function downloadsql($tpl_name)
    {
    	include_once(P_LIB.'/zip.php');
		//判断文件是否存在
		if(file_exists(ROOT.'/template/'.$tpl_name.'/'.$tpl_name.'_2_sample.sql') && file_exists(ROOT.'/template/'.$tpl_name.'/'.$tpl_name.'_2_upload')){
			return true;
		}
    	//download sql file
        
        //为了应对模板容量巨大的情况
        try{
        ini_set('max_execution_time', 1800);//把php脚本运行超期时间设为10分钟
		ini_set('memory_limit', '60M');//允许php获取60的内存大小
		
		if(file_exists("../template/{$tpl_name}_upload.zip"))
		{
			@unlink("../template/{$tpl_name}_upload.zip");
		}
		
//		$remote_file = fopen(SVC_TPL."download_upload.php?tpl_name={$tpl_name}",'r');
		$remote_file = fopen(SVC_TPL."../sitestar27_data/{$tpl_name}.zip",'r');
		
		$local_file = fopen("../template/{$tpl_name}_upload.zip",'w');
		/*
		$soap1 = new SoapClient(SVC_TPL.'MysqlUploadOutput.wsdl');
		$file_size = $soap1->uploadOutput($tpl_name);
		*/
		$file_size = file_get_contents(SVC_TPL."uploadOutput.php?tpl_name=$tpl_name");
		$count = 0;
		$width = 390;
		$width1 = $width + 2;
		$progress = 0;
		$str1 = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/transitional.dtd">   
<html>   
<head>   
    <title>下载模板</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    
    <style>   
    body, div input { font-family: Tahoma; font-size: 9pt }   
    </style>   
    <script language="JavaScript">   
    <!--   
    function updateProgress(sMsg, iWidth)  
    {     
        document.getElementById("status").innerHTML = sMsg;   
        document.getElementById("progress").style.width = iWidth + "px";   
        document.getElementById("percent").innerHTML = parseInt(iWidth /$width * 100) + "%";   
     }   
    //-->   
    </script>        
</head>
<body>   
<div style="margin: 4px; margin-left:40px;margin-top:45px;*margin-top:15px;;padding: 8px; border: 1px solid gray; background: #EAEAEA; width: {$width1}px">   
    <div><font color="gray"></font></div>   
    <div style="padding: 0; background-color: white; border: 1px solid navy; width: {$width}px">   
    <div id="progress" style="padding: 0; background-color: #FFCC66; border: 0; width: 0px; text-align: center;   height: 16px"></div>               
    </div>   
    <div id="status"> </div>   
    <div id="percent" style="position: relative; top: -30px; text-align: center; font-weight: bold; font-size: 8pt">0%</div>   
</div>
HTML;
		echo $str1;
		flush();
		while(!feof($remote_file))
		{
			$tmp = fwrite($local_file,fgets($remote_file,4096),4096);
			$count += $tmp;
			$progress = $count*$width/$file_size;
			$ptx = min($width-20,intval($progress));
$str2 = <<<HTML
<script language="JavaScript">   
    updateProgress("正在下载模板....", $ptx);   
</script>
HTML;
			echo $str2;
			flush();
		}
$width2 = $width - 19;	
$str3 = <<<HTML
<script language="JavaScript">   
    updateProgress("模板下载成功,正在安装模板...", $width2);   
</script>
HTML;
		echo $str3;
		flush();
		
		
		
		fclose($local_file);
		fclose($remote_file);
		
		if(extension_loaded('zip'))
		{
			$tpl_zip = new ZipArchive(); 
	        $tpl_zip->open("../template/{$tpl_name}_upload.zip"); 
	        $tpl_zip->extractTo("../template/{$tpl_name}"); 
	        $tpl_zip->close();
		}
		else
		{
			if(!file_exists("../template/{$tpl_name}"))
			{
				mkdir("../template/{$tpl_name}",0755);
			}
			$z = new zipper();
			$z->ExtractTotally("../template/{$tpl_name}_upload.zip","../template/{$tpl_name}");
		}
        @unlink("../template/{$tpl_name}_upload.zip");
        
        
    	} catch(Exception $ex) {
    		return false;
    	}
    	
    	return $tpl_name;
    } 
    
    /**
     * 文件拷贝
     * @param $source  拷贝$source下的文件
     * @param $destination 黏贴到目标文件
     * @param $child 拷贝目标包括子目录 1:包括 0:不包括 
     * @return bool
     */
    private function xCopy($source, $destination, $child){
    	if(!is_dir($source)){
    		return false;
    	}
    	if(!is_dir($destination)){
    		@mkdir($destination,0755);
    	}

    	$handle=dir($source);
    	while($entry=$handle->read()) {
    		if(($entry!=".")&&($entry!="..")){
    			if(is_dir($source."/".$entry)){
    				if($child)
    				$this->xCopy($source."/".$entry,$destination."/".$entry,$child);
    			}
    			else{
    				@copy($source."/".$entry,$destination."/".$entry);
    			}

    		}
    	}

    	return true;
    }
    
	private function full_rmdir( $dir )
   	{
       if ( !is_writable( $dir ) )
       {
           if ( !@chmod( $dir, 0777 ) )
           {
               return FALSE;
           }
       }
       
       $d = @dir( $dir );
       while ( FALSE !== ( $entry = $d->read() ) )
       {
           if ( $entry == '.' || $entry == '..' )
           {
               continue;
           }
           $entry = $dir . '/' . $entry;
           if ( is_dir( $entry ) )
           {
               if ( !$this->full_rmdir( $entry ) )
               {
                   return FALSE;
               }
               continue;
           }
           if ( !@unlink( $entry ) )
           {
               $d->close();
               return FALSE;
           }
       }
       
       $d->close();
       
       @rmdir( $dir );
       
       return TRUE;
   }
   
   private function delDir($path)
   {
   		if(!(strpos($path,'.svn') === false)) return 1;
	   	if (is_dir($path))
	   	{
	   		if ($dh = opendir($path))
	   		{
	   			while (($file = readdir($dh)) !== false)
	   			{
	   				if($file!=".."&&$file!="." && $file!='friend_link.gif')
	   				{
	   					if(is_dir($path."/".$file))
	   					{
	   						if(!$this->delDir($path."/".$file))
	   						{
	   							return 0;
	   						}
	   					}
	   					else
	   					{
	   						if(!@unlink($path."/".$file))
	   						{
	   							return 0;
	   						}
	   					}
	   				}
	   			}
	   			closedir($dh);
	   		}
	   		return 1;//rmdir($path);
	   	}
   }
   
   // 2011/03/04 重置站点语言
   private function initLanguage() {
   	   // 2011/03/04 重置默认语言、广告工具、缩略图、水印
		$o_param = new Parameter();
        $locale_param =& $o_param->find("`key`='DEFAULT_LOCALE'");
        $locale_param->val = 'zh_CN';
        $locale_param->save();
        $advert_param =& $o_param->find("`key`='ADVERT_STATUS'");
        $advert_param->val = '0';
        $advert_param->save();
        $thumb_param =& $o_param->find("`key`='THUMB_STATUS'");
        $thumb_param->val = '0';
        $thumb_param->save();
        $watermark_param =& $o_param->find("`key`='WATERMARK_STATUS'");
        $watermark_param->val = '0';
        $watermark_param->save();
        SessionHolder::set('_LOCALE', 'zh_CN');
		// 2011/03/04 删除新增语言
		$od_lang = new Language();
		$del_lang_data = $od_lang->findAll('locale != ? AND locale != ? ', array('en', 'zh_CN'));
		foreach($del_lang_data as $del_lang_val) {
			$del_lang = new Language($del_lang_val->id);
            $del_lang->delete();
		}
   }
   	   
    public function admin_getInstallInfo(){
   		$ezsite_uid = EZSITE_UID;
   		$sour_arr = array();
   		$tpl_id = ParamHolder::get("tpl_id");
   		$client_source = ParamParser::getDns();
   		$sour = serialize($client_source);
        $get_sour = file_get_contents(SVC_TPL."getTplInfo.php?ezsite_uid=$ezsite_uid&tplid=$tpl_id&tag=$sour");
       $sour_arr = unserialize($get_sour);
   		if ($get_sour=='error1') {
   			$isping = 'refuse';
   		}elseif($get_sour=='error2'){
   			$isping = 'error2';
   		}else {
   			
   			$isping = 'pass';
   		}
   		$this->assign('json',$isping);
   		return '_result';
          
   }
   private function err_msg($msg){
	   $str = <<<HTML
<script type="text/javascript">
alert("$msg");	
top.location.reload();
</script>
HTML;
				echo $str;
				flush();
				die();
   }
    
}
?>
