<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Product category object
 * 
 */
class ProductCategory extends RecordObject {
	public static $cache_data;
	public static $cache_handle = true;
	
	public static $cache_data1;
	public static $cache_handle1 = true;
	
	public static $cache_data2;
	public static $cache_handle2 = true;
	
    public $has_many = array('ProductCategory', 'Product');
    
    public $belong_to = array('ProductCategory');
    
    public $siblings = array(
    	'prev' => 0, 
    	'next' => 0
    );
    
    protected $no_validate = array(
        'isEmpty' => array(
            array('name', 'Missing category name!'), 
            array('alias', 'Missing category alias!'),
            array('product_category_id', 'Missing parent category!'),
            array('s_locale', 'Missing locale!'),
            array('published', 'Missing publish status!'),
            array('for_roles', 'Missing access property!')
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^0|1$/', 'published', 'Invalid publish status!'),
            array('/^(\{\w+\})+$/', 'for_roles', 'Invalid access property!'),
            array('/^[a-zA-Z0-9\-_\.]+$/', 'alias', 'Invalid category alias')
        ),
        'isNumeric' => array(
            array('product_category_id', 'Invalid parent category ID!')
        )
    );
    
	 private static function _rebuildSql($sql, &$params) {
        if (!$params) {
            return $sql;
        } else {
            $sql_part = explode('?', $sql);
            $sql = $sql_part[0];
            for ($i = 1; $i < sizeof($sql_part); $i++) {
                $sql .= "'"
                    .mysql_escape_string($params[$i - 1])
                    ."'".$sql_part[$i];
            }
            return $sql;
        }
    }	
		
	public static $listCategories=array();	
     public static function initListCategories($where = false, $params = false){
			 $oriwhere="`id`<>'1' ";
			 if($where) $oriwhere.="AND ".$where;
			 $sqlkey=self::_rebuildSql($oriwhere, $params);
			 if(!empty(self::$listCategories[$sqlkey])) return self::$listCategories[$sqlkey];
			$o_category_p = new ProductCategory();
			 $categories_p =& $o_category_p->findAll($oriwhere, 
        	      $params, "ORDER BY i_order");
			 $catekeychildren=array();
			 self::$listCategories[$sqlkey]=array();
			 $zero_category=new stdClass();
			  self::$listCategories[$sqlkey][0]=$zero_category;
			 foreach($categories_p as $cate){
				 $cate->slaves['ProductCategory'] =array();
				 self::$listCategories[$sqlkey][$cate->id]=$cate;
				 $parent_id=$cate->product_category_id;
				 if(empty($catekeychildren[$parent_id])){
					 $catekeychildren[$parent_id]=array();
					 $catekeychildren[$parent_id][]=$cate;
					 $cate->siblings['prev'] =0;
				 }else{
					 $catekeychildren[$parent_id][]=$cate;
					 $prevcate=$catekeychildren[$parent_id][count($catekeychildren[$parent_id])-2];
					 $cate->siblings['prev'] =  $prevcate->id;
					 $prevcate->siblings['next'] = $cate->id;
				 }
			 }

			 foreach($catekeychildren as $parid=>$children){

				 if(!empty(self::$listCategories[$sqlkey][$parid])) self::$listCategories[$sqlkey][$parid]->slaves['ProductCategory'] =$children;
				 else{
						 self::$listCategories[$sqlkey][$parid]=new stdClass();
						 self::$listCategories[$sqlkey][$parid]->slaves=array();
						 self::$listCategories[$sqlkey][$parid]->slaves['ProductCategory'] =$children;
				 }
				 
			 }
			 
			 return self::$listCategories[$sqlkey];
	 }
		
    public static function &listCategories($parent_id = 0, $where = false, $params = false) {
			$listcates=&self::initListCategories($where, $params);
			$categories_p=$listcates[$parent_id];
			if(!empty($categories_p)&&!empty($categories_p->slaves['ProductCategory'])){
				return $categories_p->slaves['ProductCategory'];
			}else{
				return array();
			}

    }
    
