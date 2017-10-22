<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<table id="bulletinform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
    		echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Title'); ?></td>
            <td class="entry"><?php echo $curr_bulletin->title;?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Bulletin Content');?></td>
            <td class="entry"><?php echo $curr_bulletin->content;?></td>
        </tr>
    </tbody>
</table>