<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Menu Item object
 * 
 */
class MenuItem extends RecordObject {
    public $has_many = array('MenuItem');
    
    public $belong_to = array('MenuItem');
    protected $no_validate = array(
        'isEmpty' => array(
            array('name', 'Missing menu item name!'), 
            array('menu_item_id', 'Missing parent menu item!'),
            array('s_locale', 'Missing locale!'),
            array('for_roles', 'Missing access property!')
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
            array('/^(\{\w+\})+$/', 'for_roles', 'Invalid access property!')        ),
        'isNumeric' => array(
            array('menu_item_id', 'Invalid parent menu item ID!')
        )
    );
    
    public static $cache_data;
    
    public static $cache_handle = true;
    
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
     public static function initListMenuItems($where = false, $params = false){
			 $oriwhere="";
			 if($where) $oriwhere.=$where;
			 else $oriwhere = false;
			 $sqlkey=self::_rebuildSql($oriwhere, $params);
			 if(!empty(self::$listCategories[$sqlkey])) return self::$listCategories[$sqlkey];
			$o_category_p = new MenuItem();
			 $categories_p =& $o_category_p->findAll($oriwhere, $params, "ORDER BY i_order");
			 $catekeychildren=array();
			 self::$listCategories[$sqlkey]=array();
			 $zero_category=new stdClass();
			  self::$listCategories[$sqlkey][0]=$zero_category;
			 foreach($categories_p as $cate){
				 $cate->slaves['MenuItem'] =array();
				 self::$listCategories[$sqlkey][$cate->id]=$cate;
				 $parent_id=$cate->menu_item_id;
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
				 if(MOD_REWRITE=='2'){
					if($cate->mi_category !="outer_url"){
						$cate->link = self::menu_rewirte($cate->link);
					}
				}else if(MOD_REWRITE=='3'){
					if($cate->mi_category !="outer_url"){
						$cate->link = self::menu_custom($cate->content_id,$cate->mi_category,$cate->url,$cate->s_locale);
					}				
				} else {
					if($cate->mi_category !="outer_url"){
						$cate->link = 'index.php?'.$cate->link;
					}
				}
			 }

			 foreach($catekeychildren as $parid=>$children){

				 if(!empty(self::$listCategories[$sqlkey][$parid])) self::$listCategories[$sqlkey][$parid]->slaves['MenuItem'] =$children;
			 }
			 
			 return self::$listCategories[$sqlkey];
	 }	
		
    public static function &listMenuItems($parent_id = 0, $where = false, $params = false) {
		$listcates=&self::initListMenuItems($where, $params);
		$categories_p=$listcates[$parent_id];
		if(!empty($categories_p)&&!empty($categories_p->slaves['MenuItem'])){
			return $categories_p->slaves['MenuItem'];
		}else{
			return array();
		}	
    }
    
    public static function toSelectMenu($menus) {
        if(sizeof($menus) > 0) {
            foreach($menus as $key => $val) {
                $menus[$key] = $val['name'];      
            }
        }
        return $menus;
    }
    
    public static function toSectionArray($menus) {
        $reformed = array();
        if(sizeof($menus) > 0) {
            foreach($menus as $key => $val) {
            	if ($key=="bulletins" || $key=="mod_user") {
            		continue;
            	}
                if (!isset($reformed[$val['type']])) $reformed[$val['type']] = array();
                if ($val['use_popup'])
                    $key = $key.'|1';
                else
                    $key = $key.'|0';
                $key .= '|'.__($val['name']);
                $reformed[$val['type']][$key] = $val['name'];      
            }
        }
//        var_dump($reformed);
        return $reformed;
    }
    
    public static function toSelectLink(&$menus, $flag) {
        $_m = $menus[$flag]['mod_addr']['mod_name'];
        $_a = $menus[$flag]['mod_addr']['addr'];
        $_is_id = $menus[$flag]['is_id'];
        $menus['link'] = '_m='.$_m.'&_a='.$_a;
        $menus['is_id'] = $_is_id;
        $menus['obj_name'] = $menus[$flag]['obj_name'];
        $menus['obj_field'] = $menus[$flag]['obj_field'];
        $menus['id_c'] = $menus[$flag]['id_category'];
        return $menus;
    }
    
    public static function getMaxOrder($parent_mi_id) {
        $db =& MySqlConnection::get();
        $sql = "SELECT MAX(i_order) AS max_order FROM ".Config::$tbl_prefix."menu_items WHERE menu_item_id=?";
        $rs =& $db->query($sql, array($parent_mi_id));
        if ($rs->getRecordNum() == 0) {
            return 0;
        } else {
            $row =& $rs->fetchRow();
            return intval($row['max_order']);
        }
    }
    
