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
<span id="vieworder_stat" class="status" style="display:none;"></span>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="form_table_list" style="line-height:24px;">
    <tbody>
        <tr>
            <th><?php _e('Order No.'); ?></th>
            <th><?php _e('Login Name'); ?></th>
            <th><?php _e('Total Price'); ?></th>
            <th><?php _e('Order Time'); ?></th>
            <th><?php _e('Status'); ?></th>
            <th><?php _e('Operation'); ?></th>
        </tr>
    <?php
    if (sizeof($orders) > 0) {
        $row_idx = 0;
        foreach ($orders as $order) {
            $order->loadRelatedObjects(REL_PARENT, array('User'));
    ?>
        <tr>
            <td><a href="<?php echo Html::uriquery('mod_order', 'admin_view', array('o_id' => $order->id)); ?>" title="<?php echo $order->oid; ?>">
                        <?php echo $order->oid; ?></a></td>
            <td><?php if (isset($order->masters['User']->login)) {
            	echo $order->masters['User']->login;
            } ?></td>
            <td class="aligncenter"><?php echo CURRENCY_SIGN; ?><?php echo number_format($order->total_amount, 2); ?></td>
            <td class="aligncenter"><?php echo date("Y-m-d H:i", $order->order_time); ?></td>
            <td class="aligncenter">
                <?php echo Toolkit::switchText($order->order_status, 
                    array('1' => __('Not Paid'), '2' => __('Paid'), '3' => __('In Delivery'), '100' => __('Finished'), '101' => __('Cancelled'))); ?></td>
            <td class="aligncenter">
                <span class="medium">
                    <a href="<?php echo Html::uriquery('mod_order', 'admin_view', array('o_id' => $order->id)); ?>" title="<?php _e('View'); ?>"><img style="border:none;position:relative;top:2px;" alt="<?php _e('View');?>" src="<?php echo P_TPL_WEB; ?>/images/view.gif"/></a>
					<a href="javascript:void(0);" onclick="user_delete_order('<?php echo $order->id;?>');return false;" title="<?php _e('Delete'); ?>"><img style="border:none;position:relative;top:2px;" alt="<?php _e('View');?>" src="<?php echo P_TPL_WEB; ?>/images/cross.gif"/></a>
					&nbsp;
                </span>
            </td>
        </tr>
    <?php
            $row_idx = 1 - $row_idx;
        }
    } else {
    ?>
        <tr class="row_style_0">
            <td colspan="6" class="aligncenter"><?php _e('No Records!'); ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php
include_once(P_TPL.'/common/pager.php');
?>
