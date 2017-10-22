<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<script type="text/javascript" language="javascript">
<!--
function on_buynow_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("buynow_stat");
    if (o_result.result == "ERROR") {
        document.forms["prepayform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
	    window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_buynow_failure(response) {
    document.forms["prepayform"].reset();
    
    document.getElementById("buynow_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}
//-->
</script>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('Confirm Order Payment'); ?></div>
    <div class="rightmeta rightblock">
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="buynow_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<div class="contentbody">
<?php
$prepay_form = new Form('index.php?_m=mod_order', 'prepayform', 'check_prepay_info');
$prepay_form->p_open('mod_order', 'buynow', '_ajax');
?>
<table id="prepayform_table" cellspacing="1" class="front_form_table prepay_table">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php echo Html::input('hidden', 'o_id', $curr_order->id); ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td class="label"><?php _e('Total Payment'); ?></td>
            <td class="entry"><?php echo CURRENCY_SIGN; ?>
            <?php echo $curr_order->total_amount; ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Account Balance'); ?></td>
            <td class="entry"><?php echo CURRENCY_SIGN; ?>
            <?php echo $curr_userext->balance; ?>
            </td>
        </tr>
    </tbody>
</table>
<div class="submit_order_wrapper aligncenter">
    <?php echo Html::input('submit', 'submit', __('Confirm & Pay Now'), 'class="submit_order orange"'); ?>&nbsp;
</div>
<?php
$prepay_form->close();
$running_msg = __('Sending payment information...');
$custom_js = <<<JS
$("#buynow_stat").css({"display":"block"});
$("#buynow_stat").html("$running_msg");
_ajax_submit(thisForm, on_buynow_success, on_buynow_failure);
return false;

JS;
$prepay_form->addCustValidationJs($custom_js);
$prepay_form->writeValidateJs();
?>
</div>
