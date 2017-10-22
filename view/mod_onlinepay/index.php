<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<script language="javascript">
$(document).ready(function(){
	$("#use_balance").click(function(){
		var i_tot = <?php echo $curr_order->total_amount; ?>;
		if($(this).attr("checked")){
			var bal = $("#balance").html();
			var tot = $("#total").html();
			var pri = tot-bal;
			if(pri<=0){
				//alert("<?php _e('Balance sufficient, please use the balance to pay');?>");
				$("#total").empty().html(0);
				//return false;
			}else{
				$("#total").empty().html(pri);
			}
		}else{
			$("#total").empty().html(i_tot);
		}
	})
})
</script>

<div class="art_list">
	<div class="art_list_title"><?php _e('Online Payment'); ?></div>
<?php
$prepay_form = new Form('index.php?_m=mod_onlinepay', 'prepayform', 'check_prepay_info');
$prepay_form->p_open('mod_onlinepay', 'do_payment');
?>
<?php echo Html::input('hidden', 'o_id', $curr_order->id); ?>
		<?php //unset($payaccts[6]);?>
		<div class="order_1"><?php _e('Select Gateway'); ?></div><div class="order_2"><?php
            echo Html::select('paygate', $payaccts, '', '', 
                $prepay_form, 'RequiredSelect', 
                __('Please select payment gateway!'));
            ?></div><div class="blankbar1"></div>
		<div class="order_1"><?php _e('Total Payment'); ?></div><div class="order_2"><?php echo CURRENCY_SIGN; ?><font id="total"><?php echo $curr_order->total_amount; ?></font></div><div class="blankbar1"></div>
		<?php if(CREDITS_SWITCH==1){ ?>
		<div class="order_1"><?php _e('Using the balance of payments'); ?></div>
		<div class="order_2"><?php echo CURRENCY_SIGN; ?><font id="balance"><?php echo $curr_userext->balance; ?></font> &nbsp;&nbsp; <input type="checkbox" value="1" id="use_balance" name="use_balance" /></div><div class="blankbar1"></div>
		<?php } ?>
<?php if(!empty($payaccts)) echo Html::input('submit', 'submit', __('Confirm & Pay Now'), 'class="submit_order orange"'); ?>
<?php
$custom_js = isset($custom_js)?$custom_js:'';
$prepay_form->close();
$prepay_form->addCustValidationJs($custom_js);
$prepay_form->writeValidateJs();
?>
</div>
