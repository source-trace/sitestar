<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

define('SSFCK', 1);
define('SSROOT', ROOT);
//include(P_LIB.'/image.func.php');
class ModProduct extends Module {
	public static  $t_ww;
	public static $h_ww;
	protected $_filters = array(
        'check_admin' => ''
    );
	public function admin_batch_create() {
		setlocale(LC_ALL,'zh_CN');
		$file_allow_ext_pat = '/\.(csv)$/i';
		$file_info =& ParamHolder::get('batch_file', array(), PS_FILES);
		if (empty($file_info)) {
            Notice::set('mod_product/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_batch'));
        }
		if(!preg_match($file_allow_ext_pat, $file_info["name"])) {
			Notice::set('mod_product/msg', __('File type error!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_batch'));
		}

		//$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
		if (!$this->_savetplFile($file_info)) {
            Notice::set('mod_product/msg', __('Uploading file failed!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_batch'));
        }
		//csv upload
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
		$product_info = array();
		$product_info['pub_start_time'] = -1;//开始时间
		$product_info['pub_end_time'] = -1;//结束时间
		$product_info['published'] = '1';//是否发布
		$product_info['for_roles'] = '{member}{admin}{guest}';//是否发布
		//$product_info['create_time'] = time();//创建时间
		//$product_info['price'] = 0;//产品原价
		//$product_info['introduction'] = '';//产品简介
		//$product_info['discount_price'] = 0;//产品折扣价
		//$product_info['delivery_fee'] = 0;//邮寄费
		//$product_info['online_orderable'] = 0;//是否开放在线订购
		$product_info['s_locale'] = $curr_locale;//语言
		//$product_info['recommended'] = 0;//是否推荐
		//$product_info['product_category_id'] = 1;//默认未分类
		$product_info['is_seo']='0';

		$handle = fopen(ROOT.'/upload/file/'.$file_info["name"],"r");
		$row = 1;
		
