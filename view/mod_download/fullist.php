<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<?php
$nopermissionstr=__('No Permission');
$urllink="alert('".$nopermissionstr."');return false;";
 if(ACL::isAdminActionHasPermission('mod_download', 'admin_list')){
    $urllink="popup_window('admin/index.php?_m=mod_download&_a=admin_list','".__('Downloads')."&nbsp;&nbsp;".__('Edit Content')."');return false;";
}
?>

<div class="art_list" <?php if(SessionHolder::get('page/status', 'view') == 'edit') echo "style='position:relative;' onmouseover='message_edit();' onmouseout='message_cancel();'";?>>
	<!-- 编辑时动态触发 【start】-->
	<div class="mod_toolbar" id="tb_mb_download1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
	</div>
	<!-- 编辑时动态触发 【end】-->
	<div class="art_list_title"><?php echo $category->name; ?></div>
	<div class="art_list_search"><?php include_once(dirname(__FILE__).'/_search.php'); ?></div>
	<div class="art_list_con">
		<ul>
        <?php
        if (sizeof($downloads) > 0) {
            $row_idx = 0;
            foreach ($downloads as $download) {
			
        ?>
		<li><p class="l_title"><a href="<?php 
		if(strstr($download->name,'http://')){
			echo $download->name;
		}else{
			echo Html::uriquery2('mod_download', 'download', array('dw_id' => $download->id)); 
		}
		?>" title="<?php echo $download->description; ?>"><?php 
		echo Toolkit::substr_MB($download->description, 0, 20).((Toolkit::strlen_MB($download->description) > 20)?'...':''); ?></a></p>
			<p class="n_time"><?php echo date('Y-m-d H:i', $download->create_time); ?></p></li>
            
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
           <?php _e('No Records!'); ?>
        <?php } ?>
	</ul>
</div>
</div>
<div class="contentpager">
    <?php include_once(P_TPL_VIEW.'/view/common/pager.php'); ?>
</div>