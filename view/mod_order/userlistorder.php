<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>


<div class="art_list">
	<div class="art_list_title"><?php _e('My Orders'); ?></div>
	<span id="neworder_stat" class="status" style="display:none;"></span>
<!-- 我的订单 -->
<table class="new_orders_list" cellpadding="1" cellspacing="0" width="100%" border="0">
	<tr>
		<th><?php _e('Order No.'); ?></th>
		<th><?php _e('Total Price'); ?></th>
		<th><?php _e('Order Time'); ?></th>
		<th colspan="2"><?php _e('Status'); ?></th>
	</tr>
<?php
if (sizeof($my_orders) > 0) {
    $row_idx = 0;
    foreach ($my_orders as $my_order) {
?>
	<tr>
		<td><a href="<?php echo Html::uriquery('mod_order', 'uservieworder', array('o_id' => $my_order->id)); ?>" title="<?php echo $my_order->oid; ?>"><?php echo $my_order->oid; ?></a></td>
		<td><?php echo CURRENCY_SIGN; ?><?php echo number_format($my_order->total_amount, 2); ?></td>
		<td><?php echo date("Y-m-d H:i", $my_order->order_time); ?></td>
		<td><?php echo Toolkit::switchText($my_order->order_status, 
                  array('1' => __('Not Paid'), '2' => __('Paid'), '3' => __('In Delivery'), '100' => __('Finished'), '101' => 'Cancelled')); ?></td>
    	<td><input type="button" class="submit_order ddcx" value="<?php _e('View'); ?>" onclick="location.href='<?php echo Html::uriquery('mod_order', 'uservieworder', array('o_id' => $my_order->id)); ?>'" /></td>
	</tr>
<?php
        $row_idx = 1 - $row_idx;
    }
} else {
?>
	<tr>
		<td colspan="5"><?php _e('No Records!');?> </td>
	</tr>
<?php } ?>
</table>
<!-- //我的订单 -->
</div>

