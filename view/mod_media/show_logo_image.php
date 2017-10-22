<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
if($img_src!=''){
if(preg_match('/\.('.PIC_ALLOW_EXT.')$/i', $img_src)){

?>

<div class="flash_image">
<img src="<?php echo $img_src; ?>" alt="<?php echo $curr_siteinfo->site_name; ?>"<?php echo $str_img_width.$str_img_height; ?> />
</div>
<?php }else{ ?>
<div class="flash_image">
<object <?php echo $str_img_width;?> <?php echo $str_img_height;?> codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
<param value="<?php echo $img_src; ?>" name="movie">
<param value="high" name="quality">
<param value="transparent" name="wmode">
<embed <?php echo $str_img_width;?> <?php echo $str_img_height;?> wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" quality="high" src="<?php echo $img_src; ?>">
</object>
</div>
<?php } 
}?>