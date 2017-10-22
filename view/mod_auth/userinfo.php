<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>

<div class="login_main">
	<div class="login_top"></div>
	<div class="login_con">	
			<div class="login_info"><?php _e('Hello'); ?>, <?php echo empty($curr_user->nickname)?$curr_user->login:$curr_user->nickname; ?></div>
			<div class="login_info"><?php _e('Last login time'); ?>: <?php echo date('Y-m-d H:i', SessionHolder::get('user/lastlog_time')); ?></div>
			<div class="login_info"><a href="<?php echo Html::uriquery2('mod_user', 'edit_profile'); ?>" title="<?php _e('Edit Profile'); ?>"><?php _e('Edit Profile'); ?></a></div>
			<div class="login_info"><a href="<?php echo Html::uriquery('mod_auth', 'dologout'); ?>" title="<?php _e('Logout') ?>"><?php _e('Logout') ?></a></div>
			
			<?php if((EZSITE_LEVEL=='2') && (EXCHANGE_SWITCH=='1')){?>
			<div class="login_info"><a href="<?php echo Html::uriquery2('mod_order', 'userlistorder'); ?>" title="<?php _e('My Orders') ?>"><?php _e('My Orders') ?></a></div>
			<div class="login_info"><a href="<?php echo Html::uriquery('mod_order', 'useraccountstate'); ?>" title="<?php _e('My Account') ?>"><?php _e('My Account') ?></a></div>
			<!--div class="login_info"><a href="<?php echo Html::uriquery2('mod_order', 'userjifenlist'); ?>" >我的积分</a></div-->
			
			<?php }?>
						<div class="login_info"><a href="javascript:;" title="<?php _e('My notes') ?>" onclick="popup_window('index.php?_m=mod_email&_a=full_list','<?php _e('My notes');?>',false,false,true);return false;"><?php _e('My notes') ?></a>
			<?php if($read>0){?>
			<font color="#FF0000">&nbsp;&nbsp;&nbsp;
			<?php echo $read;?>&nbsp;&nbsp;&nbsp;<img src="images/message.gif" onclick="popup_window('index.php?_m=mod_email&_a=full_list','<?php _e('My notes');?>',false,false,true);return false;" /></font>
			<?php } ?>
			</div>
	</div>
	<!--div class="list_bot login_bot"></div-->
</div>
<div class="list_bot login_bot"></div>
<!--div class="blankbar"></div-->