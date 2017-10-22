<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($articles) > 0) {
    foreach ($articles as $article) {
?>
<div class="newsflash_content">
    <h3><?php echo Toolkit::substr_MB(strip_tags($article->title), 0, 12); ?>...</h3>
    <span class="date small block"><?php echo date('Y-m-d H:i', $article->create_time); ?></span>
    <p>
        <?php echo Toolkit::substr_MB($article->intro, 0, 56); ?>...
        <a href="<?php echo Html::uriquery('mod_article', 'article_content', array('article_id' => $article->id)); ?>" title="<?php echo $article->title; ?>">
            <img src="<?php echo P_TPL_WEB; ?>/images/more.gif" border="0" /></a>
    </p>
</div>
<?php
    }
}
?>
