<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($news) > 0) {
?>
<div class="list_main">
	<div class="list_con">
	<ul class="titlelist">
<?php
    foreach ($news as $new) {
        $news_html = '<li><a href="'.Html::uriquery2('mod_news', 'news_content', array('news_id' => $new->id)).'"title="'.$new->title.'"> '
        .Toolkit::substr_MB($new->title, 0, 11).((Toolkit::strlen_MB($new->title) > 11)?'...':'').'</a>';
        $news_html .= '</li>'."\n";
        echo $news_html;
    }
?>
    <div align="right" style="padding-right:20px;"><a href="<?php echo Html::uriquery2('mod_news', 'fullist'); ?>"><img src="<?php echo P_TPL_WEB; ?>/images/more_37.jpg" width="32" height="9" border="0" /></a></div>
</ul>

	</div>
	<div class="list_bot"></div>
</div>
<div class="blankbar"></div>
<?php } ?>