<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$o_site_menu = new AdminMenuItem();
$site_items = $o_site_menu->findAll("category_id=3 ORDER BY priority");
?>
		<div class="fl">
            <h1><span class="icon2"><?php _e('Contents');?></span></h1>
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
            	<h5><div class="in_t">&nbsp;</div><?php _e('The management of the Web Front’s content.');?></h5>
            		<div style="border-bottom:dotted 1px #CCC;"><div class="index_1" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:120px; line-height:40px; margin-left:30px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_article', 'admin_list'); ?>"><?php _e('Articles');?></a></div><div style="overflow:hidden; line-height:40px;_width:400px; _overflow:hidden;"><?php _e('It contains viewing, adding, editing, deleting catalogues and contents of articles.');?></div></div>
            		<div style="border-bottom:dotted 1px #CCC;"><div class="index_2" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:120px; line-height:40px; margin-left:30px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_product', 'admin_list'); ?>"><?php _e('Products');?></a></div><div style="overflow:hidden; line-height:40px;_width:400px; _overflow:hidden;"><?php _e('It contains viewing, adding, editing, deleting categories and introduction of products.');?></div></div>
            		<div style="border-bottom:dotted 1px #CCC;"><div class="index_3" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:120px; line-height:40px; margin-left:30px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_download', 'admin_list'); ?>"><?php _e('Downloads');?></a></div><div style="overflow:hidden; line-height:40px;_width:400px; _overflow:hidden;"><?php _e('It contains viewing, adding, editing, deleting download contents.');?></div></div>
            		<div style="border-bottom:dotted 1px #CCC;"><div class="index_4" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:120px; line-height:40px; margin-left:30px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_message', 'admin_list'); ?>"><?php _e('Messages');?></a></div><div style="overflow:hidden; line-height:40px;_width:400px; _overflow:hidden;"><?php _e('You can check the message here.');?></div></div>
            		<div style="border-bottom:dotted 1px #CCC;"><div class="index_5" style="margin-left:30px;">&nbsp;</div><div style="float:left; width:120px; line-height:40px; margin-left:30px;color:#67B4E2;"><a href="<?php echo Html::uriquery('mod_bulletin', 'admin_list'); ?>"><?php _e('Bulletins');?></a></div><div style="overflow:hidden; line-height:40px;_width:400px; _overflow:hidden;"><?php _e('It contains viewing, adding, editing, deleting bulletins.');?></div></div>
               <br />
                
                
            </div>
        </div>
