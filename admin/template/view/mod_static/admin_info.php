<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
?>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminscfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["contactusform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
	    window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["contactusform"].reset();
    
    document.getElementById("adminscfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<div class="content_toolbar">
	<table cellspacing="0" class="wrap_table">
		<tbody>
			<tr>
				<td><div class="title"><?php _e('Company Info'); ?></div></td>
				<td><?php include_once(P_TPL.'/common/language_switch.php'); ?></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="adminscfrm_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$sinfo_form = new Form('index.php', 'serviceform', 'check_sinfo_info');
$sinfo_form->p_open('mod_static', 'mod_service_create', '_ajax');
?>
<table id="sinfoform_table" class="form_table" cellspacing="0">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'cus[id]', $curr_cus->id);
            echo Html::input('hidden', 'co[id]', $curr_co->id);
            echo Html::input('hidden', 'cus[s_locale]', $lang_sw);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
		
		<tr>
            <td class="label"><?php _e('Company Introduction'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('co[content]', $curr_co->content)."\n";
            $o_fck = new RichTextbox('co[content]');
     
            $o_fck->height = 360;
            echo $o_fck->create();
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Contact Us'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('cus[content]', $curr_cus->content)."\n";
            $o_fck = new RichTextbox('cus[content]');
        
            $o_fck->height = 240;
            echo $o_fck->create();
            ?>
            </td>
        </tr>
    </tbody>
</table>

<?php
$sinfo_form->close();
$running_msg = __('Saving content...');
$custom_js = <<<JS
$("#adminscfrm_stat").css({"display":"block"});
$("#adminscfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$sinfo_form->addCustValidationJs($custom_js);
$sinfo_form->writeValidateJs();

include_once(P_TPL.'/view/mod_static/admin_mod.php');
?>
