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
    
    var stat = document.getElementById("regform_stat");
    if (o_result.result == "ERROR") {
        document.forms["regform"].reset();
        
        stat.innerHTML = "<?php _e('Resetting password failed! Please contact webmaster or retry later!'); ?>";
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, password reset! Please login now!'); ?>";
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["regform"].reset();
    
    document.getElementById("regform_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<?php
$rstpwd_form = new Form('index.php', 'rstpwdform', 'check_user_info');
$rstpwd_form->p_open('mod_user', 'rstpwd', '_ajax');
?>
<table id="rstpwdform_table" cellspacing="1">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('submit', 'submit', __('Reset Password'));
            echo Html::input('hidden', 'v[login]', $v_login);
            echo Html::input('hidden', 'v[email]', $v_email);
            echo Html::input('hidden', 'v[sign]', $v_sign);
            ?>
            <span id="rstpwdform_stat" class="status" style="display:none;"></span>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'user[passwd]', '', 
                '', $rstpwd_form, 'RequiredTextbox', 
                __('Please input password!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Confirm Password'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'user[re_passwd]', '', 
                '', $rstpwd_form, 'RequiredTextbox', 
                __('Please retype your password for confirmation!'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$rstpwd_form->close();
$rstpwd_form->genCompareValidate('user[passwd]', 'user[re_passwd]', 
    '=', __('Passwords mismatch!'));
$running_msg = __('Resetting password...');
$custom_js = <<<JS
$("#rstpwdform_stat").css({"display":"block"});
$("#rstpwdform_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$rstpwd_form->addCustValidationJs($custom_js);
$rstpwd_form->writeValidateJs();
?>
