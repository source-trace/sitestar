<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Download category object
 * 
 */
class DownloadCategory extends RecordObject {
    public $has_many = array('DownloadCategory', 'Download');
    
    protected $no_validate = array(
        'isEmpty' => array(
            array('name', 'Missing download category name!'), 
            array('s_locale', 'Missing locale!'),
            array('alias', 'Missing category alias!'),
            array('published', 'Missing publish status!'),
            array('for_roles', 'Missing access property!')
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^0|1$/', 'published', 'Invalid publish status!'),
            array('/^(\{\w+\})+$/', 'for_roles', 'Invalid access property!')
        )
    );
   
    
    public static function &listCategories($parent_id = 0, $where = false, $params = false) {
	    if (!$where) {
	        $where_r = "`id`<>'1'";
	    } else {
	        $where_r = $where." AND `id`<>'1'";
	    }
	    if (!$params) {
	        $params = array();
	    }
	    
	    $prev_category_d_id = 0;
        
        $o_category_d = new DownloadCategory();
        $categories_d =& $o_category_d->findAll($where_r,$params, "ORDER BY i_order");
        if (sizeof($categories_d) > 0) {
            for ($i = 0; $i < sizeof($categories_d); $i++) {
            	$categories_d[$i]->siblings['prev'] = $prev_category_d_id;
            	if ($i > 0) {
            	    $categories_d[$i - 1]->siblings['next'] = $categories_d[$i]->id;
            	}
            	$prev_category_d_id = $categories_d[$i]->id;
            	
               
            }
        }
        
        return $categories_d;
    }

     public static function getMaxOrder() {
        $db =& MySqlConnection::get();
        $sql = "SELECT MAX(i_order) AS max_order FROM ".Config::$tbl_prefix."download_categories";
        $rs =& $db->query($sql);
        if ($rs->getRecordNum() == 0) {
            return 0;
        } else {
            $row =& $rs->fetchRow();
            return intval($row['max_order']);
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
	        if (sizeof($category->slaves['DownloadCategory']) > 0) {
	            $level++;
	            self::toSelectArray($category->slaves['DownloadCategory'], $select_array, $level, $ignore_ids);
	            $level--;
	        }
	    }
    }
    
    public static function delete_r($category_d_id) {
        $all_categories =& self::listCategories($category_d_id);
        self::delete_r_all($all_categories);
    }
    
    public static function delete_r_all(&$category_tree) {
        if (sizeof($category_tree) > 0) {
		    foreach ($category_tree as $category) {
		        if (sizeof($category->slaves['DownloadCategory']) > 0) {
		            self::delete_r_all($category->slaves['DownloadCategory']);
		        }
		        $category->delete();
		    }
        }
    }
    
   
    
    public function &getModArray() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        $all_categories =& DownloadCategory::listCategories(0, "s_locale=?", array($curr_locale));
        $select_array = array('0' => __('Top Level'));
        DownloadCategory::toSelectArray($all_categories, $select_array);
        
        return $select_array;
    }
    
    // 28/4/2010 Add >>
    public function &getCategoryArray() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        // 仅显示一级菜单
        $all_categories =& DownloadCategory::listCategories(0, "s_locale=?", array($curr_locale));
        DownloadCategory::toSelectArray($all_categories, $select_array);
        return $select_array;
    }
    // 28/4/2010 Add << 
    public function &getPublishedCategoryArray() {
        $curr_locale = trim(SessionHolder::get('_LOCALE'));
        // 仅显示一级菜单
        $category_tree =& DownloadCategory::listCategories(0, "s_locale=? AND `published`='1'", array($curr_locale));
		foreach ($category_tree as $category) {
        	$published_categories[] = $category->id;
        }
        
        return $published_categories;
    }
    
    public static function cacheStrategy($param1,$param2)
    {
    	if((TABLE_CACHE == 1) && (SessionHolder::get('page/status') == 'view') && empty($_GET['_v']))
    	{
    		$prefix = Config::$tbl_prefix;
    		$getObjects = array();
    		$objects = array();
    		
    		if(strcmp($param1,"SELECT * FROM `{$prefix}download_categories` WHERE download_category_id=? AND published='1'  AND for_roles LIKE ? AND s_locale=? AND `id`<>'1' ORDER BY i_order") == 0)
    		{
    			if(empty(self::$cache_data1))
    			{
    				if(self::$cache_handle1)
    				{
    					self::$cache_handle1 = false;
    					$db = MysqlConnection::get();
    					
    					$sql1 = "SELECT * FROM `{$prefix}download_categories` WHERE published='1'  AND for_roles LIKE '{$param2[1]}' AND s_locale='{$param2[2]}' AND `id`<>'1' ORDER BY i_order";
    					$rs =& $db->query($sql1);
    					$objects = & $rs->fetchObjects('DownloadCategory',array(false, false));
    					self::$cache_data1 = $objects;
    					$rs->free();
    					
    					foreach($objects as $v)
    					{
    						if($v->download_category_id == $param2[0])
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
    					if($v->download_category_id == $param2[0])
    					{
    						$getObjects[] = $v;
    					}
    				}
    			}
    		}
    		elseif(strcmp($param1,"SELECT * FROM `{$prefix}download_categories` WHERE download_category_id=? AND s_locale=? And `download_category_id`=0 AND `id`<>'1' ORDER BY i_order") == 0)
    		{
    			if(empty(self::$cache_data))
    			{
    				if(self::$cache_handle)
    				{
    					self::$cache_handle = false;
    					if($param2[0] == 0)
    					{
    						$db = MysqlConnection::get();
    						$sql1 = "SELECT * FROM `{$prefix}download_categories` WHERE s_locale='$param2[1]' And `download_category_id`=0 AND `id`<>'1' ORDER BY i_order";
    						$rs =& $db->query($sql1);
    						$getObjects =& $rs->fetchObjects('DownloadCategory',array(false, false));
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
    		elseif(!(strpos($param1,"SELECT * FROM `{$prefix}download_categories` WHERE download_category_id = ") === false))
    		{
    			if(preg_match("/AND s_locale = 'zh_CN'$/i",$param1) || preg_match("/AND s_locale = 'en'$/i",$param1))
    			{
    				$getObjects = Memorycache::FetchMemory($param1,"{$prefix}download_categories","all",'DownloadCategory');
    			}
    		}
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
    
//获取父级id值 2012-05-25
	public static function parent_id($parent_category_p_id) {
		$tmp = explode(",",$parent_category_p_id);
        $db =& MySqlConnection::get();
        $sql = "SELECT download_category_id  FROM ".Config::$tbl_prefix."download_categories WHERE id in($parent_category_p_id)";
        $rs =& $db->query($sql, array());
        $row =& $rs->fetchRows();
        $ret=array();
        $i=0;
        foreach($row as $v){
        	if(in_array($v['download_category_id'],$tmp)){ continue;}
        	$ret[$i++] = $v['download_category_id'];
        }
        //print_r($ret);
        return $ret;
    }
}
?>