<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php _e('charset'); ?>" />
        <title><?php echo $page_title; ?> -- <?php if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){ echo '企业网站后台管理系统';}else{ echo $_SITE->site_name;} ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo P_TPL_WEB; ?>/css/login.css" />
        <script type="text/javascript" language="javascript" src="<?php echo P_SCP; ?>/jquery.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo P_SCP; ?>/helper.js"></script>
    </head>
    <body>
    	<?php include_once($_content_); ?>
    </body>
</html>