    public static function toSelectArray(&$category_tree, &$select_array, $level = 0, 
    	$ignore_ids = array(), $first_option = array()) {
    	if ($level == 0 && sizeof($first_option) > 0) {
    	    foreach ($first_option as $key => $val) {
    	        $select_array[$key] = $val;
    	    }
    	}
    	if(empty($category_tree)) $category_tree = array();
	    foreach ($category_tree as $category) {
	    	if (in_array(intval($category->id), $ignore_ids)) {
	    	    continue;
	    	}
	    	$select_array[$category->id] = str_repeat('&nbsp;--', $level).'&nbsp;'.$category->name;
	        if (sizeof($category->slaves['ProductCategory']) > 0) {
	            $level++;
	            self::toSelectArray($category->slaves['ProductCategory'], $select_array, $level, $ignore_ids);
	            $level--;
	        }
    	}
    }
    
    public static function delete_r($category_p_id) {
        $all_categories =& self::listCategories($category_p_id);
        self::delete_r_all($all_categories);
    }
    
    public static function delete_r_all(&$category_tree) {
        if (sizeof($category_tree) > 0) {
		    foreach ($category_tree as $category) {
		        if (sizeof($category->slaves['ProductCategory']) > 0) {
		            self::delete_r_all($category->slaves['ProductCategory']);
		        }
		        $category->delete();
		    }
        }
    }
    
    public static function getMaxOrder($parent_category_p_id) {
        $db =& MySqlConnection::get();
        $sql = "SELECT MAX(i_order) AS max_order FROM ".Config::$tbl_prefix."product_categories WHERE product_category_id=?";
        $rs =& $db->query($sql, array($parent_category_p_id));
        if ($rs->getRecordNum() == 0) {
            return 0;
        } else {
            $row =& $rs->fetchRow();
            return intval($row['max_order']);
        }
    }
    
