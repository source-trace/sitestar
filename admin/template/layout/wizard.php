<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo P_TPL_WEB; ?>/css/wizard.css" rel="stylesheet" type="text/css" />
<?php include_once(P_INC.'/global_js.php'); ?>
<title></title>
</head>

<body>
	<div id="outer">
		<?php if($tag){ ?><div id="top"><?php } ?>
            <div id="top1">欢迎使用SiteStar网站建设系统使用向导! <!--span><a href="#" onclick="parent.tb_remove()"><img border="0" src="<?php echo P_TPL_WEB; ?>/images/close.gif" width="80" height="22" alt="close" /></a></span--></div>
	
	<?php include_once($_content_); ?>
	
</body>
</html>