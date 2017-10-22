<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>

<script type="text/javascript" language="javascript">
function ContentSize(size)
{
	var obj=document.getElementById("artview_content");
	obj.style.fontSize=size+"px";
}

<?php 
if(SessionHolder::get('page/status', 'view') == 'edit') //内页文章支持内容编辑
{
	echo <<<JS
function article_edit()
{
	$("#tb_mb_article1").css({"display":"block"});
}

function article_cancel()
{
	$("#tb_mb_article1").css("display","none");
}
JS;
}
$nopermissionstr=__('No Permission');
$urllink="alert('".$nopermissionstr."');return false;";
 if(ACL::isAdminActionHasPermission('mod_article', 'admin_edit')){
    $urllink="popup_window('admin/index.php?_m=mod_article&_a=admin_edit&article_id=".$curr_article->id."','". __('Article')."&nbsp;&nbsp;". __('Edit Content')."');return false;";
}
?>

</script>

<div class="artview" <?php if(SessionHolder::get('page/status', 'view') == 'edit') echo "style='position:relative;' onmouseover='article_edit();' onmouseout='article_cancel();'";?>>
	
	<!-- 编辑时动态触发 【start】-->
	<div class="mod_toolbar" id="tb_mb_article1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
	</div>
	<!-- 编辑时动态触发 【end】-->
	
	<div class="artview_title"><?php echo Toolkit::substr_MB($curr_article->title, 0, 36);?></div>
	<div class="artview_info"><?php _e('Source'); ?>: <?php echo $curr_article->source; ?>&nbsp;&nbsp;&nbsp;<?php _e('Publish Time'); ?>: <?php echo date('Y-m-d H:i', $curr_article->create_time); ?>&nbsp;&nbsp;&nbsp;<?php echo $curr_article->v_num.' '.__('Views'); ?>&nbsp;&nbsp;&nbsp;<?php _e('Size');?>:&nbsp;&nbsp;<a href="javascript:ContentSize(16)">16px</a>&nbsp;&nbsp;<a href="javascript:ContentSize(14)">14px</a>&nbsp;&nbsp;<a href="javascript:ContentSize(12)">12px</a></div>
	<div class="artview_intr"><?php echo nl2br($curr_article->intro); ?></div>
	<div id="artview_content"><?php
	if(strpos($curr_article->content,'fckeditor/upload')>0){
	$pos = strpos($_SERVER['PHP_SELF'],'/index.php');
	$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
	$curr_article->content = str_replace($path,"",$curr_article->content);
	$curr_article->content = str_replace(FCK_UPLOAD_PATH,"",$curr_article->content);
	$curr_article->content = str_replace(FCK_UPLOAD_PATH_AB,"",$curr_article->content);
	}
	echo $curr_article->content; 
	//echo $nextAndPrevArr;
	?></div>
     <?php include_once(ROOT.'/view/common/pagerbytext.php'); ?>
	<div class="blankbar1"></div>
</div>
<table>
<tr><td align="left" height="50" width="800"><?php echo A_BSHARE!="A_BSHARE"?A_BSHARE:'';?></td></tr>

<tr><td align="left" height="50" width="800"><?php echo $nextAndPrevArr; ?></td></tr>
</table>