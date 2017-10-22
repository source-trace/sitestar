<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="flash_image">

<?php
if ($img_open=='1') {
	$target = "_self";
}else{
	$target = "_blank";
}
if (isset($img_url) && !empty($img_url)) {
?><a href="<?php echo $img_url; ?>" target="<?php echo $target; ?>">
<img src="<?php echo $img_src; ?>" alt="<?php echo $img_desc; ?>" title="<?php echo $img_desc; ?>"<?php echo $str_img_width.$str_img_height; ?> /></a>
<?php
} else {
?>
<img src="<?php echo $img_src; ?>" alt="<?php echo $img_desc; ?>" title="<?php echo $img_desc; ?>"<?php echo $str_img_width.$str_img_height; ?> />
<?php }?>
</div>

<?php
if ($showtitle) {
	echo '<div class="list_bot"></div><div class="blankbar"></div>';
}
?>