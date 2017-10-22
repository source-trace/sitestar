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
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php _e('My Shopping Cart'); ?></div>
    <div class="rightmeta rightblock">
        <a href="<?php echo Html::uriquery('mod_order', 'ordernow'); ?>"><?php _e('Order now!'); ?></a>
    </div>
    <div class="clearer"></div>
</div>
<div class="space"></div>
<div class="status_bar">
	<span id="cartupdating_stat" class="status" style="display:none;"></span>
</div>
<div class="space"></div>
<div class="contentbody">
    <table cellspacing="0" class="front_list_table product_list_table">
        <tbody>
        <?php
        if (sizeof($products) > 0) {
            $row_idx = 0;
            foreach ($products as $product) {
                $product->loadRelatedObjects(REL_PARENT, array('ProductCategory'));
        ?>
            <tr valign="top" class="row_style_<?php echo $row_idx; ?>">
                <td rowspan="2">
                    <a href="<?php echo $product->feature_img; ?>" target="_blank" title="<?php _e('Click to view large image!'); ?>">
                    <img src="<?php echo $product->feature_smallimg; ?>" class="prodthumb" border="0" /></a>
                </td>
                <td>
                    <div class="prodtitle">
                        <a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->id)); ?>" title="<?php echo $product->name; ?>">
                            <?php echo $product->name; ?></a>
                    </div>
                    <div class="prodcate mediumgray">
                        <?php _e('Category'); ?>: <a href="<?php echo Html::uriquery('mod_product', 'prdlist', array('cap_id' => $product->masters['ProductCategory']->id)); ?>">
                            <?php echo $product->masters['ProductCategory']->name; ?></a>
                    </div>
                </td>
                <td rowspan="2" class="cart_product_state">
                    <table cellspacing="0" class="cart_state_wrapper">
                        <tbody>
                            <tr>
                                <td><?php _e('Quantity'); ?></td>
                                <td>
                                    <?php echo Html::input('text', 'prod_num_'.$product->id, $_COOKIE['n_prd'.SessionHolder::get('user/id','0')][$product->id], 'size="4"'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="cart_actions">
                                    <a href="#" onclick="updateprodnum('<?php echo $product->id; ?>');">&raquo; <?php _e('Update quantity'); ?></a>
                                    <a href="#" onclick="removeprod('<?php echo $product->id; ?>');">&raquo; <?php _e('Remove'); ?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr valign="top" class="row_style_<?php echo $row_idx; ?>">
                <td class="proddesc">
                    <?php echo Toolkit::substr_MB(strip_tags($product->description), 0, 72).'...'; ?>
                    &nbsp;
                    <a href="<?php echo Html::uriquery('mod_product', 'view', array('p_id' => $product->id)); ?>">
                        <img src="<?php echo P_TPL_WEB; ?>/images/more.gif" border="0" /></a>
                </td>
            </tr>
            <tr class="row_style_<?php echo $row_idx; ?>">
                <td colspan="3"><div class="separator"></div></td>
            </tr>
        <?php
                $row_idx = 1 - $row_idx;
            }
        } else {
        ?>
            <tr class="row_style_0">
                <td class="aligncenter"><?php _e('No Records!'); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
