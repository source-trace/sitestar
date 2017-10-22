<div class="recent">
<div class="recent_top"></div>
<div class="recent_con">
<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($articles) > 0) {
    foreach ($articles as $article) {
?>	

<div class="recent_list">
<a href="<?php echo Html::uriquery2('mod_article', 'article_content', array('article_id' => $article->id)); ?>" title="<?php echo $article->title; ?>"><?php echo Toolkit::substr_MB($article->intro, 0, 64).((Toolkit::strlen_MB($article->intro) > 64)?'...':''); ?></a>
<a href="<?php echo Html::uriquery2('mod_article', 'article_content', array('article_id' => $article->id)); ?>" title="<?php echo $article->title; ?>"><img src="<?php echo P_TPL_WEB; ?>/images/more_37.jpg" width="32" height="9" border="0" /></a></div>

			
			
<?php
}
}
?>
</div>
<div class="list_bot"></div>
</div>
<div class="blankbar"></div>