    public function dealSContent(&$scontents) {
        if(sizeof($scontents) > 0) {
            for($i = 0; $i < sizeof($scontents); $i++) {
                $_scontents[$scontents[$i]->id] = $scontents[$i]->title;
            }
            array_unshift($_scontents, __('Please select'));
        }
        return $_scontents;
    }
    
    public static function toSelectArray(&$mi_tree, &$select_array, $level = 0, 
        $ignore_ids = array(), $first_option = array()) {
        if ($level == 0 && sizeof($first_option) > 0) {
            foreach ($first_option as $key => $val) {
                $select_array[$key] = $val;
            }
        }
        foreach ($mi_tree as $mi) {
            if (in_array(intval($mi->id), $ignore_ids)) {
                continue;
            }
            $select_array[$mi->id] = str_repeat('&nbsp;--', $level).'&nbsp;'.$mi->name;
            if (sizeof($mi->slaves['MenuItem']) > 0) {
                $level++;
                self::toSelectArray($mi->slaves['MenuItem'], $select_array, $level, $ignore_ids);
                $level--;
            }
        }
    }
    
    public static function delete_r($mi_id) {
        $all_mis =& self::listMenuItems($mi_id);
        self::delete_r_all($all_mis);
    }
    
    public static function delete_r_all(&$mi_tree) {
        if (sizeof($mi_tree) > 0) {
            foreach ($mi_tree as $mi) {
                if (sizeof($mi->slaves['MenuItem']) > 0) {
                    self::delete_r_all($mi->slaves['MenuItem']);
                }
                $mi->delete();
            }
        }
    }
	private static function menu_rewirte($link){
		$params = explode('&',$link);
		$ret_link='';
		foreach($params as $key=>$val){
			if($key=='0'){
				$ret_link.=substr($val,3);
			}else if($key=='1'){
				$ret_link.='-'.substr($val,3);
			}else{
				$tmp_link = explode('=',$val);
				$ret_link.='-'.$tmp_link[0].'-'.$tmp_link[1];
			}
		}
		return $ret_link.'.html';
	}
	
	/**
	 * use memory cache strategy
	 * @param $param1 sql
	 * @param $param2 some conditions of sql
	 * @return memory data or empty or notmatch
	 */
	public static function cacheStrategy23($param1,$param2)
	{
		if((TABLE_CACHE == 1) && (!ACL::requireRoles(array('admin'))) && empty($_GET['_v']))//when opening cache and browsering page,system use cache strategy
		{
			if(empty(self::$cache_data) && self::$cache_handle == true)
			{
				if(count($param2) < 3) return "notmatch";
				$objects = array();
				$getObjects = array();
				$db = MysqlConnection::get();
				$sql1 = "SELECT * FROM ".Config::$tbl_prefix."menu_items WHERE published='1' AND for_roles LIKE '{$param2[1]}' AND s_locale= '{$param2[2]}' AND menu_id= $param2[3] ORDER BY i_order";
				$rs =& $db->query($sql1);
				$objects =& $rs->fetchObjects('MenuItem',array(false, false));
				self::$cache_data = $objects;
	        	$rs->free();
	        	self::$cache_handle = false;
	        	
	        	if(!empty($param1))
	        	{
	        		if(strcmp($param1,"SELECT * FROM `".Config::$tbl_prefix."menu_items` WHERE menu_item_id=? AND published='1' AND for_roles LIKE ? AND s_locale=? AND menu_id=? ORDER BY i_order") == 0)
	        		{
		        		foreach($objects as $v)
		        		{
		        			if($v->menu_item_id == $param2[0])
		        			{
		        				$getObjects[] = $v;
		        			}
		        		}
	        		}
	        		else
	        		{
	        			$getObjects = "notmatch";
	        		}
	        	}
			}
			elseif(!empty(self::$cache_data))
			{
				$getObjects = array();
				if(!empty($param1))
				{
					if(strcmp($param1,"SELECT * FROM `".Config::$tbl_prefix."menu_items` WHERE menu_item_id=? AND published='1' AND for_roles LIKE ? AND s_locale=? AND menu_id=? ORDER BY i_order") == 0)
					{
						foreach(self::$cache_data as $v)
						{
							if($v->menu_item_id == $param2[0])
							{
								$getObjects[] = $v;
							}
						}
					}
					else
					{
						$getObjects = "notmatch";
					}
				}
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
	
	/**
	 * 自定义链接导航生成
	 * author:zhangjc 2013-06-25
	 */
	public static function menu_custom($id,$mi_category,$url,$s_locale){
		
		include(P_INC.'/custom.php');
		$ret='';
			foreach($custom as $key=>$val){
				if($mi_category==$val['mi_category']){
					if($val['flag']){
						if(!empty($url)){
							$ret= $url;
						}else{
							$ret= $val['path'].'-'.$id;
						}
					}else{
						if(!empty($url)){
							$ret= $url;
						}else{
							$ret= $key;
						}
					}
				}
			}
		
		return $ret.'.html';
	}
}
?>