<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Module block object
 * 
 */
class TemplateCategory extends RecordObject {
    public $has_many = array('Template');
    
    public static function &allRemoteTplCategories() {
        $select_categories = array('999' => __('My Templates'));
        
//        $client =& Toolkit::initSoapClient();
//        $all_tpl_cates = unserialize($client->getTplCategories());
		
        $tmp = file_get_contents(SCREENSHOT_URL.'getTplCategories.php');
        $all_tpl_cates = unserialize($tmp);
        
        if (sizeof($all_tpl_cates) > 0) {
            foreach ($all_tpl_cates as $cate) {
                $select_categories[$cate['id']] = $cate['name'];
            }
        }
        
        return $select_categories;
    }
}
?>
