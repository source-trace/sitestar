<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>

<?php 
if(SessionHolder::get('page/status', 'view') == 'edit') //内页文章列表支持内容编辑
{
	echo <<<JS
<script type="text/javascript" language="javascript">
function article_list_edit()
{
	$("#tb_mb_article_list1").css({"display":"block"});
}
function article_list_cancel()
{
	$("#tb_mb_article_list1").css({"display":"none"});
}
</script>
JS;
}
$nopermissionstr=__('No Permission');
$urllink="alert('".$nopermissionstr."');return false;";
if(!empty($caa_id)) {
	$str_caa = "admin/index.php?_m=mod_category_a&_a=admin_edit&caa_id=$caa_id";
	$str_title = __("Article Categories");
          if(ACL::isAdminActionHasPermission('mod_category_a', 'admin_edit')){
                    $urllink="popup_window('$str_caa','$str_title &nbsp;&nbsp;". __('Edit Content')."');return false;";
            }
} else {
	$str_caa = "admin/index.php?_m=mod_article&_a=admin_list";
	$str_title = __("Article List");
            if(ACL::isAdminActionHasPermission('mod_article', 'admin_list')){
                    $urllink="popup_window('$str_caa','$str_title &nbsp;&nbsp;". __('Edit Content')."','',500,true);return false;";
            }
}
?>

<div class="art_list" <?php if(SessionHolder::get('page/status', 'view') == 'edit') echo "style='position:relative;' onmouseover='article_list_edit();' onmouseout='article_list_cancel();'";?>>
	<!-- 编辑时动态触发 【start】-->
	<div class="mod_toolbar" id="tb_mb_article_list1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<?php if(!empty($caa_id)) {?>
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
		<?php } else {?>
		<a onclick="<?php echo $urllink;?>" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
		<?php }?>
	</div>
	<!-- 编辑时动态触发 【end】-->
	<div class="art_list_title"><?php echo $mod_title; ?></div>
	<div class="art_list_search"><?php include_once(dirname(__FILE__).'/_search.php'); ?></div>
	<div class="art_list_con">
		<ul>
		<?php
        if (sizeof($articles) > 0) {
            $row_idx = 0;
            foreach ($articles as $article) {
        ?>
		<?php if (defined('SYSVER')) { ?>
		<li><p class="l_title"><a href="<?php if($article->article_category_id == 2) echo Html::uriquery2('mod_news', 'news_content', array('news_id' => $article->id)); else echo Html::uriquery2('mod_article', 'article_content', array('article_id' => $article->id)); ?>" title="<?php echo $article->title; ?>" target="_blank"><?php echo $article->title; ?></a></p><p class="n_time"><?php echo date('Y-m-d H:i', $article->create_time); ?></p></li>
		<?php } else { ?>
		<li><p class="l_title"><a href="<?php echo Html::uriquery2('mod_article', 'article_content', array('article_id' => $article->id)); ?>" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a></p><p class="n_time"><?php echo date('Y-m-d H:i', $article->create_time); ?></p></li>
		<?php } ?>
		
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
		<div class="norecords"><?php _e('No Records!'); ?></div>
		<?php } ?>
		</ul>
	</div>
<?php include_once(P_TPL_VIEW.'/view/common/pager.php'); ?>
</div>