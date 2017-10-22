<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('Online Saving'); ?></div>
    <div class="rightmeta rightblock">
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="contentbody">
<?php
$prepay_form = new Form('index.php?_m=mod_onlinepay', 'prepayform', 'check_prepay_info');
$prepay_form->p_open('mod_onlinepay', 'do_sav_payment');
?>
<table id="prepayform_table" cellspacing="1" class="front_form_table prepay_table">
    <tbody>
        <tr>
            <td class="label"><?php _e('Select Gateway'); ?></td>
            <td class="entry">
            <?php
            echo Html::select('paygate', $payaccts, '', '', 
                $prepay_form, 'RequiredSelect', 
                __('Please select payment gateway!'));
            ?>
            </td>
        </tr>
        <tr>
            <td class="label"><?php _e('Saving Amount'); ?></td>
            <td class="entry"><?php echo CURRENCY_SIGN; ?>
            <?php
            echo Html::input('text', 'amount', '0.00', '', 
                $prepay_form, 'RequiredTextbox', 
                __('Please input the saving amount!'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<div class="submit_order_wrapper aligncenter">
    <?php echo Html::input('submit', 'submit', __('Confirm & Pay Now'), 'class="submit_order orange"'); ?>&nbsp;
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
</div>
