<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php _e('charset'); ?>" />

<?php
if(!isset($css_tag)){
	$css_tag='';
}
if (empty($css_tag) && $css_tag!='seo') {
	//下面的CSS与SEO页面中的冲突，如果是SEO页面，不加载下面的CSS
?>	
<title><?php echo $_SITE->site_name; ?>网站系统管理</title>
<link rel="stylesheet" type="text/css" href="<?php echo P_TPL_WEB; ?>/css/style.css?a=1" />
<?php
 }
?>
<?php include_once(P_INC.'/global_js.php');
// for fckeditor
RichTextbox::jsinclude();
?>
<!-- Overlay style -->
<style type="text/css">
<!--
    #loading_overlay {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 190;
        background: #000;
        filter:alpha(opacity=50);
        -moz-opacity:0.5;
        opacity: 0.5;
    }
    #loading_anim{
        position: fixed;
        height:13px;
        width:208px;
        z-index:191;
        top: 50%;
        left: 50%;
        margin: -6px 0 0 -104px; /* -height/2 0 0 -width/2 */
    }
    * html #loading_anim { /* ie6 hack */
    position: absolute;
    margin-top: expression(0 - parseInt(this.offsetHeight / 2) + (TBWindowMargin = document.documentElement && document.documentElement.scrollTop || document.body.scrollTop) + 'px');
    }
-->
</style>
<!-- // Overlay style -->
</head>

<body>
    <script type="text/javascript" language="javascript">
    <!--
    function show_overlay() {
        $("#loading_overlay").css("display", "block");
        $("#loading_anim").css("display", "block");
    }
    function hide_overlay() {
        $("#loading_overlay").css("display", "none");
        $("#loading_anim").css("display", "none");
    }
    //-->
    </script>
    <div id="wrap">
    	<?php if(ParamHolder::get('frame', 1)) {
        $o_locale = new Parameter();
    	$locale_items = $o_locale->findAll("`key` = 'DEFAULT_LOCALE'");
    	$_user = SessionHolder::get('user/login');
    	} ?>