		while ($data = fgetcsv($handle)) {
			if($row == 1){
				$row++;
				continue;
			}
			$num = count($data);
			$row++;//行数
			//for ($c=0; $c < $num; $c++) {
			$o_prd = new Product();
			$product_info['i_order'] = Product::getMaxOrder(1) + 1;

			$prd_class = iconv('GBK','UTF-8//IGNORE',strip_tags($data[0]));//产品分类
			$o_prd_class = new ProductCategory();
			$prd_arr = $o_prd_class->findAll("name='".$prd_class."'");
			$product_info['product_category_id'] = intval(isset($prd_arr[0])?$prd_arr[0]->id:0);

			$product_info['name'] = iconv('GBK','UTF-8//IGNORE',strip_tags($data[1]));//产品名称 
			$product_info['introduction'] = str_replace( array('\r', '\n', '"<"'), array("\r", "\n", "<"), iconv('GBK','UTF-8//IGNORE',$data[2]) );//产品简介
			$product_info['description'] = str_replace( array('\r', '\n', '"<"'), array("\r", "\n", "<"), iconv('GBK','UTF-8//IGNORE',$data[3]) );//产品描述
			$product_info['feature_img'] = iconv('GBK','UTF-8//IGNORE',strip_tags($data[4]));//产品大图
			// 图片水印/缩略图
			$image_file_path = ROOT.DS."upload/image/".$product_info['feature_img'];
        	if( is_file($image_file_path) ) {
        		$photo_twidth_2=400;
				$photo_theight_2=300;
				if(isset($photo_twidth)){
					$photo_twidth_2=$photo_twidth;
				}
				if(isset($photo_theight)){
					$photo_theight_2=$photo_theight;
				}
        		$product_info['feature_smallimg'] = $this->img_restruck($product_info['feature_img'],$photo_twidth_2,$photo_theight_2);
				
        	}
          if(!isset($product_info['feature_smallimg'])) $product_info['feature_smallimg']="";
          	$product_info['feature_smallimg'] = empty($product_info['feature_smallimg'])?'':"upload/image/".$product_info['feature_smallimg'];
        	$product_info['feature_img'] = "upload/image/".iconv('GBK','UTF-8//IGNORE',strip_tags($data[4]));
			$product_info['price'] = iconv('GBK','UTF-8//IGNORE',strip_tags($data[5]));//产品原价
			$product_info['discount_price'] = iconv('GBK','UTF-8//IGNORE',strip_tags($data[6]));//产品折扣价
			$product_info['delivery_fee'] = iconv('GBK','UTF-8//IGNORE',strip_tags($data[7]));//邮寄费
			$product_info['online_orderable'] = iconv('GBK','UTF-8//IGNORE',strip_tags($data[8]));//是否开放在线订购	
			$product_info['recommended'] = iconv('GBK','UTF-8//IGNORE',strip_tags($data[9]));//是否推荐
			$product_info['create_time'] = intval( strtotime($data[10]) );//创建时间
			$product_info['is_seo'] = intval($data[11]);//是否SEO
			$product_info['meta_key'] = iconv('GBK','UTF-8//IGNORE',strip_tags($data[12]));;//
			$product_info['meta_desc'] = iconv('GBK','UTF-8//IGNORE',strip_tags($data[13]));;//
			
			$o_prd->set($product_info);
            $o_prd->save();
			//}
		}
		fclose($handle);
		/*
		//xls格式
		require_once P_LIB.'/Excel/reader.php';
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('gb2312');
		$data->read(ROOT.'/upload/file/'.$file_info["name"]);
		
		$curr_locale = trim(SessionHolder::get('_LOCALE'));
		$product_info = array();
		$product_info['pub_start_time'] = -1;//开始时间
		$product_info['pub_end_time'] = -1;//结束时间
		$product_info['published'] = '1';//是否发布
		$product_info['for_roles'] = '{member}{admin}{guest}';//是否发布
		$product_info['create_time'] = time();//创建时间
		//$product_info['price'] = 0;//产品原价
		//$product_info['introduction'] = '';//产品简介
		//$product_info['discount_price'] = 0;//产品折扣价
		//$product_info['delivery_fee'] = 0;//邮寄费
		//$product_info['online_orderable'] = 0;//是否开放在线订购
		$product_info['s_locale'] = $curr_locale;//语言
		//$product_info['recommended'] = 0;//是否推荐
		//$product_info['product_category_id'] = 1;//默认未分类
		
		for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
			$o_prd = new Product();
			$product_info['i_order'] =
            	Product::getMaxOrder(1) + 1;

			$prd_class = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][1]));//产品分类
			$o_prd_class = new ProductCategory();
			$prd_arr = $o_prd_class->findAll("name='".$prd_class."'");
			$product_info['product_category_id'] = intval($prd_arr[0]->id);

			$product_info['name'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][2]));//产品名称
			$product_info['introduction'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][3]));//产品简介
			$product_info['description'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][4]));//产品描述
			$product_info['feature_img'] = 'upload/image/'.iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][5]));//产品大图
			// 图片水印/缩略图
        	if( THUMB_STATUS ) {
        		$extend = array('_e' => 'mod_product/msg', 
	        			            '_a' => 'mod_product', 
	        						'_m' => 'admin_batch'
	        				  );
        		$resource = $this->image_mark_thumb( ROOT.'/'.$product_info['feature_img'], $extend );
        	    $product_info["feature_smallimg"] = !empty($resource['thumb']) ? 'upload/image/'.$resource['thumb'] : 'upload/image/'.$file_info["name"];
        	} else {
				$product_info['feature_smallimg'] = $product_info['feature_img'];
			}
			$product_info['price'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][6]));//产品描述
			$product_info['discount_price'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][7]));//产品描述
			$product_info['delivery_fee'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][8]));//产品描述
			$product_info['online_orderable'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][9]));//产品描述
			$product_info['recommended'] = iconv('gb2312','utf-8',strip_tags($data->sheets[0]['cells'][$i][10]));//产品描述
			$o_prd->set($product_info);
            $o_prd->save();
		}
		*/
		@unlink(ROOT.'/upload/file/'.$file_info["name"]);
		Notice::set('mod_product/msg', __('Product added successfully!'));
		Content::redirect(Html::uriquery('mod_product', 'admin_list'));
	}
	public function admin_batch() {
		$this->_layout = 'content';
    	
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_product/_LOCALE', $curr_locale));
        
        $this->assign('content_title', __('Batch Import'));
        $this->assign('next_action', 'admin_batch_create');
        
        $this->assign('mod_locale', $mod_locale);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
	}
	
	public function admin_export() {
		$this->_layout = 'content';

        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_product/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $mod_locale));
        SessionHolder::set('mod_product/_LOCALE', $lang_sw);
		$keyword = trim(ParamHolder::get('hidkeyword', '',PS_POST))?Toolkit::baseEncode(trim(ParamHolder::get('hidkeyword', '',PS_POST))):trim(ParamHolder::get('hidkeyword', '',PS_GET));      
        $keyword = Toolkit::baseDecode($keyword);

        $where = "s_locale=?";
        $params = array($lang_sw);
        
        $cap_sw = trim(ParamHolder::get('cap_sw', '-'));
        // 02/06/2010 Edit >>
		$all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($lang_sw));
        if (is_numeric($cap_sw)) {
        	$childids = '';

	        $childids = $this->getCategoryChildIds( $cap_sw, $curr_locale );
	        $catids = !empty($childids) ? $childids.$cap_sw : $cap_sw;
	        if ($cap_sw==0) {
	        	$where .= " AND product_category_id=0";
	        }else{
	        	 $where .= " AND product_category_id IN(".$catids.")";
	        }
            //$where .= " AND product_category_id =?";
            //$params[] = $cap_sw;
        }// 02/06/2010 Edit <<
        if( trim($keyword) ) $where .=  " AND name LIKE '%{$keyword}%'";



		// 28/06/2010 excel export >>
		$act = trim(ParamHolder::get('act', ''));
		$pids = trim(ParamHolder::get('prd_ids', '0'));
		if( str_replace('M', '', ini_get('memory_limit')) <= 8 ) {ini_set("memory_limit", '64M');}
		$products = $rows = array();
		$obj = new Product();

		if ($pids) $where .=  " AND `id` IN({$pids})";
		$products =& $obj->findAll($where, $params, "ORDER BY `create_time` DESC");

		$rows[] = array(__('Category'), __('Name'), __('Introduction'), __('Product Description'), __('Full Image'), __('Price'),  
								__('Discount Price'), __('Delivery Fee'), __('Online Orderable'), __('Recommend'), __('Publish Date'),__('Is Seo'),__('Meta Key'),__('Meta Desc'));

		// product list
		foreach ($products as $product) {
			$product->loadRelatedObjects(REL_PARENT, array('ProductCategory'));
			$product_ProductCategory_name='';
			if(isset($product->masters['ProductCategory']->name)){
				$product_ProductCategory_name=$product->masters['ProductCategory']->name;
			}
			$rows[] = array($product_ProductCategory_name, $product->name, $product->introduction, 
							$product->description, str_replace('upload/image/', '', $product->feature_img), 
										$product->price, $product->discount_price, $product->delivery_fee, $product->online_orderable, 
							$product->recommended, date('Y-m-d H:i:s', $product->create_time),$product->is_seo,$product->meta_key,$product->meta_desc);
		}

		include_once P_LIB."/Excel/export.class.php";
		$csv = new Export_CSV($rows, '../upload/file/products.csv');
		$csv->Export();
		exit;

		// 28/06/2010 excel export <<

	}
	
    public function admin_list() {
        $this->_layout = 'content';

        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_product/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_product/_LOCALE', $lang_sw);
		$keyword = trim(ParamHolder::get('hidkeyword', '',PS_POST))?Toolkit::baseEncode(trim(ParamHolder::get('hidkeyword', '',PS_POST))):trim(ParamHolder::get('hidkeyword', '',PS_GET));      
        $keyword = Toolkit::baseDecode($keyword);

        $where = "s_locale=?";
        $params = array($lang_sw);
        
        $cap_sw = trim(ParamHolder::get('cap_sw', '-'));
        // 02/06/2010 Edit >>
		$all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($lang_sw));
        if (is_numeric($cap_sw)) {
        	$childids = '';

	        $childids = $this->getCategoryChildIds( $cap_sw, $curr_locale );
	        $catids = !empty($childids) ? $childids.$cap_sw : $cap_sw;
	        if ($cap_sw==0) {
	        	$where .= " AND product_category_id=0";
	        }else{
	        	 $where .= " AND product_category_id IN(".$catids.")";
	        }
            //$where .= " AND product_category_id =?";
            //$params[] = $cap_sw;
        }// 02/06/2010 Edit <<
        if( trim($keyword) ) $where .=  " AND name LIKE '%{$keyword}%'";

        $product_data =&
            Pager::pageByObject('Product', $where, $params,
                "ORDER BY `i_order` DESC,`create_time` DESC");
		$this->assign('default_lang', trim(SessionHolder::get('_LOCALE')));
        $this->assign('next_action', 'admin_order');
        $this->assign('products', $product_data['data']);
        $this->assign('pager', $product_data['pager']);
        $this->assign('page_mod', $product_data['mod']);
		$this->assign('page_act', $product_data['act']);
		$this->assign('page_extUrl', $product_data['extUrl']);
        $this->assign('cap_sw', $cap_sw);
        $this->assign('keyword', $keyword);
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());

		// Prepare Product category for select list view
        //$all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($lang_sw));
        $select_categories = array();
        ProductCategory::toSelectArray($all_categories, $select_categories,
                0, array(), array('-' => __('View All'), '0' => __('Uncategorised')));


        $this->assign('select_categories', $select_categories);
    }
	public function admin_order() {

		$order_info =& ParamHolder::get('i_order', array());
		if (!is_array($order_info)) {
            $this->assign('json', Toolkit::jsonERR(__('Missing product order information!')));
            return '_result';
        }
		try {
			foreach($order_info as $key => $val) {
				$product_info['i_order'] = $val;
				$o_product = new Product($key);
				$o_product->set($product_info);
				$o_product->save();
			}
		} catch (Exception $ex) {
			$this->assign('json', Toolkit::jsonERR($ex->getMessage()));
			return '_result';
		}
		Notice::set('mod_product/msg', __('Product order added successfully!'));
		Content::redirect(Html::uriquery('mod_product', 'admin_list'));
	}
    public function admin_add() {
        $this->_layout = 'content';

        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_product/_LOCALE', $curr_locale));

        $all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        ProductCategory::toSelectArray($all_categories, $select_categories,
	        	0, array(), array('0' => __('Uncategorised')));

        $this->assign('content_title', __('New Product'));
        $this->assign('next_action', 'admin_create');

        $this->assign('mod_locale', $mod_locale);
        $this->assign('language_info',$mod_locale);
        $this->assign('select_categories', $select_categories);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
		$this->assign('act', 'add');
        return '_form';
    }
    
    public function admin_mi_quick_add() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_menu_item/_LOCALE', $curr_locale));

        $all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($mod_locale));
        $select_categories = array();
        ProductCategory::toSelectArray($all_categories, $select_categories,
	        	0, array(), array('0' => __('Uncategorised')));

        $this->assign('content_title', __('New Product'));
        $this->assign('next_action', 'admin_mi_quick_create');

        $this->assign('mod_locale', $mod_locale);
        $this->assign('select_categories', $select_categories);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
        
        $link_type_text = trim(ParamHolder::get('txt'));
        $this->assign('type_text', $link_type_text);

        $this->_layout = 'clean';
        return '_mi_quick_add_form';
    }

    public function admin_create() {
		$this->_layout = 'content';

        $product_info =& ParamHolder::get('prd', array());
        if (sizeof($product_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing product information!')));
            return '_error';
        }
    	if($product_info['product_category_id'] == -1) {
        	$product_info['product_category_id'] == 0;
        }
		$file_info =& ParamHolder::get('prd_file', array(), PS_FILES);
		if (empty($file_info)) {
            Notice::set('mod_product/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_add'));
        }
		if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_info["name"])) {
			Notice::set('mod_product/msg', __('Big image type error!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_add'));
		}
		$file_info['name'] = Toolkit::changeFileNameChineseToPinyin($file_info['name']);
		$curdir = trim(ParamHolder::get('curdir', ''));
		$curdir = !empty($curdir) ? $curdir : 'upload/image/';
        //if(file_exists(ROOT.'/upload/image/'.$file_info["name"])) {
        //$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
        //}
        if (!$this->_savelinkimg($file_info, $curdir)) {
            Notice::set('mod_product/msg', __('x'));
            Content::redirect(Html::uriquery('mod_product', 'admin_add'));
        } else {
		    // 图片水印/缩略图
//		    if( WATERMARK_STATUS ) {
				$photo_twidth=400;
                    $photo_theight=300;
				$thumb_file = $this->img_restruck($file_info["name"], $photo_twidth,$photo_theight,$curdir);
				$file_small_info["name"] = !empty($thumb_file) ? $thumb_file : $file_info["name"];
//        	}
        }

		/*$file_small_info =& ParamHolder::get('prd_small_file', array(), PS_FILES);
		if (empty($file_small_info)) {
            Notice::set('mod_product/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_add'));
        }
		if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_small_info["name"])) {
			Notice::set('mod_product/msg', __('Small image type not allowed!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_add'));
		}
        if(file_exists(ROOT.'/upload/image/'.$file_small_info["name"])) {
            $file_small_info["name"] = Toolkit::randomStr(8).strrchr($file_small_info["name"],".");
        }
        if (!$this->_savelinkimg($file_small_info)) {
            Notice::set('mod_product/msg', __('Product small image upload failed!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_add'));
        }*/

		$is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
			 $product_info['feature_img'] = $curdir.$file_info["name"];
			 if( !empty($file_small_info["name"]) ) {
			 	 $product_info['feature_smallimg'] = $curdir.$file_small_info["name"];
			 } else {
			 	 if (!empty($file_info["name"])) {
			 	 $product_info['feature_smallimg'] = $curdir.$file_info["name"];}
			 }
        	// Re-arrange publish time
        	//if (intval(ParamHolder::get('pub_start_time', '0')) == 0) {
        	    $product_info['pub_start_time'] = -1;
        	//} else {
        	//    $product_info['pub_start_time'] = strtotime($product_info['pub_start_time']);
        	//}
        	//if (intval(ParamHolder::get('pub_end_time', '0')) == 0) {
        	    $product_info['pub_end_time'] = -1;
        	//} else {
        	//    $product_info['pub_end_time'] = strtotime($product_info['pub_end_time']);
        	//}
        	// Re-arrange recommend status
            if (isset($product_info['recommended'])&&$product_info['recommended'] == '1') {
                $product_info['recommended'] = '1';
            } else {
                $product_info['recommended'] = '0';
            }
        	// Re-arrange publish status
