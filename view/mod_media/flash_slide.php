<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$flv_src = ($slide_target == '1') ? 'images/slide.swf' : 'images/slide_blank.swf';
?>
<div class="flash_image flash_slide">
<!--script type="text/javascript" language="javascript">
<
var focus_width = parseInt("<?php echo $img_width;?>");
var focus_height = parseInt("<?php echo $img_height;?>");
var text_height = 18;
var swf_height = focus_height+text_height;

var pics = "<?php echo $imgSrc;?>";
var links = "<?php echo $imgUri;?>";
var texts = "<?php echo $imgText;?>";
var fsrc = "<?php echo $flv_src;?>";

document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="'+ focus_width +'" height="'+ swf_height +'">');
document.write('<param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="'+fsrc+'" /><param name="quality" value="high" /><param name="bgcolor" value="#F0F0F0" />');
document.write('<param name="menu" value="false" /><param name="wmode" value="Opaque" />');
document.write('<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'" />');
document.write('<embed width="'+ focus_width +'" height="'+ swf_height +'" flashvars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'" wmode="Opaque" menu="false" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" bgcolor="#F0F0F0" quality="high" src="'+fsrc+'"></embed>');
document.write('</object>');
//>
</script-->
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="<?php echo $img_width;?>" height="<?php echo $img_height+18;?>">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="<?php echo $flv_src;?>" />
<param name="quality" value="high" />
<param name="menu" value="false" />
<param name="wmode" value="Opaque" />
<param name="FlashVars" value="pics=<?php echo $imgSrc;?>&links=<?php echo $imgUri;?>&texts=<?php echo $imgText;?>&borderwidth=<?php echo $img_width;?>&borderheight=<?php echo $img_height;?>&textheight=18" />
<embed width="<?php echo $img_width;?>" height="<?php echo $img_height+18;?>" flashvars="pics=<?php echo $imgSrc;?>&links=<?php echo $imgUri;?>&texts=<?php echo $imgText;?>&borderwidth=<?php echo $img_width;?>&borderheight=<?php echo $img_height;?>&textheight=18" wmode="Opaque" menu="false" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" bgcolor="#F0F0F0" quality="high" src="<?php echo $flv_src;?>"></embed>
</object>
</div>

<?php
if ($showtitle) {
	echo '<div class="list_bot"></div><div class="blankbar"></div>';
}
?>