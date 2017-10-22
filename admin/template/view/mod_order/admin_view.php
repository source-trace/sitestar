<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("adminvieworder_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
//	    reloadPage();
		parent.window.location.reload();
    } else {
        return on_failure(response);
    }
}

function on_failure(response) {
    document.getElementById("adminvieworder_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function backPrv(){
	window.location.href="index.php?_m=mod_order&_a=admin_list";	
}
//-->
</script>
<span id="adminvieworder_stat" class="status" style="display:none;"></span>
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
<tr>
  <td width="30%"><b><font style="color:#4372B0;padding-left:15px;"><?php _e('Current Order'); ?></font> : <?php echo $curr_order->oid ; ?></b><b><font style="color:#4372B0;padding-left:15px;"><?php _e('Member'); ?></font> : <?php 
  $curr_order->loadRelatedObjects(REL_PARENT, array('User'));
  echo $curr_order->masters['User']->login; ?></b></td>
</tr>
</table>
<table width="100%" class="form_table_list" border="0" cellspacing="1" cellpadding="0" style="line-height:24px;">
    <thead>
        <tr>
            <th width="35%"><?php _e('Name'); ?></th>
            <th><?php _e('Quantity'); ?></th>
            <th><?php _e('Single Price'); ?></th>
            <th><?php _e('Total Price'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if (sizeof($order_prods) > 0) {
        $row_idx = 0;
        foreach ($order_prods as $product) {
    ?>
        <tr class="row_style_<?php echo $row_idx; ?>">
            <td class="mainlistlink"><a href="../<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->product_id)); ?>" title="<?php echo $product->product_name; ?>" target="_blank">
                        <?php echo $product->product_name; ?></a></td>
            <td class="aligncenter" width="56"><?php echo $product->amount; ?></td>
            <td class="aligncenter" width="96"><?php echo CURRENCY_SIGN; ?><?php echo $product->price; ?></td>
            <td class="aligncenter" width="96"><?php echo CURRENCY_SIGN; ?><?php echo $product->ttl_price; ?></td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
        <tr class="row_style_0">
            <td colspan="4" class="aligncenter"><?php _e('No Records!'); ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
<tr>
  <td style="color:#4372B0;padding-left:15px;"><b><?php _e('Delivery Information'); ?></b></td>
</tr>
</table>
<table id="deliveryaddrview_table" border="0" cellspacing="1" cellpadding="2" style="margin:0;margin-left:15px;width:98%;">
    <tbody>
<?php if (strlen(trim($curr_order->reciever_name)) > 0) { ?>
        <tr>
            <td class="label" width="35%"><?php _e('Customer Name'); ?></td>
            <td class="entry"><?php echo $curr_order->reciever_name; ?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Province, City'); ?></td>
            <td class="entry">
                <?php echo Toolkit::getDSName($curr_order->prov_id); ?>&nbsp;
                <?php echo Toolkit::getDSName($curr_order->city_id); ?>&nbsp;
                <?php echo Toolkit::getDSName($curr_order->dist_id); ?>
            </td>
        </tr>
        <tr valign="top">
            <td class="label"><?php _e('Address'); ?></td>
            <td class="entry"><?php echo $curr_order->detailed_addr; ?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Postal'); ?></td>
            <td class="entry"><?php echo $curr_order->postal; ?></td>
        </tr>
        <tr>
            <td class="label"><?php _e('Phone'); ?></td>
            <td class="entry"><?php echo $curr_order->phone; ?></td>
        </tr>
<?php } else { ?>
        <tr>
            <td class="aligncenter"><?php _e('No delivery needed.'); ?></td>
        </tr>
<?php } ?>
    </tbody>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
<tr>
  <td style="color:#4372B0;padding-left:15px;"><b><?php _e('Message'); ?></b></td>
</tr>
</table>
<table id="deliveryaddrview_table" border="0" cellspacing="1" cellpadding="2" style="margin:0;margin-left:15px;width:98%;">
    <tbody>
    	<tr>
    		<td><?php echo $curr_order->message; ?></td>
    	</tr>
    </tbody>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
<tr>
  <td style="color:#4372B0;padding-left:15px;"><b><?php _e('Payment'); ?></b></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="2">
    <tbody>
        <tr>
            <td style="width:15%;text-align:right;padding-right:15px;"><?php _e('Total Price'); ?></td>
            <td><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->discount_price; ?></td>
        </tr>
        <tr>
            <td style="width:15%;text-align:right;padding-right:15px;"><?php _e('Delivery Fee'); ?></td>
            <td><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->delivery_fee; ?></td>
        </tr>
        <tr>
            <td style="width:15%;text-align:right;padding-right:15px;"><?php _e('Total Payment'); ?></td>
            <td><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->total_amount; ?></td>
        </tr>
    </tbody>
</table>
<?php
// Do not show the update form when order is finished
if (intval($curr_order->order_status) != 100) {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="line-height:24px;">
<tr>
  <td style="color:#4372B0;padding-left:15px;"><b><?php _e('Change Order'); ?></b></td>
</tr>
</table>
<?php
$chgorder_form = new Form('index.php', 'chgorderform', 'check_order_info');
$chgorder_form->p_open('mod_order', 'admin_update', '_ajax');
?>
<table class="form_table" width="100%" border="0" cellspacing="1" cellpadding="2">
    <tfoot>
        <tr>
            <td colspan="2">
            <?php
            echo Html::input('reset', 'reset', __('Reset'));
            echo Html::input('button', 'cancel', __('Cancel'), 'onclick="backPrv();"');
            echo Html::input('submit', 'submit', __('Save'));
            echo Html::input('hidden', 'order[id]', $curr_order->id);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td style="width:15%;text-align:right;padding-right:15px;"><?php _e('Status'); ?></td>
            <td>
            <?php
            echo Html::select('order[order_status]', 
                array('1' => __('Not Paid'), '2' => __('Paid'), '3' => __('In Delivery'), '100' => __('Finished'), '101' => __('Cancelled')), 
                $curr_order->order_status, 'class="textselect"');
            ?>
            </td>
        </tr>
        <tr>
            <td style="width:15%;text-align:right;padding-right:15px;"><?php _e('Total Payment'); ?></td>
            <td>
            <?php
            echo Html::input('text', 'order[total_amount]', $curr_order->total_amount, 
                'class="textinput"', $chgorder_form, 'RequiredTextbox', 
                __('Please input order total payment!'));
            ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
$chgorder_form->close();
$running_msg = __('Updating order...');
$custom_js = <<<JS
$("#adminvieworder_stat").css({"display":"block"});
$("#adminvieworder_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$chgorder_form->addCustValidationJs($custom_js);
$chgorder_form->writeValidateJs();
}
?>
