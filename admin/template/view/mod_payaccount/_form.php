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
    
    var stat = document.getElementById("adminpayacctfrm_stat");
    if (o_result.result == "ERROR") {
        document.forms["payacctform"].reset();
        
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
    document.forms["payacctform"].reset();
    
    document.getElementById("adminpayacctfrm_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<div class="content_title">
	<h3><?php _e($payacct_title); ?></h3>
</div>
<div class="space"></div>
<div class="content_toolbar">
	<a href="<?php echo Html::uriquery('mod_payaccount', 'admin_list'); ?>" title=""><?php _e('Back'); ?></a>
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="adminpayacctfrm_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<?php
$payacct_form = new Form('index.php', 'payacctform', 'check_acct_info');
$payacct_form->p_open('mod_payaccount', $next_action, '_ajax');
?>
<table id="payacctform_table" class="form_table" cellspacing="0">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="window.history.go(-1);"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'payacct[id]', $curr_payacct->id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Account Type'); ?></td>
            <td class="entry">
            <?php
            echo Html::select('payacct[payment_provider_id]', 
                Toolkit::toSelectArray($providers, 'id', 'disp_name'), 
                $curr_payacct->payment_provider_id);
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Account ID'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('text', 'payacct[payment_id]', $curr_payacct->payment_id, 
                'size="48"', $payacct_form, 'RequiredTextbox', 
                __('Please input account ID!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Account Key'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'payacct[payment_key]', $curr_payacct->payment_key, 
                'size="48"', $payacct_form, 'RequiredTextbox', 
                __('Please input account key!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Confirm Key'); ?></td>
            <td class="entry">
            <?php
            echo Html::input('password', 'payacct[re_payment_key]', $curr_payacct->payment_key, 
                'size="48"', $payacct_form, 'RequiredTextbox', 
                __('Please retype your account key for confirmation!'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$payacct_form->close();
$payacct_form->genCompareValidate('payacct[payment_key]', 'payacct[re_payment_key]', 
    '=', __('Account keys mismatch!'));
$running_msg = __('Saving account...');
$custom_js = <<<JS
$("#adminpayacctfrm_stat").css({"display":"block"});
$("#adminpayacctfrm_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$payacct_form->addCustValidationJs($custom_js);
$payacct_form->writeValidateJs();
?>
