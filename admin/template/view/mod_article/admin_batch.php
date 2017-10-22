<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="status_bar">
<?php if (Notice::get('mod_article/msg')) { ?>
	<span id="admindownfrm_stat" class="status"><?php echo Notice::get('mod_article/msg'); ?></span>
<?php } ?>
</div>
<div class="space"></div>
<?php
$batch_form = new Form('index.php', 'batchform', 'check_batch_info');
$batch_form->setEncType('multipart/form-data');
$batch_form->p_open('mod_article', $next_action, '');
?>
<table id="batchform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
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
            echo Toolkit::switchText($curr_category_a->s_locale?$curr_category_a->s_locale:$mod_locale, 
                Toolkit::toSelectArray($langs, 'locale', 'name'));
            echo Html::input('hidden', 'caa[s_locale]', 
           		$curr_category_a->s_locale?$curr_category_a->s_locale:$mod_locale);
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
			<a href="template/view/mod_article/article.zip" target="_blank"><?php _e('File format'); ?></a>	<img id="answer" class="title" src="<?php echo P_TPL_WEB; ?>/images/answer1.gif" alt="help" title="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;需按照指定格式制作excel表格文件，请下载模板文件，模板文件格式不可新增列，仅需在新增记录即可！每列的内容需按照批注提示格式输入。"/>
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
