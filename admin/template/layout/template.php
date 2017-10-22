<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include_once(P_INC.'/global_js.php'); ?>
<title></title>
</head>

<body style="background:#f5f9fb;">
<?php
if ($install_tag=='pass') {
	
?>
	<div style="height:57px;border-bottom:1px solid #dee6ef; margin-top:15px;margin-left:15px;text-align:center;">
	 	<p style="font-size:12px;text-align:left;font-family:宋体;"><span style="color:#015ec0;"><?php _e('If choose 1');?></span><span style="color:#6b6b6b;margin-left:12px;"><?php _e('The system will download templates and experience data, you will receive the same  case with official effect website.');?></span></p>
	 	<p style="margin-top:15px;font-size:12px;text-align:left;font-family:宋体;"><span style="color:#015ec0;"><?php _e('If choose 2');?></span><span style="color:#6b6b6b;margin-left:12px;"><?php _e('The system is installed, use only the website template data.');?></span></p>
	</div>
	
	<?php include_once($_content_); ?>
	<?php
}elseif ($install_tag=='refuse'){
	echo "<div style='font-size:12px;margin-left:30px;margin-top:30px;'>".__("Buy license please!")."<a href='http://www1.sitestar.cn/license/' target='_blank'>".__("Buy herf")."</a></div>";
}elseif ($install_tag=='error2'){
	echo "<div style='font-size:12px;margin-left:30px;margin-top:30px;'>".__("Install error2")."</div>";
}else{
	_e("Can not content internet,check it please!");
}
?>
</body>
</html>