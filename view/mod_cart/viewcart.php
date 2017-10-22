<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<script type="text/javascript" language="javascript">
<!--
    function cartupdated(response) {
        var o_result = _eval_json(response);
        if (!o_result) {
            return on_failure(response);
        }
        
        if (o_result.result == "ERROR") {
            $("#cartupdating_stat").html(o_result.errmsg);
            return false;
        } else if (o_result.result == "OK") {
            $("#cartupdating_stat").html("<?php _e('OK, refreshing...'); ?>");
            reloadPage();
        } else {
            return on_failure(response);
        }
    }
    
    function cartupdatefailed(response) {
        $("#cartupdating_stat").html("<?php _e('Request failed!'); ?>");
        return false;
    }
    
    function updateprodnum(p_id) {
        $("#cartupdating_stat").css({"display":"block"});
        $("#cartupdating_stat").html("<?php _e('Updating shopping cart...'); ?>");
        var p_num = document.getElementById("prod_num_" + p_id).value;
        _ajax_request("mod_cart", "updateprodnum", 
            { p_id: p_id, p_num: p_num }, cartupdated, cartupdatefailed);
    }
    
    function removeprod(p_id) {
        $("#cartupdating_stat").css({"display":"block"});
        $("#cartupdating_stat").html("<?php _e('Updating shopping cart...'); ?>");
        _ajax_request("mod_cart", "delfromcart", 
                    { p_id: p_id }, cartupdated, cartupdatefailed);
    }
//-->
</script>

<div class="art_list">
	<div class="art_list_title"><?php _e('My Shopping Cart'); ?></div>
	<div class="ordernow">
		<?php if((EZSITE_LEVEL == '2') && (EXCHANGE_SWITCH == '1')){?>
		<input type="button" value="<?php _e('Order now!'); ?>" class="order_now_b" onclick="location.href='<?php echo Html::uriquery('mod_order', 'ordernow'); ?>'" />
		<?php }?>
	</div>

<div class="status_bar">
	<span id="cartupdating_stat" class="status" style="display:none;"></span>
</div>

<div class="cartlist">
	
        <?php
        if((EZSITE_LEVEL == '2') && (EXCHANGE_SWITCH == '1')){
        if (sizeof($products) > 0) {
            $row_idx = 0;
            foreach ($products as $product) {
                $product->loadRelatedObjects(REL_PARENT, array('ProductCategory'));
        ?>
		
	<div class="cartlist_list">
		<div class="cartlist_pic"><a href="<?php echo $product->feature_img; ?>" class="thickbox" title="<?php echo $product->name; ?>"><img src="<?php echo $product->feature_smallimg; ?>" class="prodthumb" name="picautozoom" border="0" /></a></div>
		<div class="cartlist_nametype"><a href="<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>"><?php echo $product->name; ?></a><br /><?php _e('Category'); ?>: <a href="<?php echo Html::uriquery2('mod_product', 'prdlist', array('cap_id' => $product->masters['ProductCategory']->id)); ?>"><?php echo $product->masters['ProductCategory']->name; ?></a></div>
		<div class="cartlist_no"><?php _e('Quantity'); ?>：<?php echo Html::input('text', 'prod_num_'.$product->id, $_COOKIE['n_prd'.SessionHolder::get('user/id','0')][$product->id], 'size="4"'); ?><br /><a href="javascript:void(0);" onclick="updateprodnum('<?php echo $product->id; ?>');">[<?php _e('Update quantity'); ?>]</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="removeprod('<?php echo $product->id; ?>');">[<?php _e('Remove'); ?>]</a></div>
		<div class="cartlist_intr"><?php echo Toolkit::substr_MB(strip_tags($product->description), 0, 72).((Toolkit::strlen_MB(strip_tags($product->description)) > 72)?'...':''); ?><a href="<?php echo Html::uriquery2('mod_product', 'view', array('p_id' => $product->id)); ?>"><img src="<?php echo P_TPL_WEB; ?>/images/more_37.jpg" border="0" /></a></div>
	</div>
	
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
		<?php _e('No Records!'); ?>
		
        <?php }} ?>
		
</div>


</div>
