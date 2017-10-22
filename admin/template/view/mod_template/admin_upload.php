<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

?>
<div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php _e('Upload Template File'); ?></div></td>
				<td><a href="<?php echo Html::uriquery('mod_template', 'admin_list'); ?>" title=""><?php _e('Back'); ?></a></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="space"></div>
<div class="status_bar">
<?php if (Notice::get('mod_template/msg')) { ?>
	<span id="adminaddtplfrm_stat" class="status"><?php echo Notice::get('mod_template/msg'); ?></span>
<?php } ?>
</div>
<div class="space"></div>
<?php
$admin_addtpl_form = new Form('index.php', 'adminaddtplform', 'check_tpl_info');
$admin_addtpl_form->setEncType('multipart/form-data');
$admin_addtpl_form->p_open('mod_template', 'admin_create');
?>
<table id="adminaddtplform_table" class="form_table" cellspacing="0">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('submit', 'submit', __('Upload'));
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Template File'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('file', 'tpl_file', '', 
                '', $admin_addtpl_form, 'RequiredTextbox', 
                __('Please select a template file to upload!'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$admin_addtpl_form->close();
$admin_addtpl_form->writeValidateJs();
?>