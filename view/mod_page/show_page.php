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

if(!empty($caa_id)) {
	$str_caa = "admin/index.php?_m=mod_category_a&_a=admin_edit&caa_id=$caa_id";
	$str_title = __("Article Categories");
} else {
	$str_caa = "admin/index.php?_m=mod_article&_a=admin_list";
	$str_title = __("Article List");
}
?>

<div class="art_list" <?php if(SessionHolder::get('page/status', 'view') == 'edit') echo "style='position:relative;' onmouseover='article_list_edit();' onmouseout='article_list_cancel();'";?>>
	<!-- 编辑时动态触发 【start】-->
	<div class="mod_toolbar" id="tb_mb_article_list1" style="display: none; height: 28px; position: absolute; right: 2px; background: none repeat scroll 0% 0% rgb(247, 182, 75); width: 70px;">
		<a onclick="popup_window('<?php echo $str_caa;?>','<?php echo $str_title;?>&nbsp;&nbsp;<?php echo _e('Edit Content');?>');return false;" title="<?php echo _e('Edit Content');?>" href="#"><img border="0" alt="<?php echo _e('Edit Content');?>" src="images/edit_content.gif">&nbsp;<?php echo _e('Edit Content');?></a>
	</div>
	<!-- 编辑时动态触发 【end】-->
	<div class="art_list_title"><?php echo '类别'; ?></div>
	<div class="art_list_con">
		<ul>
		<?php
        if (sizeof($articles) > 0) {
            $row_idx = 0;
            foreach ($articles as $article) {
        ?>
		<?php if (defined('SYSVER')) { ?>
		<li><p class="l_title"><a href="<?php if($article->article_category_id == 2) echo Html::uriquery2('mod_news', 'news_content', array('news_id' => $article->id)); else echo Html::uriquery2('mod_article', 'article_content', array('article_id' => $article->id)); ?>" title="<?php echo $article->title; ?>"><?php echo $article->title; ?></a></p><p class="n_time"><?php echo date('Y-m-d H:i', $article->create_time); ?></p></li>
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