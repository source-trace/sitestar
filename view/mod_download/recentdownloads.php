<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

if (sizeof($downloads) > 0) {
?>
<div class="list_main">
	<div class="list_con">
	<ul class="titlelist">
<?php
    foreach ($downloads as $download) {
		if(strstr($download->name,'http://')){
			$download_html = '<li><a href="'.$download->name.'"title="'.$download->name.'"> '
        .Toolkit::substr_MB($download->description, 0, 11).((Toolkit::strlen_MB($download->description) > 11)?'...':'').'</a>';
        $download_html .= '</li>'."\n";
		}else{
	
        $download_html = '<li><a href="'.Html::uriquery2('mod_download', 'download', array('dw_id' => $download->id)).'"title="'.$download->name.'"> '
        .Toolkit::substr_MB($download->description, 0, 11).((Toolkit::strlen_MB($download->description) > 11)?'...':'').'</a>';
        $download_html .= '</li>'."\n";
		}
        echo $download_html;
    }
?>
</ul>
<div class="list_more"><a href="<?php echo Html::uriquery2('mod_download', 'fullist'); ?>"><img src="<?php echo P_TPL_WEB; ?>/images/more_37.jpg" width="32" height="9" border="0" /></a></div>
</div>
	<div class="list_bot"></div>
</div>
<div class="blankbar"></div>
<?php } ?>
