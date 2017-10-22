<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>

<div class="art_list">
	<div class="art_list_title"><?php _e('Online Saving'); ?></div>
	<?php
$prepay_form = new Form('index.php?_m=mod_onlinepay', 'prepayform', 'check_prepay_info');
$prepay_form->p_open('mod_onlinepay', 'do_sav_payment');
?>
	<div class="order_1"><?php _e('Select Gateway'); ?></div><div class="order_2"><?php
		if(!empty($payaccts[1])) $payaccts[1] = '支付宝标准双接口';
           	echo Html::select('paygate', $payaccts, '', '',
                $prepay_form, 'RequiredSelect', 
                __('Please select payment gateway!'));
            ?></div><div class="blankbar1"></div>
	<div class="order_1"><?php _e('Saving Amount'); ?></div><div class="order_2"><?php echo CURRENCY_SIGN; ?><?php
            echo Html::input('text', 'amount', '0.00', '', 
                $prepay_form, 'RequiredTextbox', 
                __('Please input the saving amount!'));
            ?></div><div class="blankbar1"></div>
	<?php echo Html::input('submit', 'submit', __('Confirm & Pay Now'), 'class="submit_order orange"'); ?>
</div>


<?php
$prepay_form->close();
$zero_msg = __('Saving amount could not be empty or 0.00!');
$custom_js = <<<JS
if (/^[0\.]*$/.test(thisForm.elements["amount"].value)) {
	alert("$zero_msg");
	thisForm.elements["amount"].focus();
	return false;
}

JS;
$prepay_form->addCustValidationJs($custom_js);
$prepay_form->writeValidateJs();
?>
