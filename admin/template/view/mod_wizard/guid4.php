<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
            <ul>
                <li><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 2)); ?>" >站点基本设置</a></li>
                <li class="icon"></li>
                <li><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 3)); ?>" >添加产品</a></li>
                <li class="icon"></li>
                <li><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 4)); ?>" style="color:#f7580a;">设置完成</a></li>
            </ul>
        </div>
        
        <div id="center">
          <h1 style="padding:10px 0;">恭喜您，您已经完成了SiteStar网站建设系统的基本设置，您现在就可以浏览和访问您的网站了。</h1>
            <div class="xx"></div>
          <h1>为了增加您的企业形象提高同行业内竞争力，我们还提供了：</h1>
          <h2><span id="center_xd1">&nbsp;</span><a href="http://www1.sitestar.cn/license/" target='_blank'>授权服务：</a>去除版权信息的同时也得到网站的认知度、商业使用合法性以及官方的相关技术支持。</h2>
          <h2><span id="center_xd2">&nbsp;</span><a href="http://www1.sitestar.cn/templates/" target='_blank'>模板市场：</a>提供多套精美模板，让您的网站与众不同。</h2>
          <h2><span id="center_xd3">&nbsp;</span><a href="http://www1.sitestar.cn/Package/" target='_blank'>建站套餐：</a>提供包含空间和域名的建站系统，简单快捷。</h2>
          <h2><span id="center_xd4">&nbsp;</span><a href="http://www.cndns.com/" target='_blank'>域名注册：</a>提供多种域名注册服务，cnnic四星级域名注册商。</h2>
          <h2><span id="center_xd5">&nbsp;</span><a href="http://www.cndns.com/" target='_blank'>虚拟主机：</a>提供多款虚拟主机服务，2009年度IDC产业优秀虚拟主机奖。</h2>
          <h2><span id="center_xd6">&nbsp;</span><a href="http://www.cndns.com/" target='_blank'>企业邮箱：</a>提供以企业域名为后缀的电子邮件系统，提升公司企业形象，彰显实力。</h2>
          <div id="center_pic"><a href="http://www.cndns.com"><img src="<?php echo P_TPL_WEB; ?>/images/Cndns_logo.gif" width="98" height="30" alt="cndns" /></a><a href="http://www.sitestar.cn"><img src="<?php echo P_TPL_WEB; ?>/images/sitestar_logo1.gif" width="130" height="30" alt="SiteStar" /></a></div>
   	  </div>
        
        <div id="footer">
       		<div id="footer_right1"><a href="#" onclick="parent.tb_remove()">完成</a></div>
        	<div id="footer_right1"><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 3)); ?>">上一步</a></div>
        </div>  