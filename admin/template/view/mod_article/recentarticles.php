<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($articles) > 0) {
?>
<ul class="titlelist">
<?php
    foreach ($articles as $article) {
        $article_html = '<li><a href="'.Html::uriquery('mod_article', 'article_content', array('article_id' => $article->id)).'"title="'.$article->title.'"> '
        .Toolkit::substr_MB($article->title, 0, 11).((Toolkit::strlen_MB($article->title) > 11)?'...':'').'</a>';
        $article_html .= '</li>'."\n";
        echo $article_html;
    }
?>
    <li class="more"><a href="<?php echo Html::uriquery('mod_article', 'fullist', array('caa_id' => $article_category)); ?>"><img src="<?php echo P_TPL_WEB; ?>/images/more.gif" border="0" /></a></li>
</ul>
<?php } ?>