<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_paramSuccess(response) {alert(response);
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminsinfofrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["sparamform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('Parameters updated!'); ?>";
	    window.parent.tb_remove();
	    window.location.reload();
    } else {
        return on_failure(response);
    }
}

function on_paramFailure(response) {
    document.forms["sparamform"].reset();
    
    document.getElementById("adminsinfofrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php _e('Site Settings'); ?></div></td>
				<td><?php include_once(P_TPL.'/common/language_switch.php'); ?></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="adminsinfofrm_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$sparam_form = new Form('index.php', 'sparamform', 'check_sparam_info');
$sparam_form->p_open('mod_database', 'backup', '_ajax');
?>
<table id="sinfoform_table" class="form_table" cellspacing="0">
    <tfoot>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Export Database'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'sparam[SITE_BACKUP]', '1', 
                Toolkit::switchText(SITE_BACKUP, 
                    array('0' => '', '1' => 'checked="checked"')));
            echo '&nbsp&nbsp&nbsp';
            echo Html::input('submit', 'submit', __('Save'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$sparam_form->close();
$running_msg = __('Saving site parameters...');
$custom_js = <<<JS
$("#adminsinfofrm_stat").css({"display":"block"});
$("#adminsinfofrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_paramSuccess, on_paramFailure);
return false;

JS;
$sparam_form->addCustValidationJs($custom_js);
$sparam_form->writeValidateJs();

//----------------------------------------------
$import_form = new Form('index.php', 'importform', 'check_import_info');
$import_form->p_open('mod_database', 'import', '_ajax');
?>
<table id="sinfoform_table" class="form_table" cellspacing="0">
    <tfoot>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Import Database'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('checkbox', 'import[SITE_IMPORT]', '1', 
                Toolkit::switchText(SITE_IMPORT, 
                    array('0' => '', '1' => 'checked="checked"')));
            echo '&nbsp&nbsp&nbsp';
            echo Html::input('submit', 'submit', __('Save'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$import_form->close();
$running_msg = __('Saving site parameters...');
$custom_js = <<<JS
$("#adminsinfofrm_stat").css({"display":"block"});
$("#adminsinfofrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_paramSuccess, on_paramFailure);
return false;

JS;
$import_form->addCustValidationJs($custom_js);
$import_form->writeValidateJs();
?>
