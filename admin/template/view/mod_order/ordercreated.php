<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<script type="text/javascript" language="javascript">
<!--
function on_finishorder_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("vieworder_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, refreshing...'); ?>";
	    reloadPage();
    } else {
        return on_failure(response);
    }
}

function on_delorder_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("vieworder_stat");
    if (o_result.result == "ERROR") {
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
	    window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_userorder_failure(response) {
    document.getElementById("vieworder_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function user_delete_order(o_id) {
	if (confirm("<?php _e('Delete the current order?'); ?>")) {
	    var stat = document.getElementById("vieworder_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting current order...'); ?>";
		_ajax_request("mod_order", 
			"userdelorder", 
	        {
	            o_id:o_id
	        }, 
			on_delorder_success, 
			on_userorder_failure);
	}
}

function user_finish_transaction(o_id) {
	if (confirm("<?php _e('Are you sure?'); ?>")) {
	    var stat = document.getElementById("vieworder_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Updating order status...'); ?>";
		_ajax_request("mod_order", 
			"userfinishorder", 
	        {
	            o_id:o_id
	        }, 
			on_finishorder_success, 
			on_userorder_failure);
	}
}
//-->
</script>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('Current Order'); ?> : <?php echo $curr_order->oid ; ?> 
        - <?php _e('Status'); ?> : <?php echo Toolkit::switchText($curr_order->order_status, 
                        array('1' => __('Not Paid'), '2' => __('Paid'), '3' => __('In Delivery'), '100' => __('Finished'), '101' => 'Cancelled')); ?></div>
    <div class="rightmeta rightblock">
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="vieworder_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<div class="contentbody">
    <table cellspacing="1" class="front_list_table order_product_list">
        <thead>
            <tr>
                <th><?php _e('Name'); ?></th>
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
                <td class="mainlistlink"><a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->product_id)); ?>" title="<?php echo $product->product_name; ?>" target="_blank">
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
</div>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('Delivery Information'); ?></div>
    <div class="rightmeta rightblock">
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="contentbody">
    <table id="deliveryaddrview_table" class="front_form_table" cellspacing="1">
        <tbody>
<?php if (strlen(trim($curr_order->reciever_name)) > 0) { ?>
            <tr>
                <td class="label"><?php _e('Customer Name'); ?></td>
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
</div>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('Payment'); ?></div>
    <div class="rightmeta rightblock">
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="contentbody">
    <table id="orderviewtotal_table" class="front_form_table" cellspacing="1">
        <tbody>
            <tr>
                <td class="label"><?php _e('Total Price'); ?></td>
                <td class="entry"><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->discount_price; ?></td>
            </tr>
            <tr>
                <td class="label"><?php _e('Delivery Fee'); ?></td>
                <td class="entry"><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->delivery_fee; ?></td>
            </tr>
            <tr>
                <td class="label"><?php _e('Total Payment'); ?></td>
                <td class="entry"><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->total_amount; ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="submit_order_wrapper aligncenter">
    <?php if (intval($curr_order->order_status) == 1) { ?>
        <?php echo Html::input('button', 'direct_buy', __('Buy Now'), 'class="submit_order orange" onclick="window.location.href=\'index.php?_m=mod_order&amp;_a=confirm_buynow&amp;o_id='.$curr_order->id.'\'"'); ?>&nbsp;
        <?php echo Html::input('button', 'pay_now', __('Online Pay Now'), 'class="submit_order orange" onclick="window.location.href=\'index.php?_m=mod_onlinepay&amp;o_id='.$curr_order->id.'\'"'); ?>&nbsp;
        <?php echo Html::input('button', 'delete_order', __('Delete This Order'), 'class="submit_order red" onclick="user_delete_order('.$curr_order->id.');return false;"'); ?>&nbsp;
    <?php } ?>
    <?php if (intval($curr_order->order_status) == 3) { ?>
        <?php echo Html::input('button', 'transaction_finished', __('Transaction Finished'), 'class="submit_order red" onclick="user_finish_transaction('.$curr_order->id.');return false;"'); ?>&nbsp;
    <?php } ?>
    <?php echo Html::input('button', 'back', __('Back'), 'class="submit_order" onclick="window.location.href=\'index.php?_m=mod_order&amp;_a=userlistorder\'"'); ?>&nbsp;
</div>
