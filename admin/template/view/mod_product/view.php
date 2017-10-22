<?php if (!defined('IN_CONTEXT')) die('access violation error!'); ?>
<div class="contenttoolbar">
    <div class="contenttitle leftblock"><?php echo $curr_product->name; ?></div>
    <div class="rightmeta rightblock">
    </div>
    <div class="clearer"></div>
</div>
<div class="productinfo alignleft medium">
    <?php _e('Category'); ?>: <?php echo $curr_product->masters['ProductCategory']->name; ?>&nbsp;
    <?php _e('Publish Time'); ?>: <?php echo date('Y-m-d H:i', $curr_product->create_time); ?>&nbsp;
</div>
<div class="contentbody">
    <blockquote class="productintro">
        <?php echo $curr_product->introduction; ?>
    </blockquote>
    <div class="productcontent">
        <a href="<?php echo $curr_product->feature_img; ?>" title="<?php echo $curr_product->name; ?>" class="thickbox">
        <img src="<?php echo $curr_product->feature_smallimg; ?>" alt="" border="0" align="left" width="200" class="productimg" /></a>
        <?php if ($curr_product->online_orderable) { ?>
            <script type="text/javascript" language="javascript">
            <!--
                function updatecartstate(response) {
                    var o_result = _eval_json(response);
                    if (!o_result) {
                        return addprodfailed(response);
                    }
                    
                    if (o_result.result == "ERROR") {
                        alert(o_result.errmsg);
                        return false;
                    } else if (o_result.result == "OK") {
                        $("#disp_n_prds").html(o_result.n_prds);
                        alert("<?php _e('The product has been added to cart!'); ?>");
                        return true;
                    } else {
                        return on_failure(response);
                    }
                }
                
                function addprodfailed(response) {
                    alert("<?php _e('Request failed!'); ?>");
                    return false;
                }
                
                function add2cart(p_id) {
                    var p_num = document.getElementById("prod_num_" + p_id).value;
                    _ajax_request("mod_cart", "addtocart", 
                        { p_id: p_id, p_num: p_num }, updatecartstate, addprodfailed);
                }
            //-->
            </script>
            <p>
                <table cellspacing="1" class="productprice">
                    <tbody>
                        <?php if ($curr_product->discount_price != $curr_product->price) { ?>
                            <tr><td class="label"><?php _e('Price'); ?> : </td><td class="strike"><?php echo CURRENCY_SIGN; ?><?php echo $curr_product->price; ?></td></tr>
                            <tr><td class="label"><?php _e('Discount Price'); ?> : </td><td class="price"><?php echo CURRENCY_SIGN; ?><?php echo $curr_product->discount_price; ?></td></tr>
                        <?php } else { ?>
                            <tr><td class="label"><?php _e('Price'); ?> : </td><td class="price"><?php echo CURRENCY_SIGN; ?><?php echo $curr_product->price; ?></td></tr>
                        <?php } ?>
                        <tr><td class="label"><?php _e('Delivery Fee'); ?> : </td><td class="price"><?php echo CURRENCY_SIGN; ?><?php echo $curr_product->delivery_fee; ?></td></tr>
                        <tr><td class="label"><?php _e('Quantity'); ?> : </td><td class="price"><?php echo Html::input('text', 'prod_num_'.$curr_product->id, $_COOKIE['n_prd'.SessionHolder::get('user/id','0')][$curr_product->id]?$_COOKIE['n_prd'.SessionHolder::get('user/id','0')][$curr_product->id]:1, 'size="4"'); ?></td></tr>
                        <tr><td class="addtocart aligncenter" colspan="2"><a class="orange" href="#" onclick="add2cart('<?php echo $curr_product->id; ?>');return false;" title="<?php _e('Add to cart'); ?>">
                            <img src="<?php echo P_TPL_WEB; ?>/images/cart.gif" border="0" class="imgvmid" alt="<?php _e('Add to cart'); ?>" />
                            <?php _e('Add to cart'); ?></a></td></tr>
                    </tbody>
                </table>
            </p>
            <?php } ?>
        <p><?php echo $curr_product->description; ?></p>
    </div>
</div>