    public function &getModArray() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($curr_locale));
        $select_array = array('0' => __('All Categories'));
        ProductCategory::toSelectArray($all_categories, $select_array);
        
        return $select_array;
    }
    
    // 28/4/2010 Add >>
	public function &getCategoryArray() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        // 仅显示一级菜单
        $all_categories =& ProductCategory::listCategories(0, "s_locale=?", array($curr_locale));
        $select_array = array('0' => __('Top Level'));
        ProductCategory::toSelectArray($all_categories, $select_array);
        
        return $select_array;
    }
    // 28/4/2010 Add <<
    
    public function &getPublishedCategoryArray() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        // 仅显示一级菜单
        $published_categories = array();
        $category_tree =& ProductCategory::listCategories(0, "s_locale=? And `product_category_id`=0 AND `published`='1'", array($curr_locale));
        foreach ($category_tree as $category) {
        	$published_categories[] = $category->id;
        }
        
        return $published_categories;
    }
    
    /**
     * for menu-drop type 
     */
    public function dropType() {
    	return array('slide' => __('Mouse over to start type'), 'click' => __('Mouse click to start type'), 'always' => __('Always open'));
    }
    
    public static function cacheStrategy23($param1,$param2)
    {
    	if((TABLE_CACHE == 1) && (SessionHolder::get('page/status') == 'view') && empty($_GET['_v']))
    	{
    		$prefix = Config::$tbl_prefix;
    		$getObjects = array();
    		$objects = array();
    		
    		if(strcmp($param1,"SELECT * FROM `{$prefix}product_categories` WHERE product_category_id=? AND s_locale=? And `product_category_id`=0 AND `id`<>'1' ORDER BY i_order") == 0)
    		{
    			if(empty(self::$cache_data))
    			{
    				if(self::$cache_handle)
    				{
    					self::$cache_handle = false;
    					if($param2[0] == 0)
    					{
    						$db = MysqlConnection::get();
    						$sql1 = "SELECT * FROM `{$prefix}product_categories` WHERE s_locale='$param2[1]' And `product_category_id`=0 AND `id`<>'1' ORDER BY i_order";
    						$rs =& $db->query($sql1);
    						$getObjects =& $rs->fetchObjects('ProductCategory',array(false, false));
    						self::$cache_data = $getObjects;
    						$rs->free();
    						
    					}
    				}
    			}
    			else
    			{
    				if($param2[0] == 0)
    				{
    					$getObjects = self::$cache_data;
    				}
    			}
    		}
    		elseif(strcmp("SELECT * FROM `{$prefix}product_categories` WHERE product_category_id=? AND published='1'  AND  for_roles LIKE ? AND s_locale=? AND `id`<>'1' ORDER BY i_order",$param1) == 0)
    		{
    			if(empty(self::$cache_data1))
    			{
    				if(self::$cache_handle1)
    				{
    					self::$cache_handle1 = false;
    					$db = MysqlConnection::get();
    					
    					$sql1 = "SELECT * FROM `{$prefix}product_categories` WHERE published='1'  AND  for_roles LIKE '{$param2[1]}' AND s_locale='{$param2[2]}' AND `id`<>'1' ORDER BY i_order";
    					$rs =& $db->query($sql1);
    					$objects =& $rs->fetchObjects('ProductCategory',array(false, false));
    					self::$cache_data1 = $objects;
    					$rs->free();
    					
    					foreach($objects as $v)
    					{
    						if($v->product_category_id == $param2[0])
    						{
    							$getObjects[] = $v;
    						}
    					}
    				}
    			}
    			else
    			{
    				foreach(self::$cache_data1 as $v)
    				{
    					if($v->product_category_id == $param2[0])
    					{
    						$getObjects[] = $v;
    					}
    				}
    			}
    		}
//    		elseif(strpos($param1,"SELECT * FROM `{$prefix}product_categories` WHERE product_category_id=? AND published='1' AND `id` NOT IN(") == 0)
//    		{
//    			if(strpos($param1,") AND  for_roles LIKE ? AND s_locale=? AND `id`<>'1' ORDER BY i_order"))
//    			{
//					if(empty(self::$cache_data2))
//					{
//						if(self::$cache_handle2)
//						{
//							$tmp = str_replace("SELECT * FROM `{$prefix}product_categories` WHERE product_category_id=? AND published='1' AND `id` NOT IN(","",$param1);
//							$tmp = str_replace(") AND  for_roles LIKE ? AND s_locale=? AND `id`<>'1' ORDER BY i_order","",$tmp);
//							
//							self::$cache_handle2 = false;
//							$db = MysqlConnection::get();
//							$sql1 = "SELECT * FROM `{$prefix}product_categories` WHERE published='1' AND `id` NOT IN($tmp) AND  for_roles LIKE '{$param2[1]}' AND s_locale='{$param2[2]}' AND `id`<>'1' ORDER BY i_order";
//							$rs =& $db->query($sql1);
//							$objects = & $rs->fetchObjects('ProductCategory',array(false, false));
//							self::$cache_data2 = $objects;
//							$rs->free();
//							
//							foreach($objects as $v)
//							{
//								if($v->product_category_id == $param2[0])
//								{
//									$getObjects[] = $v;
//								}
//							}
//						}
//					}
//					else
//					{
//						foreach(self::$cache_data2 as $v)
//	    				{
//	    					if($v->product_category_id == $param2[0])
//	    					{
//	    						$getObjects[] = $v;
//	    					}
//	    				}
//					}
//    			}
//    		}
    		else
    		{
    			$getObjects = "notmatch";
    		}
    		
    		if(empty($getObjects))
    		{
    			return "empty";
    		}
    		else
    		{
    			return $getObjects;
    		}
    	}
    }

	//获取分类ID
	public static function getAllCategoryID($param){
		
		$arr = explode(",",$param);
		
		$result=array();
		foreach($arr as $val){
			$_tmp=array($val);
			$ret_arr = self::recurrence($val,$_tmp);
			$result=array_unique(array_merge($ret_arr,$result));
		}
		return implode(',',$result);
	}
	private function recurrence($val,& $ret_arr=array()){
		$db =& MySqlConnection::get();
		$sql ="select id,product_category_id from ".Config::$tbl_prefix."product_categories where product_category_id=?";
		$rs =& $db->query($sql, array($val));
		
			while($row =& $rs->fetchRow()){
				//$ret_str .=$row['id'];
				if(!in_array($row['id'],$ret_arr)){
					array_push($ret_arr,$row['id']);
				}
				if(intval($row['id'])>0){
					self::recurrence($row['id'],$ret_arr);
				}
		}
		return $ret_arr;
	}
	
	//获取父级id值 2012-05-25
	public static function parent_id($parent_category_p_id) {
		$tmp = explode(",",$parent_category_p_id);
        $db =& MySqlConnection::get();
        $sql = "SELECT product_category_id  FROM ".Config::$tbl_prefix."product_categories WHERE id in($parent_category_p_id)";
        $rs =& $db->query($sql, array());
        $row =& $rs->fetchRows();
        $ret=array();
        $i=0;
        foreach($row as $v){
        	if(in_array($v['product_category_id'],$tmp)){ continue;}
        	$ret[$i++] = $v['product_category_id'];
        }
        //print_r($ret);
        return $ret;
    }
}
?>