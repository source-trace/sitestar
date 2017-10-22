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
    
    var stat = document.getElementById("reqrstpwdform_stat");
    if (o_result.result == "ERROR") {
        document.forms["reqrstpwdform"].reset();
        
        stat.innerHTML = "<?php _e('Sending E-mail failed! Please contact webmaster or retry later!'); ?>";
        return false;
    } else if (o_result.result == "OK") {
        stat.innerHTML = "<?php _e('OK, E-mail sent! You can close this popup window now!'); ?>";
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.forms["reqrstpwdform"].reset();
    
    document.getElementById("reqrstpwdform_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<?php
$reqrstpwd_form = new Form('index.php', 'reqrstpwdform', 'check_user_info');
$reqrstpwd_form->p_open('mod_user', 'send_rstpwd_req', '_ajax');
?>
<table id="reqrstpwdform_table" cellspacing="1">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('submit', 'submit', __('Send Reset Password E-mail'));
            ?>
            <span id="reqrstpwdform_stat" class="status" style="display:none;"></span>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Login Name'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'user[login]', '', 
                '', $reqrstpwd_form, 'RequiredTextbox', 
                __('Please input login name!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('E-mail'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'user[email]', '', 
                '', $reqrstpwd_form, 'RequiredTextbox', 
                __('Please input e-mail address!'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$reqrstpwd_form->close();
$running_msg = __('Saving request...');
$custom_js = <<<JS
$("#reqrstpwdform_stat").css({"display":"block"});
$("#reqrstpwdform_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$reqrstpwd_form->addCustValidationJs($custom_js);
$reqrstpwd_form->writeValidateJs();
?>
