<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($articles) > 0) {
?>
<div class="list_main">
	<div class="list_con">
	<ul class="titlelist">
	<?php
		foreach ($articles as $article) {
			$article_html = '<li><a href="'.Html::uriquery2('mod_article', 'article_content', array('article_id' => $article->id)).' "title="'.$article->title.'"> '
			.Toolkit::substr_MB($article->title, 0, 15).((Toolkit::strlen_MB($article->title) > 15)?'...':'').'</a>';
			$article_html .= '</li>'."\n";
			echo $article_html;
		}
	?>
	</ul>
    <div class="list_more"><a href="<?php
		echo Html::uriquery2('mod_article', 'fullist',array("caa_id"=>$article_category));
		?>"><img src="<?php echo P_TPL_WEB; ?>/images/more_37.jpg" width="32" height="9" border="0" /></a></div>
	 
	</div>
	<div class="list_bot"></div>
</div>
<div class="blankbar"></div><?php } ?>