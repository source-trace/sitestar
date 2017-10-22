<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<!--div class="contenttoolbar">
    <div class="art_list_title friend_link_title"><?php //echo $page_title; ?></div>
</div-->
<?php
$nopermissionstr=__('No Permission');
$urllink="alert('".$nopermissionstr."');return false;";
 if(ACL::isAdminActionHasPermission('mod_friendlink', 'admin_list')){
    $urllink="popup_window('admin/index.php?_m=mod_friendlink&_a=admin_list','".__('Links')."&nbsp;&nbsp;".__('Edit Content')."');return false;";
}
?>
<div class="art_list" <?php if(SessionHolder::get('page/status', 'view') == 'edit') echo "style='position:relative;'";?>>
	<!-- 编辑时动态触发 【start】-->
	<div class="mod_toolbar" id="tb_mb_download1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
	</div>
	<!-- 编辑时动态触发 【end】-->
<?php if(sizeof($fls) > 0) { ?>
<div class="art_list_con flinkbody">
<?php foreach ($fls as $fl) {?>
<?php if($fl->fl_type == '1'){?>
<a href=<?php echo $fl->fl_addr;?> target='_blank'><img src="upload/image/<?php echo $fl->fl_img ?>" border="0"></a>
<?php } elseif($fl->fl_type == '2'){
		echo "<a target='_blank' href='{$fl->fl_addr}'>{$fl->fl_name}</a>&nbsp;&nbsp;"; 
	  }
}?>
</div>
<?php } ?>
</div>