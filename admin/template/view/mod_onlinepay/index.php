<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('Online Payment'); ?></div>
    <div class="rightmeta rightblock">
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="contentbody">
<?php
$prepay_form = new Form('index.php?_m=mod_onlinepay', 'prepayform', 'check_prepay_info');
$prepay_form->p_open('mod_onlinepay', 'do_payment');
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
            <td class="label"><?php _e('Total Payment'); ?></td>
            <td class="entry"><?php echo CURRENCY_SIGN; ?>
            <?php echo $curr_order->total_amount; ?>
            </td>
        </tr>
    </tbody>
</table>
<div class="submit_order_wrapper aligncenter">
    <?php echo Html::input('submit', 'submit', __('Confirm & Pay Now'), 'class="submit_order orange"'); ?>&nbsp;
</div>
<?php
$prepay_form->close();
$prepay_form->addCustValidationJs($custom_js);
$prepay_form->writeValidateJs();
?>
</div>
