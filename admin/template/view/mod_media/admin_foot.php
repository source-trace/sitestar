<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<div class="status_bar">
	<span id="adminsinfofrm_stat" class="status" style="display:none;"></span>
</div>
<script type="text/javascript" language="javascript">
<!--
var popup_win = false;

function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    if (o_result.result == "OK") {
        reloadParent();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    alert("<?php _e('Update failed!'); ?>");
}
//-->
</script>
<?php
//$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
//$sinfo_form->setEncType('multipart/form-data');
//$sinfo_form->p_open('mod_media', 'save_foot');

$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
$sinfo_form->setEncType('multipart/form-data');
$sinfo_form->p_open('mod_media', 'save_foot', '_ajax');
?>
<div style="overflow:auto;width:100%;">
<!--style type="text/css">
.form_table .label{ background:#f3f3f3; border-top:solid 1px #fff; border-right:solid 1px #ececec; text-align:right; width: 120px; padding-right: 5px;}
.form_table tbody td.entry .txtinput{border:solid 1px #dedede; padding:2px; line-height:18px; width:200px;}

</style-->
<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
            echo Html::input('submit', 'submit', __('Save'));
     
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>     
        

		<?php
		$curr_logo_id='';
		if(isset($curr_logo->id)){
			$curr_logo_id=$curr_logo->id;
		}
		$p_logo_img_src='';
		if(isset($p_logo['img_src'])){
			$p_logo_img_src=$p_logo['img_src'];
		}
		$curr_banner_id='';
		if(isset($curr_banner->id)){
			$curr_banner_id=$curr_banner->id;
		}
		$p_banner_img_src='';
		if(isset($p_banner['img_src'])){
			$p_banner_img_src=$p_banner['img_src'];
		}
		$curr_foot_id='';
		if(isset($curr_foot->id)){
			$curr_foot_id=$curr_foot->id;
		}
		echo Html::input('hidden', 'logo[id]', $curr_logo_id);
            echo Html::input('hidden', 'param[logo_img]', $p_logo_img_src);
            echo Html::input('hidden', 'banner[id]', $curr_banner_id);
			echo Html::input('hidden', 'param[banner_img]', $p_banner_img_src);
            echo Html::input('hidden', 'foot[id]', $curr_foot_id);
		?>
		<tr>
            <td class="label"><?php _e('Foot Information');?></td>
            <td class="entry">
			<?php
            echo Html::textarea('param[html]', $p_foot['html'], 'rows="8" cols="76" class="textinput" style="width:450px;"')
            ?>
            </td>
        </tr>
		
    </tbody>
</table>
</div>
<?php
//$sinfo_form->close();
//$sinfo_form->writeValidateJs();


$sinfo_form->close();
$custom_js = <<<JS
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$sinfo_form->addCustValidationJs($custom_js);
$sinfo_form->writeValidateJs();
?>
