<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SiteStar管理后台</title>
<link rel="stylesheet" type="text/css" href="<?php echo P_TPL_WEB; ?>/css/style1.css" />
<!-- 23/03/2010 Jane Add >> -->
<?php include_once(P_INC.'/global_js.php'); ?>
<script language="javascript">
<!--
	function siteGuid() 
    {
        show_iframe_win('index.php?<?php echo Html::xuriquery('mod_wizard', 'admin_index'); ?>', '', 732, 503);
	}
//-->
</script>
<!-- 23/03/2010 Jane Add << -->
</head>
<body>
 	<div id="header">
        <?php include_once($_content_);?>
    </div>
</body>
</html>
