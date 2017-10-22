<?php if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<!–[if lte IE 6]>
<?php
Html::includeJs('/png.js');
?>
<![endif]–> 
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("loginform_stat");
    if (o_result.result == "ERROR") {
        document.forms["loginform"].reset();
        reload_captcha();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
        
        window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["loginform"].reset();
    reload_captcha();
    
    document.getElementById("loginform_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function reload_captcha() {
    var captcha = document.getElementById("login_captcha");
    if (captcha) {
        captcha.src = "../captcha.php?s=" + random_str(6);
    }
}

$(document).ready(function() {
    $("#login_user").focus();
});
//-->
</script>
<?php
if ((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){
	//if (file_exists('template/images/logo.png')) @unlink('template/images/logo.png');
	if (file_exists(ROOT.'/data/admin_block_config.xml')) {
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
	} else {
		$logo_src = 'template/images/agent_site_logo.png';
		$local="zh_CN";
		$logo_width = 299;
		$logo_height = 92;
		$bbs_title = __('Custom shortcuts');
		$bbs_url = 'http://';
		$bbs_description = __('Custom shortcuts brief introduction');
		$host_title = __('Custom shortcuts');
		$host_url = 'http://';
		$host_description = __('Custom shortcuts brief introduction');
		$footer = __('Enterprise intelligence destinati management system<br />Copyrigt@2013 yourdomain All Right Reserved');
		$xmlfooter = htmlspecialchars($footer);
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
?>
<div id="logo" style="background:url(<?php echo $logo_src;?>) no-repeat scroll 0 0 transparent;width:<?php echo $logo_width;?>px;height:<?php echo $logo_height;?>px;"></div>
<?php }else{ ?>
<div id="logo"></div>
<?php } ?>
<div id="in">
	<div id="left" <?php if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL) && ($logo_src != 'template/images/site_logo.png')){ ?>style="background:none;"<?php }?>></div>
	<div id="right">
<?php
$loginform = new Form('index.php', 'loginform', 'check_login_info');
$loginform->p_open('frontpage', 'dologin', '_ajax');
?>
	<ul>
        <li><span><?php _e('Username'); ?>: </span><?php echo Html::input('text', 'login_user', '', 'style="width:192px;"', $loginform, 'RequiredTextbox', __('Please input your username!')); ?></li>
        <li><span><?php _e('Password'); ?>: </span><?php echo Html::input('password', 'login_pwd', '', 'style="width:192px;"', $loginform, 'RequiredTextbox',  __('Please input your password!')); ?></li>
        <li><span><?php _e('Security'); ?>: </span><img onclick="reload_captcha()" style="position:relative;top:3px;margin-right:4px;" id="login_captcha" src="../captcha.php" class="captchaimg" border="0" /><?php echo Html::input('text', 'rand_rs', '', 'style="width:52px;"', $loginform, 'RequiredTextbox', __('Please give me an answer!')); ?></li>
        <li><?php echo Html::input('submit', 'bt_login', '','class="button"'); ?></li>
	</ul>
<?php
$loginform->close();
$running_msg = __('Checking user...');
$custom_js = <<<JS
$("#loginform_stat").css({"display":"block"});
$("#loginform_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$loginform->addCustValidationJs($custom_js);
$loginform->writeValidateJs();
?>
	</div>
</div>
<span id="loginform_stat" style="text-align:center;margin-top:28px;	color:#999999;font-family:Arial,Helvetica,sans-serif;display:block;font-size:12px;">欢迎使用<?php if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){ ?>企业网站<?php }else{ ?>SiteStar网站<?php }?>管理系统</span>
<div id="footer">
<?php if((!Toolkit::getAgent() && !IS_INSTALL) || (!ToolKit::getCorp() && IS_INSTALL)){ echo $footer; }else{ ?>Copyright © 2013 SiteStar.cn All Rights Reserved.<?php }?>
</div>