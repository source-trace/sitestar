<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>

<script type="text/javascript" language="javascript">
<!--
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
?>

-->
</script>
<?php
$nopermissionstr=__('No Permission');
$urllink="alert('".$nopermissionstr."');return false;";
 if(ACL::isAdminActionHasPermission('mod_bulletin', 'admin_edit')){
    $urllink="popup_window('admin/index.php?_m=mod_bulletin&_a=admin_edit&bulletin_id=".$curr_bulletin->id."','".$curr_bulletin->title."&nbsp;&nbsp;".__('Edit Content')."');return false;";
}
?>
<div class="artview" <?php if(SessionHolder::get('page/status', 'view') == 'edit') echo "style='position:relative;' onmouseover='article_edit();' onmouseout='article_cancel();'";?>>
    <!-- 编辑时动态触发 【start】-->
	<div class="mod_toolbar" id="tb_mb_article1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
	</div>
	<!-- 编辑时动态触发 【end】-->
	<div class="artview_title"><?php echo Toolkit::substr_MB($curr_bulletin->title, 0, 36);?></div>
	<div id="artview_content"><?php 
	$pos = strpos($_SERVER['PHP_SELF'],'/index.php');
	$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
	$curr_bulletin->content = str_replace($path,"",$curr_bulletin->content);
	$curr_bulletin->content = str_replace(FCK_UPLOAD_PATH,"",$curr_bulletin->content);
	$curr_bulletin->content = str_replace(FCK_UPLOAD_PATH_AB,"",$curr_bulletin->content);
	echo $curr_bulletin->content; ?></div>
	<div class="blankbar1"></div>
</div>