<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php _e('charset'); ?>" />
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo P_TPL_WEB; ?>/css/style.css" />
<?php include_once(P_INC.'/global_js.php'); ?>
</head>
<body>
    <div id="clean_wrapper">
        <?php include_once($_content_); ?>
    </div>
</body>
</html>