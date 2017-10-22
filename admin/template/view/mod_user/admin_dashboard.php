<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$o_site_menu = new AdminMenuItem();
$site_items = $o_site_menu->findAll("category_id=6 and level <='".EZSITE_LEVEL."' ORDER BY priority");
?>
		<div class="fl">
            <h1><span class="icon2"><?php _e('Users');?></span></h1>
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
            	<h5><div class="in_t">&nbsp;</div><?php _e('You can manage the users here.');?></h5>
            		<div style="border-bottom:dotted 1px #CCC;"><div class="index_1" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:135px; line-height:40px; margin-left:30px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_user', 'admin_list'); ?>"><?php _e('Users');?></a></div><div style="overflow:hidden; line-height:40px;_width:400px; _overflow:hidden;"><?php _e('You can add, modify and delete the website administrator and general registration users.');?></div></div>
					<?php if(EZSITE_LEVEL=='2'){?>
                    <div style="border-bottom:dotted 1px #CCC;"><div class="index_2" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:135px; margin-left:30px;line-height:40px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_order', 'admin_list'); ?>"><?php _e('User Orders');?></a> </div><div style="overflow:hidden;line-height:40px;_width:400px; _overflow:hidden;"><?php _e('You can check the order information which ordered on the web front and amend the order state.');?></div></div>
					<?php }?>
                <br />

            </div>
        </div>