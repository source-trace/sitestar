<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<script type="text/javascript" language="javascript" src="script/popup/jquery.ui.custom.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
function on_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("neworder_stat");
    if (o_result.result == "ERROR") {
        document.forms["orderform"].reset();
        
        stat.innerHTML = o_result.errmsg;
        return false;
    } else if (o_result.result == "OK") {
	    stat.innerHTML = "<?php _e('OK, redirecting...'); ?>";
	    window.location.href = o_result.forward;
    } else {
        return on_failure(response);
    }
}

function on_deladdr_success(response) {
    var o_result = _eval_json(response);
    if (!o_result) {
        return on_failure(response);
    }
    
    var stat = document.getElementById("neworder_stat");
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

function on_failure(response) {
    document.forms["orderform"].reset();
    
    document.getElementById("neworder_stat").innerHTML = "<?php _e('Request failed!'); ?>";
    return false;
}

function delete_addr(addr_id) {
	if (confirm("<?php _e('Delete the selected address?'); ?>")) {
	    var stat = document.getElementById("neworder_stat");
	    stat.style.display = "block";
	    stat.innerHTML = "<?php _e('Deleting selected address...'); ?>";
		_ajax_request("mod_user", 
			"deldeliveryaddr", 
	        {
	            da_id:addr_id
	        }, 
			on_deladdr_success, 
			on_failure);
	}
}
//-->
</script>
<?php if((EZSITE_LEVEL == '2') && (EXCHANGE_SWITCH == '1')){?>
<div class="art_list">
	<div class="art_list_title"><?php _e('New Order'); ?></div>
	<div class="ordernow"><input type="button" value="<?php _e('Modify items'); ?>" class="Modify_items_b" onclick="location.href='<?php echo Html::uriquery('mod_cart', 'viewcart'); ?>'" /></div>

<div class="status_bar">
	<span id="neworder_stat" class="status" style="display:none;"></span>
</div>

<!-- 新订单 -->
<table class="new_orders_list" cellpadding="1" cellspacing="0" width="100%" border="0">
	<tr>
		<th width="45%"><?php _e('Name'); ?></th>
		<th width="10%"><?php _e('Quantity'); ?></th>
		<th width="15%"><?php _e('Single Price'); ?></th>
		<th width="15%"><?php _e('Total Price'); ?></th>
		<th width="15%"><?php _e('Delivery Fee'); ?></th>
	</tr>
<?php
if (sizeof($products) > 0) {
    $row_idx = 0;
    foreach ($products as $product) {
?>
	<tr>
		<td><div class="new_order_name"><a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>" target="_blank"><?php echo $product->name; ?></a></div></td>
		<td><?php echo $product->order_num; ?></td>
		<td><?php echo CURRENCY_SIGN; ?><?php echo $product->discount_price; ?></td>
		<td><?php echo CURRENCY_SIGN; ?><?php echo $product->order_ttl_price; ?></td>
		<td><?php echo CURRENCY_SIGN; ?><?php echo $product->delivery_fee; ?></td>
	</tr>
<?php
        $row_idx = 1 - $row_idx;
    }
} else {
?>
	<tr>
		<td colspan="5"><?php _e('No Records!');?></td>
	</tr>
<?php } ?>
	<tr>
		<th colspan="3"><?php _e('Order Total'); ?>：</th>
		<td><?php echo CURRENCY_SIGN; ?><?php echo $order_price; ?></td>
		<td><?php echo CURRENCY_SIGN; ?><?php echo $order_delivery_fee; ?></td>
	</tr>
	<tr>
		<th colspan="3"><?php _e('Order Payment'); ?>：</th>
		<td colspan="2"><?php echo CURRENCY_SIGN; ?><?php echo $order_grand_ttl; ?></td>
	</tr>
</table>
<!-- //新订单 -->

<?php
$order_form = new Form('index.php', 'orderform', 'check_order_info');
$order_form->p_open('mod_order', 'createorder', '_ajax');
?>
	<div class="addr"><div class="addr1"><b><?php _e('Delivery Address'); ?></b></div></div>
	<div class="addr2"><a href="#"  onclick="popup_window('<?php echo Html::uriquery('mod_user', 'adddeliveryaddr'); ?>', '<?php _e('Add delivery address'); ?>');return false;" title="<?php _e('Add delivery address'); ?>"><b><?php _e('Add delivery address'); ?></b></a><br /><?php echo Html::input('radio', 'selected_delivery_addr', '0', 'checked="checked"'); ?><?php _e('No delivery needed.'); ?><br />

	
        <?php
        if (sizeof($my_delivery_addrs) > 0) {
            $row_idx = 1;
            foreach ($my_delivery_addrs as $addr) {
        ?>
		<?php echo Html::input('radio', 'selected_delivery_addr', $addr->id); ?>&lt;<?php echo $addr->reciever_name; ?>&gt;<?php echo Toolkit::getDSName($addr->city_id); ?><?php echo Toolkit::getDSName($addr->dist_id); ?><?php echo $addr->detailed_addr; ?>&nbsp;&nbsp;<?php if (trim(SessionHolder::get('_LOCALE')) == 'zh_CN') {?><?php _e("Postal"); ?>：<?php } ?><?php echo $addr->postal; ?>&nbsp;&nbsp;<?php _e("Telephone"); ?>：<?php echo $addr->phone; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="popup_window('<?php echo Html::uriquery('mod_user', 'editdeliveryaddr', array('da_id' => $addr->id)); ?>', '<?php _e('Edit Delivery Address'); ?>');return false;" title="<?php _e('Edit'); ?>"><?php _e('Edit'); ?></a>&nbsp;<a href="#" onclick="delete_addr(<?php echo $addr->id; ?>);return false;" title="<?php _e('Delete'); ?>"><?php _e('Delete'); ?></a><br />
		
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
		<?php _e('No Records!'); ?>
		
        <?php } ?>
	</div>


	<!-- 13/05/2010 >> -->
	<div class="addr"><div class="addr1"><b><?php _e('Message'); ?></b></div></div>
	<div class="addr2">
	<?php
		echo Html::textarea('message', '', 'cols="60" rows="3"');
	?>
	</div>
	<!-- 13/05/2010 << -->

</div>


<div class="submit_order_wrapper aligncenter">
    <?php echo Html::input('submit', 'submit_order', __('Submit Order'), 'class="submit_order"'); ?>
</div>
	
<?php
$order_form->close();
$running_msg = __('Sending order...');
$custom_js = <<<JS
$("#neworder_stat").css({"display":"block"});
$("#neworder_stat").html("$running_msg");
_ajax_submit(thisForm, on_success, on_failure);
return false;

JS;
$order_form->addCustValidationJs($custom_js);
$order_form->writeValidateJs();
}
?>
