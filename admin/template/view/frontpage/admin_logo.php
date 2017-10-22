<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div style="color:#FF0000;padding:2px 0 2px 5px;"><?php if(isset($err)){echo $err;}?></div>
<?php
$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
$sinfo_form->setEncType('multipart/form-data');
$sinfo_form->p_open('frontpage', 'admin_logo');
?>
<div style="overflow:auto;width:100%;">
<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
	<tfoot>
		<tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', '_p', $curr_loop);
            echo Html::input('hidden', 'act', $act);
            ?>
            </td>
        </tr>
	</tfoot>
	<tbody>
		<tr>
			<td class="label"><?php _e('Logo'); ?></td>
			<td class="entry">
			<img src="<?php echo $logo_src;?>" width="299" height="92" border="0" /><br />
            <?php
            echo Html::input('file', 'logo_src');
            echo Html::input('hidden', 'hid_logo_src', $logo_src);
            ?>
			<br />
			<?php _e('Supported file format'); ?>:<?php echo PIC_ALLOW_EXT;?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<br />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize'); echo '&nbsp;&nbsp;&nbsp;&nbsp;'.__('Recommended Size').'：299('.__('Width').') &times; 92('.__('Height').')';?>
            </td>
		</tr>
		<!--tr>
			<td class="label"><?php _e('Size'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'logo_width', $logo_width, 'class="textinput" style="width:40px;"');
            ?>
            &times;
            <?php
            echo Html::input('text', 'logo_height', $logo_height, 'class="textinput" style="width:40px;"');
            echo '&nbsp;&nbsp;&nbsp;&nbsp;'.__('Recommended Size').'：299('.__('Width').') &times; 92('.__('Height').')';
            ?>
            </td>
		</tr-->
	</tbody>
</table>
</div>
<?php
$sinfo_form->close();
$sinfo_form->writeValidateJs();
?>