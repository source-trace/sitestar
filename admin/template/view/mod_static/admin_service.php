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
				<td><div class="title"><?php _e('service'); ?></div></td>
				<td>&nbsp;</td>
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
$sinfo_form = new Form('index.php', 'contactusform', 'check_sinfo_info');
$sinfo_form->p_open('mod_static', $next_action, '_ajax');
?>
<table id="sinfoform_table" class="form_table" cellspacing="0">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('submit', 'submit', __('Save'));
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
		
		<tr>
            <td class="label"><?php _e('service'); ?></td>
            <td class="entry">
            <?php
            echo Html::textarea('param[service]', $service, 'rows="8" cols="108"');
			
            ?>
            </td>
        </tr>
		<!--tr>
        	<td class="label" ><?php _e('Memo'); ?></td>
        	<td><?php _e('53kf_remark');?></td>
        </tr>
		<tr>
        	<td class="label" rowspan="4"><?php _e('service Provider'); ?></td>
        	<td><a href="http://www.53kf.com" target="_blank">http://www.53kf.com</a></td>
        </tr-->
        
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

?>
