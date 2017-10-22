<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>

<?php 
if(SessionHolder::get('page/status', 'view') == 'edit')
{
	echo <<<JS
<script type="text/javascript" language="javascript">
function product_edit()
{
	tb_mb_product1.css('display','block');
}
function product_cancel()
{
	tb_mb_product1.css('display','none');
}
</script>
JS;
}
?>
<?php
$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
$sinfo_form->setEncType('multipart/form-data');
$sinfo_form->p_open('mod_media', 'save_company_introduction');
?>

<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="line-height:24px;">
	<tfoot>
		<tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'si[id]', $curr_co->id);
            echo Html::input('hidden', 'si[s_locale]', $lang_sw);
            ?>
            </td>
        </tr>
	</tfoot>
	<tbody>
			
		<tr>
            <td class="label"><?php _e('Company Introduction'); ?></td>
            <td class="entry">
			<?php
			if(strpos($_SERVER['PHP_SELF'],'/admin/index.php') != 0){
				$pos = strpos($_SERVER['PHP_SELF'],'/admin/index.php');
				$path = substr($_SERVER['PHP_SELF'],0,$pos);
				if(strpos($curr_co->content,$path.'/') == 0) {
					$curr_co->content = str_replace('/admin/fckeditor',$path.'/admin/fckeditor',$curr_co->content);
				}
			}
			?>
            <?php
            echo Html::textarea('co[content]', $curr_co->content)."\n";
            $o_fck = new RichTextbox('co[content]');
            $o_fck->height = 360;
            echo $o_fck->create();
            echo Html::input('hidden', 'co[id]', $curr_co->id);
            ?>
            </td>
        </tr>
	</tbody>
</table>
<?php
$sinfo_form->close();
$sinfo_form->writeValidateJs();
?>