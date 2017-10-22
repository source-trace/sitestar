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

<div class="art_list">
	<div class="art_list_title order_title"><?php _e('Current Order'); ?> : <?php echo $curr_order->oid ; ?> 
        - <?php _e('Status'); ?> : <?php echo Toolkit::switchText($curr_order->order_status, 
                        array('1' => __('Not Paid'), '2' => __('Paid'), '3' => __('In Delivery'), '100' => __('Finished'), '101' => 'Cancelled')); ?></div>
	<span id="vieworder_stat" class="status" style="display:none;"></span>
<!-- 当前订单 -->
<table class="new_orders_list" cellpadding="1" cellspacing="0" width="100%" border="0">
	<tr>
		<th width="60%"><?php _e('Name'); ?></th>
		<th width="10%"><?php _e('Quantity'); ?></th>
		<th width="15%"><?php _e('Single Price'); ?></th>
		<th width="15%"><?php _e('Total Price'); ?></th>
	</tr>
<?php
if (sizeof($order_prods) > 0) {
    $row_idx = 0;
    foreach ($order_prods as $product) {
?>
	<tr>
		<td><div class="new_order_name"><a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->product_id)); ?>" title="<?php echo $product->product_name; ?>" target="_blank"><?php echo $product->product_name; ?></a></div></td>
		<td><?php echo $product->amount; ?></td>
		<td><?php echo CURRENCY_SIGN; ?><?php echo $product->price; ?></td>
		<td><?php echo CURRENCY_SIGN; ?><?php echo $product->ttl_price; ?></td>
	</tr>
<?php
        $row_idx = 1 - $row_idx;
    }
} else {
?>
	<tr>
		<td colspan="4"><?php _e('No Records!');?></td>
	</tr>
<?php } ?>
</table>
<!-- //当前订单 -->
	<div class="addr"><div class="addr1"><b><?php _e('Delivery Information'); ?></b></div></div>
	<div class="addr2">
		<?php if (strlen(trim($curr_order->reciever_name)) > 0) { ?>
		<?php _e('Customer Name'); ?>：<?php echo $curr_order->reciever_name; ?><br />
		<?php _e('Province, City'); ?>：<?php echo Toolkit::getDSName($curr_order->prov_id); ?>&nbsp;<?php echo Toolkit::getDSName($curr_order->city_id); ?>&nbsp;<?php echo Toolkit::getDSName($curr_order->dist_id); ?><br />
		<?php _e('Address'); ?>：<?php echo $curr_order->detailed_addr; ?><br />
		<?php _e('Postal'); ?>：<?php echo $curr_order->postal; ?><br />
		<?php _e('Phone'); ?>：<?php echo $curr_order->phone; ?><br />
		
		
		<?php } else { ?>
		<?php _e('No delivery needed.'); ?>
		<?php } ?>
	</div>
		
	<!-- 13/05/2010 >> -->
	<?php
	if ( !empty($curr_order->message) ) {	
	?>
	<div class="addr"><div class="addr1"><b><?php _e('Message'); ?></b></div></div>
	<div class="addr2">
	<?php
		echo $curr_order->message;
	?>
	</div>
	<?php }?>
	<!-- 13/05/2010 << -->

	<div class="addr"><div class="addr1"><b><?php _e('Payment'); ?></b></div></div>
	<div class="addr2">
		<div class="order_1"><?php _e('Total Price'); ?></div><div class="order_2"><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->discount_price; ?></div><div class="blankbar1"></div>
		<div class="order_1"><?php _e('Delivery Fee'); ?></div><div class="order_2"><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->delivery_fee; ?></div><div class="blankbar1"></div>
		<div class="order_1"><?php _e('Total Payment'); ?></div><div class="order_2"><?php echo CURRENCY_SIGN; ?><?php echo $curr_order->total_amount; ?></div><div class="blankbar1"></div>
	</div>
</div>


<div class="submit_order_wrapper aligncenter">
    <?php if (intval($curr_order->order_status) == 1) { ?>
        <?php echo Html::input('button', 'direct_buy', __('Buy Now'), 'class="submit_order orange" onclick="window.location.href=\'index.php?_m=mod_order&amp;_a=confirm_buynow&amp;o_id='.$curr_order->id.'\'"'); ?>&nbsp;
        <?php if(!empty($payaccts)) echo Html::input('button', 'pay_now', __('Online Pay Now'), 'class="submit_order orange" onclick="window.location.href=\'index.php?_m=mod_onlinepay&amp;o_id='.$curr_order->id.'\'"'); ?>&nbsp;
        <?php echo Html::input('button', 'delete_order', __('Delete This Order'), 'class="submit_order red" onclick="user_delete_order('.$curr_order->id.');return false;"'); ?>&nbsp;
    <?php } ?>
    <?php if (intval($curr_order->order_status) == 3) { ?>
        <?php echo Html::input('button', 'transaction_finished', __('Transaction Finished'), 'class="submit_order red" onclick="user_finish_transaction('.$curr_order->id.');return false;"'); ?>&nbsp;
    <?php } ?>
    <?php echo Html::input('button', 'back', __('Back'), 'class="submit_order" onclick="window.location.href=\'index.php?_m=mod_order&amp;_a=userlistorder\'"'); ?>&nbsp;
</div>
