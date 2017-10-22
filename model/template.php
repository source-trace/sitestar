<?php


if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * Module block object
 *
 */
class Template extends RecordObject {
    public $belong_to = array('TemplateCategory');

    public static function allRemoteTemplates($cateid) {
    	/*
        $client =& Toolkit::initSoapClient();
        $templates = unserialize($client->getAvailableTpls(EZSITE_UID, $cateid, EZSITE_LEVEL));
       
    	$ezsite_uid = EZSITE_UID;
    	$level = EZSITE_LEVEL;
    	$templates = @file_get_contents(SCREENSHOT_URL."getAvailableTpls_new.php?ezsite_uid=$ezsite_uid&cateid=$cateid&level=$level");
    	if(!empty($templates)) $templates = unserialize($templates);
        return $templates;
       */
        $ezsite_uid = EZSITE_UID;
        $level = EZSITE_LEVEL;
        
//           $remoteurl=SCREENSHOT_URL."templates_cache.csv";       
//        $remoteurl="http://127.0.0.1:8086/templates_cache.csv";
        $remoteurl=SCREENSHOT_URL."getAvailableTpls_csv.php";
        $localcsvfile='../cache/templates_cache.csv';
        	if(file_exists( $localcsvfile))
          {
            @unlink( $localcsvfile);
          }
          
//          return @copy($remoteurl,  $localcsvfile);
          $fp1=@fopen($remoteurl,"r");
           if(!$fp1){
               return false;
           }
          $fp2=@fopen ($localcsvfile,"w");
          if(!$fp2){
             if($fp1)  fclose($fp1);
              return false;
          }
        while (!feof($fp1)) {
          $contents = fread($fp1, 8192);
          fwrite($fp2, $contents);
        }

            fclose($fp1);
            fclose($fp2);
            return TRUE;
    }
    
    public static function &allRemoteTemplatesCategories()
    {
    	/*
    	$client =& Toolkit::initSoapClient();
    	$templates = unserialize($client->getTplCategories());
    	*/
    	$templates = @file_get_contents(SCREENSHOT_URL.'getTplCategories_new.php');
    	if(!empty($templates)) $templates = unserialize($templates);//print_r($templates);die();
    	return $templates;
    }

    public static function &getMyTemplates($cateid) {
        $rearranged = array();
        $o_tpl = new Template();
        $templates =& $o_tpl->findAll("template_category_id=?", array($cateid), "ORDER BY id");
        if (sizeof($templates) > 0) {
            foreach ($templates as $template) {
                $curr_tpl['id'] = $template->id;
                $curr_tpl['name'] = $template->template;
                $curr_tpl['screenshot_url'] = Toolkit::fixpic('../template/'.$template->template.'/screenshot.jpg');
                $rearranged[] = $curr_tpl;
            }
        }

        return $rearranged;
    }
    
    public static function gbktoutf8($string){
        return  iconv('GBK', 'UTF-8//IGNORE', $string);
    }
    
	public static function ResetTplData(){
		$db =& MySqlConnection::get();
        $sql = "show tables";
        $rs =& $db->query($sql);
		$rows =& $rs->fetchRows();
		foreach($rows as $row){
			$_row_val = array_values($row);
			$val = $_row_val[0];
			if($val != Config::$tbl_prefix."users" &&$val != Config::$tbl_prefix."user_extends" && $val != Config::$tbl_prefix."parameters"&& $val != Config::$tbl_prefix."admin_menu_categories"&& $val != Config::$tbl_prefix."admin_menu_items"&& $val != Config::$tbl_prefix."languages" && $val != Config::$tbl_prefix."payment_accounts" 
							&& $val != Config::$tbl_prefix."payment_providers" && $val != Config::$tbl_prefix."roles" && $val != Config::$tbl_prefix."user_oauths"&& $val != Config::$tbl_prefix."user_fields"&& $val != Config::$tbl_prefix."third_accounts"&& $val != Config::$tbl_prefix."msg_fields"){
				$sql = "delete from ".$val;
				$rs =& $db->query($sql);
			}
		}
	}
}
?>
