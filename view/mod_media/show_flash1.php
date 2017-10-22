<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="flash_image">
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"<?php echo $str_flv_width.$str_flv_height; ?>>
    <param name="movie" value="<?php echo $flv_src; ?>" />
    <param name="quality" value="high" />
    <param name="wmode" value="transparent" />
    <embed src="<?php echo $flv_src; ?>"<?php echo $str_flv_width.$str_flv_height; ?> quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" wmode="transparent"></embed>
</object>
</div>
<?php
if ($showtitle) {
	echo '<div class="list_bot"></div><div class="blankbar"></div>';
}
?>