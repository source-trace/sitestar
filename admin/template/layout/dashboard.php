<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$frontpagemappings=  Frontpage::frontpagemapping();
$fronturlrel=array();
$nopermissionstr=__('No Permission');
foreach($frontpagemappings as $key=>$modactrel){
        $mod=$modactrel[0];
        $action=$modactrel[1];
         if(ACL::isAdminActionHasPermission($mod, $action)){
         	if($key==7){
         		$fronturlrel[$key]=Html::uriquery('frontpage', 'admin',array("_c"=>$key,'sc_id'=>$scs[1]->id,"isback"=>2));
         	}else if($key==8){
         		$fronturlrel[$key]=Html::uriquery('frontpage', 'admin',array("_c"=>$key,'sc_id'=>$scs[0]->id,"isback"=>2));
         	}else{
         		$fronturlrel[$key]=Html::uriquery('frontpage', 'admin',array("_c"=>$key));
         	}
         }else{
                $fronturlrel[$key]='javascript:alert(\''.$nopermissionstr.'\');';   
         }
}

$eblock = ''; // for editable item(s)
// 授权或代理
if ((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)) {
	$eblock = '.mod_logo, .mod_block, .mod_nag';
	$local = SessionHolder::get('_LOCALE');
	if (file_exists(ROOT.'/data/admin_block_config.xml')) {
		$i = -1;$k = 0;
		$tmpXml = simplexml_load_file(ROOT.'/data/admin_block_config.xml');
		foreach ($tmpXml->node as $items) {
            $k++;
			if ($items->lang == $local) {
				$i += $k;
				break;
			}
		}
		
		$dataXml = new DOMDocument('1.0','utf-8');
		$dataXml->load(ROOT.'/data/admin_block_config.xml');
		// Read xml
		if ($i > -1) {
			$xml = $dataXml->getElementsByTagName('node')->item($i);
			$logo_src = $xml->getElementsByTagName('logo_src')->item(0)->nodeValue;
			$logo_width = $xml->getElementsByTagName('logo_width')->item(0)->nodeValue;
			$logo_height = $xml->getElementsByTagName('logo_height')->item(0)->nodeValue;
			$bbs_title = $xml->getElementsByTagName('bbs_title')->item(0)->nodeValue;
			$bbs_url = $xml->getElementsByTagName('bbs_url')->item(0)->nodeValue;
			$bbs_description = $xml->getElementsByTagName('bbs_description')->item(0)->nodeValue;
			$host_title = $xml->getElementsByTagName('host_title')->item(0)->nodeValue;
			$host_url = $xml->getElementsByTagName('host_url')->item(0)->nodeValue;
			$host_description = $xml->getElementsByTagName('host_description')->item(0)->nodeValue;
			$footer = $xml->getElementsByTagName('footer')->item(0)->nodeValue;
		} else {
			$xml = $dataXml->getElementsByTagName('node')->item(0);
			$logo_src = $xml->getElementsByTagName('logo_src')->item(0)->nodeValue;
			$bbs_title = __('Official BBS');
			$bbs_url = 'http://www1.sitestar.cn/bbs/';
			$bbs_description = __('Any trouble，please seek help from here');
			$host_title = __('Recommend Host');
			$host_url = 'http://www.cndns.com/';
			$host_description = __('Better Host,Better Site');
			$footer = '建站之星（SiteStar）网站建设系统 V2.7 美橙互联<br />Copyrigt@2013 www1.sitestar.cn All Right Reserved';
			// Write xml
			$xmlfooter = htmlspecialchars($footer);
			$newNode = <<<XML
<node>
 <lang>{$local}</lang>
 <logo_src>{$logo_src}</logo_src>
 <logo_width>299</logo_width>
 <logo_height>92</logo_height>
 <bbs_title>{$bbs_title}</bbs_title>
 <bbs_url>{$bbs_url}</bbs_url>
 <bbs_description>{$bbs_description}</bbs_description>
 <host_title>{$host_title}</host_title>
 <host_url>{$host_url}</host_url>
 <host_description>{$host_description}</host_description>
 <footer>{$xmlfooter}</footer>
</node>
</root>
XML;
			$oldxml = file_get_contents(ROOT.'/data/admin_block_config.xml');
			if ($oldxml) {
				$newxml = str_replace('</root>', $newNode, $oldxml);
				@file_put_contents(ROOT.'/data/admin_block_config.xml', $newxml);
			}
		}
	} else {
		$i = 0;
		if(!Toolkit::getAgent() && !IS_INSTALL){
		$logo_src = 'template/images/agent_site_logo.png';
		$bbs_title = __('Custom shortcuts');
		$bbs_url = 'http://';
		$bbs_description = __('Custom shortcuts brief introduction');
		$host_title = __('Custom shortcuts');
		$host_url = 'http://';
		$host_description = __('Custom shortcuts brief introduction');
		$footer = __('Enterprise intelligence destinati management system<br />Copyrigt@2013 yourdomain All Right Reserved');
		$xmlfooter = htmlspecialchars($footer);
		}else{
		$logo_src = 'template/images/site_logo.png';
		$bbs_title = __('Official BBS');
		$bbs_url = 'http://www1.sitestar.cn/bbs/';
		$bbs_description = __('Any trouble，please seek help from here');
		$host_title = __('Recommend Host');
		$host_url = 'http://www.cndns.com/';
		$host_description = __('Better Host,Better Site');
		$footer = '建站之星（SiteStar）网站建设系统 V2.7 美橙互联<br />Copyrigt@2013 www1.sitestar.cn All Right Reserved';
		$xmlfooter = htmlspecialchars($footer);
		}
		
		// create xml
		$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
<node>
<lang>{$local}</lang>
<logo_src>{$logo_src}</logo_src>
<logo_width>299</logo_width>
<logo_height>92</logo_height>
<bbs_title>{$bbs_title}</bbs_title>
<bbs_url>{$bbs_url}</bbs_url>
<bbs_description>{$bbs_description}</bbs_description>
<host_title>{$host_title}</host_title>
<host_url>{$host_url}</host_url>
<host_description>{$host_description}</host_description>
<footer>{$xmlfooter}</footer>
</node>
</root>
XML;
		$fp = fopen(ROOT.'/data/admin_block_config.xml', 'wb');
		@fwrite($fp, $xml);
		fclose($fp);
	}
} else {
	$logo_width = 299;
	$logo_height = 92;
	$logo_src = 'template/images/site_logo.png';
	$bbs_title = __('Official BBS');
	$bbs_url = 'http://www1.sitestar.cn/bbs/';
	$bbs_description = __('Any trouble，please seek help from here');
	$host_title = __('Recommend Host');
	$host_url = 'http://www.cndns.com/';
	$host_description = __('Better Host,Better Site');
	$footer = '建站之星（SiteStar）网站建设系统 V2.7 美橙互联<br />Copyrigt@2013 www1.sitestar.cn All Right Reserved';
}