//            if ($product_info['published'] == '1') {
//                $product_info['published'] = '1';
//            } else {
//                $product_info['published'] = '0';
//            }
				$product_info['published'] = '1';
        	// Re-arrange online orderable status
            if (isset($product_info['online_orderable'])&&$product_info['online_orderable'] == '1') {
                $product_info['online_orderable'] = '1';
            } else {
                $product_info['online_orderable'] = '0';
            }

			// Re-arrange prices for basic version
			if (!isset($product_info['price'])) {
				$product_info['price'] = '0.00';
			}
			if (!isset($product_info['discount_price'])||$product_info['discount_price']=='') {
				$product_info['discount_price'] = '0.00';
			}
			if (!isset($product_info['delivery_fee'])) {
				$product_info['delivery_fee'] = '0.00';
			}
			
            // Re-arrange discount price
			/*
            if (number_format($product_info['discount_price'], 2) == '0.00') {
                $product_info['discount_price'] = $product_info['price'];
            }
			*/
            $product_info['introduction'] = $product_info['introduction'] =strip_tags($product_info['introduction'])?strip_tags($product_info['introduction']):mb_substr(strip_tags($product_info['description']),0,120,'utf-8');
            $product_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // The create time
            $product_info['create_time'] = intval( strtotime($product_info['date']) );
            $product_info['i_order'] =
            	Product::getMaxOrder($product_info['product_category_id']) + 1;
            
             //解决在IE下插入本地图片无法正常显示问题  author :renzhen
           $fckuploadpath='../../../';
           $reqpath=$_SERVER['HTTP_HOST' ].$_SERVER['REQUEST_URI'];
           $reqroot=preg_replace('/admin\/[^\/]*$/', '', $reqpath);
           $reqpattern='/((http)|(https)):\/\/'.preg_quote($reqroot, '/').'/';
           
           $product_info['description'] =preg_replace($reqpattern, $fckuploadpath, $product_info['description']);
           $product_info['price'] = str_replace(',', '', $product_info['price']);
           $product_info['discount_price'] = str_replace(',', '', $product_info['discount_price']);
           $product_info['delivery_fee'] = str_replace(',', '', $product_info['delivery_fee']);
           $product_info['introduction'] = str_replace('<div>', '', $product_info['introduction']);
           $product_info['introduction'] = str_replace('</div>', '', $product_info['introduction']);
           $product_info['introduction'] = trim($product_info['introduction']);
            // Data operation
            $o_product = new Product();
            $o_product->set($product_info);
            $o_product->save();
            
            $this->_upload_gala_pics($o_product->id, ParamHolder::get('prd_extpic', array(), PS_FILES), $curdir);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

       Notice::set('mod_product/msg', __('Product added successfully!'));
       Content::redirect(Html::uriquery('mod_product', 'admin_list'));
    }
    
    public function admin_mi_quick_create() {
		$this->_layout = 'clean';

        $product_info =& ParamHolder::get('prd', array());
        if (sizeof($product_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing product information!')));
            return '_error';
        }
		$file_info =& ParamHolder::get('prd_file', array(), PS_FILES);
		if (empty($file_info)) {
            Notice::set('mod_product/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_mi_quick_add'));
        }
		if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_info["name"])) {
			Notice::set('mod_product/msg', __('Big image type error!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_mi_quick_add'));
		}
		
		$curdir = trim(ParamHolder::get('curdir', ''));
		$curdir = !empty($curdir) ? $curdir : 'upload/image/';
        //if(file_exists(ROOT.'/upload/image/'.$file_info["name"])) {
        //$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
        //}
        if (!$this->_savelinkimg($file_info, $curdir)) {
            Notice::set('mod_product/msg', __('Product big image upload failed!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_mi_quick_add'));
        } else {
		    // 图片水印/缩略图
//		    if( WATERMARK_STATUS ) {
				
				$thumb_file = $this->img_restruck($file_info["name"],$photo_twidth,$photo_theight, $curdir);
				$file_small_info["name"] = !empty($thumb_file) ? $thumb_file : $file_info["name"];
//        	}
        }

		/*$file_small_info =& ParamHolder::get('prd_small_file', array(), PS_FILES);
		if (empty($file_small_info)) {
            Notice::set('mod_product/msg', __('Invalid post file data!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_mi_quick_add'));
        }
		if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_small_info["name"])) {
			Notice::set('mod_product/msg', __('Small image type not allowed!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_mi_quick_add'));
		}
        if(file_exists(ROOT.'/upload/image/'.$file_small_info["name"])) {
            $file_small_info["name"] = Toolkit::randomStr(8).strrchr($file_small_info["name"],".");
        }
        if (!$this->_savelinkimg($file_small_info)) {
            Notice::set('mod_product/msg', __('Product small image upload failed!'));
            Content::redirect(Html::uriquery('mod_product', 'admin_mi_quick_add'));
        }*/

		$is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
			 $product_info['feature_img'] = $curdir.$file_info["name"];
			 if( !empty($file_small_info["name"]) ) {
			 	$product_info['feature_smallimg'] = $curdir.$file_small_info["name"];
			 } else {
			 	 if (!empty($file_info["name"])) {
			 	$product_info['feature_smallimg'] = $curdir.$file_info["name"];}
			 }
        	// Re-arrange publish time
        	//if (intval(ParamHolder::get('pub_start_time', '0')) == 0) {
        	    $product_info['pub_start_time'] = -1;
        	//} else {
        	//    $product_info['pub_start_time'] = strtotime($product_info['pub_start_time']);
        	//}
        	//if (intval(ParamHolder::get('pub_end_time', '0')) == 0) {
        	    $product_info['pub_end_time'] = -1;
        	//} else {
        	//    $product_info['pub_end_time'] = strtotime($product_info['pub_end_time']);
        	//}
        	// Re-arrange recommend status
            if ($product_info['recommended'] == '1') {
                $product_info['recommended'] = '1';
            } else {
                $product_info['recommended'] = '0';
            }
        	// Re-arrange publish status
