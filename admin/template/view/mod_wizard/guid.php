<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
$uid = SessionHolder::get('user/id');
$o_user = new User($uid);
$wizard = $o_user->wizard;
?>
		<div id="top1_tit"><img src="<?php echo P_TPL_WEB; ?>/images/sitestar_logo.gif" width="503" height="22" alt="logo" /></div>
        
        <div id="center1">
        <div class="xx"></div><br>
        <h1>通过使用向导，我们将帮助您快速掌握操作方法，大大缩短建站时间。</h1>
        <h2>完成网站设置后，建议您了解一下SiteStar提供的更多高级功能，这些将帮助您有效提升网站的前台表现。</h2><br />
        <div class="xx"></div>
        <h1>您还可以通过以下方式来获得帮助：</h1>
        <a class="icon1">小提示：鼠标滑过会看到注释说明</a><a class="icon2" href="#" style="text-decoration:none;cursor:default;">SiteStar帮助中心     <span style="color:#000;"><a target="_blank" href="http://help.sitestar.cn">http://help.sitestar.cn</a></span></a><br /><br />
        <div class="xx"></div>
        <h1>向导关闭后，再次开启，请点击右上角<span class="orange" style="margin-left:10px;">向导</span></h1>
        </div>
        
        <div id="footer">
        	<div id="footer_left"><input type="hidden" name="uid" id="uid" value="<?php echo $uid;?>" />
        	<?php 
        	if($wizard) {
        		$wizard_msg = '<input name="checkbox" type="checkbox" onclick="clickCheckbox();" value="yes">&nbsp;下次不再显示此向导</div>';
        	} else {
        		$wizard_msg = '<input name="checkbox" type="checkbox" onclick="clickCheckbox();" checked="checked" value="no">&nbsp;下次不再显示此向导</div>';
        	}
        	echo $wizard_msg;
        	?>
            <div id="footer_right"><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 2)); ?>">开始使用向导</a></div>
        </div>       