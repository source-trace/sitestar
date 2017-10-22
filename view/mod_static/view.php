<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

?>

<div class="art_list" <?php  echo "style='position:relative;'";?>>
<!-- 编辑时动态触发 【start】-->
	<?php
		$content_url='';
		foreach($_GET as $k => $v)
		{
			if($k == '_l' || $k == '_v') continue;
			if($k == '_a') $v = 'admin_edit';
			$content_url .= "$k=$v&";
		}

		if(MOD_REWRITE=='3'){
			$content_url = '_m=mod_static&_a=admin_edit&sc_id='.$sc_id.'&';
		}
		$content_url .= "_isback=1";
		
		//弹出框判别公司简介还是联系我们
		$static_contents = $curr_scontent;
		$popup_title = isset($static_contents->title)?$static_contents->title:'';
        $nopermissionstr=__('No Permission');
                     $urllink="alert('".$nopermissionstr."');return false;";
                     if(ACL::isAdminActionHasPermission('mod_static', 'admin_edit')){
                             $urllink="popup_window('admin/index.php?".$content_url."','".$popup_title."&nbsp;&nbsp;".__('Edit Content')."',false,false,true);return false;";
                     }
	?>
	<div class="mod_toolbar" id="tb_mb_product1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
	</div>
	<!-- 编辑时动态触发 【end】-->

<?php
echo '<h3 class="blk_t">'.$page_cat.'</h3>';
?>


	
	<div id="sta_content">
	<?php
	
	$curr_scontent_content='';
	if(isset($curr_scontent->content)){
		$curr_scontent_content=$curr_scontent->content;
	}
	if(strpos($curr_scontent->content,'fckeditor/upload')>0){
	$pos = strpos($_SERVER['PHP_SELF'],'/index.php');
	$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
	$curr_scontent_content = str_replace(FCK_UPLOAD_PATH,"",$curr_scontent_content);
	$curr_scontent_content = str_replace($path,"",$curr_scontent_content);
	if(strpos($curr_scontent_content,"/admin/fckeditor/browser")){
		$path_ab = '/admin/fckeditor/browser/';
	}else{
		$path_ab = FCK_UPLOAD_PATH_AB;
	}
	$curr_scontent_content = str_replace($path_ab,"",$curr_scontent_content);
	}
	echo $curr_scontent_content;
	?>
	</div>
</div>
<?php include_once(ROOT.'/view/common/pagerbytext.php'); ?>