//            if ($product_info['published'] == '1') {
//                $product_info['published'] = '1';
//            } else {
//                $product_info['published'] = '0';
//            }
				$product_info['published'] = '1';
        	// Re-arrange online orderable status
            if ($product_info['online_orderable'] == '1') {
                $product_info['online_orderable'] = '1';
            } else {
                $product_info['online_orderable'] = '0';
            }

			// Re-arrange prices for basic version
			if (!isset($product_info['price'])) {
				$product_info['price'] = '0.00';
			}
			if (!isset($product_info['discount_price'])) {
				$product_info['discount_price'] = '0.00';
			}
			if (!isset($product_info['delivery_fee'])) {
				$product_info['delivery_fee'] = '0.00';
			}
			
            // Re-arrange discount price
            if (number_format($product_info['discount_price'], 2) == '0.00') {
                $product_info['discount_price'] = $product_info['price'];
            }
            $product_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            // The create time
            $product_info['create_time'] = intval( strtotime($product_info['date']) );
            $product_info['i_order'] =
            	Product::getMaxOrder($product_info['product_category_id']) + 1;
            // Data operation
            $o_product = new Product();
            $o_product->set($product_info);
            $o_product->save();
            
            $this->_upload_gala_pics($o_product->id, ParamHolder::get('prd_extpic', array(), PS_FILES), $curdir);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

        $link_type_text = trim(ParamHolder::get('txt'));
        $this->assign('type_text', $link_type_text);
        $this->assign('id', $o_product->id);
        $this->assign('name', $o_product->name);
        return '_mi_quick_add_success';
    }

    public function admin_edit() {
        $this->_layout = 'content';

        $p_id = ParamHolder::get('p_id', '0');
		$p_id = intval($p_id);
        if ($p_id == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_error';
        }

        try {
            $curr_product = new Product($p_id);
            $this->assign('curr_product', $curr_product);
			$this->assign('language_info',$curr_product->s_locale);
	        $all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($curr_product->s_locale));
	        $select_categories = array();
	        ProductCategory::toSelectArray($all_categories, $select_categories,
		        	0, array(), array('0' => __('Uncategorised')));
	        $this->assign('select_categories', $select_categories);
			//2012-3-28
			$curr_product->loadRelatedObjects(REL_CHILDREN, array('ProductPic'));
            $ext_pics = $curr_product->slaves['ProductPic'];
			$this->assign('ext_pics', $ext_pics);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

        $this->assign('content_title', __('Edit Product'));
        $this->assign('next_action', 'admin_update');
        $this->assign('p_id', $p_id);

        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());

        return '_form';
    }

    public function admin_update() {
        $product_info =& ParamHolder::get('prd', array());
        if (sizeof($product_info) <= 0) {
            $this->assign('json', Toolkit::jsonERR(__('Missing product information!')));
            return '_error';
        }
        if (!isset($product_info['price'])) {
          	$product_info['price'] = '0.00';         	
        }
        if (!isset($product_info['discount_price'])||$product_info['discount_price']==''||$product_info['discount_price']=='0.00') {
        	if ($product_info['price']!='0.00'&&$product_info['price']>0) {
        		$product_info['discount_price'] = $product_info['price'];
        	}else{
        		$product_info['discount_price'] = '0.00';
        	}
        }
        if (!isset($product_info['delivery_fee'])) {
        	$product_info['delivery_fee'] = '0.00';
        }
     
		$file_info =& ParamHolder::get('prd_file', array(), PS_FILES);
		$curdir = trim(ParamHolder::get('curdir', ''));
		$curdir = !empty($curdir) ? $curdir : 'upload/image/';

		$file_info['name'] = Toolkit::changeFileNameChineseToPinyin($file_info['name']);	
		if (!empty($file_info["name"])) {
			if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_info["name"])) {
				Notice::set('mod_product/msg', __('Big image type error!'));
				Content::redirect(Html::uriquery('mod_product', 'admin_edit', array('p_id' => $product_info['id'])));
			}
			//if(file_exists(ROOT.'/upload/image/'.$file_info["name"])) {
			//$file_info["name"] = Toolkit::randomStr(8).strrchr($file_info["name"],".");
			//}
			
			if (!$this->_savelinkimg($file_info, $curdir)) {
				Notice::set('mod_product/msg', __('Product big image upload failed!'));
				Content::redirect(Html::uriquery('mod_product', 'admin_edit', array('p_id' => $product_info['id'])));
			} else {
				/**
				 * 编辑模式下删除编辑前的对应图片文件
				 */
				 
				 if($curdir.$file_info['name']!=$product_info['feature_img']){
				 $str_feature_img = ROOT.'/'.$product_info['feature_img']; // 全图
				 if (file_exists($str_feature_img)) {
				 	 $mix_paths = pathinfo($str_feature_img);
				 	 $str_ext = strtolower( $mix_paths["extension"] );
				 	 $str_small_img = str_replace( $str_ext, 'thumb.'.$str_ext, $str_feature_img );// 缩略图
				 	 if (file_exists($str_small_img)) {unlink($str_small_img);}
				 	 unlink($str_feature_img);
				 }
				 }
	        	// 图片水印/缩略图
//	        	if( WATERMARK_STATUS ) {
					$photo_twidth=400;
                         $photo_theight=300;
					$thumb_file = $this->img_restruck($file_info["name"],$photo_twidth,$photo_theight, $curdir);
					$file_small_info["name"] = !empty($thumb_file) ? $thumb_file : $file_info["name"];
//	        	}
	        }
		}
		/*$file_small_info =& ParamHolder::get('prd_small_file', array(), PS_FILES);
		if (!empty($file_small_info["name"])) {
			if(!preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $file_small_info["name"])) {
				Notice::set('mod_product/msg', __('Small image type error!'));
				Content::redirect(Html::uriquery('mod_product', 'admin_edit', array('p_id' => $product_info['id'])));
			}
			if(file_exists(ROOT.'/upload/image/'.$file_small_info["name"])) {
				$file_small_info["name"] = Toolkit::randomStr(8).strrchr($file_small_info["name"],".");
			}
			if (!$this->_savelinkimg($file_small_info)) {
				Notice::set('mod_product/msg', __('Product small image upload failed!'));
				Content::redirect(Html::uriquery('mod_product', 'admin_edit', array('p_id' => $product_info['id'])));
			}
		}*/

        $is_member_only = ParamHolder::get('ismemonly', '0');
		$is_member_only = intval($is_member_only);
        try {
			//add image
			if (!empty($file_info["name"])) {
				$product_info['feature_img'] = $curdir.$file_info["name"];
			}
			if (!empty($file_small_info["name"])) {
				$product_info['feature_smallimg'] = $curdir.$file_small_info["name"];
			} else {
				if (!empty($file_info["name"])) {
				$product_info['feature_smallimg'] = $curdir.$file_info["name"];}
			}
        	// Re-arrange publish time
        	//if (intval(ParamHolder::get('pub_start_time', '0')) == 0) {
        	    $product_info['pub_start_time'] = -1;
        	//} else {
        	//    $product_info['pub_start_time'] = strtotime($product_info['pub_start_time']);
        	//}
        	//if (intval(ParamHolder::get('pub_end_time', '0')) == 0) {
        	    $product_info['pub_end_time'] = -1;
        	//} else {
        	//    $product_info['pub_end_time'] = strtotime($product_info['pub_end_time']);
        	//}
        	// Re-arrange recommend status
            if (isset($product_info['recommended'])&&$product_info['recommended'] == '1') {
                $product_info['recommended'] = '1';
            } else {
                $product_info['recommended'] = '0';
            }
        	// Re-arrange publish status
//            if ($product_info['published'] == '1') {
//                $product_info['published'] = '1';
//            } else {
//                $product_info['published'] = '0';
//            }
        	// Re-arrange online orderable status
            if (isset($product_info['online_orderable'])&&$product_info['online_orderable'] == '1') {
                $product_info['online_orderable'] = '1';
            } else {
                $product_info['online_orderable'] = '0';
            }
            // Re-arrange discount price
			/*
            if (number_format($product_info['discount_price'], 2) == '0.00') {
                $product_info['discount_price'] = number_format($product_info['price'],2);
            }
			*/
            $product_info['for_roles'] = ACL::explainAccess(intval($is_member_only));
            $product_info['create_time'] = intval( strtotime($product_info['date']) );
            $product_info['price'] = str_replace(',', '', $product_info['price']);
           $product_info['discount_price'] = str_replace(',', '', $product_info['discount_price']);
           $product_info['delivery_fee'] = str_replace(',', '', $product_info['delivery_fee']);

            // Data operation
            $o_product = new Product($product_info['id']);
           // $pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
			//$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
           //解决在IE下插入本地图片无法正常显示问题  author :renzhen
           $fckuploadpath='../../../';
           $reqpath=$_SERVER['HTTP_HOST' ].$_SERVER['REQUEST_URI'];
           $reqroot=preg_replace('/admin\/[^\/]*$/', '', $reqpath);
           $reqpattern='/((http)|(https)):\/\/'.preg_quote($reqroot, '/').'/';
           
           $product_info['description'] =preg_replace($reqpattern, $fckuploadpath, $product_info['description']);
//			$product_info['content'] = str_replace($path,"",isset($product_info['content'])?$product_info['content']:'');
            $o_product->set($product_info);
            $o_product->save();
            
            $this->_upload_gala_pics($o_product->id, ParamHolder::get('prd_extpic', array(), PS_FILES), $curdir);
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return '_error';
        }

       Notice::set('mod_product/msg', __('Product updated successfully!'));
       Content::redirect(Html::uriquery('mod_product', 'admin_list'));
    }

    public function admin_delete() {

        $p_id = trim(ParamHolder::get('p_id', '0'));
        if (intval($p_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
			$tmp_arr = explode('_', $p_id);
			$len = sizeof($tmp_arr);
			for ($i = 0; $i< $len; $i++){
				$curr_product = new Product($tmp_arr[$i]);
				/**
				 * 删除模式下删除当前产品的对应图片文件
				 */
				 if (isset($curr_product->feature_img)&&file_exists(ROOT.'/'.$curr_product->feature_img)) {
				 	@unlink(ROOT.'/'.$curr_product->feature_img);
				 }
				 if (isset($curr_product->feature_smallimg)&&file_exists(ROOT.'/'.$curr_product->feature_smallimg)) {
				 	@unlink(ROOT.'/'.$curr_product->feature_smallimg);
				 }
				$curr_product->delete();
			}
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }

        $this->assign('json', Toolkit::jsonOK());
        return '_result';
    }
    
    public function admin_delete_extpic() {
        $p_id = trim(ParamHolder::get('p_id', '0'));
        if (intval($p_id) == 0) {
            $this->assign('json', Toolkit::jsonERR(__('Invalid ID!')));
            return '_result';
        }
        try {
				$curr_pic = new ProductPic($p_id);
				@unlink(ROOT.'/'.$curr_pic->pic);
				$curr_pic->delete();
        } catch (Exception $ex) {
            $this->assign('json', Toolkit::jsonERR($ex->getMessage()));
            return ('_result');
        }
        
        $this->assign('json', Toolkit::jsonOK(array('id' => $p_id)));
        return '_result';
    }

	private function _savelinkimg(&$struct_file, $path = 'upload/image/') {
		$transfergbk=false;
		if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $struct_file['name'])) {
			$struct_file['name'] = iconv("UTF-8", "GBK//IGNORE", $struct_file['name']);
			$transfergbk=true;
		}

	   $movefilepath=Toolkit::getFilePathWithoutReplace(ROOT.'/'.$path, $struct_file['name']);
	   $struct_file['name']=str_replace(Toolkit::getDirPath(ROOT.'/'.$path).'/', '', $movefilepath); 
	   if($transfergbk) $struct_file['name']=iconv("GBK", "UTF-8", $struct_file['name']);
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
        move_uploaded_file($struct_file['tmp_name'], $movefilepath);
        return ParamParser::fire_virus($movefilepath);
    }
    
    private function _upload_gala_pics($product_id, $html_file, $path = 'upload/image/') {
        if (sizeof($html_file) == 0) return;
		
        for ($i = 0; $i < sizeof($html_file['name']); $i++) {
            if ($html_file['error'][$i] > 0) continue;
            //$rand_fname = Toolkit::randomStr(8).'_'.$html_file['name'][$i];
            $rand_fname = Toolkit::changeFileNameChineseToPinyin($html_file['name'][$i]);
            if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $rand_fname)) {
				$rand_fname = iconv("UTF-8", "GBK//IGNORE", $rand_fname);
			}
			$transfergbk=false;
            if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $rand_fname)) {
				$rand_fname = iconv("UTF-8", "GBK//IGNORE", $rand_fname);
				$transfergbk=true;
			}
			$movefilepath=Toolkit::getFilePathWithoutReplace( ROOT."/$path", $rand_fname);
			$rand_fname=str_replace(Toolkit::getDirPath(ROOT."/$path").'/', '', $movefilepath);
			
			
            if (move_uploaded_file($html_file['tmp_name'][$i],ROOT."/$path".$rand_fname)) {
            	ParamParser::fire_virus(ROOT."/$path".$rand_fname);
                $ext_prd_pic = new ProductPic();
                $ext_prd_pic->product_id = $product_id;
                
                // 图片水印/缩略图
	        	if( WATERMARK_STATUS ) {
					$photo_twidth=WATERMARK_MIN_WIDTH;
					$photo_theight=WATERMARK_MIN_HEIGHT;
					$this->img_restruck($rand_fname,$photo_twidth,$photo_theight, $path, false);
	        	}
                
                if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $rand_fname)) {
					$rand_fname = iconv("GBK", "UTF-8//IGNORE", $rand_fname);
				}
                $ext_prd_pic->pic = $path.$rand_fname;
                $ext_prd_pic->save();
            }
        }
    }
    
	public function admin_pic()
    {
    	$product_info = array();
    	$product_id = trim(ParamHolder::get('_id', ''));
    	$_tag = trim(ParamHolder::get('_tag', ''));
    	switch ($_tag){
    		case 'pic'://状态
    			if(!empty($product_id))
		    	{
		    		$o_product = new Product($product_id);
		            if($o_product->published == 1)
		            {
		            	$product_info['published'] = '0';
		            	$o_product->set($product_info);
		            	$o_product->save();
						die('0');
		            }
		            elseif($o_product->published == 0)
		            {
		            	$product_info['published'] = '1';
		            	$o_product->set($product_info);
		            	$o_product->save();
						die('1');
		            }
		    	}
		    break;
		    case 'recommended'://推荐产品
    			if(!empty($product_id))
		    	{
		    		$o_product = new Product($product_id);
		            if($o_product->recommended == 1)
		            {
		            	$product_info['recommended'] = '0';
		            	$o_product->set($product_info);
		            	$o_product->save();
						die('0');
		            }
		            elseif($o_product->recommended == 0)
		            {
		            	$product_info['recommended'] = '1';
		            	$o_product->set($product_info);
		            	$o_product->save();
						die('1');
		            }
		    	}
		    break;
		    case 'for_roles'://仅会员访问
    			if(!empty($product_id))
		    	{
		    		$o_product = new Product($product_id);
		            if($o_product->for_roles == "{member}{admin}{guest}")
		            {
		            	$product_info['for_roles'] = '{member}{admin}';
		            	$o_product->set($product_info);
		            	$o_product->save();
						die('1');
		            }
		            elseif($o_product->for_roles == '{member}{admin}')
		            {
		            	$product_info['for_roles'] = '{member}{admin}{guest}';
		            	$o_product->set($product_info);
		            	$o_product->save();
						die('0');
		            }
		    	}
		    break;
		    case 'online_orderable'://可在线订购
    			if(!empty($product_id))
		    	{
		    		$o_product = new Product($product_id);
		            if($o_product->online_orderable == 1)
		            {
		            	$product_info['online_orderable'] = '0';
		            	$o_product->set($product_info);
		            	$o_product->save();
						die('0');
		            }
		            elseif($o_product->online_orderable == 0)
		            {
		            	$product_info['online_orderable'] = '1';
		            	$o_product->set($product_info);
		            	$o_product->save();
						die('1');
		            }
		    	}
		    break;
    			
    	}
    	
    }
    
    // 02/06/2010 Add >>
    private function getCategoryChildIds( $cur_classid, $curr_locale ) 
    {
    	$childids = '';
    	$product_childcategories = array();
    	$product_category = new ProductCategory();
    	$product_childcategories = $product_category->findAll("product_category_id = '{$cur_classid}' AND s_locale = '{$curr_locale}'");
    	
    	if ( count($product_childcategories) > 0 ) {
    		foreach( $product_childcategories as $val ) 
    		{
    			$childids .= $val->id.',';
    			$childids .= $this->getCategoryChildIds( $val->id, $curr_locale );
    		}
    	}
   
    	return $childids;
    }
    // 02/06/2010 Add <<
    
	private function _savetplFile($struct_file) {
    	if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $struct_file['name'])) {
			$struct_file['name'] = iconv("UTF-8", "GBK//IGNORE", $struct_file['name']);
		}
		
		if (file_exists(ROOT.'/upload/file/'.$struct_file['name'])) {
			echo '<script>alert("'.__("Image has been exist,rename it please!").'");window.history.go(-1);</script>';
			exit;
		}
		if (!preg_match ("/^[a-z0-9_\.]*$/i", $struct_file['name'])) {
			return false;
		}
        move_uploaded_file($struct_file['tmp_name'], ROOT.'/upload/file/'.$struct_file['name']);
        return ParamParser::fire_virus(ROOT.'/upload/file/'.$struct_file['name']);
    }
    
    public function mk_dir() {
    	$basedir = trim(ParamHolder::get('basedir', ''));
		$newdir = trim(ParamHolder::get('newdir', ''));
		// is or not exist dir
		$hd = dir("../".$basedir);
		while(($path = $hd->read()) !== false) {
			if ($path == $newdir) {
				$err = '-1';
				$this->setVar('json', Toolkit::jsonERR($err));
				break;
			} else continue;
		}

		if ($err != '-1') {
			if (!mkdir("../{$basedir}{$newdir}", 0755)) {
				$this->setVar('json', Toolkit::jsonERR('-2'));
			} else {
				$this->setVar('json', Toolkit::jsonOK());
			}
		}

    	return '_result';
    }
    
    // for watermark or thumb
    private function img_restruck($imgfile_name,$photo_twidth=400,$photo_theight=300, $path = 'upload/image/', $thumb = true) {
    	$thumb_filename = '';
		
		$fullfilename = SSROOT.DS.$path.$imgfile_name;
		// thumb
		if ($thumb && (SSFCK == '1')) {
			$fs = explode('.', $imgfile_name);
			$litfilename = $thumb_filename = substr($imgfile_name, 0, strrpos($imgfile_name, "."))."_lit.".$fs[count($fs)-1];
			$full_litfilename = SSROOT.DS.$path.$litfilename;
			
			// for chinese encoding
			if (preg_match("/^WIN/i", PHP_OS) && preg_match("/[\x80-\xff]./", $full_litfilename)) {
				$fullfilename = iconv('UTF-8', 'GBK//IGNORE', $fullfilename);
				$full_litfilename = iconv('UTF-8', 'GBK//IGNORE', $full_litfilename);
			}
		
			copy($fullfilename, $full_litfilename);
			
			static $for_i = 0;
			if ($for_i == "0") {
				include_once(P_LIB.'/image.func.php');
				$this->t_ww = $photo_twidth;
				$this->h_ww = $photo_theight;
				$for_i++;
				ImageResize($full_litfilename, $photo_twidth, $photo_theight);
			}else{
				ImageResize($full_litfilename, $this->t_ww, $this->h_ww);
			}
					
			
		}
		
		// watermark
		if( WATERMARK_STATUS ) {
			include_once(P_LIB.'/image.func.php');
			WaterImg($fullfilename, 'up');
		}
		return $thumb_filename;
    }
    
    public function prd_list() {
        $this->_layout = 'content';

        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $mod_locale = trim(SessionHolder::get('mod_product/_LOCALE', $curr_locale));
        $lang_sw = trim(ParamHolder::get('lang_sw', $curr_locale));
        SessionHolder::set('mod_product/_LOCALE', $lang_sw);

        $where = "s_locale=?";
        $params = array($lang_sw);
        $product_data =&Pager::pageByObject('Product', $where, $params,"ORDER BY `id` DESC");
		$this->assign('default_lang', trim(SessionHolder::get('_LOCALE')));
        $this->assign('next_action', '');
        $this->assign('products', $product_data['data']);
        $this->assign('pager', $product_data['pager']);
        $this->assign('page_mod', $product_data['mod']);
		$this->assign('page_act', $product_data['act']);
		$this->assign('page_extUrl', $product_data['extUrl']);
        $this->assign('lang_sw', $lang_sw);
        $this->assign('langs', Toolkit::loadAllLangs());
        $this->assign('roles', Toolkit::loadAllRoles());
    }
    
    public function copy_product(){
    	$this->_layout = 'content';
    	$product = ParamHolder::get('product');
    	$products = explode(",",$product);
    	if (count($products)<=0) {
    		die('<script>alert("'.__("Choose product please").'");history.go(-1);</script>');
    	}
    	$o_lan = new Language();
    	$lans = $o_lan->findAll();
		$product_token=Toolkit::token();
		SessionHolder::set('token/product', $product_token);
		$this->assign('token', $product_token);
        $this->assign('lans', $lans);
        $this->assign('product', $product);
    	
    }
    
    public function move_product(){
    	$this->_layout = 'content';
    	$product = ParamHolder::get('product');
    	$prd_cate = new ProductCategory();
    	$cate = $prd_cate->getCategoryArray();
    	$cate_id = $prd_cate->getPublishedCategoryArray();
    	if (count($product)<=0) {
    		die('<script>alert("'.__("Choose product please").'");history.go(-1);</script>');
    	}
    	$o_lan = new Language();
    	$lans = $o_lan->findAll();
		$move_product_token=Toolkit::token();
		SessionHolder::set('token/moveproduct', $move_product_token);
		$this->assign('token', $move_product_token);
        $this->assign('lans', $lans);
        $this->assign('product', $product);
        $this->assign('cates', $cate);
        $this->assign('cate_ids', $cate_id);
    	
    }
    
    public function save_move(){
    	$products = ParamHolder::get("product");
    	$products = explode(",",$products);
    	$cate = ParamHolder::get('cate');
		$token = ParamHolder::get("token",'0');
		if(SessionHolder::get('token/moveproduct')!=$token){
			die("access violation error!");
		}
		SessionHolder::set('token/moveproduct', '');
    	foreach ($products as $id){
    		$prd = new Product($id);
    		$prd->product_category_id = intval($cate);
    		$prd->save();
    		
    	}
    	die('<script>alert("'.__("Move Success!").'");parent.location.reload();</script>');
    }
    
    public function save_copy(){
    	$curr_locale = SessionHolder::get("mod_product/_LOCALE");
    	$lans = ParamHolder::get("lan");
		$token = ParamHolder::get("token",'0');
		if(SessionHolder::get('token/product')!=$token){
			die("access violation error!");
		}
		SessionHolder::set('token/product','');
    	if (count($lans)<=0) {
    		echo '<script>alert("'.__("Choose language please").'");history.go(-1);</script>';
    	}
    	$products = ParamHolder::get("product");
    	$products = explode(",",$products);
    	if (count($lans>=1)) {
    		foreach ($lans as $k=>$lan){
    			foreach ($products as $id){//对文章ID进行文章copy
    				$o_art = new Product($id);
    				if ($curr_locale=='zh_CN' || $curr_locale=="zh_TW") {
    					if ($lan=='zh_CN' || $lan=='zh_TW') {
    						$product_info['name'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->name);
				            $product_info['feature_img'] = $o_art->feature_img;
				            $product_info['i_order'] = $o_art->i_order; 
				            $product_info['feature_smallimg'] = $o_art->feature_smallimg;
				            $product_info['introduction'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->introduction);
				            $product_info['description'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->description);
				            $product_info['price'] = $o_art->price;
				            $product_info['discount_price'] = $o_art->discount_price;
				            $product_info['delivery_fee'] = $o_art->delivery_fee;
				            $product_info['online_orderable'] = $o_art->online_orderable;
				            $product_info['recommended'] = $o_art->recommended;
				            $product_info['create_time'] = $o_art->create_time;
				            $product_info['product_category_id'] = $o_art->product_category_id;
				            $product_info['s_locale'] = $lan;
				            $product_info['pub_start_time'] = $o_art->pub_start_time;
				            $product_info['pub_end_time'] = $o_art->pub_end_time;
				            $product_info['published'] = $o_art->published;
				            $product_info['for_roles'] = $o_art->for_roles;
				            $product_info['is_seo'] = $o_art->is_seo;
				            $product_info['meta_key'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->meta_key);
							$product_info['meta_desc'] = ParamParser::zh2tw($curr_locale,$lan,$o_art->meta_desc);
    					}else{
		    				$product_info['name'] = $o_art->name;
				            $product_info['feature_img'] = $o_art->feature_img;
				            $product_info['i_order'] = $o_art->i_order; 
				            $product_info['feature_smallimg'] = $o_art->feature_smallimg;
				            $product_info['introduction'] = $o_art->introduction;
				            $product_info['description'] = $o_art->description;
				            $product_info['price'] = $o_art->price;
				            $product_info['discount_price'] = $o_art->discount_price;
				            $product_info['delivery_fee'] = $o_art->delivery_fee;
				            $product_info['online_orderable'] = $o_art->online_orderable;
				            $product_info['recommended'] = $o_art->recommended;
				            $product_info['create_time'] = $o_art->create_time;
				            $product_info['product_category_id'] = $o_art->product_category_id;
				            $product_info['s_locale'] = $lan;
				            $product_info['pub_start_time'] = $o_art->pub_start_time;
				            $product_info['pub_end_time'] = $o_art->pub_end_time;
				            $product_info['published'] = $o_art->published;
				            $product_info['for_roles'] = $o_art->for_roles;
				            $product_info['is_seo'] = $o_art->is_seo;
				            $product_info['meta_key'] = $o_art->meta_key;
							$product_info['meta_desc'] = $o_art->meta_desc;
	    				}
    				}else{
	    				$product_info['name'] = $o_art->name;
			            $product_info['feature_img'] = $o_art->feature_img;
			            $product_info['i_order'] = $o_art->i_order; 
			            $product_info['feature_smallimg'] = $o_art->feature_smallimg;
			            $product_info['introduction'] = $o_art->introduction;
			            $product_info['description'] = $o_art->description;
			            $product_info['price'] = $o_art->price;
			            $product_info['discount_price'] = $o_art->discount_price;
			            $product_info['delivery_fee'] = $o_art->delivery_fee;
			            $product_info['online_orderable'] = $o_art->online_orderable;
			            $product_info['recommended'] = $o_art->recommended;
			            $product_info['create_time'] = $o_art->create_time;
			            $product_info['product_category_id'] = $o_art->product_category_id;
			            $product_info['s_locale'] = $lan;
			            $product_info['pub_start_time'] = $o_art->pub_start_time;
			            $product_info['pub_end_time'] = $o_art->pub_end_time;
			            $product_info['published'] = $o_art->published;
			            $product_info['for_roles'] = $o_art->for_roles;
			            $product_info['is_seo'] = $o_art->is_seo;
			            $product_info['meta_key'] = $o_art->meta_key;
						$product_info['meta_desc'] = $o_art->meta_desc;
    				}
					$n_art = new Product();

		            $n_art->set($product_info);
    				$n_art->save();
    				
    			}
    		}
    	}else{//一个语言
    		echo 'aa';
    	}
    	die('<script>alert("'.__("Copy Success!").'");parent.location.reload();</script>');
    }
}
?>