<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>        	
			<ul>
                <li><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 2)); ?>" style="color:#f7580a;">站点基本设置</a></li>
                <li class="icon"></li>
                <li><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 3)); ?>">添加产品</a></li>
                <li class="icon"></li>
                <li><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 4)); ?>">设置完成</a></li>
            </ul>
        </div>
        
        <div id="center">
        <h1>1、填写网站相关信息</h1>
        <h2>您可以在这里设置您的网站名称、logo、关键字、网站描述、公司介绍、联系我们等信息，请务必仔细填写和设置。<br /><a target="_blank" href="<?php echo Html::uriquery('mod_site', 'admin_list'); ?>">开始设置</a></h2>
        <div class="xx"></div>
        <h1>2、设置在线客服(可选)</h1>
        <h2>SiteStar为您提供多种常用的在线沟通工具，包括&nbsp;<span id="center_qq">&nbsp;</span>QQ、<span id="center_msn">&nbsp;</span>MSN、<span id="center_ww">&nbsp;</span>淘宝旺旺、<span id="center_53kf">&nbsp;</span>53客服等<br />方便顾客和您即时在线交流。<br /><a target="_blank" href="<?php echo Html::uriquery('mod_qq', 'admin_list'); ?>">开始设置</a></h2>
        <div class="xx"></div>

      </div>
        
        <div id="footer">
       		<div id="footer_right1"><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 3)); ?>">下一步</a></div>
        	<div id="footer_right1"><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index'); ?>">上一步</a></div>
        </div>