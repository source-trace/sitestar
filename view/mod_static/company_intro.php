<div class="list_con company_intro">
<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

include_once(P_INC."/htmlsubstring.php");

// Company Intro
$temp = htmlSubString($curr_scontent->content, intval($cpy_intro_number));

$content = str_replace(FCK_UPLOAD_PATH,"",$temp);

if(strpos($content,"/admin/fckeditor/browser")){
	$path_ab = '/admin/fckeditor/browser/';
}else{
	$path_ab = FCK_UPLOAD_PATH_AB;
}
if(strpos($content,'fckeditor/upload')>0){
$path_ab=substr($path_ab,1);
$content = str_replace($path_ab,"",$content);
$pos = strpos($_SERVER['PHP_SELF'],'/index.php');
$path = substr($_SERVER['PHP_SELF'],0,$pos)=='/'?'':substr($_SERVER['PHP_SELF'],0,$pos);
}

$hasdiv = false;
if (preg_match('/<\/div>$/is', $content)) {
	$hasdiv = true;
	echo preg_replace("/<\/div>$/", '', $content);
} else {
	echo $content;
}
?>
<div class="list_more"><a href="<?php echo Html::uriquery2('mod_static', 'view', array('sc_id'=>$curr_scontent->id)); ?>"><img src="<?php echo P_TPL_WEB; ?>/images/more_37.jpg" width="32" height="9" border="0" /></a></div>
<?php if ($hasdiv) { ?></div><?php } ?></div><div class="list_bot"></div>