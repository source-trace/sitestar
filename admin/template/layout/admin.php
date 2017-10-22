<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){ echo '企业网站';}else{ echo 'SiteStar';} ?>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="<?php echo P_TPL_WEB; ?>/css/admin_index.css" />
</head>

<body>
<div id="dashboard_main" class="overz">
<div class="dashboard_content overz" >
<div class="dashboard_top overz" >
<div class="dashboard_top_t">

<?php
	$ext = false;
	if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){
		if(!isset($i)){
			$i=0;
		}
		$dataXml = new DOMDocument('1.0','utf-8');
		$dataXml->load(ROOT.'/data/admin_block_config.xml');
		$xml = $dataXml->getElementsByTagName('node')->item($i);
		$logo_src = $xml->getElementsByTagName('logo_src')->item(0)->nodeValue;
		$logo_width = $xml->getElementsByTagName('logo_width')->item(0)->nodeValue;
		$logo_height = $xml->getElementsByTagName('logo_height')->item(0)->nodeValue;
		$footer = $xml->getElementsByTagName('footer')->item(0)->nodeValue;
		if(!file_exists(P_TPL_WEB.'/images/site_agent_logo.gif')) $ext = true;
	?><?php }else{
	 $ext = true;
	 $logo_src=P_TPL_WEB.'/images/site_logo.png'; 
	  }?></a>

<div class="dashboard_logo" style="background:url(<?php echo $logo_src;?>) no-repeat;">


<img src="template/images/spacer.gif" width="<?php echo $logo_width;?>" height="60" border="0" />

<?php if (!empty($eblock)) {?></div><?php }?>
</div><!--dashboard_logo end-->
<div class="dashboard_top_t_r">
<div class="dashboard_version">
<?php
if(SessionHolder::get('user/s_role')=='{admin}'){
?>
当前版本：
<?php
echo str_replace('sitestar_','',SYSVER);
}
?>
</div><!--dashboard_version end-->
<div class="dashboard_userinfo">
<div class="dashboard_userinfo_l"></div>
<div class="dashboard_userinfo_c"><span class="dashboard_userinfo_c_span">
<?php 
	$o_locale = new Parameter();
	$locale_items = $o_locale->findAll("`key` = 'DEFAULT_LOCALE'");
	$_user = SessionHolder::get('user/login');
	echo __('Hello,').$_user;
?>
</span><a href="<?php echo Html::uriquery('frontpage', 'dashboard');?>"><?php _e('Return');?></a>
<a href="<?php echo Html::uriquery('frontpage', 'dologout');?>"><?php _e('Logout');?></a></div>
<div class="dashboard_userinfo_r"></div>
</div><!--dashboard_userinfo end-->
</div><!--dashboard_top_t_r end-->
</div><!--dashboard_top_t end-->
<iframe id="adminiframe" src="index.php?<?php echo $url;?>" frameborder=0 width=880 onload="this.height=184;this.height=this.contentWindow.document.documentElement.scrollHeight" ></iframe>
</div>
</div>
</div>

<div class="dashboard_footer2" id="bottom"><?php if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){
	echo $footer;
}else{
	echo '建站之星（SiteStar）网站建设系统 版本 SiteStar V2.7 美橙互联<br />Copyrigt@2013 www.sitestar.cn All Right Reserved';
}	
?></div><!--dashboard_copyright end-->


</div><!--dashboard_main end-->

</body>
</html>