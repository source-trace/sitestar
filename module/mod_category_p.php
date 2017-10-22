<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

class ModCategoryP extends Module {
    protected $_filters = array(
        'check_login' => '{category_p_menu}'
    );
    
    public function category_p_menu() {
    	$curr_locale = trim(SessionHolder::get('_LOCALE', DEFAULT_LOCALE));
        $user_role = trim(SessionHolder::get('user/s_role', '{guest}'));
        
        // 28/4/2010 Add >>
        $type_a = ParamHolder::get('product_category_list', '0');
		if(empty($type_a)){
				 $type_a='0';
			}
        $pattern = '/^[0-9][\,0-9]*$/';
        if(!preg_match($pattern, $type_a)){
        	die('access violation error!');;
        }
        $extra = '';
        $arr1 = $arr2 = $arr3 = array();
        $menu_no1 = array();
        if ( !empty($type_a) ) {
        	// 一级菜单列表
	        
	        $menu_no1 = & ProductCategory::getCategoryArray();
	       	unset($menu_no1[0]);
	        $arr1 = array_keys( $menu_no1 );
	        $arr2 = explode( ',', $type_a ); // 包含"最上层"菜单
	        $arr3 = array_diff( $arr1, $arr2 );
	        
	        if( sizeof($arr3) )
	        $extra = 'AND `id` NOT IN('.join( ',', $arr3 ).')';
        }
        // 28/4/2010 Add <<
        $level = self::level($menu_no1,$arr2);
	    if($level == '0'){
        if (ACL::requireRoles(array('admin'))) {
        	$all_categories =& ProductCategory::listCategories(0, 
				"published='1' ".$extra." AND s_locale=?", array($curr_locale));
        } else {
        	$all_categories =& ProductCategory::listCategories(0, 
				"published='1' ".$extra." AND  for_roles LIKE ? AND s_locale=?", 
				array('%'.$user_role.'%', $curr_locale));
        }
	    }else{
	    $all_categories = array();
	    $parent_arr = array_unique(self::parent_id($type_a));
	    foreach($parent_arr as $v){
	    $_all_categories =& ProductCategory::listCategories($v, 
				"published='1' ".$extra." AND s_locale=?", array($curr_locale));
	    	$all_categories = self::merge($all_categories,$_all_categories);
	    }
	    
	    }
        $this->assign('categories', $all_categories);
    }
    
    //计算最顶级级别
    private function level($arr,$arr2){
    	$ret_arr=array();
    	$i=0;
    	foreach($arr as $k=>$val){
    		if(!in_array($k,$arr2)){
    			continue;
    		}
    		$count = substr_count($val,'--');
    		$ret_arr[$i++] = $count;
    	}
    	sort($ret_arr);
    	if(sizeof($ret_arr)>0)
    	return $ret_arr[0];
    	else
    	return 0;
    }
    //合并数组
    private function merge($o_arr,$n_arr){
    	$count  = sizeof($o_arr);
    	$t_arr=array();
    	foreach($n_arr as $v){
    		$t_arr[++$count] = $v;
    	}
    	return array_merge($o_arr,$t_arr);
    }
    //
    private function parent_id($str){
    	$ret = ProductCategory::parent_id($str);
    	return $ret;
    }
}
?>