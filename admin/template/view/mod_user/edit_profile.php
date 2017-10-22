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
    
    var stat = document.getElementById("edtprofform_stat");
    if (o_result.result == "ERROR") {
        document.forms["edtprofform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
        if (o_result.forward) {
            parent_goto_d(o_result.forward);
        } else {
            stat.innerHTML = "<?php _e('OK! Profile saved!'); ?>";
        }
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["edtprofform"].reset();
    
    document.getElementById("edtprofform_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<?php
$edtprof_form = new Form('index.php', 'edtprofform', 'check_prof_info');
$edtprof_form->p_open('mod_user', 'save_profile', '_ajax');
?>
<table id="regform_table" cellspacing="1" class="front_form_table">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('submit', 'submit', __('Save'));
            ?>
            <span id="edtprofform_stat" class="status" style="display:none;"></span>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Login Name'); ?></td>
            <td class="entry"><?php echo $curr_user->login; ?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'passwd[passwd]');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Confirm Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'passwd[re_passwd]');
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('E-mail'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'user[email]', $curr_user->email, 
                '', $edtprof_form, 'RequiredTextbox', 
                __('Please input e-mail address!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Name'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'user[full_name]', $curr_user->full_name);
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$edtprof_form->close();
$edtprof_form->genCompareValidate('passwd[passwd]', 'passwd[re_passwd]', 
    '=', __('Passwords mismatch!'));
$running_msg = __('Saving profile...');
$custom_js = <<<JS
$("#edtprofform_stat").css({"display":"block"});
$("#edtprofform_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$edtprof_form->addCustValidationJs($custom_js);
$edtprof_form->writeValidateJs();
?>
