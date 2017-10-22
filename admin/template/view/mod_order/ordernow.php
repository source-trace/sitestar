<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
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
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('New Order'); ?></div>
    <div class="rightmeta rightblock">
        <a href="<?php echo Html::uriquery('mod_cart', 'viewcart'); ?>"><?php _e('Modify items'); ?></a>
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
                <th><?php _e('Name'); ?></th>
                <th><?php _e('Quantity'); ?></th>
                <th><?php _e('Single Price'); ?></th>
                <th><?php _e('Total Price'); ?></th>
                <th><?php _e('Delivery Fee'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (sizeof($products) > 0) {
            $row_idx = 0;
            foreach ($products as $product) {
        ?>
            <tr class="row_style_<?php echo $row_idx; ?>">
                <td class="mainlistlink"><a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>" target="_blank">
                            <?php echo $product->name; ?></a></td>
                <td class="aligncenter" width="56"><?php echo $product->order_num; ?></td>
                <td class="aligncenter" width="84"><?php echo CURRENCY_SIGN; ?><?php echo $product->discount_price; ?></td>
                <td class="aligncenter" width="84"><?php echo CURRENCY_SIGN; ?><?php echo $product->order_ttl_price; ?></td>
                <td class="aligncenter" width="84"><?php echo CURRENCY_SIGN; ?><?php echo $product->delivery_fee; ?></td>
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
        <tfoot>
            <tr class="order_total">
                <td colspan="3" class="alignright label"><?php _e('Order Total'); ?>: </td>
                <td class="aligncenter number"><?php echo CURRENCY_SIGN; ?><?php echo $order_price; ?></td>
                <td class="aligncenter number"><?php echo CURRENCY_SIGN; ?><?php echo $order_delivery_fee; ?></td>
            </tr>
            <tr class="order_grand_total">
                <td colspan="3" class="alignright label"><?php _e('Total Payment'); ?>: </td>
                <td colspan="2" class="aligncenter number"><?php echo CURRENCY_SIGN; ?><?php echo $order_grand_ttl; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php
$order_form = new Form('index.php', 'orderform', 'check_order_info');
$order_form->p_open('mod_order', 'createorder', '_ajax');
?>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('Delivery Address'); ?></div>
    <div class="rightmeta rightblock">
        <a href="#"  onclick="show_iframe_win('index.php?<?php echo Html::xuriquery('mod_user', 'adddeliveryaddr'); ?>', '<?php _e('Add delivery address'); ?>', 560, 520);return false;" title="<?php _e('Add delivery address'); ?>">
        <?php _e('Add delivery address'); ?></a>
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="contentbody">
    <table cellspacing="0" class="front_list_table order_delivery_addr_list">
        <tbody>
            <tr class="row_style_0">
                <td class="aligncenter" width="28">
                    <?php echo Html::input('radio', 'selected_delivery_addr', '0', 'checked="checked"'); ?></td>
                <td colspan="7"><?php _e('No delivery needed.'); ?></td>
            </tr>
        <?php
        if (sizeof($my_delivery_addrs) > 0) {
            $row_idx = 1;
            foreach ($my_delivery_addrs as $addr) {
        ?>
            <tr class="row_style_<?php echo $row_idx; ?>">
                <td class="aligncenter" width="28">
                    <?php echo Html::input('radio', 'selected_delivery_addr', $addr->id); ?></td>
                <td><?php echo $addr->reciever_name; ?></td>
                <td><?php echo Toolkit::getDSName($addr->city_id); ?></td>
                <td><?php echo Toolkit::getDSName($addr->dist_id); ?></td>
                <td><?php echo $addr->detailed_addr; ?></td>
                <td class="aligncenter"><?php echo $addr->postal; ?></td>
                <td class="aligncenter"><?php echo $addr->phone; ?></td>
                <td class="aligncenter" width="72">
                    <span class="medium">
                        <a href="#" onclick="show_iframe_win('index.php?<?php echo Html::xuriquery('mod_user', 'editdeliveryaddr', array('da_id' => $addr->id)); ?>', '<?php _e('Edit Delivery Address'); ?>', 560, 520);return false;" title="<?php _e('Edit'); ?>"><?php _e('Edit'); ?></a>
                        &nbsp;
                        <a href="#" onclick="delete_addr(<?php echo $addr->id; ?>);return false;" title="<?php _e('Delete'); ?>"><?php _e('Delete'); ?></a>
                    </span>
                </td>
            </tr>
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
            <tr class="row_style_0">
                <td class="aligncenter" colspan="8"><?php _e('No Records!'); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
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
?>
