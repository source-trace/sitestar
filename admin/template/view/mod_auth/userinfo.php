<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="loginblock">
    <h3 class="usr_blk_t"><?php _e('User Login'); ?></h3>
    <table cellspacing="1" class="front_form_table" width="100%">
        <tbody>
            <tr>
                <td><?php _e('Hello'); ?>, <?php echo $curr_user->login; ?></td>
            </tr>
            <tr>
                <td><?php _e('Last login time'); ?>: <?php echo date('Y-m-d H:i', SessionHolder::get('user/lastlog_time')); ?></td>
            </tr>
            <tr>
                <td><?php _e('Last login IP'); ?>: <?php echo SessionHolder::get('user/lastlog_ip'); ?></td>
            </tr>
            <tr>
                <td><a href="#" onclick="show_iframe_win('index.php?<?php echo Html::xuriquery('mod_user', 'edit_profile'); ?>', '<?php _e('Edit Profile'); ?>', 560, 520);return false;" title="<?php _e('Edit Profile'); ?>"><?php _e('Edit Profile'); ?></a></td>
            </tr>
            <!-- check module availability here # start -->
            <tr>
                <td><a href="<?php echo Html::uriquery('mod_order', 'userlistorder'); ?>" title="<?php _e('My Orders') ?>"><?php _e('My Orders') ?></a></td>
            </tr>
            <tr>
                <td><a href="<?php echo Html::uriquery('mod_order', 'useraccountstate'); ?>" title="<?php _e('My Account') ?>"><?php _e('My Account') ?></a></td>
            </tr>
            <!-- check module availability here # end -->
            <tr>
                <td><a href="<?php echo Html::uriquery('mod_auth', 'dologout'); ?>" title="<?php _e('Logout') ?>"><?php _e('Logout') ?></a></td>
            </tr>
        </tbody>
    </table>
</div>