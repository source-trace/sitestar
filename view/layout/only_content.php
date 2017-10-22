<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$only_content_page_title='';
if(isset($page_title)){
	$only_content_page_title=$page_title;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=<?php _e('charset'); ?>" />
        <title><?php echo $only_content_page_title; ?> -- <?php echo $_SITE->site_name; ?></title>
        <meta name="keywords" content="<?php echo $_SITE->keywords; ?>" />
        <meta name="description" content="<?php echo $_SITE->description; ?>" />
        <link rel="stylesheet" type="text/css" href="view/css/panel.css" />
        <?php include_once(P_INC.'/global_js.php'); ?>
    </head>
    <body>
        <div id="maindisp">
            <?php include_once($_content_); ?>
        </div>
    </body>
</html>
