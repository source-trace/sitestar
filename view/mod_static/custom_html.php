<style type="text/css">
.copyright .list_bot{display:none;}
</style>
<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$html = str_replace(FCK_UPLOAD_PATH,"",$html);
if(strpos($html,'www.sitestar.cn')){
echo '<div class="com_con_sq" id="com_con_sq">'.$html.'</div><div class="list_bot"></div>';
}else{
	if($html!=''){
echo '<div class="com_con">'.$html.'</div><div class="list_bot"></div>';}
}
?>