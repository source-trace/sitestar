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


<div class="art_list">
	<div class="art_list_title"><?php _e('Confirm Order Payment'); ?></div>
	<span id="buynow_stat" class="status" style="display:none;"></span>
	
<?php
$prepay_form = new Form('index.php?_m=mod_order', 'prepayform', 'check_prepay_info');
$prepay_form->p_open('mod_order', 'buynow', '_ajax');
?>
	 <?php echo Html::input('hidden', 'o_id', $curr_order->id); ?>
	
<table class="new_orders_list" cellpadding="1" cellspacing="0" width="100%" border="0">
	<tr>
		<td><?php _e('Total Payment'); ?></td>
		<td><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->total_amount; ?></td>
	</tr>
	<?php if(CREDITS_SWITCH==1){ ?>
	<tr>
		<td><?php _e('Account Balance'); ?></td>
		<td><?php echo CURRENCY_SIGN; ?><?php echo $curr_userext->balance; ?></td>
	</tr>
	<?php } ?>
</table>

<?php echo Html::input('submit', 'submit', __('Confirm & Pay Now'), 'class="submit_order orange"'); ?>

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
