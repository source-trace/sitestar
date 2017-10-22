<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModTool extends Module {
	protected $_filters = array(
	'check_login' => '{upload_img}'
	);
	public function img_edit_update() {

		$img_id =& ParamHolder::get('img_id', array());

		if ($img_id <=0) {
			$this->assign('json', Toolkit::jsonERR(__('Missing image information!')));
			return '_result';
		}
		$file_info =& ParamHolder::get('img_name', array(), PS_FILES);
		if (!empty($file_info["name"])) {
			if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_info["name"])) {
				Notice::set('mod_marquee/msg', __('File type error!'));
				Content::redirect(Html::uriquery('mod_tool', 'img_edit', array('id' => $img_id)));
			}
			if(file_exists(ROOT.'/upload/image/'.$file_info["name"])) {
				$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
			}
			if (!$this->_savelinkimg($file_info)) {
				Notice::set('mod_marquee/msg', __('Link image upload failed!'));
				Content::redirect(Html::uriquery('mod_tool', 'img_edit', array('id' => $img_id)));
			}
		}

		$is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
		try {
			//add image
			if (!empty($file_info["name"])) {
				$img_info['img_name'] = 'upload/image/'.$file_info["name"];
			}

			$img_info['img_type'] = '1';

			// Data operation
			$o_m = new Marquee($img_id);
			$o_m->set($img_info);
			$o_m->save();
		} catch (Exception $ex) {
			$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		Notice::set('mod_marquee/msg', __('Link updated successfully!'));
		Content::redirect(Html::uriquery('mod_tool', 'new_mblock_s2',array("widget"=>"mod_marquee-marquee")));
	}
	public function img_edit() {
		$this->_layout = 'content';

		$id = ParamHolder::get('id', '0');
		$id = intval($id);
		if ($id == '0') {
			$this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
			return '_error';
		}
		try {
			$curr_m = new Marquee($id);
			$this->assign('curr_m', $curr_m);
		} catch (Exception $ex) {
			$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			return '_error';
		}

	}
	public function upload_img() {
		$this->_layout = 'content';
		$this->assign('test', '1234');
	}
	public function img_delete() {
		$id = ParamHolder::get('id', '0');
		$id = intval($id);
		if ($id == 0) {
			$this->setVar('json', Toolkit::jsonERR(__('Invalid ID!')));
			return '_result';
		}

		try {
			$curr_m = new Marquee($id);
			$curr_m->delete();
		} catch (Exception $ex) {
			$this->setVar('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}

		Content::redirect('index.php?'.Html::xuriquery('mod_tool', 'new_mblock_s2',array("widget"=>"mod_marquee-marquee")));
	}
	public function img_create() {
		/*die();//此函数已经无用
		$file_info =& ParamHolder::get('img_name', array(), PS_FILES);
		if ($file_info['error'] > 0) {
			Notice::set('mod_marquee/msg', __('Invalid post file data!'));
			Content::redirect(Html::uriquery('mod_tool', 'upload_img'));
		}
		if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_info["name"])) {
			Notice::set('mod_marquee/msg', __('File type error!'));
			Content::redirect(Html::uriquery('mod_marquee', 'upload_img'));
		}
		if(file_exists(ROOT.'/upload/image/'.$file_info["name"])) {
			$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
		}
		if (!$this->_savelinkimg($file_info)) {
			Notice::set('mod_marquee/msg', __('Link image upload failed!'));
			Content::redirect(Html::uriquery('mod_marquee', 'upload_img'));
		}
		try {
			$mod_marquee['img_name'] = 'upload/image/'.$file_info["name"];

			$mod_marquee['img_type'] = '1';

			// Data operation
			$o_m = new Marquee();
			$o_m->set($mod_marquee);
			$o_m->save();
		} catch (Exception $ex) {
			Notice::set('mod_marquee/msg', $ex->getMessage());
			Content::redirect(Html::uriquery('mod_tool', 'upload_img'));
		}

		Notice::set('mod_marquee/msg', __('Link added successfully!'));
		Content::redirect('index.php?'.Html::xuriquery('mod_tool', 'new_mblock_s2',array("widget"=>"mod_marquee-marquee")));
	*/
	}
	public function upd_position() {
		if (!$this->_requireAdmin()) {
			return '_result';
		}

		foreach (TplInfo::$positions as $pos) {
			$target_pos = trim(ParamHolder::get('SZ_'.$pos, ''));
			if (strlen($target_pos) == 0) {
				continue;
			}
			parse_str(urldecode($target_pos));
			if(isset($MODBLK)){

				$n_modblk = sizeof($MODBLK);
				if ($n_modblk > 0) {
					try {
						for ($i = 0; $i < $n_modblk; $i++) {
							if (is_numeric($MODBLK[$i])) {
								$curr_block = new ModuleBlock($MODBLK[$i]);
								if ($curr_block) {
									$curr_block->s_pos = $pos;
									$curr_block->i_order = $i;
									$curr_block->save();
								}
							}
						}
					} catch (Exception $ex) {
						$this->setVar('json', Toolkit::jsonERR($ex->getMessage()));
						return '_result';
					}
				}
			}
			unset($MODBLK);
		}

		$this->setVar('json', Toolkit::jsonOK());
		return '_result';
	}

	public function new_mblock_s1() {
		$this->_layout = 'content';

		if (!$this->_requireAdmin()) {
			return '_error';
		}

		include_once(P_INC.'/widgets.php');
		if (empty($widgets)) {
			$this->setVar('json', Toolkit::jsonERR(__('No widgets defined!')));
			return '_error';
		}

		// Arrange widgets info
		global $userlevels;
		$widgets_info = array();
		foreach($widgets as $w_module => $w_actions) {
			if (check_mod($w_module)) {
				foreach($w_actions as $w_action => $w_info ) {
					$widgets_info[$w_module.'-'.$w_action] = __($w_info['name']);
				}
			}
		}

		$this->assign('widgets_info', $widgets_info);
	}

	public function new_mblock_s2() {
		$this->_layout = 'content';

		if (!$this->_requireAdmin()) {
			return '_error';
		}

		$currpos = ParamHolder::get('currpos', 'center');
		$modblk = ParamHolder::get('modblk', 'center');

		$this->assign('currpos', $currpos);
		$this->assign('modblk', $modblk);

		$widget = ParamHolder::get('widget', '');
		list($w_module, $w_action) = explode('-', $widget);
		if (strlen(trim($w_module)) == 0 || strlen(trim($w_action)) == 0) {
			$this->setVar('json', Toolkit::jsonERR(__('Invalid widget info!')));
			return '_error';
		}
		$this->assign('w_module', $w_module);
		$this->assign('w_action', $w_action);

		include_once(P_INC.'/widgets.php');
		$this->assign('params', $widgets[$w_module][$w_action]['parameters']);

		$this->assign('roles', Toolkit::loadAllRoles());
		$this->assign('positions', Toolkit::reformPositions());
	}

	public function add_mblock() {
		if (!$this->_requireAdmin()) {
			return '_result';
		}
		$locales = trim(SessionHolder::get('_LOCALE'));
		$mb_data =& ParamHolder::get('mb', array());
		$ex_params =& ParamHolder::get('ex_params', array());
		$modblk = ParamHolder::get('modblk', 'center');
		$is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
		try {
			//QQ浮动样式写到Parameter表，修复删除QQ模板后，浮动样式会改为1的bug
			if ($mb_data['module']=='mod_qq' && $mb_data['action']=='qqlist') {
				$f_style = $ex_params['qq_show_type'];
				$o_param = new Parameter();
				$o_res = $o_param->find(' `key`=?',array("QQ_SHOW_TYPE"));
				if ($o_res) {
					$o_res->QQ_SHOW_TYPE = $f_style;
					$o_res->save();
				}else{
					$o_param->key='QQ_SHOW_TYPE';
					$o_param->val=$f_style;
					$o_param->save();
				}
			}
			$mb_data['alias'] = 'mb_'.Toolkit::randomStr(8);
			$curr_mblock = new ModuleBlock();
			$curr_mblock->set($mb_data);
			if (isset($mb_data['show_title'])&&$mb_data['show_title']) {
				$curr_mblock->show_title = '1';
			} else {
				$curr_mblock->show_title = '0';
			}
			$curr_mblock->published = '1';
			$curr_mblock->s_locale = SessionHolder::get('_LOCALE', DEFAULT_LOCALE);
			$query_str = '';
			$dispage = trim(ParamHolder::get('dispage', ''));
			if (!empty($dispage)) {
				// for rewrite
				if (MOD_REWRITE == 2) {
					$result = $this->rewrite($dispage);
					$dispage = preg_replace('/'.$result['pattern'].'/i', $result['replace'], $dispage);
				}elseif(MOD_REWRITE == 3){
					$http = substr($dispage,0,strrpos($dispage,'/'));
					$http .= '/index.php?'; 
					include(P_INC.'/custom.php');
			        $_path=substr($dispage,strrpos($dispage,'/')+1);
			        if (empty($_path)) {
			        	$_path = 'index';
			        }
			        
			        $_path = str_replace('.html','',$_path);//需要去掉.html
			        
			        if (strpos($_path,'-')) {//系统默认Url
			        	$arr = explode("-",$_path);
			        	$m_url = $arr[1];
			        }else{//用户自定义URL
			        	$m_url = $_path;
			        }
					$o = new MenuItem();
					$s = $o->find("`url`='".$m_url."' and s_locale='".$locales."'");
					if(!$s){//不是用户自定义链接，则分析找系统的链接
						foreach ($custom as $k=>$pa_v){
						  	if ($m_url==$k) {//系统定义的以key为查询条件,如message.html,contact.html
								$_m = $custom[$k]['m'];
								$_a = $custom[$k]['a'];
								$url1 = '_m='.$_m.'&_a='.$_a;
								if ($m_url=='company') {
									$o_sc = new StaticContent();
									$curr_id = $o_sc->find("s_locale=? and flag=?",array($locales,"company"),"ORDER BY `id`");
									$sc_id = $curr_id->id;
								}elseif($m_url=='contact'){
									$o_sc = new StaticContent();
									$curr_id = $o_sc->find("s_locale=? and flag=?",array($locales,"contact"),"ORDER BY `id`");
									$sc_id = $curr_id->id;
								}
								if (!empty($sc_id)) {
									$url1 .= '&sc_id='.$sc_id;
								}
								$dispage = $http.$url1;
							}elseif($pa_v['path'] == $arr[0] && strpos($_path,'-')){//系统定义的，以path为查询条件,如al-0.html,pl-23.html
								$_m = $pa_v['m'];
								$_a = $pa_v['a'];
								if ($m_url==0) {//全部显示
									$url1 = '_m='.$_m.'&_a='.$_a;
								}else{//分类显示，加上ID参数
									$url1 = '_m='.$_m.'&_a='.$_a.'&'.$pa_v['param'].'='.$m_url;
								}
								$dispage = $http.$url1;
							}
						}
					}else{
						$url1 = $s->link;
						$dispage = $http.$url1;
					}
				}
//				var_dump($dispage);exit;
				$url_info = parse_url($dispage);
				if (isset($url_info['query'])) {
					$query_str = trim($url_info['query']);
				}elseif(MOD_REWRITE == 3){
					$query_str = trim($url_info['path']);
				}
			}
			if (ParamHolder::get('dispallpg', false)) {
				$curr_mblock->s_query_hash = '_ALL';
			} else {
				$curr_mblock->s_query_hash = Toolkit::calcMQHash($query_str);
			}
//			exit;
			$curr_mblock->i_order = '-1';
			$modblk_arr = explode(",",$modblk);
			foreach($modblk_arr as $modblk_key=>$modblk_val){
				if($modblk_val!="id"){
					$modblk_tmp['i_order'] = $modblk_key;
					$o_modblk = new ModuleBlock($modblk_val);
					$o_modblk->set($modblk_tmp);
					$o_modblk->save();
				}else{
					$curr_mblock->i_order = $modblk_key;
				}
			}

			$curr_mblock->for_roles = ACL::explainAccess(intval($is_member_only));
			// Now collect extra parameters
			include_once(P_INC.'/widgets.php');
			$params = $widgets[$curr_mblock->module][$curr_mblock->action]['parameters'];
			if ($curr_mblock->action=="flash_slide") {
				$ex_params =& ParamHolder::get('ex_params', array());
				foreach ($ex_params as $k=>$v){
					if (strstr($k,'slide_img_src')) {
						if (empty($v)) {
							$this->assign('json', Toolkit::jsonERR(__('Image can not empty!')));
            				return '_result';
						}
					}
				}
			}
			
			if (!empty($params)) {
				foreach ($params as $param) {
					if (!isset($ex_params[$param['id']])) {
						// 28/4/2010 Add >>
						$multiple = false;
						$category = array();
						$key = str_replace('[]', '', $param['id']);
						$category = & ParamHolder::get( $key, array() );
						if ( is_array($category) && sizeof($category) ) {
							$multiple = true;
							$category_list = join(',', $category);
						}

						if ($multiple) {
							$ex_params[$key] = $category_list;
						} else { // 28/4/2010 Add <<
							//$ex_params[$param['id']] = '';
							$ex_params[$key] = '';
						}
					}
				}
				$curr_mblock->s_param = serialize($ex_params);
			}
			$curr_mblock->save();

			//走马灯效果开始，pic：图片，text：产品名称，picText：图片加产品名称

			if ($ex_params['prd_list_tag']) {//具体产品
				if (sizeof($ex_params['mar_prd_id2'])>0) {
					$mar_prd_id = implode(",",$ex_params['mar_prd_id2']);
					$sql = "id in (".$mar_prd_id.") and s_locale='".$curr_mblock->s_locale."' and published='1'";
				}
			}else{//按产品类别选择
				$mar_prd_id = $ex_params['mar_prd_id'];
				$mar_prd_id = ProductCategory::getAllCategoryID($mar_prd_id);
				$sql = "product_category_id in (".$mar_prd_id.") and s_locale='".$curr_mblock->s_locale."' and published='1'";
			}

			if(isset($ex_params['marquee_class'])&&$ex_params['marquee_class']=="pic"){
				if(!empty($mar_prd_id)){
					$o_prd = new Product();
					$m_prds = $o_prd->findAll($sql);
					$o_marq = new Marquee($curr_mblock->id);
					$o_marq->delete();
					foreach($m_prds as $m_prd){
						$m_cnt['module_id'] = $curr_mblock->id;
						$m_cnt['marquee_type'] = 'pic';
						$m_cnt['title'] = $m_prd->name;
						$m_cnt['pic'] = $m_prd->feature_smallimg;
						$m_cnt['flag'] = 3;
						$m_cnt['link'] = $m_prd->id;
						$m_cnt['i_order'] = $m_prd->i_order;
						$m_cnt['create_time'] = $m_prd->create_time;
						$o_mar = new Marquee();
						$o_mar->set($m_cnt);
						$o_mar->save();
					}
				}
			} else if(isset($ex_params['marquee_class'])&&$ex_params['marquee_class']=="text"){
				if(!empty($mar_prd_id)){
					$o_prd = new Product();
					$m_prds = $o_prd->findAll($sql);
					$o_marq = new Marquee($curr_mblock->id);
					$o_marq->delete();
					foreach($m_prds as $m_prd){
						$m_cnt['module_id'] = $curr_mblock->id;
						$m_cnt['marquee_type'] = 'text';
						$m_cnt['title'] = $m_prd->name;
						$m_cnt['pic'] = $m_prd->feature_smallimg;
						$m_cnt['flag'] = 3;
						$m_cnt['link'] = $m_prd->id;
						$m_cnt['i_order'] = $m_prd->i_order;
						$m_cnt['create_time'] = $m_prd->create_time;
						$o_mar = new Marquee();
						$o_mar->set($m_cnt);
						$o_mar->save();
					}
				}

			}else if(isset($ex_params['marquee_class'])&&$ex_params['marquee_class']=="picText"){
				if(!empty($mar_prd_id)) {
					$o_prd = new Product();
					$m_prds = $o_prd->findAll($sql);

					$o_maquee_exist = new Marquee();
					$ma_exists = $o_maquee_exist->findAll("module_id=".$curr_mblock->id." and pic !=''");
					foreach($ma_exists as $ma_exist){
						$o_marq = new Marquee($ma_exist->id);
						$o_marq->delete();
					}

					foreach($m_prds as $m_prd){
						$m_cnt['module_id'] = $curr_mblock->id;
						$m_cnt['marquee_type'] = 'picText';
						$m_cnt['title'] = $m_prd->name;
						$m_cnt['pic'] = $m_prd->feature_smallimg;
						$m_cnt['flag'] = 3;
						$m_cnt['link'] = $m_prd->id;
						$m_cnt['i_order'] = $m_prd->i_order;
						$m_cnt['create_time'] = $m_prd->create_time;
						$o_mar = new Marquee();
						$o_mar->set($m_cnt);
						$o_mar->save();
					}
				}

			}

		} catch (Exception $ex) {
			$this->setVar('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		$this->setVar('json', Toolkit::jsonOK());
		return '_result';

	}

	public function edit_prop() {
		$this->_layout = 'content';

		if (!$this->_requireAdmin()) {
			return '_error';
		}

		if(!ACL::isAdminActionHasPermission('edit_block', 'process')){
			$this->setVar('json', Toolkit::jsonERR(__('No Permission')));
			return '_error';
		}

		$mb_id = ParamHolder::get('mb_id', '0');
		$mb_id = intval($mb_id);
		if ($mb_id == 0) {
			$this->setVar('json', Toolkit::jsonERR(__('Invalid block ID!')));
			return '_error';
		}
		$curr_mblock = new ModuleBlock($mb_id);
		$curr_marquee = new Marquee();
		$marquees = $curr_marquee->findAll("module_id=".$mb_id);
		foreach ($marquees as $marq){
			if ($marq->flag==3) {
				$prdListArr[] = array($marq->link=>$marq->title);
			}
		}
		$this->assign('curr_mblock', $curr_mblock);
		$this->assign('marquees', $marquees);
		$this->assign('prdListArr', $prdListArr);

		include_once(P_INC.'/widgets.php');
		$this->assign('params', $widgets[$curr_mblock->module][$curr_mblock->action]['parameters']);

		$this->assign('roles', Toolkit::loadAllRoles());
	}

	public function save_prop() {
		if (!$this->_requireAdmin()) {
			return '_result';
		}

		$mb_data =& ParamHolder::get('mb', array());
		$ex_params =& ParamHolder::get('ex_params', array());
		if ($ex_params['qq_show_type']) {
			$o_param = new Parameter();
			$o_res = $o_param->find(' `key`=?',array("QQ_SHOW_TYPE"));
			$o_res->val = $ex_params['qq_show_type'];
			$o_res->save();
		}
		if (!isset($mb_data['id']) || intval($mb_data['id']) <= 0) {
			$this->setVar('json', Toolkit::jsonERR(__('Invalid block ID!')));
			return '_result';
		}
		$is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
		//$is_ajax = ParamHolder::get('_r', '_ajax');
		try {
			$curr_mblock = new ModuleBlock($mb_data['id']);
			$curr_mblock->set($mb_data);
			if (isset($mb_data['show_title'])&&$mb_data['show_title']) {
				$curr_mblock->show_title = '1';
			} else {
				$curr_mblock->show_title = '0';
			}
			/* [Disable publish status temporarily] */
			/*
			if ($mb_data['published']) {
			$curr_mblock->published = '1';
			} else {
			$curr_mblock->published = '0';
			}
			*/
			$curr_mblock->published = '1';
			// Assign default data
			$curr_mblock->s_locale = SessionHolder::get('_LOCALE', DEFAULT_LOCALE);

			$query_str = '';
			$dispage = trim(ParamHolder::get('dispage', ''));
			if (!empty($dispage)) {
				// for rewrite
				if (MOD_REWRITE == 2) {
					$result = $this->rewrite($dispage);
					$dispage = preg_replace('/'.$result['pattern'].'/i', $result['replace'], $dispage);
				}
				$url_info = parse_url($dispage);
				if (isset($url_info['query'])) {
					$query_str = trim($url_info['query']);
				}
			}
			if (ParamHolder::get('dispallpg', false)) {
				$curr_mblock->s_query_hash = '_ALL';
			} else {
				$curr_mblock->s_query_hash = Toolkit::calcMQHash($query_str);
			}
			//$curr_mblock->s_query_hash = '_ALL';
			$curr_mblock->for_roles = ACL::explainAccess(intval($is_member_only));

			// Now collect extra parameters
			include_once(P_INC.'/widgets.php');
			$params = $widgets[$curr_mblock->module][$curr_mblock->action]['parameters'];
			
			if (!empty($params)) {
				foreach ($params as $param) {
					if (!isset($ex_params[$param['id']])) {
						// 28/4/2010 Add >>
						$multiple = false;
						$category = array();
						$key = str_replace('[]', '', $param['id']);
						$category = & ParamHolder::get( $key, array() );
						if ( is_array($category) && sizeof($category) ) {
							$multiple = true;
							$category_list = join(',', $category);
						}
						if ($multiple) {
							$ex_params[$key] = $category_list;
						} else { // 28/4/2010 Add <<
							//$ex_params[$param['id']] = '';
							$ex_params[$key] = '';
						}
					}
				}
				$curr_mblock->s_param = serialize($ex_params);
			}
			$curr_mblock->save();

			//走马灯效果开始，pic：图片，text：产品名称，picText：图片加产品名称

			if ($ex_params['prd_list_tag']) {//具体产品
				if (sizeof($ex_params['mar_prd_id2'])>0) {
					$mar_prd_id = implode(",",$ex_params['mar_prd_id2']);
					$sql = "id in (".$mar_prd_id.") and s_locale='".$curr_mblock->s_locale."' and published='1'";
				}
			}else{//按产品类别选择
				$mar_prd_id = $ex_params['mar_prd_id'];
				$mar_prd_id = ProductCategory::getAllCategoryID($mar_prd_id);
				$sql = "product_category_id in (".$mar_prd_id.") and s_locale='".$curr_mblock->s_locale."' and published='1'";
			}
			if(isset($ex_params['marquee_class'])&&$ex_params['marquee_class']=="pic"){
				if(!empty($mar_prd_id)){
					$o_prd = new Product();
					$m_prds = $o_prd->findAll($sql);
					$o_maquee_exist = new Marquee();
					$ma_exists = $o_maquee_exist->findAll("module_id=".$curr_mblock->id." and pic !=''");
					foreach($ma_exists as $ma_exist){
						$o_marq = new Marquee($ma_exist->id);
						$o_marq->delete();
					}
					foreach($m_prds as $m_prd){
						$m_cnt['module_id'] = $curr_mblock->id;
						$m_cnt['marquee_type'] = 'pic';
						$m_cnt['title'] = $m_prd->name;
						$m_cnt['pic'] = $m_prd->feature_smallimg;
						$m_cnt['flag'] = 3;
						$m_cnt['link'] = $m_prd->id;
						$m_cnt['i_order'] = $m_prd->i_order;
						$m_cnt['create_time'] = $m_prd->create_time;
						$o_mar = new Marquee();
						$o_mar->set($m_cnt);
						$o_mar->save();
					}
				}
			} else if(isset($ex_params['marquee_class'])&&$ex_params['marquee_class']=="text"){
				if(!empty($mar_prd_id)){
					$o_prd = new Product();
					$m_prds = $o_prd->findAll($sql);
					$o_maquee_exist = new Marquee();
					$ma_exists = $o_maquee_exist->findAll("module_id=".$curr_mblock->id." and pic !=''");
					foreach($ma_exists as $ma_exist){
						$o_marq = new Marquee($ma_exist->id);
						$o_marq->delete();
					}
					foreach($m_prds as $m_prd){
						$m_cnt['module_id'] = $curr_mblock->id;
						$m_cnt['marquee_type'] = 'text';
						$m_cnt['title'] = $m_prd->name;
						$m_cnt['pic'] = $m_prd->feature_smallimg;
						$m_cnt['flag'] = 3;
						$m_cnt['link'] = $m_prd->id;
						$m_cnt['i_order'] = $m_prd->i_order;
						$m_cnt['create_time'] = $m_prd->create_time;
						$o_mar = new Marquee();
						$o_mar->set($m_cnt);
						$o_mar->save();
					}
				}

			}else if(isset($ex_params['marquee_class'])&&$ex_params['marquee_class']=="picText"){
				if(!empty($mar_prd_id)) {
					$o_prd = new Product();
					$m_prds = $o_prd->findAll($sql);
					$o_maquee_exist = new Marquee();
					$ma_exists = $o_maquee_exist->findAll("module_id=".$curr_mblock->id." and pic !=''");
					foreach($ma_exists as $ma_exist){
						$o_marq = new Marquee($ma_exist->id);
						$o_marq->delete();
					}

					foreach($m_prds as $m_prd){
						$m_cnt['module_id'] = $curr_mblock->id;
						$m_cnt['marquee_type'] = 'picText';
						$m_cnt['title'] = $m_prd->name;
						$m_cnt['pic'] = $m_prd->feature_smallimg;
						$m_cnt['flag'] = 3;
						$m_cnt['link'] = $m_prd->id;
						$m_cnt['i_order'] = $m_prd->i_order;
						$m_cnt['create_time'] = $m_prd->create_time;
						$o_mar = new Marquee();
						$o_mar->set($m_cnt);
						$o_mar->save();
					}
				}

			}

		} catch (Exception $ex) {
			$this->setVar('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		//if($is_ajax=='_ajax'){
		$this->setVar('json', Toolkit::jsonOK());
		return '_result';
		//}else {
		//	return 'add_result';
		//}
	}

	public function rm_mblock() {
		if (!$this->_requireAdmin()) {
			return '_result';
		}
		if(!ACL::isAdminActionHasPermission('edit_block', 'process')){
			$this->setVar('json', Toolkit::jsonERR(__('No Permission')));
			return '_result';
		}

		$mb_id = ParamHolder::get('mb_id', '0');
		$mb_id = intval($mb_id);
		if ($mb_id == 0) {
			$this->setVar('json', Toolkit::jsonERR(__('Invalid block ID!')));
			return '_result';
		}

		$block_id = '0';
		$block_alias = '';
		try {
			$curr_mblock = new ModuleBlock($mb_id);
			$block_id = $curr_mblock->id;
			$block_alias = $curr_mblock->alias;
			$curr_mblock->delete();
		} catch (Exception $ex) {
			$this->setVar('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}

		$this->setVar('json', Toolkit::jsonOK(array('dom_id' => 'MODBLK_'.$block_id)));
		return '_result';
	}

	private function _requireAdmin() {
		if (!ACL::requireRoles(array('admin'))) {
			$this->setVar('json', Toolkit::jsonERR(__('No Permission!')));
			return false;
		}
		return true;
	}
	private function _savelinkimg($struct_file) {
		$struct_file['name'] = iconv("UTF-8", "gb2312", $struct_file['name']);
		
		move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/image/'.$struct_file['name']);
		return ParamParser::fire_virus(ROOT.'/upload/image/'.$struct_file['name']);
	}
	private function _upload_update_pics($module_id, $html_file,$prd_name,$prd_id,$prd_pic) {
		if (sizeof($prd_id) == 0) return;
		for ($i = 0; $i < sizeof($prd_id); $i++) {
			if(!$prd_id[$i]){
				if($html_file['error'][$i]>0){
					continue;
				}else{
					$rand_fname = Toolkit::randomStr(8).'_'.$html_file['name'][$i];
					if (move_uploaded_file($html_file['tmp_name'][$i],ROOT.'/upload/image/'.$rand_fname)) {
						ParamParser::fire_virus(ROOT.'/upload/image/'.$rand_fname);
						$ext_prd_pic = new Marquee();
						$ext_prd_pic->module_id = $module_id;
						$ext_prd_pic->marquee_type = 'pic';
						$ext_prd_pic->title = '';
						$ext_prd_pic->flag = 1;
						$ext_prd_pic->link = $prd_name[$i];
						$ext_prd_pic->pic = 'upload/image/'.$rand_fname;
						$ext_prd_pic->save();
					}
				}
			}else{
				if ($html_file['error'][$i] > 0){
					$pic_arr['module_id'] = $module_id;
					$pic_arr['marquee_type'] = 'pic';
					$pic_arr['title'] = '';
					$pic_arr['pic'] = $prd_pic[$i];
					$pic_arr['flag'] = 1;
					$pic_arr['link'] = $prd_name[$i];
					$ext_prd_pic = new Marquee($prd_id[$i]);
					$ext_prd_pic->set($pic_arr);
					$ext_prd_pic->save();
				} else {
					$rand_fname = Toolkit::randomStr(8).'_'.$html_file['name'][$i];
					if (move_uploaded_file($html_file['tmp_name'][$i],ROOT.'/upload/image/'.$rand_fname)) {
						ParamParser::fire_virus(ROOT.'/upload/image/'.$rand_fname);
						$pic_arr['module_id'] = $module_id;
						$pic_arr['marquee_type'] = 'pic';
						$pic_arr['title'] = '';
						$pic_arr['pic'] = 'upload/image/'.$rand_fname;
						$pic_arr['flag'] = 1;
						$pic_arr['link'] = $prd_name[$i];

						$ext_prd_pic = new Marquee($prd_id[$i]);
						$ext_prd_pic->set($pic_arr);
						$ext_prd_pic->save();
					}
				}
			}

		}
	}
	private function _upload_gala_pics($module_id, $html_file,$prd_name) {
		if (sizeof($html_file) == 0) return;
		for ($i = 0; $i < sizeof($html_file['name']); $i++) {
			if ($html_file['error'][$i] > 0) continue;
			$rand_fname = Toolkit::randomStr(8).'_'.$html_file['name'][$i];
			if (move_uploaded_file($html_file['tmp_name'][$i],ROOT.'/upload/image/'.$rand_fname)) {
				ParamParser::fire_virus(ROOT.'/upload/image/'.$rand_fname);
				$ext_prd_pic = new Marquee();
				$ext_prd_pic->module_id = $module_id;
				$ext_prd_pic->marquee_type = 'pic';
				$ext_prd_pic->title = '';
				$ext_prd_pic->flag = 1;
				$ext_prd_pic->link = $prd_name[$i];
				$ext_prd_pic->pic = 'upload/image/'.$rand_fname;
				$ext_prd_pic->save();
			}
		}
	}
	/**
     * for  09/03/2010
     */
	private function rewrite($str) {
		$result = array();

		if (preg_match("/([a-zA-Z_]{1,})\-([a-zA-Z_]{1,}).html$/i", $str)) {
			$result['pattern'] = "([a-zA-Z_]{1,})-([a-zA-Z_]{1,}).html$";
			$result['replace'] = "index.php?_m=\\1&_a=\\2";
		} else {
			$result['pattern'] = "([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([a-zA-Z_]{1,})-([0-9]{1,}).html$";
			$result['replace'] = "index.php?_m=\\1&_a=\\2&\\3=\\4";
		}

		return $result;
	}
}
?>