// 套餐版
if (!empty($eblock) && !IS_INSTALL && Toolkit::getAgent()) {
	$logo_width = 299;
	$logo_height = 92;
	$logo_src = 'template/images/site_logo.png';
	$bbs_title = __('Official BBS');
	$bbs_url = 'http://www1.sitestar.cn/bbs/';
	$bbs_description = __('Any trouble，please seek help from here');
	$host_title = __('Recommend Host');
	$host_url = 'http://www.cndns.com/';
	$host_description = __('Better Host,Better Site');
	$footer = '建站之星（SiteStar）网站建设系统 V2.7美橙互联<br />Copyrigt@2013 www1.sitestar.cn All Right Reserved';
	// 取消内容编辑
	$eblock = '';
}

//非超级管理员不能内容编辑
if(!ACL::isRoleSuperAdmin()){
	$eblock = '';
/*
if(ACL::isAdminActionHasPermission('mod_static', 'contact')){
	$eblock .= '.mod_info1';
}
 if(ACL::isAdminActionHasPermission('mod_static', 'about')){
	$eblock .= ',.mod_info2';
}
*/
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if(!ToolKit::getCorp()){?>企业网站<?php }else{ ?>SiteStar<?php }?>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="<?php echo P_TPL_WEB; ?>/css/admin_index.css" />
<?php if (!empty($eblock)) { ?>
<link rel="stylesheet" type="text/css" href="../script/popup/theme/jquery.ui.core.css" />
<link rel="stylesheet" type="text/css" href="../script/popup/theme/jquery.ui.dialog.css" />
<link rel="stylesheet" type="text/css" href="../script/popup/theme/jquery.ui.theme.css" />
<link rel="stylesheet" type="text/css" href="../script/popup/theme/jquery.ui.resizable.css" />
<?php } ?>
<script type="text/javascript" language="javascript" src="../script/popup/jquery-1.4.3.min.js"></script>
<script type="text/javascript" language="javascript" src="../script/helper.js"></script>
<script language="javascript" type="text/javascript">
var edt = "<?php echo $eblock;?>";
$(function(){
	if (edt.length > 0) {		
		$(edt).hover(function(){
			if($(this).attr("class")!='mod_logo' && $(this).attr("class")!='mod_block'){
			$(this).css({'cursor':'pointer','border':'2px dashed #FF0000','width':'366px'});
			}else{
			$(this).css({'cursor':'pointer','border':'2px dashed #FF0000','width':'auto'});
			}
			$(this).find('.mod_toolbar2').css('display','block');
		},function(){
			if($(this).attr("class")!='mod_logo' && $(this).attr("class")!='mod_block'){
			$(this).css({'cursor':'normal','border':'none','width':'392px'});
			}else{
			$(this).css({'cursor':'normal','border':'none','width':'auto'});
			}
			$(this).find('.mod_toolbar2').css('display','none');
		});
	}
});

function getV() {
	$('#site_upgrade').empty().html("<?php _e('Saving request...');?>");
	_ajax_request('frontpage', 'get_version', null, ongetvok, ongetverr);
}

function ongetvok(response) {
    var o_result = _eval_json(response);

    if (!o_result) {
        return ongetverr(response);
    }
    
    if (o_result.result == "ERROR") {
        alert(o_result.errmsg);
        reloadPage();
    } else if (o_result.result == "OK") {
		if( o_result.curvn >= o_result.vn ) {
			alert("<?php _e('Currently is the latest version, do not upgrade!');?>");
			reloadPage();
		} else {
			$('#site_upgrade').empty().html("<a href=\"javascript:void(0);\" onclick=\"autoupgrade('" + o_result.tag + "', '" + o_result.vn + "')\"><font color=\"#FF0000\"><?php _e('Click here to update');?></font></a>");
		}
    } else {
        return ongetverr(response);
    }
}

function ongetverr(response) {
	alert("<?php _e('Remote request failed!');?>");
	reloadPage();
	return false;
}

function autoupgrade(tag, vn) {
	$("#site_upgrade").empty().html("<?php _e('Upgrading...'); ?>");
	_ajax_request('frontpage', 'auto_upgrade', {'status': tag,'orgvn': vn}, onsuccess, onfailed);
}

function onsuccess(response) {
    var o_result = _eval_json(response);

    if (!o_result) {
        return onfailed(response);
    }
    
    if (o_result.result == "ERROR") {
        $("#site_upgrade").empty().html(o_result.errmsg);
        return false;
    } else if (o_result.result == "OK") {
        $("#site_upgrade").empty().html("<?php _e('Upgrade success!'); ?>");
        reloadPage();
    } else {
        return onfailed(response);
    }
}

function onfailed(response) {
	var retry = "<a href=\"javascript:void(0);\" onclick=\"autoupgrade()\"><font color=\"#FF0000\"><?php _e('Retry');?></font></a>";
    $("#site_upgrade").empty().html("<?php _e('Request failed!'); ?>&nbsp;&nbsp;" + retry);
    return false;
}
</script>
</head>

<body>
<div id="dashboard_main" class="overz">
<div class="dashboard_content overz" >
<div class="dashboard_top overz" >
<div class="dashboard_top_t">

<div class="dashboard_logo" style="background:url(<?php echo $logo_src;?>) no-repeat;">
<?php if (!empty($eblock)) {?>
<div class="mod_logo"><div class="mod_toolbar2">
<a onClick="popup_window('index.php?_m=frontpage&amp;_a=admin_logo&amp;_p=<?php echo $i;?>','<?php _e("Edit Logo");?>','','',true,'','','','',true);return false;" title="<?php _e('Edit Logo');?>" href="#"><?php _e('Edit Logo');?></a></div>
<?php }?>

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
</span><a href="../" target="_blank"><?php _e('Preview Home');?></a><?php
if(SessionHolder::get('user/s_role')=='{admin}'){
?>
<span id="site_upgrade"><a href="javascript:void(0);" onClick="getV()"><?php _e('Upgrade');?></a></span>
&nbsp;
<?php }?>
<a href="<?php echo Html::uriquery('frontpage', 'dologout');?>"><?php _e('Logout');?></a></div>
<div class="dashboard_userinfo_r"></div>
</div><!--dashboard_userinfo end-->
</div><!--dashboard_top_t_r end-->
</div><!--dashboard_top_t end-->
<div class="dashboard_notice">
<?php 
    $db = MysqlConnection::get();
    $prefix = Config::$tbl_prefix;
    
    $user_sql = <<<SQL
SELECT COUNT(*) AS count_user FROM {$prefix}users
SQL;
	$result_user = $db->query($user_sql);
	$rows_user = $result_user->fetchRows(); 
	
	$article_sql = <<<SQL
SELECT COUNT(*) AS count_article FROM {$prefix}articles	
SQL;
	$result_article = $db->query($article_sql);
	$rows_article = $result_article->fetchRows();
	
	$product_sql = <<<SQL
SELECT COUNT(*) AS count_product FROM {$prefix}products	
SQL;
	$result_product = $db->query($product_sql);
	$rows_product = $result_product->fetchRows();
	
	$order_sql = <<<SQL
SELECT COUNT(*) AS count_order FROM {$prefix}online_orders
SQL;
	$result_order = $db->query($order_sql);
	$rows_order = $result_order->fetchRows();
	
	$message_sql = <<<SQL
SELECT COUNT(*) AS count_message FROM {$prefix}messages
SQL;
	$result_message = $db->query($message_sql);
	$rows_message = $result_message->fetchRows();
    ?>
<marquee direction='left' onMouseOver="this.stop();" onMouseOut="this.start();" scrollamount=3 style='width:760px;margin-left:60px;*margin-left:60px;_margin-left:60px;'>
<span class="dashboard_notice_title"><?php _e('Information Statistics');?></span><span><a href="<?php echo $fronturlrel[3];?>"><?php _e('Users Number');?>:
<?php
if(SessionHolder::get('user/s_role')=='{admin}'){
			echo $rows_user[0]['count_user'];
			}else{
			echo $rows_user[0]['count_user']-1;
			}
?>
</a></span><span><a href="<?php echo $fronturlrel[1];?>" ><?php _e('Articles Number');?>:<?php echo $rows_article[0]['count_article'];?></a></span>
<span><a href="<?php echo $fronturlrel[2];?>"><?php _e('Products Number');?>:<?php echo $rows_product[0]['count_product'];?></a></span>
<span><?php _e('Orders Number');?>:<?php echo $rows_order[0]['count_order'];?></span>
<span><a href="<?php echo $fronturlrel[4];?>"><?php _e('Message Number');?>:<?php echo $rows_message[0]['count_message'];?></a></span>
</marquee>
</div><!--dashboard_notice end-->
</div><!--dashboard_top end-->
<div class="dashboard_manage overz">
<div class="dashboard_manage_element overz">
<?php
if(ACL::isAdminActionHasPermission('mod_all_articles', 'articles')){
?>
<div class="dashboard_manage_button">
<div class="dashboard_manage_c dashboard_manage_icon_01">
<h2><a href="<?php echo $fronturlrel[1];?>"><?php _e("Articles");?></a></h2>
<p><?php _e("View,Edit,Add,Delete article and category");?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
<?php }?>
<?php
if(ACL::isAdminActionHasPermission('mod_all_message', 'message')){
?>
<div class="dashboard_manage_button">
<div class="dashboard_manage_c dashboard_manage_icon_02">
<h2><a href="<?php echo $fronturlrel[4];?>"><?php _e("Messages");?></a></h2>
<p><?php _e("Manage your custom message");?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
<?php }?>
<?php
if(ACL::isAdminActionHasPermission('mod_all_products', 'products')){
?>
<div class="dashboard_manage_button">
<div class="dashboard_manage_c dashboard_manage_icon_03">
<h2><a href="<?php echo $fronturlrel[2];?>"><?php _e("Products");?></a></h2>
<p><?php _e("View,Edit,Add,Delete product and category");?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
<?php }?>
<?php
if(ACL::isAdminActionHasPermission('mod_all_bulletins', 'bulletins')){
?>
<div class="dashboard_manage_button">
<div class="dashboard_manage_c dashboard_manage_icon_04">
<h2><a href="<?php echo $fronturlrel[5];?>"><?php _e("Bulletins");?></a></h2>
<p><?php _e("Publishing the new notice in timely");?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
<?php }?>
<?php
if(ACL::isAdminActionHasPermission('mod_all_web', 'web')){
?>
<div class="dashboard_manage_button">
<div class="dashboard_manage_c dashboard_manage_icon_05">
<h2><a href="../"><?php _e("Web Edit");?></a></h2>
<p><?php _e("Easy manage web content,layout");?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
<?php }?>
<div class="dashboard_manage_button"><div class="mod_nag">
<?php if (!empty($eblock)) {?><div class="mod_toolbar2"><a onClick="popup_window('index.php?_m=frontpage&amp;_a=admin_cell&amp;_t=bbs&amp;_p=<?php echo $i;?>','<?php _e("Edit Content");?>','','',true,'','','','',true);return false;" title="<?php _e('Edit Content');?>" href="#"><?php _e('Edit Content');?></a></div><?php }?>
<div class="dashboard_manage_c dashboard_manage_icon_06 mod_toolbar">
<h2><a href="<?php if($bbs_url){echo $bbs_url;}else{echo '#';} ?>" target="_blank"><?php echo $bbs_title;?></a></h2>
<p><?php echo $bbs_description;?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
</div>
<?php
if(ACL::isAdminActionHasPermission('mod_all_member', 'member')){
?>
<div class="dashboard_manage_button">
<div class="dashboard_manage_c dashboard_manage_icon_07">
<h2><a href="<?php echo $fronturlrel[3];?>"><?php _e("Member Manage");?></a></h2>
<p><?php _e("Manage your web member");?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
<?php }?>
<div class="dashboard_manage_button"><div class="mod_nag">
<?php if (!empty($eblock)) {?><div class="mod_toolbar2"><a onClick="popup_window('index.php?_m=frontpage&amp;_a=admin_cell&amp;_t=host&amp;_p=<?php echo $i;?>','<?php _e("Edit Content");?>','','',true,'','','','',true);return false;" title="<?php _e('Edit Content');?>" href="#"><?php _e('Edit Content');?></a></div><?php }?>
<div class="dashboard_manage_c dashboard_manage_icon_08 mod_toolbar">
<h2><a href="<?php if($host_url){echo $host_url;}else{echo '#';} ?>" target="_blank"><?php echo $host_title;?></a></h2>
<p><?php echo $host_description;?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
</div>
<?php

if(ACL::isAdminActionHasPermission('mod_all_contact', 'contact')){
?>
<div class="dashboard_manage_button">
<div class="dashboard_manage_c dashboard_manage_icon_09">
<h2><a href="<?php echo $fronturlrel[7];?>"><?php _e('Company Profile');?></a></h2>
<p><?php _e('Edit Company Profile');?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
<?php }?>
<?php
if(ACL::isAdminActionHasPermission('mod_all_about', 'about')){
?>
<div class="dashboard_manage_button">

<div class="dashboard_manage_c dashboard_manage_icon_10">
<h2><a href="<?php echo $fronturlrel[8];?>"><?php _e('Contact Us');?></a></h2>
<p><?php _e('Edit Contact Us');?></p>
</div><!--dashboard_manage_c end-->
</div><!--dashboard_manage_button end-->
<?php }?>
</div><!--dashboard_manage_element end-->
</div><!--dashboard_manage end-->

<?php if(ToolKit::getCorp()){ ?>
<div class="dashboard_authorize">
<div class="dashboard_authorize_font">
<h2>未获得商业授权</h2>
<p>您现在使用的是未授权版本，仅可用于学习和产品了解之用，不得用于商业目的，取得商业授权后系统自动去除网站下方版权信息，<a href="http://www1.sitestar.cn/license/"><font color="#FF3300">立即申请授权</font></a>。如果您已经购买了授权服务，<a href="index.php?_m=mod_site&_a=admin_dat"><font color="#FF3300">请点击此处</font></a>即刻开通。</p>
</div><!--dashboard_authorize_font end-->
<div class="dashboard_authorize_oneyear">
<div class="dashboard_authorize_buy">
<p>商业授权<span>200</span>元/年</p>
<p>服务期限为一年，需逐年续费</p>
<a href="http://www1.sitestar.cn/license/" target="_blank">立即购买商业授权</a>
</div><!--dashboard_authorize_buy end-->
</div>
<div class="dashboard_authorize_all">
<div class="dashboard_authorize_buy">
<p>商业授权<span>1000</span>元</p>
<p>终身授权服务期限为终身</p>
<a href="http://www1.sitestar.cn/license/" target="_blank">立即购买商业授权</font></a>
</div><!--dashboard_authorize_buy end-->
</div>
</div><!--dashboard_authorize end-->
<?php }?>

</div><!--dashboard_content end-->

<?php if (!empty($eblock)) {?>
<div class="mod_block"><div class="mod_toolbar2"><a onClick="popup_window('index.php?_m=frontpage&amp;_a=admin_foot&amp;_p=<?php echo $i;?>','<?php _e("Edit Content");?>','','',true,'','','','',true);return false;" title="<?php _e('Edit Content');?>" href="#"><?php _e('Edit Content');?></a></div><?php }?>
<div class="dashboard_footer" id="bottom"><?php echo $footer;?></div><!--dashboard_copyright end-->


</div><!--dashboard_main end-->
<?php if (!empty($eblock)) {?>
<script type="text/javascript" language="javascript" src="../script/popup/jquery.ui.custom.min.js"></script><?php }?>
</body>
</html>