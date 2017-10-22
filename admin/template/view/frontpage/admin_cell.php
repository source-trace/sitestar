<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

$sinfo_form = new Form('index.php', 'sinfoform', 'check_sinfo_info');
$sinfo_form->p_open('frontpage', $next_action, '_ajax');
?>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    if (o_result.result == "ERROR") {
        document.forms["sinfoform"].reset();
        alert(o_result.errmsg);
        return false;
    } else if (o_result.result == "OK") {
	    reloadParent();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["sinfoform"].reset();
    alert("<?php _e('Request failed!'); ?>");
    return false;
}
//-->
</script>
<div style="overflow:auto;width:100%;">
<table id="sinfoform_table" class="form_table" width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
	<tfoot>
		<tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'), 'onclick="if(!confirm(\''.__('Do you want to reset ?').'\')){return false;}"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', '_t', $status);
            echo Html::input('hidden', '_p', $curr_loop);
            ?>
            </td>
        </tr>
	</tfoot>
	<tbody>
		<tr>
			<td class="label"><?php _e('Title'); ?></td>
			<td class="entry">
			<?php
            echo Html::input('text', 'title', $title, 'class="textinput"');
            ?>
            </td>
		</tr>
		<tr>
			<td class="label"><?php _e('Link Addr'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'link', urldecode($url), 'class="textinput"');
            ?>
            </td>
		</tr>
		<tr>
			<td class="label"><?php _e('Introduction'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'description', $description, 'class="textinput"');
            ?>
            </td>
		</tr>
	</tbody>
</table>
</div>
<?php
$sinfo_form->close();
$custom_js = <<<JS
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$sinfo_form->addCustValidationJs($custom_js);
$sinfo_form->writeValidateJs();
?>