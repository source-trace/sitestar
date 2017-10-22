<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="status_bar">
<?php if (Notice::get('mod_marquee/msg')) { ?>
	<span id="adminflfrm_stat" class="status"><?php echo Notice::get('mod_marquee/msg'); ?></span>
<?php } ?>
</div>
<?php
$new_mblock_form_s1 = new Form('index.php', 'newmblockform');
$new_mblock_form_s1->setEncType('multipart/form-data');
$new_mblock_form_s1->p_open('mod_tool', 'img_create');
?>
<table id="mblockform_table" class="form_table" cellspacing="1">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php

            echo Html::input('submit', 'submit', __('Submit'));
			echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');

            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Upload Img'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('file', "img_name");
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$new_mblock_form_s1->close();
?>