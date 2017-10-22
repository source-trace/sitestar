<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
			<ul>
                <li><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 2)); ?>" >站点基本设置</a></li>
                <li class="icon"></li>
                <li><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 3)); ?>" style="color:#f7580a;">添加产品</a></li>
                <li class="icon"></li>
                <li><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 4)); ?>">设置完成</a></li>
            </ul>
        </div>
        
        <div id="center">
        <h1>1、添加产品分类</h1>
        <h2>商品分类是对公司销售的产品进行分门别类，添加产品分类，可以让顾客快速方便地找到感兴趣的产品。<br /><a target="_blank" href="<?php echo Html::uriquery('mod_category_p', 'admin_list'); ?>">开始添加</a></h2>
        <div class="xx"></div>
        <h1>2、添加产品资料</h1>
        <h2>尽量把您的产品资料填写详细，客户只有对您的产品非常了解，才会增加他（她）对此产品的兴趣。<br /><a target="_blank" href="<?php echo Html::uriquery('mod_product', 'admin_add'); ?>">开始添加</a></h2>
        <div class="xx"></div>
      </div>
        
        <div id="footer">
       		<div id="footer_right1"><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 4)); ?>">下一步</a></div>
        	<div id="footer_right1"><a href="<?php echo Html::uriquery('mod_wizard', 'admin_index', array('_t' => 2)); ?>">上一步</a></div>
        </div> 