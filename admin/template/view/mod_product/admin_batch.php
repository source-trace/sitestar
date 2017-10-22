<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="status_bar">
<?php if (Notice::get('mod_product/msg')) { ?>
	<span id="admindownfrm_stat" class="status"><?php echo Notice::get('mod_product/msg'); ?></span>
<?php } ?>
</div>
<div class="space"></div>
<?php
$batch_form = new Form('index.php', 'batchform', 'check_batch_prd_info');
$batch_form->setEncType('multipart/form-data');
$batch_form->p_open('mod_product', $next_action, '');
?>
<table id="batchform_table" class="form_table" width="100%" border="0" cellspacing="1" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
			echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Language'); ?></td>
            <td class="entry">
            <?php
            echo Toolkit::switchText(isset($curr_category_a->s_locale)?$curr_category_a->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'caa[s_locale]', 
           		isset($curr_category_a->s_locale)?$curr_category_a->s_locale:$mod_locale);
            ?>
            </td>
        </tr>
		<tr>
            <td class="label"><?php _e('File'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('file', 'batch_file', '', 
                '', $batch_form, 'RequiredTextbox', 
                __('Please select a xls file to upload!'));
            ?>
			<BR />
			<a href="template/view/mod_product/product.zip" target="_blank"><?php _e('File format'); ?></a><img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Download note');?>"/>
			<BR />
			<?php _e('Supported file format'); ?>:csv
			<BR />
			<?php _e('Upload size limit'); ?>:<?php echo ini_get('upload_max_filesize');?>
            </td>
        </tr>
        
    </tbody>
</table>
<?php
$batch_form->close();
$batch_form->writeValidateJs();

?>
