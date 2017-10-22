<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$o_site_menu = new AdminMenuItem();
$site_items = $o_site_menu->findAll("category_id=2 and level <='".EZSITE_LEVEL."' ORDER BY priority");
?>
		<div class="fl">
            <h1><span class="icon2"><?php _e('Pages');?></span></h1>
            <ul>
            	<?php 
            	foreach($site_items as $v) {
            		$str2 = Html::uriquery($v->module, $v->action);
            		$text = __($v->text);
            		echo "<li class='icon3'><a href='$str2'>$text</a></li>";
            	}
            	?>
            </ul>
        </div>
        <div class="fr1">
            <div style=" margin:10px; border:solid 1px #ccc;">
            	<h5><div class="in_t">&nbsp;</div><?php _e('It’s the management for the showing page of the web front page.');?></h5>
                    <div style="border-bottom:dotted 1px #CCC;"><div class="index_1" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:135px; line-height:40px; margin-left:30px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_menu_item', 'admin_list'); ?>"><?php _e('Site Columns');?></a></div><div style="overflow:hidden; line-height:40px;_width:400px; _overflow:hidden;"><?php _e('You can make the titles of webpage columns, like company introduction, company news, production introduction …etc, please contact us.');?></div></div>
                    <div style="border-bottom:dotted 1px #CCC;"><div class="index_2" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:135px; margin-left:30px;line-height:40px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_static', 'admin_list'); ?>"><?php _e('Static Contents');?> </a></div><div style="overflow:hidden;line-height:40px;_width:400px; _overflow:hidden;"><?php _e('You can define the personal page, as the single content page.');?></div></div>
                   	<div style="border-bottom:dotted 1px #CCC;"><div class="index_3" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:135px; margin-left:30px;line-height:40px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_navigation', 'admin_list'); ?>"><?php _e('Homepage Guidances');?></a></div><div style="overflow:hidden;line-height:40px;_width:400px; _overflow:hidden;"><?php _e('Homepage Guidance can enable the image and brand of the website, the loyalty of the regular member upgrade to a perfect level.');?></div></div>
                    <div style="border-bottom:dotted 1px #CCC;"><div class="index_4" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:135px; margin-left:30px;line-height:40px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_template', 'admin_list'); ?>"><?php _e('Templates');?></a></div><div style="overflow:hidden;line-height:40px;_width:400px; _overflow:hidden;"><?php _e('Template is the skin of web site, changing different templates could shows different page styles of your website, our system have a large number of beautiful templates, you could switch or edit them.');?></div></div>
<br />
            </div>
        </div>
