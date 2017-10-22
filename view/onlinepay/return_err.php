<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $page_title; ?><?php _e('Payment Failed!'); ?> -- <?php echo $_SITE->site_name; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php _e('charset'); ?>" />
        <meta name="keywords" content="<?php echo $_SITE->keywords; ?>" />
        <meta name="description" content="<?php echo $_SITE->description; ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo P_TPL_WEB; ?>/css/style.css" />
    </head>
    <body>
        <div id="bodywrapper">
            <div id="payresult">
                <h3><?php _e('Payment Failed!'); ?></h3>
                <div id="payerr_details">
                    <span><?php echo $error_msg; ?></span><br />
                    <a href="../../<?php echo Html::uriquery2('mod_order', 'useraccountstate'); ?>"><?php _e('Go to account page'); ?></a>
                </div>
            </div>
        </div>
    </body>
</html>
