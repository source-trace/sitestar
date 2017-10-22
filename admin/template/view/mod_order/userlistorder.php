<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('My Orders'); ?></div>
    <div class="rightmeta rightblock">
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="neworder_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<div class="contentbody">
    <table cellspacing="1" class="front_list_table order_product_list">
        <thead>
            <tr>
                <th><?php _e('Order No.'); ?></th>
                <th><?php _e('Total Price'); ?></th>
                <th><?php _e('Order Time'); ?></th>
                <th><?php _e('Status'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (sizeof($my_orders) > 0) {
            $row_idx = 0;
            foreach ($my_orders as $my_order) {
        ?>
            <tr class="row_style_<?php echo $row_idx; ?>">
                <td class="mainlistlink"><a href="<?php echo Html::uriquery('mod_order', 'uservieworder', array('o_id' => $my_order->id)); ?>" title="<?php echo $my_order->oid; ?>">
                            <?php echo $my_order->oid; ?></a></td>
                <td class="aligncenter"><?php echo CURRENCY_SIGN; ?><?php echo number_format($my_order->total_amount, 2); ?></td>
                <td class="aligncenter"><?php echo date("Y-m-d H:i", $my_order->order_time); ?></td>
                <td class="aligncenter">
                    <?php echo Toolkit::switchText($my_order->order_status, 
                        array('1' => __('Not Paid'), '2' => __('Paid'), '3' => __('In Delivery'), '100' => __('Finished'), '101' => 'Cancelled')); ?></td>
                <td class="aligncenter">
                    <span class="medium">
                        <a href="<?php echo Html::uriquery('mod_order', 'uservieworder', array('o_id' => $my_order->id)); ?>" title="<?php _e('View'); ?>"><?php _e('View'); ?></a>
                    </span>
                </td>
            </tr>
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
            <tr class="row_style_0">
                <td colspan="5" class="aligncenter"><?php _e('No Records!'); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
