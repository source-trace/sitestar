<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Language parameter object
 * 
 */
class Language extends RecordObject {
    protected $no_validate = array(
        'isEmpty' => array(
            array('name', 'Missing language name!'), 
            array('locale', 'Missing locale code!'),
        )
    );
    
    public static function cacheStrategy($param1,$param2)
    {
    	if((TABLE_CACHE == 1) && (!ACL::isRoleAdmin()) && empty($_GET['_v']))
    	{
    		$prefix = Config::$tbl_prefix;
    		$getObjects = array();
    		$objects = array();
    		
    		if(strcmp(trim($param1),"SELECT * FROM `{$prefix}languages`") == 0)
    		{
    			$getObjects = Memorycache::FetchMemory($param1,"{$prefix}languages","all",'Language');
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
}
?>